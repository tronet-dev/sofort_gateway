<?php

    /**
     * @file      trosofortueberweisungoxorder.php
     * @link      http://www.tro.net
     * @copyright (C) tronet GmbH 2014
     * @package   modules
     * @addtogroup models
     * @extend oxorder
     */

    /**
     * Order manager.
     * Performs creation assigning, updating, deleting and other order functions.
     *
     * NEW: sets ordernr before finalizing and continues finalizing, if stopped during paymentexecution
     */
    class trosofortueberweisungoxorder extends trosofortueberweisungoxorder_parent {

        /**
         * Bestellung mit dieser ID loeschen
         * Bestellung mit dieser ID kann existieren, wenn vorher schon mal zu SofortUeberweisung
         * weitergeleitet wurde und ueber Back-Button des Browsers zurueckgekehrt wurde
         *
         * @return null
         */
        public function troDeleteOldOrder()
        {
            $oSession = new oxSession();
            $sOrderId = $oSession->getVariable('sess_challenge');

            if ($sOrderId)
            {
                $oDb = oxDb::getDb();
                $sSql = "select oxid from oxorder 
                      where oxpaymenttype = 'trosofortgateway_su' 
                      and oxtransstatus = 'NOT_FINISHED'
                      and oxid = " . $oDb->quote($sOrderId);

                if ($oDb->getOne($sSql))
                {
                    // check what should happen, depending on the users choice. Delete or cancel the old order
                    // 0 => cancel order
                    // 1 => delete order#
                    $iMode = $this->getConfig()->getConfigParam('iTroGatewayCanceledOrders');

                    if ($iMode == 0)
                    {
                        $this->load($sOrderId);
                        $this->cancelOrder();
                        $this->oxorder__oxordernr = null;
                        $this->oxorder__oxstorno = null;
                        $this->getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID());
                    }
                    elseif ($iMode == 1)
                    {
                        $this->delete($sOrderId);
                        $this->getSession()->setVariable('sess_challenge', oxUtilsObject::getInstance()->generateUID());
                    }
                }
            }
        }

        /**
         * Order checking, processing and saving method.
         * Before saving performed checking if order is still not executed (checks in
         * database oxorder table for order with know ID), if yes - returns error code 3,
         * if not - loads payment data, assigns all info from basket to new oxorder object
         * and saves full order with error status. Then executes payment. On failure -
         * deletes order and returns error code 2. On success - saves order (oxorder::save()),
         * removes article from wishlist (oxorder::_updateWishlist()), updates voucher data
         * (oxorder::_markVouchers()). Finally sends order confirmation email to customer
         * (oxemail::SendOrderEMailToUser()) and shop owner (oxemail::SendOrderEMailToOwner()).
         * If this is order recalculation, skipping payment execution, marking vouchers as used
         * and sending order by email to shop owner and user
         * Mailing status (1 if OK, 0 on error) is returned.
         *
         * @param oxBasket $oBasket              Shopping basket object
         * @param oxUser   $oUser                Current user object
         * @param bool     $blRecalculatingOrder Order recalculation
         *
         * @return integer
         */
        public function finalizeOrder(oxBasket $oBasket, $oUser, $blRecalculatingOrder = false)
        {
            $this->troDeleteOldOrder();

            return parent::finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);
        }

        /**
         * Executes payment. Additionally loads oxPaymentGateway object, initiates
         * it by adding payment parameters (oxPaymentGateway::setPaymentParams())
         * and finally executes it (oxPaymentGateway::executePayment()). On failure -
         * deletes order and returns * error code 2.
         *
         * @param oxBasket|trosofortueberweisungoxbasket $oBasket      basket object
         * @param object   $oUserPayment user payment object
         *
         * @return  integer 2 or an error code
         */
        protected function _executePayment(oxBasket $oBasket, $oUserPayment)
        {
            $sPaymentId = $oBasket->getPaymentId();
            if ($sPaymentId == 'trosofortgateway_su')
            {
                // Safe current order only ($this) and the current basket ($oBasket) in the session,
                // so that the info are available when the user returns from the SOFORT AG.
                $oSession = new oxSession();

                if (!$this->oxorder__oxordernr->value)
                {
                    $this->_setNumber();
                }

                $oOrder = clone $this;
                $oSession->setVariable('trosuoxorder', $oOrder);
                $oSession->setVariable('trosubasket', $oBasket);
                $oBasket->troStoreInSession();
            }

            return parent::_executePayment($oBasket, $oUserPayment);
        }

        /**
         * continueFinalizeOrder
         *
         * On return from SOFORT AG OXIDs core method finalizeOrder is continued.
         *
         * @param oxBasket $oBasket              Shopping basket object
         * @param object   $oUser                Current user object
         * @param bool     $blRecalculatingOrder Order recalculation
         *
         * @return string
         */
        public function continueFinalizeOrder(oxBasket $oBasket, $oUser, $blRecalculatingOrder = false)
        {
            $oSession = new oxSession();
            // payment information
            $oUserPayment = $this->_setPayment($oBasket->getPaymentId());

            //// Rest of finalizeOrder
            // executing TS protection
            if (!$blRecalculatingOrder && $oBasket->getTsProductId()) {
                $blRet = $this->_executeTsProtection($oBasket);
                if ($blRet !== true) {
                    return $blRet;
                }
            }

            // deleting remark info only when order is finished
            $oSession->deleteVariable('ordrem');
            $oSession->deleteVariable('stsprotection');

            if (!$this->oxorder__oxordernr->value) {
                $this->_setNumber();
            } else {
                oxNew('oxCounter')->update($this->_getCounterIdent(), $this->oxorder__oxordernr->value);
            }

            //#4005: Order creation time is not updated when order processing is complete
            if (!$blRecalculatingOrder) {
                $this->_updateOrderDate();
            }

            // updating order trans status (success status)
            $this->_setOrderStatus('OK');

            // store orderid
            $oBasket->setOrderId($this->getId());

            // updating wish lists
            $this->_updateWishlist($oBasket->getContents(), $oUser);

            // updating users notice list
            $this->_updateNoticeList($oBasket->getContents(), $oUser);

            // marking vouchers as used and sets them to $this->_aVoucherList (will be used in order email)
            // skipping this action in case of order recalculation
            if (!$blRecalculatingOrder) {
                $this->_markVouchers($oBasket, $oUser);
            }

            // send order by email to shop owner and current user
            // skipping this action in case of order recalculation
            if (!$blRecalculatingOrder) {
                $iRet = $this->_sendOrderByEmail($oUser, $oBasket, $oUserPayment);
            } else {
                $iRet = self::ORDER_STATE_OK;
            }

            // Call the original finalizeOrder method. In case the method is extended by other modules,
            // this makes sure those features are executed as well.
            $iRet2 = $this->finalizeOrder($oBasket, $oUser, $blRecalculatingOrder);

            // Return value of the original finalizeOrder should be self::ORDER_STATE_ORDEREXISTS in any case,
            // as the order has been created before the redirection to SOFORT AG.
            // In case a different value is returned, we pass this return value.
            if ($iRet2 == self::ORDER_STATE_ORDEREXISTS)
            {
                return $iRet;
            }
            return $iRet2;
        }

        /**
         * Sets OrderNumber.
         *
         * This method is triggered before we execute payment via SOFORT.
         *
         * @author tronet GmbH
         */
        public function troSetOrderNr()
        {
            if (is_null($this->getFieldData('oxordernr')))
            {
                $this->_setNumber();
            }
        }
    }

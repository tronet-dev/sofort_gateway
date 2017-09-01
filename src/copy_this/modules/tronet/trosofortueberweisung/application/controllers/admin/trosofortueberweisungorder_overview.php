<?php

/**
 * Admin order overview manager.
 * Collects order overview information, updates it on user submit, etc.
 * Admin Menu: Orders -> Display Orders -> Overview.
 *
 * NEW: shows paymentstatus via DB-table trogatewaylog
 *
 * @file          trosofortueberweisungorder_overview.php
 * @link          http://www.tro.net
 * @copyright (C) tronet GmbH 2017
 * @package       modules
 * @addtogroup    controllers
 * @extend        order_overview
 */
class trosofortueberweisungorder_overview extends trosofortueberweisungorder_overview_parent
{
    private $_sStatus = null;
    
    /**
     * returns current paymentstatus from DB-table trogatewaylog
     *
     * @return string
     * @author tronet GmbH
     */
    public function troGetPaymentStatus()
    {
        if ($this->_sStatus === null)
        {
            $sOxId = $this->getEditObjectId();
            $oOrder = oxNew('oxOrder');
            if ($oOrder->load($sOxId))
            {
                if ($oOrder->getPaymentType()->oxuserpayments__oxpaymentsid->rawValue == 'trosofortgateway_su')
                {
                    $oDB = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

                    $sSelect = "SELECT status FROM trogatewaylog WHERE transactionid='" . $oOrder->oxorder__oxtransid->value . "' ORDER BY timestamp DESC LIMIT 1";
                    $this->_sStatus = $oDB->getOne($sSelect);
                };
            };
        }

        return $this->_sStatus;
    }
}

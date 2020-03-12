<?php

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       7.0.8
 */
class trosofortueberweisungorder extends trosofortueberweisungorder_parent
{
    /**
     * Finalizes order, if we stopped during executePayment
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 7.0.8
     */
    public function troContinueExecute()
    {
        $oConfig = $this->getConfig();
        $sOrderId = $oConfig->getRequestParameter('orderid');
        $oOrder = oxNew('oxorder');
        $oOrder->load($sOrderId);
        $oOrderUser = $oOrder->getOrderUser();
        $oUser = $this->getUser();

        // Bestellung nur zu Ende bringen, wenn
        // - gültige oxorder-ID
        // - ein User existiert
        // - der User zur Bestellung passt
        if (!$oOrder->getId() || !$oUser || $oUser->getId() != $oOrderUser->getId())
        {
            return 'order';
        }

        if ($this->_troContinueExecuteSofortueberweisungOrder($oOrder))
        {
            // setze Status auf IN_PROGRESS, damit Notification-Controller nicht während der Abarbeitung dieser Methode die gleiche Bestellung finalisiert
            // Speichern erfolgt nicht mit $oOrder->save, da die Methode noch Nebeneffekte aufweisen kann
            $oDB = oxDb::getDb();
            $sUpdate = "UPDATE oxorder SET oxtransstatus = 'IN_PROGRESS' WHERE oxid = '$sOrderId'";
            $oDb->execute($sUpdate); 

            // Finish oxOrder::finalizeOrder
            $oBasket = $this->getSession()->getBasket();
            $iSuccess = $oOrder->troContinueFinalizeOrder($oBasket, $oUser);

            ////////////////////////////////////////////////////////////
            //// Following is the rest of original oxOrder::execute

            // performing special actions after user finishes order (assignment to special user groups)
            $oUser->onOrderExecute($oBasket, $iSuccess);

            // proceeding to next view
            return $this->_getNextStep($iSuccess);
        }
        else
        {
            return $this->_getNextStep(oxOrder::ORDER_STATE_OK);
        }
    }

    /**
     * _troContinueExecuteSofortueberweisungOrder
     *
     * Verifies previously initiated order has been payed.
     *
     * The success link contains the Transaction-ID.
     *
     * This function sends a new request to SOFORT AG regarding the current transaction status.
     *
     * If the current transaction status is either "pending", "received" or "untraceable" plus table oxOrder
     * contains a record with the Transaction-ID which has not been finished yet (status: NOT_FINISHED) plus
     * the defined payment method is "trosofortgateway_su" the order processing will be continued.
     *
     * @return boolean
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 7.0.3
     */
    protected function _troContinueExecuteSofortueberweisungOrder($oOrder)
    {
        $oConfig = $this->getConfig();
        require_once($oConfig->getModulesDir().'tronet/trosofortueberweisung/library/core/sofortLibTransactionData.inc.php');

        // initialize
        $sTransactionId = $oConfig->getRequestParameter('transactionid');
        $sConfigKey = $oConfig->getConfigParam('sTroGatewayConfKey');

        // Request the current transaction-status.
        $oTransactionData = new SofortLibTransactionData($sConfigKey);
        $oTransactionData->addTransaction($sTransactionId);
        $oTransactionData->sendRequest();
        $sTransactionDataStatus = $oTransactionData->getStatus();

        if ($this->_troSOFORTOrderHasBeenPayed($sTransactionDataStatus) && $this->_troSOFORTOrderIsNotFinishedYet($oOrder))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Status "pending": Data have been recorded by SOFORT AG but the SOFORT AG did not written off yet.
     *                      The order is counted as payed anyway.
     *
     * Status: "received": SOFORT AG has written off and received the money.
     *
     * @param string $sPaymentStatus
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 7.0.3
     */
    protected function _troSOFORTOrderHasBeenPayed($sPaymentStatus)
    {
        $aValidPaymentStatus = array('pending', 'received', 'untraceable');
        $blSOFORTOrderHasBeenPayed = in_array($sPaymentStatus, $aValidPaymentStatus);

        return $blSOFORTOrderHasBeenPayed;
    }

    /**
     * Determine whether current order with the present Transaction-ID is opened (status: NOT_FINISHED)
     *
     * @param string $sTransactionId
     * @param string $sOrderId
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 7.0.3
     */
    protected function _troSOFORTOrderIsNotFinishedYet($oOrder)
    {
        $blSOFORTOrderIsNotFinishedYet = (bool)($oOrder->oxorder__oxtransstatus->value == 'NOT_FINISHED');

        return $blSOFORTOrderIsNotFinishedYet;
    }
}

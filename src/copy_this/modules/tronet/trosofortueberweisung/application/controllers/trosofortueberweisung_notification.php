<?php

/**
 * Notifications-Controller
 * Collects order paymentstatus information from SOFORT, stores them to DB and updates orders (cancel, paymentdate)
 *
 * @file          trosofortueberweisungsofortgateway_notification.php
 * @link          http://www.tro.net
 * @copyright (C) tronet GmbH 2017
 * @package       modules
 * @addtogroup    controllers
 * @extend        oxUBase
 */
class trosofortueberweisung_notification extends oxUBase
{
    /**
     * process the transaction status
     */
    public function render()
    {
        parent::render();

        $oTransactionData = $this->_getTroTransactionData();
        
        $this->_troStoreGatewayLog($oTransactionData);
        switch($oTransactionData->getStatus())
        {
            // Deutsche Handelsbank account - waiting for another status
            case 'pending':
                break;
                
            // Deutsche Handelsbank account - money not received
            case 'loss':
                $this->_troCancelOrder($oTransactionData);
                break;
                
            // Deutsche Handelsbank account - money received
            case 'received':
                $this->_troSetOrderPaid($oTransactionData);
                break;
                
            // no Deutsche Handelsbank account
            case 'untraceable':
                $this->_troSetOrderPaid($oTransactionData);
                break;
                
            // money refunded
            case 'refunded':
                $this->_troCancelOrder($oTransactionData);
                break;
        }

        // just exit, as we are not in frontend here
        oxRegistry::getUtils()->showMessageAndExit('No info received');
    }

    /**
     * get the transaction data
     *
     * @return $oTransactionData
     */
    private function _getTroTransactionData()
    {
        $sModuleDirectory = oxRegistry::getConfig()->getModulesDir();
        $sSOFORTLibraryCoreDirectory = $sModuleDirectory . 'tronet/trosofortueberweisung/library/core/';

        require_once($sSOFORTLibraryCoreDirectory . 'sofortLibNotification.inc.php');
        require_once($sSOFORTLibraryCoreDirectory . 'sofortLibTransactionData.inc.php');

        // create SofortLib_Notification-object and fetch transaction-id
        $oNotification = $this->getSofortLibNotificationObject();
        $oNotification->getNotification(file_get_contents('php://input'));
        $sTransactionId = $oNotification->getTransactionId();

        if ($sTransactionId == '')
        {
            oxRegistry::getUtils()->showMessageAndExit('No info received');
        }

        // create SofortLib_TransactionData-object and fetch some information for the transaction-id retrieved above
        $oTransactionData = $this->getSofortLibTransactionDataObject();
        $oTransactionData->addTransaction($sTransactionId);
        $oTransactionData->sendRequest();
        return $oTransactionData;
    }
    
    public function getSofortLibNotificationObject()
    {
        return new SofortLibNotification();
    }

    public function getSofortLibTransactionDataObject()
    {
        return new SofortLibTransactionData(oxRegistry::getConfig()->getConfigParam('sTroGatewayConfKey'));
    }

    /**
     * stores received information to log-table trogatewaylog
     *
     * @param string $oTransactionData
     */
    private function _troStoreGatewayLog($oTransactionData)
    {
        $oTroGatewayLog = oxNew('trosofortueberweisunggatewaylog');
        $oTroGatewayLog->trogatewaylog__transactionid = new oxField($oTransactionData->getTransaction());
        $oTroGatewayLog->trogatewaylog__status = new oxField($oTransactionData->getStatus());
        $oTroGatewayLog->trogatewaylog__statusreason = new oxField($oTransactionData->getStatusReason());
        $oTroGatewayLog->trogatewaylog__timestamp = new oxField($oTransactionData->getStatusModifiedTime());
        $oTroGatewayLog->save();
    }

    /**
     * gets oxorder.oxid for the current transaction-id
     *
     * @param string $oTransactionData
     * @return string
     */
    private function _getTroOrderID($oTransactionData)
    {
        $sTransactionId = $oTransactionData->getTransaction();
                
        $sSelect = "SELECT OXID FROM oxorder where oxtransid='" . $sTransactionId . "' ";
        $oDB = oxDb::getDb();
        $sOrderId = $oDB->getOne($sSelect);
        return $sOrderId;
    }
    
    /**
     * sets order as paid
     *
     * @param string $oTransactionData
     */
    private function _troSetOrderPaid($oTransactionData)
    {
        $sOrderId = $this->_getTroOrderID($oTransactionData);
        
        if ($sOrderId)
        {
            $sDate = $oTransactionData->getStatusModifiedTime();

            $sUpdate = "UPDATE oxorder SET oxpaid='$sDate' where oxid='$sOrderId'";
            $oDB = oxDb::getDb();
            $oDB->execute($sUpdate);
            oxRegistry::getUtils()->showMessageAndExit('Order updated');
        }
        oxRegistry::getUtils()->showMessageAndExit('Order not found');
    }

    /**
     * cancels order
     *
     * @param string $oTransactionData
     */
    private function _troCancelOrder($oTransactionData)
    {
        $sOrderId = $this->_getTroOrderID($oTransactionData);
        
        if ($sOrderId)
        {
            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);
            $oOrder->cancelOrder();
            
            $sUpdate = "UPDATE oxorder SET oxpaid='' where oxid='$sOrderId'";
            $oDB = oxDb::getDb();
            $oDB->execute($sUpdate);
            
            oxRegistry::getUtils()->showMessageAndExit('Order canceled');
        }
        oxRegistry::getUtils()->showMessageAndExit('Order not found');
    }
}

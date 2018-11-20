<?php

namespace Tronet\Trosofortueberweisung\Application\Controller;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\ConnectionException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Order;
use Sofort\SofortLib\Notification;
use Sofort\SofortLib\TransactionData;
use Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungGatewayLog;
use Tronet\Trosofortueberweisung\Core\SofortConfiguration;

/**
 * Notifications-Controller
 *
 * Collects order paymentstatus information from Sofort., stores them to DB
 * and updates orders (cancel, paymentdate)
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.6
 */
class TrosofortueberweisungNotificationController extends FrontendController
{
    /**
     * process the transaction status
     *
     * @throws ConnectionException from SofortueberweisungNotificationController::_troUpdateOrderRoutine()
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.1
     */
    public function render()
    {
        parent::render();

        // Heart of this controller
        $this->_troUpdateOrderByStatus();

        // just exit, as we are not in frontend here
        Registry::getUtils()->showMessageAndExit('No info received');
    }

    /**
     * Wrapper function to update order depending on the transactions status
     *
     * @throws ConnectionException from \SofortueberweisungNotificationController::_troCancelOrder or
     *                             \SofortueberweisungNotificationController::_troSetOrderPaid
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.1
     */
    protected function _troUpdateOrderByStatus()
    {
        $sTransactionId = $this->_getTroTransactionID();
        $oTransactionData = $this->_getTroTransactionData($sTransactionId);
        $this->_troStoreGatewayLog($oTransactionData);

        switch ($oTransactionData->getStatus())
        {
            // "Deutsche Handelsbank" account - waiting for another status
            case 'pending':
                $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                Registry::getUtils()->showMessageAndExit('payment pending');

            // "Deutsche Handelsbank" account - money not received
            case 'loss':
                $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                $this->_troCancelOrder($oTransactionData);
                break;

            // "Deutsche Handelsbank" account - money received
            case 'received':
                $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                $this->_troSetOrderPaid($oTransactionData);
                break;

            // no "Deutsche Handelsbank" account
            case 'untraceable':
                $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                $this->_troSetOrderPaid($oTransactionData);
                break;

            // money refunded
            case 'refunded':
                $this->_troFinalizeOrderIfStatusNotFinished($oTransactionData);
                $this->_troCancelOrder($oTransactionData);
                break;
        }
    }

    /**
     * get the transaction-ID
     *
     * @return string $sTransactionId
     *
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _getTroTransactionID()
    {
        $sTransactionId = Registry::getConfig()->getRequestParameter('transactionid');
        if ($sTransactionId == '')
        {
            // create SofortLib_Notification-object and fetch transaction-id
            $oNotification = $this->_getTroSofortLibNotificationObject();
            $oNotification->getNotification(file_get_contents('php://input'));
            $sTransactionId = $oNotification->getTransactionId();

            if ($sTransactionId == '')
            {
                Registry::getUtils()->showMessageAndExit('No info received');
            }
        }
        
        return $sTransactionId;
    }

    /**
     * @return Notification
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _getTroSofortLibNotificationObject()
    {
        return new Notification();
    }

    /**
     * get the transaction data
     *
     * @param string $sTransactionId
     *
     * @return TransactionData $oTransactionData
     *
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _getTroTransactionData($sTransactionId)
    {
        // create SofortLib_TransactionData-object and fetch some information for the transaction-id retrieved above
        $oTransactionData = $this->_getTroSofortLibTransactionDataObject();
        $oTransactionData->addTransaction($sTransactionId);
        $oTransactionData->sendRequest();

        return $oTransactionData;
    }

    /**
     * @return TransactionData
     * 
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.1
     */
    protected function _getTroSofortLibTransactionDataObject()
    {
        $sTroGatewayConfigKey = Registry::getConfig()->getConfigParam('sTroGatewayConfKey');
        $oTransactionData = new TransactionData($sTroGatewayConfigKey);
        $oTransactionData->setApiVersion(SofortConfiguration::getTroApiVersion());

        return $oTransactionData;
    }

    /**
     * stores received information to log-table trogatewaylog
     *
     * @param TransactionData $oTransactionData
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troStoreGatewayLog($oTransactionData)
    {
        $sTransactionId = $oTransactionData->getTransaction();
        $oTroGatewayLog = oxNew(TrosofortueberweisungGatewayLog::class);
        $aTroGatewayLogNewestEntry = $oTroGatewayLog->getTroNewestLog($sTransactionId);
        
        // Datum des bereits in der Datenbank gespeicherten Eintrags ist ungleich der aktuell vorliegenden Statusaenderung
        if ($aTroGatewayLogNewestEntry['STATUS'] != $oTransactionData->getStatus())
        {
            $oTroGatewayLog->trogatewaylog__transactionid = new Field($oTransactionData->getTransaction());
            $oTroGatewayLog->trogatewaylog__status = new Field($oTransactionData->getStatus());
            $oTroGatewayLog->trogatewaylog__statusreason = new Field($oTransactionData->getStatusReason());
            $oTroGatewayLog->trogatewaylog__timestamp = new Field($oTransactionData->getStatusModifiedTime());
            $oTroGatewayLog->save();
        }
    }

    /**
     * Wird nach Bezahlung auf der Seite von Sofortueberweisung 
     * z.B. der Browser geschlossen
     * und der User nicht mehr in den Shop zurückgeleitet,
     * führe die Bestellung nun zu Ende
     *
     * @param TransactionData $oTransactionData
     *
     * @author  tronet GmbH
     * @since   8.0.1
     * @version 8.0.6
     */
    protected function _troFinalizeOrderIfStatusNotFinished($oTransactionData)
    {
        $sTransactionId = $oTransactionData->getTransaction();
        $sOrderId = $this->_getTroOrderID($oTransactionData);
        $oOrder = oxNew(Order::class);
        $oOrder->load($sOrderId);

        if ($oOrder->oxorder__oxpaymenttype->value == 'trosofortgateway_su'
         && $oOrder->oxorder__oxtransstatus->value == 'NOT_FINISHED'
         && $oOrder->oxorder__oxtransid->value == $sTransactionId
         && $oOrder->oxorder__trousersession->rawValue)
        {
            $this->setAdminMode(true);

            Registry::getSession()->setBasket(null);
            $aSession = unserialize($oOrder->oxorder__trousersession->rawValue);
            foreach($aSession as $sVarname => $oVarvalue)
            {
                Registry::getSession()->setVariable($sVarname, $oVarvalue);
            }
            $oBasket = Registry::getSession()->getBasket();
            $oUser = $oOrder->getOrderUser();

            // Finish oxOrder::finalizeOrder
            $iSuccess = $oOrder->troContinueFinalizeOrder($oBasket, $oUser);

            // Finish order::execute
            // performing special actions after user finishes order (assignment to special user groups)
            $oUser->onOrderExecute($oBasket, $iSuccess);
        }
    }
    
    /**
     * cancels order
     *
     * @param TransactionData $oTransactionData
     *
     * @throws ConnectionException
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troCancelOrder($oTransactionData)
    {
        $sOrderId = $this->_getTroOrderID($oTransactionData);

        if ($sOrderId)
        {
            $oOrder = oxNew(Order::class);
            $oOrder->load($sOrderId);
            $oOrder->cancelOrder();

            $sSqlUpdate = "UPDATE oxorder SET oxpaid='' where oxid='$sOrderId'";
            DatabaseProvider::getDb()->execute($sSqlUpdate);

            Registry::getUtils()->showMessageAndExit('Order canceled');
        }
        Registry::getUtils()->showMessageAndExit('Order not found');
    }

    /**
     * sets order as paid
     *
     * @param TransactionData $oTransactionData
     *
     * @throws ConnectionException
     * 
     * @author  tronet GmbH
     * @since   8.0.0
     * @version 8.0.1
     */
    protected function _troSetOrderPaid($oTransactionData)
    {
        $sOrderId = $this->_getTroOrderID($oTransactionData);

        if ($sOrderId)
        {
            $sDate = $oTransactionData->getStatusModifiedTime();

            $sSqlUpdate = "UPDATE oxorder SET oxpaid='$sDate' where oxid='$sOrderId'";
            DatabaseProvider::getDb()->execute($sSqlUpdate);
            Registry::getUtils()->showMessageAndExit('Order updated');
        }
        Registry::getUtils()->showMessageAndExit('Order not found');
    }

    /**
     * gets oxorder.oxid for the current transaction-id
     *
     * @param TransactionData $oTransactionData
     *
     * @return string
     * @throws ConnectionException
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.1
     */
    protected function _getTroOrderID($oTransactionData)
    {
        $sTransactionId = $oTransactionData->getTransaction();
        $sSqlSelect = "SELECT OXID FROM oxorder where oxtransid='$sTransactionId'";

        $oDB = DatabaseProvider::getDb();
        $sOrderId = $oDB->getOne($sSqlSelect);
        return $sOrderId;
    }
}

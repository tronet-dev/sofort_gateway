<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\UtilsView;
use OxidEsales\Eshop\Application\Model\Country;
use Sofort\SofortLib\Sofortueberweisung;
use Tronet\Trosofortueberweisung\Core\Exception\SofortException;
use Tronet\Trosofortueberweisung\Core\SofortConfiguration;
use Tronet\Trosofortueberweisung\Core\Utility\LocalizationUtility;

/**
 * Payment gateway manager.
 * Checks and sets payment method data, executes payment.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.9
 *
 * @property string $sRedirUrl
 */
class TrosofortueberweisungPaymentGateway extends TrosofortueberweisungPaymentGateway_parent
{
    /**
     * @var array $_aTransactionPlaceholder
     * 
     * @author  tronet GmbH
     * @since   8.0.9
     * @version 8.0.9
     */
    protected $_aTransactionPlaceholder = [];

    /**
     * Executes payment gateway functionality.
     *
     * @extend executePayment
     *
     * @param double $dAmount order price
     * @param Order|TrosofortueberweisungOrder  $oOrder order object
     *
     * @return bool $mReturn
     * @since         7.0.0
     * @version       8.0.1
     */
    public function executePayment($dAmount, &$oOrder)
    {
        if ($this->_oPaymentInfo->oxuserpayments__oxpaymentsid->value == 'trosofortgateway_su')
        {
            // Commit every not committed query, so that order
            // is in database before redirecting to SOFORT.com
            $oDb = DatabaseProvider::getDb();
            if ($oDb->isTransactionActive())
            {
                $oDb->commitTransaction();
            }

            // Important hint: redirect is executed
            $this->_troExecutePayment($oOrder);
        }
        
        return parent::executePayment($dAmount, $oOrder);
    }

    /**
     * _troExecutePayment
     *
     * Creates an object of type Sofortueberweisung and stores data in it.
     *
     * The in shop configured language is not transmitted as of request by SOFORT AG.
     *
     * At the end a redirect is performed.
     *
     * @param Order $oOrder order object
     *
     * @author  tronet GmbH
     * @since    7.0.0
     * @version  8.0.9
     */
    protected function _troExecutePayment(&$oOrder)
    {
        // Initialize
        $oUser = $oOrder->getOrderUser();
        $oConfig = Registry::getConfig();

        $sGatewayReason1 = $this->_getTroPreparedGatewayReason($oOrder, $oConfig->getConfigParam('sTroGatewayReason'));
        $sGatewayReason2 = $this->_getTroPreparedGatewayReason($oOrder, $oConfig->getConfigParam('sTroGatewayReason2'));

        // Create new Sofortueberweisung and fill with data
        $oSofortueberweisung = new Sofortueberweisung($oConfig->getConfigParam('sTroGatewayConfKey'));
        $oSofortueberweisung->setApiVersion(SofortConfiguration::getTroApiVersion());
        $oSofortueberweisung->setReason($sGatewayReason1, $sGatewayReason2);

        $sUserCountryId = $oUser->oxuser__oxcountryid;
        $oCountry = oxNew(Country::class);
        $oCountry->load($sUserCountryId);
        $oSofortueberweisung->setSenderCountryCode($oCountry->oxcountry__oxisoalpha2);

        $totalOrderSumAmount = round($oOrder->oxorder__oxtotalordersum->value, 2);
        $oSofortueberweisung->setAmount($totalOrderSumAmount);

        // force_sid
        $force_sid = $this->_getTroForceSid();

        ########################################
        // Set urls
        $oSofortueberweisung->setSuccessUrl($oConfig->getSslShopUrl().'?cl=order&fnc=troContinueExecute&transactionid=-TRANSACTION-&orderid='.$oOrder->oxorder__oxid->value.'&force_sid='.$force_sid.'&shp='.$oConfig->getShopId());
        $oSofortueberweisung->setAbortUrl($oConfig->getSslShopUrl().'?cl=payment&fnc=troDeleteOldOrder&force_sid='.$force_sid.'&shp='.$oConfig->getShopId());
        $oSofortueberweisung->setTimeoutUrl($oConfig->getSslShopUrl().'?cl=payment&fnc=troDeleteOldOrder&force_sid='.$force_sid.'&shp='.$oConfig->getShopId());
        $oSofortueberweisung->setNotificationUrl($oConfig->getSslShopUrl().'?cl=tronet_trosofortueberweisung_notificationcontroller&shp='.$oConfig->getShopId());

        // Determine OXID eShop and trosofortueberweisung-Module-Version
        $aModuleVersions = $oConfig->getConfigParam('aModuleVersions');
        $sModuleVersion = 'oxid_'.$oConfig->getVersion().'; trosu_'.$aModuleVersions['trosofortueberweisung'];
        $oSofortueberweisung->setVersion($sModuleVersion);

        $oSofortueberweisung->setEmailCustomer($oUser->oxuser__oxusername->value);

        $oSofortueberweisung->setCurrencyCode($oConfig->getActShopCurrencyObject()->name);

        // After setting up an instance of Sofortueberweisung a request is send to the SOFORT API.
        // SOFORT API responses with a URL to which current customer is redirected to.
        $oSofortueberweisung->sendRequest();

        $this->_troRedirect($oSofortueberweisung, $oOrder);
    }

    /**
     * Returns a prepared gateway reason.
     *
     * @param string $sGatewayReason
     *
     * @return string
     *
     * @author  tronet GmbH
     * @since    8.0.9
     * @version  8.0.9
     */
    protected function _getTroPreparedGatewayReason($oOrder, $sGatewayReason)
    {
        $aTransactionPlaceholder = $this->_getTransactionPlaceholders($oOrder);

        $aTransactionPlaceholderKeys = array_keys($aTransactionPlaceholder);
        $aTransactionPlaceholderValues = array_values($aTransactionPlaceholder);

        $sGatewayReason = str_replace($aTransactionPlaceholderKeys, $aTransactionPlaceholderValues, $sGatewayReason);

        $sGatewayReason = $this->_troNormalizeReason($sGatewayReason);

        return $sGatewayReason;
    }
    
    /**
     * Returns a prepared gateway reason.
     *
     * @param string $sGatewayReason
     *
     * @return string
     *
     * @author  tronet GmbH
     * @since    8.0.9
     * @version  8.0.9
     */
    protected function _getTransactionPlaceholders($oOrder)
    {
        if (!isset($this->_aTransactionPlaceholder[$oOrder->oxorder__oxid->value]))
        {
            $oUser = $oOrder->getOrderUser();
            $oActiveShop = $this->getConfig()->getActiveShop();

            $this->_aTransactionPlaceholder[$oOrder->oxorder__oxid->value] = [
                '[BSTNR]' => $oOrder->oxorder__oxordernr->value,
                '[KNR]'   => $oUser->oxuser__oxcustnr->value,
                '[KNAME]' => $oUser->oxuser__oxlname->value,
                '[DATUM]' => date('d.m.Y'),
                '[PRICE]' => number_format($oOrder->oxorder__oxtotalordersum->value, 2, ',', ''),
                '[SHP]'   => utf8_decode($oActiveShop->oxshops__oxname->value),
            ];
        }

        return $this->_aTransactionPlaceholder[$oOrder->oxorder__oxid->value];
    }

    /**
     * @return string
     *
     * @author  tronet GmbH
     * @since    8.0.9
     * @version  8.0.9
     */
    public function _getTroForceSid()
    {
        $force_sid = $this->getConfig()->getRequestParameter('force_sid');
        
        if (!$force_sid)
        {
            $force_sid = $_COOKIE['force_sid'];
        }
        
        if (!$force_sid)
        {
            $force_sid = $_SESSION['force_sid'];
        }

        return $force_sid;
    }

    /**
     * _troNormalizeReason
     *
     * Replaces umlaut as not every bank supports them in transferal reasons.
     *
     * @param string $sString The string to process.
     *
     * @return string
     * 
     * @author  tronet GmbH
     * @since    8.0.9
     * @version  8.0.9
     */
    protected function _troNormalizeReason($sString)
    {
        $oTranslationUtility = oxNew(LocalizationUtility::class);

        $sString = strip_tags(html_entity_decode($sString, ENT_QUOTES));
        $sString = $oTranslationUtility->troRemoveAccents($sString);
        $sString = preg_replace("/[^a-zA-Z0-9+,-.\s]/", '', $sString);
        
        return substr($sString, 0, 27);
    }

    /**
     * checks for error and redirects to SOFORT or back to payment-view
     *
     * @param Sofortueberweisung $oSofortueberweisung
     * @param Order              $oOrder order object
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    protected function _troRedirect($oSofortueberweisung, &$oOrder)
    {
        if ($oSofortueberweisung->isError())
        {
            // Data transmitted to SOFORT AG are invalid and cannot be processed by SOFORT AG.
            $oLang = Registry::getLang();
            $sBaseLanguage = $oLang->getBaseLanguage();
            $sErrorMessage = $oLang->translateString('TRO_SOFORTGATEWAY_PAYMENTERROR', $sBaseLanguage);
            Registry::get(UtilsView::class)->addErrorToDisplay($sErrorMessage, false, true);

            // Add a log entry
            $aErrors = $oSofortueberweisung->getErrors();
            if (is_array($aErrors))
            {
                foreach ($aErrors as $aErrorDetails)
                {
                    $oSofortException = oxNew(SofortException::class, $aErrorDetails['message'], $aErrorDetails['code'], 'Payment-redirect to Sofort AG', Registry::getConfig());
                    $oSofortException->debugOut();
                }
            }

            // Delete initiated order, show order step 3 (payment) and prompt him to choose an other payment
            // method.
            $this->sRedirUrl = Registry::getConfig()->getSslShopUrl() . '?cl=payment&fnc=troDeleteOldOrder';
            Registry::getUtils()->redirect($this->sRedirUrl);
        }
        else
        {
            // Data transmitted to SOFORT AG are valid. Save the Transaction-ID and redirect to SOFORT AG.
            $sTransactionId = $oSofortueberweisung->getTransactionId();
            $oOrder->oxorder__oxtransid = new Field($sTransactionId);
            $oOrder->save();
            $this->sRedirUrl = $oSofortueberweisung->getPaymentUrl();
            Registry::getUtils()->redirect($this->sRedirUrl);
        }
    }
}

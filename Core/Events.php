<?php

namespace Tronet\Trosofortueberweisung\Core;

use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\ConnectionException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;

/**
 * Event handler class for OXID eShop module tronet/trosofortueberweisung.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.6
 */
class Events
{
    /**
     * Performs required actions when activating the module.
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.6
     */
    public static function onActivate()
    {
        $oDb = DatabaseProvider::getDb(false);

        $blChangedDb = false;

		$sSqlcheck = "SELECT count(*)
            FROM information_schema.columns
            WHERE table_schema = '" . Registry::getConfig()->getConfigParam('dbName') . "' AND table_name = 'oxorder' AND column_name = 'TROUSERSESSION'";

		if ($oDb->getRow($sSqlcheck)[0] == 0)
        {
		    $aSql[] = "ALTER TABLE `oxorder` ADD `TROUSERSESSION` LONGBLOB NOT NULL;";
		}

        $aSql[] = "CREATE TABLE IF NOT EXISTS `trogatewaylog` (
            `OXID`          VARCHAR(32)   NOT NULL,
            `TRANSACTIONID` VARCHAR(255)  NOT NULL,
            `TRANSACTION`   VARCHAR(255)  NOT NULL,
            `STATUS`        VARCHAR(255)  NOT NULL,
            `STATUSREASON`  VARCHAR(255)  NOT NULL,
            `TIMESTAMP`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`OXID`)
        )";

        $aSql[] = "INSERT IGNORE INTO `oxpayments` SET
            `OXID`          = 'trosofortgateway_su',
            `OXACTIVE`      = 1,
            `OXDESC`        = 'Sofort.',
            `OXDESC_1`      = 'Sofort.',
            `OXADDSUM`      = 0,
            `OXADDSUMTYPE`  = 'abs',
            `OXADDSUMRULES` = 15,
            `OXFROMBONI`    = 0,
            `OXFROMAMOUNT`  = 0,
            `OXTOAMOUNT`    = 999999,
            `OXCHECKED`     = 1,
            `OXSORT`        = 1,
            `OXLONGDESC`    = '<div id=\"payment_form_sofortueberweisung\">Einfach und direkt bezahlen per Online Banking.</div><div class=\"clear\"></div>',
            `OXLONGDESC_1`  = '<div id=\"payment_form_sofortueberweisung\">Simple and secure.</div><div class=\"clear\"></div>';
        ";

        foreach($aSql as $sSql)
        {
            try {
                $oDb->execute($sSql);
                $blChangedDb = true;
            } catch ( ConnectionException $oE ) {
            }
        }

        if ($blChangedDb)
        {
            // Views aktualisieren
            $oMetaData = oxNew(DbMetaDataHandler::class);
            $oMetaData->updateViews();

            // tmp-Ordner leeren
            self::troClearTmp();
        }

        // Zahlungsart aktivieren
        self::setTroPaymentMethodActiveStatus('trosofortgateway_su', 1);
    }

    /**
     * Set the active status of a payment method.
     *
     * @param string $sPaymentMethod
     * @param int    $iStatus
     *
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public static function setTroPaymentMethodActiveStatus($sPaymentMethod, $iStatus)
    {
        $oPayment = oxNew(Payment::class);
        if ($oPayment->load($sPaymentMethod))
        {
            $oPayment->oxpayments__oxactive = new Field($iStatus);
            $oPayment->save();
        }
    }

    /**
     * Performs required actions when deactivating the module.
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   7.0.0
     * @version 8.0.0
     */
    public static function onDeactivate()
    {
        self::setTroPaymentMethodActiveStatus('trosofortgateway_su', 0);

        return true;
    }

    /**
    * Clear tmp
    *
    * @author tronet GmbH
    * @since  8.0.6
    */
    public static function troClearTmp()
    {
        foreach (glob(getShopBasePath() . "tmp/*_oxorder_*") as $filename)
        {
            @unlink($filename);
        }
    }
}

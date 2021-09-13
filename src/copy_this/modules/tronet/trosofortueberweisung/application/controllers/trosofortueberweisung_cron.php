<?php

/**
 * Cronjob-Controller
 *
 * Löscht bzw. storniert unvollständige Sofortüberweisungs-Bestellungen, 
 * die älter als eine Stunde sind
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.3
 * @version       7.0.3
 */
class trosofortueberweisung_cron extends oxUBase
{
    /**
     * @author  tronet GmbH
     * @since   7.0.3
     * @version 7.0.3
     */
    public function render()
    {
        parent::render();
        $oDB = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $sSelectSql = "SELECT oxid FROM oxorder
            WHERE oxtransstatus = 'NOT_FINISHED'
            AND oxpaymenttype = 'trosofortgateway_su'
            AND oxorderdate < date_sub(NOW(), INTERVAL 1 HOUR)
            AND oxstorno = 0";            
        $aDbResult = $oDB->getAll($sSelectSql);
        
        foreach($aDbResult as $aDbRow)
        {
            $sOrderId = $aDbRow['oxid'];
            $oOrder = oxNew('oxorder');
            $oOrder->load($sOrderId);
            $oOrder->troDeleteOldOrder();
        }
        
        // just exit, as we are not in frontend here
        oxRegistry::getUtils()->showMessageAndExit('');
    }
}

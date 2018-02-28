<?php

    namespace Tronet\Trosofortueberweisung\Application\Controller;

    use OxidEsales\Eshop\Application\Controller\FrontendController;
    use OxidEsales\Eshop\Core\DatabaseProvider;
    use OxidEsales\Eshop\Core\Registry;
    use OxidEsales\Eshop\Application\Model\Order;

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
     * @since         8.0.1
     * @version       8.0.1
     */
    class TrosofortueberweisungCronController extends FrontendController
    {
        /**
         * @author  tronet GmbH
         * @since   8.0.1
         * @version 8.0.1
         */
        public function render()
        {
            parent::render();
            $oDB = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

            $sSelectSql = "SELECT oxid FROM oxorder
                WHERE oxtransstatus = 'NOT_FINISHED'
                AND oxpaymenttype = 'trosofortgateway_su'
                AND oxorderdate < date_sub(NOW(), INTERVAL 1 HOUR)
                AND oxstorno = 0";            
            $aDbResult = $oDB->getAll($sSelectSql);
            
            foreach($aDbResult as $aDbRow)
            {
                $sOrderId = $aDbRow['oxid'];
                $oOrder = oxNew(Order::class);
                $oOrder->load($sOrderId);
                $oOrder->troDeleteOldOrder();
            }
            
            // just exit, as we are not in frontend here
            Registry::getUtils()->showMessageAndExit('');
        }
    }

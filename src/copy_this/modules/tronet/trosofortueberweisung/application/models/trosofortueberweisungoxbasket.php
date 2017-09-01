<?php
    /**
     * @file      trosofortueberweisungoxbasket.php
     * @link      http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package   modules
     * @addtogroup models
     * @extend oxbasket
     */

    class trosofortueberweisungoxbasket extends trosofortueberweisungoxbasket_parent
    {
        public function troStoreInSession()
        {
            $oSession = new oxSession();
            /** @var trosofortueberweisungoxbasketitem $oBasketItem */
            foreach ( $this->_aBasketContents as $sItemKey => $oBasketItem )
            {
                $oSession->setVariable('trosubasket'.$sItemKey, $oBasketItem);
                $oBasketItem->troStoreInSession($sItemKey);
            }
        }

        public function troGetFromSession()
        {
            $oSession = new oxSession();
            /** @var trosofortueberweisungoxbasketitem $oBasketItem */
            foreach ( $this->_aBasketContents as $sItemKey => $oBasketItem )
            {
                $oBasketItem = $oSession->getVariable('trosubasket'.$sItemKey);
                $oBasketItem->troGetFromSession($sItemKey);
            }
        }
    }

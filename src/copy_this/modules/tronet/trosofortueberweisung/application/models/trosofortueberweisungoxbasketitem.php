<?php
    /**
     * @file      trosofortueberweisungoxbasketitem.php
     * @link      http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package   modules
     * @addtogroup models
     * @extend oxbasketitem
     */

    class trosofortueberweisungoxbasketitem extends trosofortueberweisungoxbasketitem_parent
    {
        public function troStoreInSession($sItemKey)
        {
            $oSession = new oxSession();
            $oSession->setVariable('trosubasketitem'.$sItemKey, $this->_oArticle);
        }

        public function troGetFromSession($sItemKey)
        {
            $oSession = new oxSession();
            $this->_oArticle = $oSession->getVariable('trosubasketitem'.$sItemKey);
        }
    }

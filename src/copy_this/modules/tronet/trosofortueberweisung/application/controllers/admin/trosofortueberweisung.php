<?php
class trosofortueberweisung extends oxAdminView {
    
    function render() 
    {        
        parent::render();
        
        $sCurrentAdminShop = oxRegistry::getSession()->getVariable('currentadminshop');
        
        if ( !$sCurrentAdminShop ) {
            if ( oxRegistry::getSession()->getVariable('malladmin') ) {
                $sCurrentAdminShop = 'oxbaseshop';
            } else {
                $sCurrentAdminShop = oxRegistry::getSession()->getVariable('actshop');
            }
        }
        
        $this->_aViewData['linkFix'] = "?";
        $aVersion = explode( '.', $this->getConfig()->getActiveShop()->oxshops__oxversion->value );
        if( 4 == $aVersion[0] && 3 <= $aVersion[1] ) $this->_aViewData['linkFix'] = "";
        
        $this->_aViewData['currentadminshop'] = $sCurrentAdminShop;
        oxRegistry::getSession()->setVariable('currentadminshop', $sCurrentAdminShop);
        
        return 'trosofortueberweisung.tpl';
    }
}

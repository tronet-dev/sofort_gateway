<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\Basket;

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         8.0.6
 * @version       8.0.6
 */
class TrosofortueberweisungBasket extends TrosofortueberweisungBasket_parent
{
    /**
     * Lade $this->_oArticle mit einem oxarticle-Objekt statt einem oxorderarticle-Objekt
     *
     * @author  tronet GmbH
     * @since   8.0.6
     * @version 8.0.6
     */
    public function __wakeUp()
    {
        $ret = parent::__wakeUp();

        foreach ($this->_aBasketContents as $sItemKey => $oBasketItem)
        {
            $oBasketItem->getArticle();
        }

        return $ret;
    }
}

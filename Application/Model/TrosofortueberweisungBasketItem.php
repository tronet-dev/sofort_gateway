<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\BasketItem;

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         8.0.2
 * @version       8.0.2
 */
class TrosofortueberweisungBasketItem extends TrosofortueberweisungBasketItem_parent
{
    /**
     * Lade $this->_oArticle mit einem oxarticle-Objekt statt einem oxorderarticle-Objekt
     *
     * @author  tronet GmbH
     * @since   8.0.2
     * @version 8.0.2
     */
    protected function _setFromOrderArticle($oOrderArticle)
    {
        parent::_setFromOrderArticle($oOrderArticle);

        if ($oOrderArticle->getTroUseArticleInsteadOfOrderArticle())
        {
            $this->_oArticle = $oOrderArticle->getArticle();
            $this->setStockCheckStatus(false);
        }
    }
}

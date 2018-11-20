<?php

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.6
 * @version       7.0.6
 */
class trosofortueberweisungoxbasket extends trosofortueberweisungoxbasket_parent
{
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

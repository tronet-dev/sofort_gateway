<?php

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.6
 * @version       7.0.11
 */
class trosofortueberweisungoxbasket extends trosofortueberweisungoxbasket_parent
{
    /**
     * @var string $_blTroRecalculatedBasket
     * 
     * @author  tronet GmbH
     * @since   7.0.10
     * @version 7.0.10
     */
    protected $_blTroRecalculatedBasket = false;

    public function __wakeUp()
    {
        $ret = parent::__wakeUp();

        foreach ($this->_aBasketContents as $oBasketItem)
        {
            $oBasketItem->getArticle(false);
        }

        return $ret;
    }

    /**
     * @author  tronet GmbH
     * @since   7.0.10
     * @version 7.0.10
     */
    public function setTroRecalculatedBasket($blTroRecalculatedBasket)
    {
        $this->_blTroRecalculatedBasket = $blTroRecalculatedBasket;
    }

    /**
     * @author  tronet GmbH
     * @since   7.0.10
     * @version 7.0.10
     */
    public function troIsRecalculatedBasket()
    {
        return $this->_blTroRecalculatedBasket;
    }

    /**
     * Adds order articles to basket without removing bundle articles
     *
     * @author  tronet GmbH
     * @since   7.0.11
     * @version 7.0.11
     */
    public function troAddOrderArticlesToBasket($aOrderArticles)
    {
        if (count($aOrderArticles) > 0)
        {
            foreach ($aOrderArticles as $oOrderArticle)
            {
                if ($oOrderArticle->oxorderarticles__oxamount->value > 0 && !$oOrderArticle->isBundle()) {
                    $this->_isForOrderRecalculation = true;
                    $sItemId = $oOrderArticle->getId();
        
                    //inserting new
                    $this->_aBasketContents[$sItemId] = oxNew('oxbasketitem');
                    $this->_aBasketContents[$sItemId]->initFromOrderArticle($oOrderArticle);
                    $this->_aBasketContents[$sItemId]->setWrapping($oOrderArticle->oxorderarticles__oxwrapid->value);
                    $this->_aBasketContents[$sItemId]->setBundle($oOrderArticle->isBundle());
        
                    //calling update method
                    $this->onUpdate();
        
                    return $this->_aBasketContents[$sItemId];
                }
            }
        }
    }

}

<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2020
 * @author        tronet GmbH
 *
 * @since         8.0.10
 * @version       8.0.10
 */
class TrosofortueberweisungDiscount extends TrosofortueberweisungDiscount_parent
{
    /**
     * Tests if total amount or price (price priority) of articles that can be applied to current discount fits to discount configuration
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket basket
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   8.0.10
     * @version 8.0.10
     */
    public function isForBasketAmount($oBasket)
    {
        if($oBasket->troIsRecalculatedBasket()) {
            return $this->_troIsForBasketAmount($oBasket);
        }

        return parent::isForBasketAmount($oBasket);
    }

    /**
     * Tests if total amount or price (price priority) of articles that can be applied to current discount fits to discount configuration.
     * Calculates with Article objects not with OrderArticle objects because otherwise the recalculation for SOFORT gets some discounts wrong.
     *
     * @param \OxidEsales\Eshop\Application\Model\Basket $oBasket basket
     *
     * @return bool
     * 
     * @author  tronet GmbH
     * @since   8.0.10
     * @version 8.0.10
     */
    protected function _troIsForBasketAmount($oBasket) {
        $dAmount = 0;
        $aBasketItems = $oBasket->getContents();
        foreach ($aBasketItems as $oBasketItem) {
            $oBasketArticle = $oBasketItem->getArticle(false);

            
            if($oBasketArticle->isOrderArticle()) {
                $oArticle = $oBasketArticle->getArticle();
            } else {
                $oArticle = $oBasketArticle;
            }

            $blForBasketItem = false;
            if ($this->oxdiscount__oxaddsumtype->value != 'itm') {
                $blForBasketItem = $this->isForBasketItem($oBasketArticle);
            } else {
                $blForBasketItem = $this->isForBundleItem($oBasketArticle);
            }

            if ($blForBasketItem) {
                $dRate = $oBasket->getBasketCurrency()->rate;
                if ($this->oxdiscount__oxprice->value) {
                    if (($oPrice = $oArticle->getPrice())) {
                        $dAmount += ($oPrice->getPrice() * $oBasketItem->getAmount()) / $dRate;
                    }
                } elseif ($this->oxdiscount__oxamount->value) {
                    $dAmount += $oBasketItem->getAmount();
                }
            }
        }

        return $this->isForAmount($dAmount);
    }
}
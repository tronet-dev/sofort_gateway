<?php
/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.4
 * @version       7.0.4
 */
class trosofortueberweisungoxbasketitem extends trosofortueberweisungoxbasketitem_parent
{
    /**
     * Lade $this->_oArticle mit einem oxarticle-Objekt statt einem oxorderarticle-Objekt
     *
     * @author  tronet GmbH
     * @since   7.0.4
     * @version 7.0.4
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

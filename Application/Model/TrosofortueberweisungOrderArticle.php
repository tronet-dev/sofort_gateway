<?php

namespace Tronet\Trosofortueberweisung\Application\Model;

use OxidEsales\Eshop\Application\Model\OrderArticle;

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         8.0.2
 * @version       8.0.2
 */
class TrosofortueberweisungOrderArticle extends TrosofortueberweisungOrderArticle_parent 
{
    /**
     * @var string $_blTroUseArticleInsteadOfOrderArticle
     * 
     * @author  tronet GmbH
     * @since   8.0.2
     * @version 8.0.2
     */
    protected $_blTroUseArticleInsteadOfOrderArticle = null;

    /**
     * setter für $_blTroUseArticleInsteadOfOrderArticle
     *
     * @author  tronet GmbH
     * @since   8.0.2
     * @version 8.0.2
     */
    public function setTroUseArticleInsteadOfOrderArticle()
    {
        $this->_blTroUseArticleInsteadOfOrderArticle = true;
    }

    /**
     * getter für $_blTroUseArticleInsteadOfOrderArticle
     *
     * @author  tronet GmbH
     * @since   8.0.2
     * @version 8.0.2
     */
    public function getTroUseArticleInsteadOfOrderArticle()
    {
        return $this->_blTroUseArticleInsteadOfOrderArticle;
    }
}

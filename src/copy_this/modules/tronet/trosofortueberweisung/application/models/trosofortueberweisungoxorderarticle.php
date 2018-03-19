<?php

/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.4
 * @version       7.0.4
 */
class trosofortueberweisungoxorderarticle extends trosofortueberweisungoxorderarticle_parent 
{
    /**
     * @var string $_blTroUseArticleInsteadOfOrderArticle
     * 
     * @author  tronet GmbH
     * @since   7.0.4
     * @version 7.0.4
     */
    protected $_blTroUseArticleInsteadOfOrderArticle = null;

    /**
     * setter für $_blTroUseArticleInsteadOfOrderArticle
     *
     * @author  tronet GmbH
     * @since   7.0.4
     * @version 7.0.4
     */
    public function setTroUseArticleInsteadOfOrderArticle()
    {
        $this->_blTroUseArticleInsteadOfOrderArticle = true;
    }

    /**
     * getter für $_blTroUseArticleInsteadOfOrderArticle
     *
     * @author  tronet GmbH
     * @since   7.0.4
     * @version 7.0.4
     */
    public function getTroUseArticleInsteadOfOrderArticle()
    {
        return $this->_blTroUseArticleInsteadOfOrderArticle;
    }
}

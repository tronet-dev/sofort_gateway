<?php

/**
 * Main admin controller for SOFORT Banking by tronet.
 *
 * This backend controller provides a UI for following
 * business processes:
 *      * manual check for new versions
 *      * manual check if and what files has been adjusted
 *
 * @file          trosofortueberweisung_main.php
 * @link          http://www.tro.net
 * @copyright (C) tronet GmbH 2016
 * @package       modules
 * @addtogroup    application/controllers/admin
 * @author        tronet GmbH
 * @since         7.0.0
 */
class trosofortueberweisung_list extends oxAdminList
{
    /**
     * Extends rendering process by our needs.
     *
     * @return string
     * @author tronet GmbH
     * @since  7.0.0
     */
    public function render()
    {
        parent::render();
        return "trosofortueberweisung_list.tpl";
    }
}

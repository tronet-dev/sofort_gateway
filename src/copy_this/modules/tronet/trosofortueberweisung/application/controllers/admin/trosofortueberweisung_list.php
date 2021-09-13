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
 * @copyright (C) tronet GmbH 2018
 * @package       modules
 * @addtogroup    application/controllers/admin
 * @author        tronet GmbH
 * @since         7.0.0
 */
class trosofortueberweisung_list extends oxAdminList
{
    /**
     * @var ThisTemplate $_sThisTemplate
     *
     * @author        tronet GmbH
     * @since         7.0.9
     * @version       7.0.9
     */
    protected $_sThisTemplate = 'trosofortueberweisung_list.tpl';
}

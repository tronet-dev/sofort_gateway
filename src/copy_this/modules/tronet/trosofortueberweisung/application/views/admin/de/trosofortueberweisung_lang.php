<?php
    /**
     * Translation file for OXID eShop. This file is required for among other
     * OXID eShop versions 4.9.3 to 4.9.7
     *
     * @file          trosofortueberweisung_lang.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    modules
     */

    /*
     * General configuration
     */
    $sTheme = 'admin';
    $sLanguageKey = 'de';

    /*
     * Gather required paths
     */
    $sShopHomeDir = oxRegistry::getConfig()->getConfigParam('sShopDir');
    $sModulePath = oxNew('oxModule')->getModulePath('trosofortueberweisung');

    $sAbsolutePath = $sShopHomeDir . 'modules/' . $sModulePath . '/library/translations/' . $sTheme . '/' . $sLanguageKey . '/trosofortueberweisung_lang.php';

    /*
     * Include master translation file
     */
    include_once($sAbsolutePath);

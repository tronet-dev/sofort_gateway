<?php

/**
 * Provides methods for logging actions.
 *
 * @file          trosofortueberweisungloggingutility.php
 * @link          http://www.tro.net
 * @copyright (C) tronet GmbH 2018
 * @package       modules
 * @addtogroup    core/utility
 * @author        tronet GmbH
 * @since         7.0.0
 */
class trosofortueberweisungloggingutility
{
    /**
     * @var trosofortueberweisungconfig|null $_oTroSOFORTConfig
     * @author tronet GmbH
     * @since  7.0.0
     */
    protected $_oTroSOFORTConfig = null;

    /**
     * @param string $sLevel INFO|WARNING|...
     * @param string $sMessage
     *
     * @author tronet GmbH
     * @since  7.0.0
     */
    public function writeToLog($sLevel, $sMessage)
    {
        $sLogTime = date('Y-m-d_h-i-s', time());
        $sFinalLogMessage = sprintf($this->getLogTemplate(), $sLogTime, $sLevel, $sMessage);
        oxRegistry::getUtils()->writeToLog($sFinalLogMessage, $this->getTroSOFORTConfig()->getLogFile());
    }

    /**
     * Returns the log template. Where the first placeholder
     * is the date, the second the level and the third the
     * actual message to log.
     *
     * @return string
     * @author tronet GmbH
     * @since  7.0.0
     */
    protected function getLogTemplate()
    {
        return '[%1$s][%2$s] %3$s' . "\n";
    }

    /**
     * @return trosofortueberweisungconfig
     * @author tronet GmbH
     * @since  7.0.0
     */
    public function getTroSOFORTConfig()
    {
        if ($this->_oTroSOFORTConfig == null)
        {
            $this->_oTroSOFORTConfig = oxNew('trosofortueberweisungconfig');
        }

        return $this->_oTroSOFORTConfig;
    }

    /**
     * @param trosofortueberweisungconfig $oTroSOFORTConfig
     *
     * @author tronet GmbH
     * @since  7.0.0
     */
    public function setTroSOFORTConfig($oTroSOFORTConfig)
    {
        $this->_oTroSOFORTConfig = $oTroSOFORTConfig;
    }
}

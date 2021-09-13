<?php

/**
 * Order Notifications Logger.
 *
 * Stores Notification-messages from SOFORT, containing status-changes, in DB
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       7.0.0
 */
class trosofortueberweisunggatewaylog extends oxI18n
{
    /**
     * Object core table name
     *
     * @var string
     */
    protected $_sCoreTbl = 'trogatewaylog';

    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'trosofortueberweisunggatewaylog';

    /**
     * Set $_blUseLazyLoading to true if you want to load only actually used fields not full object, depending on
     * views.
     *
     * @var bool
     */
    protected $_blUseLazyLoading = false;

    /**
     * Class constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->init($this->_sCoreTbl);
    }
    
    /**
     * @author  tronet GmbH
     * @since   7.0.3
     * @version 7.0.3
     */
    public function getTroNewestLog($sTransactionId)
    {
        $oDB = oxDb::getDb(false);
        $sSelect = "SELECT * FROM trogatewaylog WHERE transactionid = '$sTransactionId' ORDER BY timestamp DESC LIMIT 1";
        return $oDB->getRow($sSelect);
    }
}

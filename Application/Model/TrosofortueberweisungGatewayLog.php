<?php
    namespace Tronet\Trosofortueberweisung\Application\Model;

    use OxidEsales\Eshop\Core\Model\MultiLanguageModel;
    use OxidEsales\Eshop\Core\DatabaseProvider;

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
     * @version       8.0.0
     */
    class TrosofortueberweisungGatewayLog extends MultiLanguageModel
    {
        /**
         * Object core table name
         *
         * @var string
         */
        protected $_sCoreTable = 'trogatewaylog';

        /**
         * Current class name
         *
         * @var string
         */
        protected $_sClassName = TrosofortueberweisungGatewayLog::class;

        /**
         * Set $_blUseLazyLoading to true if you want to load only actually used fields not full object, depending on
         * views.
         *
         * @var bool
         */
        protected $_blUseLazyLoading = false;

        /**
         * Class constructor
         */
        public function __construct()
        {
            parent::__construct();
            $this->init($this->_sCoreTable);
        }
        
        /**
         * @author  tronet GmbH
         * @since   8.0.1
         * @version 8.0.1
         */
        public function getTroNewestLog($sTransactionId)
        {
            $oDB = DatabaseProvider::getDb(false);
            $sSelect = "SELECT * FROM trogatewaylog WHERE transactionid = '$sTransactionId' ORDER BY timestamp DESC LIMIT 1";
            return $oDB->getRow($sSelect);
        }
    }

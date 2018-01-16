<?php

    namespace Tronet\Trosofortueberweisung\Application\Model;

    use OxidEsales\Eshop\Core\Model\MultiLanguageModel;

    /**
     * Order Notifications Logger.
     *
     * Stores Notification-messages from SOFORT, containing status-changes, in DB
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2017
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
        protected $_sCoreTbl = 'trogatewaylog';

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
         * @inheritdoc
         */
        public function __construct()
        {
            parent::__construct();
            $this->init($this->_sCoreTbl);
        }
    }

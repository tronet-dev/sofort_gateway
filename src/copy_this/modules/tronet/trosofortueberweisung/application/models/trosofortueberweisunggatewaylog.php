<?php
    /**
     * @file          trosofortueberweisunggatewaylog.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2017
     * @package       modules
     * @addtogroup    models
     * @extend        oxI18n
     */

    /**
     * Order Notifications Logger.
     *
     * Stores Notification-messages from SOFORT, containing status-changes, in DB
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
    }

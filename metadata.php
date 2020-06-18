<?php
    /**
     * Metadata file for module tronet/trosofortueberweisung.
     *
     * @link          http://www.tro.net
     * @copyright (c) tronet GmbH 2018
     * @author        tronet GmbH
     *
     * @since         8.0.0
     */
    $sMetadataVersion = '2.0';

    $aModule = [
        'id'          => 'trosofortueberweisung',
        'title'       => 'Sofort.',
        'description' => [
            'de' => 'Sofort. by tronet',
            'en' => 'Sofort. by tronet',
        ],
        'thumbnail'   => "out/img/logo_sofort_fallback.png",
        'version'     => '8.0.8',
        'author'      => 'tronet GmbH',
        'email'       => 'integration@sofort.com',
        'url'         => 'https://www.tronet.media/',
        'blocks'      => [
            // Backend
            [
                'template' => 'order_overview.tpl',
                'block'    => 'admin_order_overview_checkout',
                'file'     => 'trosofortueberweisung_order_payment_status.tpl',
            ],
            // Frontend
            [
                'template' => 'layout/base.tpl',
                'block'    => 'base_style',
                'file'     => 'trosofortueberweisung_base_style.tpl',
            ],
            [
                'template' => 'widget/sidebar/partners.tpl',
                'block'    => 'partner_logos',
                'file'     => 'trosofortueberweisung_partnerbox.tpl',
            ],
            [
                'template' => 'page/checkout/payment.tpl',
                'block'    => 'select_payment',
                'file'     => 'trosofortueberweisung_paymentSelector.tpl',
            ],
        ],
        'templates'   => [
            'trosofortueberweisung.tpl'                        => 'tronet/trosofortueberweisung/Application/views/admin/tpl/trosofortueberweisung.tpl',
            'trosofortueberweisung_list.tpl'                   => 'tronet/trosofortueberweisung/Application/views/admin/tpl/trosofortueberweisung_list.tpl',
            'trosofortueberweisung_main.tpl'                   => 'tronet/trosofortueberweisung/Application/views/admin/tpl/trosofortueberweisung_main.tpl',
            'trosofortueberweisungorder_notifications.tpl'     => 'tronet/trosofortueberweisung/Application/views/admin/tpl/trosofortueberweisungorder_notifications.tpl',
            'trosofortueberweisung_updateavailable.tpl'        => 'tronet/trosofortueberweisung/Application/views/admin/tpl/messages/trosofortueberweisung_updateavailable.tpl',
            'trosofortueberweisung_updateavailable_failed.tpl' => 'tronet/trosofortueberweisung/Application/views/admin/tpl/messages/trosofortueberweisung_updateavailable_failed.tpl',
            'trosofortueberweisung_noupdateavailable.tpl'      => 'tronet/trosofortueberweisung/Application/views/admin/tpl/messages/trosofortueberweisung_noupdateavailable.tpl',
        ],
        'events'      => [
            'onActivate'   => '\Tronet\Trosofortueberweisung\Core\Events::onActivate',
            'onDeactivate' => '\Tronet\Trosofortueberweisung\Core\Events::onDeactivate',
        ],
        'controllers' => [
            // Controller - FE
            'tronet_trosofortueberweisung_croncontroller'                                         => \Tronet\Trosofortueberweisung\Application\Controller\TrosofortueberweisungCronController::class,
            'tronet_trosofortueberweisung_notificationcontroller'                                 => \Tronet\Trosofortueberweisung\Application\Controller\TrosofortueberweisungNotificationController::class,

            // Controller - BE
            'tronet_trosofortueberweisung_admin_trosofortueberweisungcontroller'                  => \Tronet\Trosofortueberweisung\Application\Controller\Admin\TrosofortueberweisungController::class,
            'tronet_trosofortueberweisung_admin_trosofortueberweisunglistcontroller'              => \Tronet\Trosofortueberweisung\Application\Controller\Admin\TrosofortueberweisungListController::class,
            'tronet_trosofortueberweisung_admin_trosofortueberweisungmaincontroller'              => \Tronet\Trosofortueberweisung\Application\Controller\Admin\TrosofortueberweisungMainController::class,
            'tronet_trosofortueberweisung_admin_trosofortueberweisungordernotificationcontroller' => \Tronet\Trosofortueberweisung\Application\Controller\Admin\TrosofortueberweisungOrderNotificationController::class,
        ],
        'extend'      => [
            // Model
            \OxidEsales\Eshop\Application\Model\PaymentGateway::class                  => \Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungPaymentGateway::class,
            \OxidEsales\Eshop\Application\Model\Basket::class                          => \Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungBasket::class,
            \OxidEsales\Eshop\Application\Model\Order::class                           => \Tronet\Trosofortueberweisung\Application\Model\TrosofortueberweisungOrder::class,

            // Controller - FE
            \OxidEsales\Eshop\Application\Controller\OrderController::class            => \Tronet\Trosofortueberweisung\Application\Controller\TrosofortueberweisungOrderController::class,
            \OxidEsales\Eshop\Application\Controller\PaymentController::class          => \Tronet\Trosofortueberweisung\Application\Controller\TrosofortueberweisungPaymentController::class,

            // Controller - BE
            \OxidEsales\Eshop\Application\Controller\Admin\NavigationController::class => \Tronet\Trosofortueberweisung\Application\Controller\Admin\TrosofortueberweisungNavigationController::class,
        ],
        'settings'    => [
            [
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayConfKey',
                'type'  => 'str',
                'value' => '',
            ],
            [
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayReason',
                'type'  => 'str',
                'value' => 'Bestellnr. [BSTNR]',
            ],
            [
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayReason2',
                'type'  => 'str',
                'value' => '[SHP]',
            ],
            [
                'group'       => 'troPaymentHandling',
                'name'        => 'iTroGatewayCanceledOrders',
                'type'        => 'select',
                'value'       => '0',
                'constraints' => '0|1',
            ],
            [
                'group' => 'troUpdateRoutine',
                'name'  => 'blTroGateWayUpdateCheck',
                'type'  => 'bool',
                'value' => '1',
            ],
            [
                'group' => 'troOtherSettings',
                'name'  => 'blTroGateWayShowLogoInDeliverAndPayment',
                'type'  => 'bool',
                'value' => '1',
            ],
        ],

    ];

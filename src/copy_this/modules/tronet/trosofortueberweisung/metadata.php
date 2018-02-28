<?php
    /**
     * Metadata file for module tronet/trosofortueberweisung.
     *
     * @file          metadata.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    modules
     */

    /**
     * Module information
     */
    $aModule = array(
        'id'          => 'trosofortueberweisung',
        'title'       => '<img height="10px" style="margin-top: 3px;" src="../modules/tronet/tronet.gif" /> Sofort.',
        'description' => array(
            'de' => 'Sofort. by tronet',
            'en' => 'Sofort. by tronet',
        ),
        'thumbnail'   => 'logo_sofort.png',
        'version'     => '7.0.3',
        'author'      => 'tronet GmbH',
        'email'       => 'integration@sofort.com',
        'url'         => 'http://www.tro.net',
        'blocks'      => array(
            array(
                'template' => 'widget/sidebar/partners.tpl',
                'block'    => 'partner_logos',
                'file'     => 'trosofortueberweisung_partnerbox.tpl',
            ),
            array(
                'template' => 'page/checkout/payment.tpl',
                'block'    => 'select_payment',
                'file'     => 'trosofortueberweisung_paymentSelector.tpl',
            ),
            array(
                'template' => 'page/checkout/payment.tpl',
                'block'    => 'mb_select_payment',
                'file'     => 'trosofortueberweisung_mb_paymentSelector.tpl',
            ),
            array(
                'template' => 'order_overview.tpl',
                'block'    => 'admin_order_overview_checkout',
                'file'     => 'trosofortueberweisung_order_payment_status.tpl',
            ),
            array(
                'template' => 'layout/base.tpl',
                'block'    => 'base_style',
                'file'     => 'trosofortueberweisung_base_style.tpl',
            ),
        ),
        'files'       => array(
            'trosofortueberweisungevents'               => 'tronet/trosofortueberweisung/core/trosofortueberweisungevents.php',
            'trosuexception'                            => 'tronet/trosofortueberweisung/core/exception/trosuexception.php',
            'trosofortueberweisungupdateutility'        => 'tronet/trosofortueberweisung/core/utility/trosofortueberweisungupdateutility.php',
            'trosofortueberweisungconfig'               => 'tronet/trosofortueberweisung/core/trosofortueberweisungconfig.php',
            'trosofortueberweisungdirectoryutility'     => 'tronet/trosofortueberweisung/core/utility/trosofortueberweisungdirectoryutility.php',
            'trosofortueberweisunggatewaylog'           => 'tronet/trosofortueberweisung/application/models/trosofortueberweisunggatewaylog.php',
            'trosofortueberweisungrelease'              => 'tronet/trosofortueberweisung/application/models/trosofortueberweisungrelease.php',
            'trosofortueberweisungreleaserequirements'  => 'tronet/trosofortueberweisung/application/models/trosofortueberweisungreleaserequirements.php',
            'trosofortueberweisung_notification'        => 'tronet/trosofortueberweisung/application/controllers/trosofortueberweisung_notification.php',
            'trosofortueberweisung_cron'                => 'tronet/trosofortueberweisung/application/controllers/trosofortueberweisung_cron.php',
            'trosofortueberweisungorder_notifications'  => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisungorder_notifications.php',
            'trosofortueberweisung'                     => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung.php',
            'trosofortueberweisung_list'                => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_list.php',
            'trosofortueberweisung_main'                => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_main.php',
            'trosofortueberweisung_update'              => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_update.php',
            'trosofortueberweisungreleaselist'          => 'tronet/trosofortueberweisung/application/models/trosofortueberweisungreleaselist.php',
            'trosofortueberweisungcurl'                 => 'tronet/trosofortueberweisung/core/utility/trosofortueberweisungcurl.php',
        ),
        'templates'   => array(
            'trosofortueberweisungorder_notifications.tpl'     => 'tronet/trosofortueberweisung/application/views/admin/tpl/trosofortueberweisungorder_notifications.tpl',
            'trosofortueberweisung.tpl'                        => 'tronet/trosofortueberweisung/application/views/admin/tpl/trosofortueberweisung.tpl',
            'trosofortueberweisung_list.tpl'                   => 'tronet/trosofortueberweisung/application/views/admin/tpl/trosofortueberweisung_list.tpl',
            'trosofortueberweisung_main.tpl'                   => 'tronet/trosofortueberweisung/application/views/admin/tpl/trosofortueberweisung_main.tpl',
            'trosofortueberweisung_update.tpl'                 => 'tronet/trosofortueberweisung/application/views/admin/tpl/trosofortueberweisung_update.tpl',
            'trosofortueberweisung_updateavailable.tpl'        => 'tronet/trosofortueberweisung/application/views/admin/tpl/messages/trosofortueberweisung_updateavailable.tpl',
            'trosofortueberweisung_updateavailable_failed.tpl' => 'tronet/trosofortueberweisung/application/views/admin/tpl/messages/trosofortueberweisung_updateavailable_failed.tpl',
            'trosofortueberweisung_noupdateavailable.tpl'      => 'tronet/trosofortueberweisung/application/views/admin/tpl/messages/trosofortueberweisung_noupdateavailable.tpl',
        ),
        'events'      => array(
            'onActivate'   => 'trosofortueberweisungevents::onActivate',
            'onDeactivate' => 'trosofortueberweisungevents::onDeactivate',
        ),
        'extend'      => array(
            'oxpaymentgateway' => 'tronet/trosofortueberweisung/application/models/trosofortueberweisungoxpaymentgateway',
            'oxorder'          => 'tronet/trosofortueberweisung/application/models/trosofortueberweisungoxorder',
            'order'            => 'tronet/trosofortueberweisung/application/controllers/trosofortueberweisungorder',
            'payment'          => 'tronet/trosofortueberweisung/application/controllers/trosofortueberweisungpayment',
            'navigation'       => 'tronet/trosofortueberweisung/application/controllers/admin/trosofortueberweisung_navigation',
        ),
        'settings'    => array(
            array(
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayConfKey',
                'type'  => 'str',
                'value' => '',
            ),
            array(
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayReason',
                'type'  => 'str',
                'value' => 'Bestellnr. [BSTNR]',
            ),
            array(
                'group' => 'troPaymentHandling',
                'name'  => 'sTroGatewayReason2',
                'type'  => 'str',
                'value' => '[SHP]',
            ),
            array(
                'group'       => 'troPaymentHandling',
                'name'        => 'iTroGatewayCanceledOrders',
                'type'        => 'select',
                'value'       => '0',
                'constraints' => '0|1',
            ),
            array(
                'group' => 'troUpdateRoutine',
                'name'  => 'blTroGateWayUpdateCheck',
                'type'  => 'bool',
                'value' => '1',
            ),
            array(
                'group' => 'troOtherSettings',
                'name'  => 'blTroGateWayShowLogoInDeliverAndPayment',
                'type'  => 'bool',
                'value' => '1',
            ),
        ),

    );

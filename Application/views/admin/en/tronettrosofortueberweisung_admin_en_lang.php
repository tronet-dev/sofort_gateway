<?php

/**
 * Language file for OXID eShop.
 *
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.0
 * @version       8.0.0
 */
$sLangName = 'English';

$sTroGatewayReasonTemplate = '<p>Specify the transaction\'s reason below.</p>                  
                    <p>The following placeholder are available:</p>
                    <table  cellspacing="0" cellpadding="0" border="0" height="100%" width="50%">
                        <tr>
                           <td class="edittext">Order-no:</td>
                           <td class="edittext">[BSTNR]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Customer-no:</td>
                           <td class="edittext">[KNR]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Customer name:</td>
                           <td class="edittext">[KNAME]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Order-date:</td class="edittext">
                           <td class="edittext">[DATUM]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Total price:</td class="edittext">
                           <td class="edittext">[PRICE]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Shopname:</td class="edittext">
                           <td class="edittext">[SHP]</td>
                        </tr>
                        <tr>
                           <td class="edittext">Transaktions-ID:</td>
                           <td class="edittext">-TRANSACTION-</td>
                        </tr>
                    </table>
                    <p> 
                        Caution: 
                        <ul>
                            <li>The maximum length after the replacement must not exceed 27 characters!</li>
                            <li>If you use -TRANSACTION- no other characters are permitted in the reason.</li>
                        </ul>
                    </p>';

$aLang = [
    'charset'                                                       => 'UTF-8',
    'TRO_CURRENT_LANGUAGE'                                          => 'en',

    // module settings
    'SHOP_MODULE_GROUP_troPaymentHandling'                          => 'Payment handling',
    'SHOP_MODULE_sTroGatewayConfKey'                                => 'Configuration-key',
    'HELP_SHOP_MODULE_sTroGatewayConfKey'                           => 'You find the configuration-key in your project settings at the <a href="https://www.sofort.com/payment/users" target="_blank">Sofort.-Backend</a>.',
    'SHOP_MODULE_sTroGatewayReason'                                 => $sTroGatewayReasonTemplate,
    'SHOP_MODULE_sTroGatewayReason2'                                => $sTroGatewayReasonTemplate,
    'SHOP_MODULE_iTroGatewayCanceledOrders'                         => 'Cancel or delete aborted orders?',
    'SHOP_MODULE_iTroGatewayCanceledOrders_0'                       => 'Cancel',
    'SHOP_MODULE_iTroGatewayCanceledOrders_1'                       => 'Delete',

    'SHOP_MODULE_GROUP_troUpdateRoutine'                            => 'Updating',
    'SHOP_MODULE_blTroGateWayUpdateCheck'                           => 'Automatically check for updates?',
    'HELP_SHOP_MODULE_blTroGateWayUpdateCheck'                      => 'When this option is selected a message is shown on the startpage
                                                                        as there is a new module version for your OXID version.',
    
    'SHOP_MODULE_GROUP_troOtherSettings'                            => 'Other',
    'SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment'           => 'Show logo in order step 3 "Pay".',
    'HELP_SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment'      => 'In case the logo does not fit visually in your theme, you may deactivate it.',
    
    // general interface
    'TRO_NEW_VERSION_AVAILABLE'                                     => 'A new version of Sofort. is available. You may update it via composer.',
    'TRO_NEW_VERSION_ALREADY_INSTALLED'                             => 'You have already installed the latest module release.',
    'TRO_NEW_VERSION_AVAILABLE_FAILED'                              => 'An error occured',
    'TRO_NEW_VERSION_AVAILABLE_FAILED_MESSAGE'                      => 'Latest Sofort-Module-Releases could not be fetched.',
    'TRO_VERSION_DOWNLOAD'                                          => 'Download new Version (%1$s)',
    'TRO_SOFORT_BY_TRONET'                                          => 'Sofort. by tronet GmbH',
    'TRO_CHANGE_LOG_URL_DESCRIPTION'                                => 'Open the change log',

    // general interface :: update routine
    'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_USER_HINT'          => 'Following files are different to the shipped files.',
    'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_CHANGED'       => 'File changed:',
    'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_DELETED'       => 'File removed:',

    // general interface :: update routine :: json messages
    'TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult'                   => 'An unknown error occured.',

    // general interface :: update routine (manual)
    'TRO_SOFORT_UPDATE_MAIN_INTROTEXT'                              => 'Check manually for both, new versions and module-core-file changes.',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION'                  => 'check for new releases now',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION_DONE_HL'          => 'It has been checked for new releases',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES'                 => 'check whether module-core-files have been changed',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HL'         => 'It has been checked for changed module-core-files',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_ERROR'           => 'An error occured.',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HINT'       => '<u>Hint:</u> It is recommended to extend classes by OXID developers guidelines. If you perform an update your changes would be discarded.',
    'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_NO_CHANGES' => 'No module-core-files have been changed.',
    'TRO_SOFORT_UPDATE_MAIN_CHOOSE_ACTION'                          => 'Choose an action.',

    // OXID eShop navigation
    'TRONET_MENU_HEAD'                                              => 'tronet',
    'mxtronotifications'                                            => 'Sofortgateway-Log',
    'mxtrosofortueberweisung'                                       => '<img height="10px" style="margin-top: 3px;"  src="../modules/tronet/trosofortueberweisung/out/img/tronet.gif" alt="[tronet]"/> Sofort.',
    'mxtrosofortueberweisung_dashboard'                             => 'Dashboard',
    'mxtrosofortueberweisung_main'                                  => 'Main',

    // cURL error translations
    'TRO_SOFORT_CURLE_UNSUPPORTED_PROTOCOL'     => 'CURLE_UNSUPPORTED_PROTOCOL',
    'TRO_SOFORT_CURLE_FAILED_INIT'              => 'CURLE_FAILED_INIT',
    'TRO_SOFORT_CURLE_URL_MALFORMAT'            => 'CURLE_URL_MALFORMAT',
    'TRO_SOFORT_CURLE_URL_MALFORMAT_USER'       => 'CURLE_URL_MALFORMAT_USER',
    'TRO_SOFORT_CURLE_COULDNT_RESOLVE_PROXY'    => 'CURLE_COULDNT_RESOLVE_PROXY',
    'TRO_SOFORT_CURLE_COULDNT_RESOLVE_HOST'     => 'CURLE_COULDNT_RESOLVE_HOST',
    'TRO_SOFORT_CURLE_COULDNT_CONNECT'          => 'CURLE_COULDNT_CONNECT',
    'TRO_SOFORT_CURLE_FTP_WEIRD_SERVER_REPLY'   => 'CURLE_FTP_WEIRD_SERVER_REPLY',
    'TRO_SOFORT_CURLE_REMOTE_ACCESS_DENIED'     => 'CURLE_REMOTE_ACCESS_DENIED',
    'TRO_SOFORT_CURLE_FTP_WEIRD_PASS_REPLY'     => 'CURLE_FTP_WEIRD_PASS_REPLY',
    'TRO_SOFORT_CURLE_FTP_WEIRD_PASV_REPLY'     => 'CURLE_FTP_WEIRD_PASV_REPLY',
    'TRO_SOFORT_CURLE_FTP_WEIRD_227_FORMAT'     => 'CURLE_FTP_WEIRD_227_FORMAT',
    'TRO_SOFORT_CURLE_FTP_CANT_GET_HOST'        => 'CURLE_FTP_CANT_GET_HOST',
    'TRO_SOFORT_CURLE_FTP_COULDNT_SET_TYPE'     => 'CURLE_FTP_COULDNT_SET_TYPE',
    'TRO_SOFORT_CURLE_PARTIAL_FILE'             => 'CURLE_PARTIAL_FILE',
    'TRO_SOFORT_CURLE_FTP_COULDNT_RETR_FILE'    => 'CURLE_FTP_COULDNT_RETR_FILE',
    'TRO_SOFORT_CURLE_QUOTE_ERROR'              => 'CURLE_QUOTE_ERROR',
    'TRO_SOFORT_CURLE_HTTP_RETURNED_ERROR'      => 'CURLE_HTTP_RETURNED_ERROR',
    'TRO_SOFORT_CURLE_WRITE_ERROR'              => 'CURLE_WRITE_ERROR',
    'TRO_SOFORT_CURLE_UPLOAD_FAILED'            => 'CURLE_UPLOAD_FAILED',
    'TRO_SOFORT_CURLE_READ_ERROR'               => 'CURLE_READ_ERROR',
    'TRO_SOFORT_CURLE_OUT_OF_MEMORY'            => 'CURLE_OUT_OF_MEMORY',
    'TRO_SOFORT_CURLE_OPERATION_TIMEDOUT'       => 'CURLE_OPERATION_TIMEDOUT',
    'TRO_SOFORT_CURLE_FTP_PORT_FAILED'          => 'CURLE_FTP_PORT_FAILED',
    'TRO_SOFORT_CURLE_FTP_COULDNT_USE_REST'     => 'CURLE_FTP_COULDNT_USE_REST',
    'TRO_SOFORT_CURLE_RANGE_ERROR'              => 'CURLE_RANGE_ERROR',
    'TRO_SOFORT_CURLE_HTTP_POST_ERROR'          => 'CURLE_HTTP_POST_ERROR',
    'TRO_SOFORT_CURLE_SSL_CONNECT_ERROR'        => 'CURLE_SSL_CONNECT_ERROR',
    'TRO_SOFORT_CURLE_BAD_DOWNLOAD_RESUME'      => 'CURLE_BAD_DOWNLOAD_RESUME',
    'TRO_SOFORT_CURLE_FILE_COULDNT_READ_FILE'   => 'CURLE_FILE_COULDNT_READ_FILE',
    'TRO_SOFORT_CURLE_LDAP_CANNOT_BIND'         => 'CURLE_LDAP_CANNOT_BIND',
    'TRO_SOFORT_CURLE_LDAP_SEARCH_FAILED'       => 'CURLE_LDAP_SEARCH_FAILED',
    'TRO_SOFORT_CURLE_FUNCTION_NOT_FOUND'       => 'CURLE_FUNCTION_NOT_FOUND',
    'TRO_SOFORT_CURLE_ABORTED_BY_CALLBACK'      => 'CURLE_ABORTED_BY_CALLBACK',
    'TRO_SOFORT_CURLE_BAD_FUNCTION_ARGUMENT'    => 'CURLE_BAD_FUNCTION_ARGUMENT',
    'TRO_SOFORT_CURLE_INTERFACE_FAILED'         => 'CURLE_INTERFACE_FAILED',
    'TRO_SOFORT_CURLE_TOO_MANY_REDIRECTS'       => 'CURLE_TOO_MANY_REDIRECTS',
    'TRO_SOFORT_CURLE_UNKNOWN_TELNET_OPTION'    => 'CURLE_UNKNOWN_TELNET_OPTION',
    'TRO_SOFORT_CURLE_TELNET_OPTION_SYNTAX'     => 'CURLE_TELNET_OPTION_SYNTAX',
    'TRO_SOFORT_CURLE_PEER_FAILED_VERIFICATION' => 'CURLE_PEER_FAILED_VERIFICATION',
    'TRO_SOFORT_CURLE_GOT_NOTHING'              => 'CURLE_GOT_NOTHING',
    'TRO_SOFORT_CURLE_SSL_ENGINE_NOTFOUND'      => 'CURLE_SSL_ENGINE_NOTFOUND',
    'TRO_SOFORT_CURLE_SSL_ENGINE_SETFAILED'     => 'CURLE_SSL_ENGINE_SETFAILED',
    'TRO_SOFORT_CURLE_SEND_ERROR'               => 'CURLE_SEND_ERROR',
    'TRO_SOFORT_CURLE_RECV_ERROR'               => 'CURLE_RECV_ERROR',
    'TRO_SOFORT_CURLE_SSL_CERTPROBLEM'          => 'CURLE_SSL_CERTPROBLEM',
    'TRO_SOFORT_CURLE_SSL_CIPHER'               => 'CURLE_SSL_CIPHER',
    'TRO_SOFORT_CURLE_SSL_CACERT'               => 'CURLE_SSL_CACERT',
    'TRO_SOFORT_CURLE_BAD_CONTENT_ENCODING'     => 'CURLE_BAD_CONTENT_ENCODING',
    'TRO_SOFORT_CURLE_LDAP_INVALID_URL'         => 'CURLE_LDAP_INVALID_URL',
    'TRO_SOFORT_CURLE_FILESIZE_EXCEEDED'        => 'CURLE_FILESIZE_EXCEEDED',
    'TRO_SOFORT_CURLE_USE_SSL_FAILED'           => 'CURLE_USE_SSL_FAILED',
    'TRO_SOFORT_CURLE_SEND_FAIL_REWIND'         => 'CURLE_SEND_FAIL_REWIND',
    'TRO_SOFORT_CURLE_SSL_ENGINE_INITFAILED'    => 'CURLE_SSL_ENGINE_INITFAILED',
    'TRO_SOFORT_CURLE_LOGIN_DENIED'             => 'CURLE_LOGIN_DENIED',
    'TRO_SOFORT_CURLE_TFTP_NOTFOUND'            => 'CURLE_TFTP_NOTFOUND',
    'TRO_SOFORT_CURLE_TFTP_PERM'                => 'CURLE_TFTP_PERM',
    'TRO_SOFORT_CURLE_REMOTE_DISK_FULL'         => 'CURLE_REMOTE_DISK_FULL',
    'TRO_SOFORT_CURLE_TFTP_ILLEGAL'             => 'CURLE_TFTP_ILLEGAL',
    'TRO_SOFORT_CURLE_TFTP_UNKNOWNID'           => 'CURLE_TFTP_UNKNOWNID',
    'TRO_SOFORT_CURLE_REMOTE_FILE_EXISTS'       => 'CURLE_REMOTE_FILE_EXISTS',
    'TRO_SOFORT_CURLE_TFTP_NOSUCHUSER'          => 'CURLE_TFTP_NOSUCHUSER',
    'TRO_SOFORT_CURLE_CONV_FAILED'              => 'CURLE_CONV_FAILED',
    'TRO_SOFORT_CURLE_CONV_REQD'                => 'CURLE_CONV_REQD',
    'TRO_SOFORT_CURLE_SSL_CACERT_BADFILE'       => 'CURLE_SSL_CACERT_BADFILE',
    'TRO_SOFORT_CURLE_REMOTE_FILE_NOT_FOUND'    => 'CURLE_REMOTE_FILE_NOT_FOUND',
    'TRO_SOFORT_CURLE_SSH'                      => 'CURLE_SSH',
    'TRO_SOFORT_CURLE_SSL_SHUTDOWN_FAILED'      => 'CURLE_SSL_SHUTDOWN_FAILED',
    'TRO_SOFORT_CURLE_AGAIN'                    => 'CURLE_AGAIN',
    'TRO_SOFORT_CURLE_SSL_CRL_BADFILE'          => 'CURLE_SSL_CRL_BADFILE',
    'TRO_SOFORT_CURLE_SSL_ISSUER_ERROR'         => 'CURLE_SSL_ISSUER_ERROR',
    'TRO_SOFORT_CURLE_FTP_PRET_FAILED'          => 'CURLE_FTP_PRET_FAILED',
    'TRO_SOFORT_CURLE_RTSP_CSEQ_ERROR'          => 'CURLE_RTSP_CSEQ_ERROR',
    'TRO_SOFORT_CURLE_RTSP_SESSION_ERROR'       => 'CURLE_RTSP_SESSION_ERROR',
    'TRO_SOFORT_CURLE_FTP_BAD_FILE_LIST'        => 'CURLE_FTP_BAD_FILE_LIST',
    'TRO_SOFORT_CURLE_CHUNK_FAILED'             => 'CURLE_CHUNK_FAILED',
];

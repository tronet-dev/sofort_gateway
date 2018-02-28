<?php
    /**
     * German language for module tronet/trosofortueberweisung.
     *
     * @file          trosofortueberweisung_lang.php
     * @link          http://www.tro.net
     * @copyright (C) tronet GmbH 2018
     * @package       modules
     * @addtogroup    modules
     */
    $sLangName = "Deutsch";
    $iLangNr = 0;

    $sTroGatewayReasonTemplate = '<p>Tragen Sie hier den gew&uuml;nschten %s ein, der in der &Uuml;berweisung aufgef&uuml;hrt sein soll.</p>                  
                        <p>Es sind folgende Platzhalter verf&uuml;gbar:</p>
                        <table  cellspacing="0" cellpadding="0" border="0">
                            <tr>
                               <td class="edittext">Bestellnummer:</td>
                               <td class="edittext">[BSTNR]</td>
                            </tr>
                            <tr>
                               <td class="edittext">Kundennumer:</td>
                               <td class="edittext">[KNR]</td>
                            </tr>
                            <tr>
                               <td class="edittext">Kundenname:</td>
                               <td class="edittext">[KNAME]</td>
                            </tr>
                            <tr>
                               <td class="edittext">Datum:</td>
                               <td class="edittext">[DATUM]</td>
                            </tr>
                            <tr>
                               <td class="edittext">zu zahlender Betrag:</td>
                               <td class="edittext">[PRICE]</td>
                            </tr>
                            <tr>
                               <td class="edittext">Shopname:</td>
                               <td class="edittext">[SHP]</td>
                            </tr>
                            <tr>
                               <td class="edittext">Transaktions-ID:</td>
                               <td class="edittext">-TRANSACTION-</td>
                            </tr>
                        </table>
                        <p> 
                            ACHTUNG: 
                            <ul>
                                <li>Die maximale L&auml;nge jeder Verwendungszweckzeile darf inklusive der ersetzten Platzhalter 27 Zeichen nicht &uuml;berschreiten!</li>
                                <li>Bei Verwendung von -TRANSACTION- darf der Verwendungszweck keine weiteren Zeichen enthalten.</li>
                            </ul>
                        </p>';

    $aLang = array(
        'charset'                                                       => 'ISO-8859-15',
        'TRO_CURRENT_LANGUAGE'                                          => 'de',

        // module settings
        'SHOP_MODULE_GROUP_troPaymentHandling'                          => 'Kaufabwicklung',
        'SHOP_MODULE_sTroGatewayConfKey'                                => 'Konfigurationsschl&uuml;ssel',
        'HELP_SHOP_MODULE_sTroGatewayConfKey'                           => 'Sie erhalten den Konfigurationsschl&uuml;ssel im <a href="https://www.sofort.com/payment/users" target="_blank">Sofort.-Backend</a> in den Projekteinstellungen.',
        'SHOP_MODULE_sTroGatewayReason'                                 => sprintf($sTroGatewayReasonTemplate, 'Verwendungszweck 1'),
        'SHOP_MODULE_sTroGatewayReason2'                                => sprintf($sTroGatewayReasonTemplate, 'Verwendungszweck 2'),
        'SHOP_MODULE_iTroGatewayCanceledOrders'                         => 'Vom Endkunden abgebrochende Bestellungen stornieren oder l&ouml;schen?',
        'SHOP_MODULE_iTroGatewayCanceledOrders_0'                       => 'Stornieren',
        'SHOP_MODULE_iTroGatewayCanceledOrders_1'                       => 'L&ouml;schen',

        'SHOP_MODULE_GROUP_troUpdateRoutine'                            => 'Update-Routine',
        'SHOP_MODULE_blTroGateWayUpdateCheck'                           => 'Automatisch auf Aktualisierungen pr&uuml;fen?',
        'HELP_SHOP_MODULE_blTroGateWayUpdateCheck'                      => 'Wenn diese Option ausgew&auml;hlt ist, wird auf der
                                                                            Startseite im eShop Backend eine Nachricht angezeigt, sobald
                                                                            es f&uuml;r Ihre OXID Version eine neue Modulversion gibt.',
        
        'SHOP_MODULE_GROUP_troOtherSettings'                            => 'Sonstiges',
        'SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment'           => 'Zeige das Logo im Bestellschritt 3 "Versand & Zahlungsart".',
        'HELP_SHOP_MODULE_blTroGateWayShowLogoInDeliverAndPayment'      => 'Falls das Logo visuell nicht in Ihr Theme passt, k&ouml;nnen Sie es deaktivieren.',
        
        // general interface
        'TRO_NEW_VERSION_AVAILABLE'                                     => 'Eine Neue Version von Sofort. ist zum Download verf&uuml;gbar.',
        'TRO_NEW_VERSION_ALREADY_INSTALLED'                             => 'Sie haben die neueste Version installiert.',
        'TRO_NEW_VERSION_AVAILABLE_FAILED'                              => 'Es ist ein Fehler aufgetreten',
        'TRO_NEW_VERSION_AVAILABLE_FAILED_MESSAGE'                      => 'Die neuesten Sofort-Modul-Versionen konnten nicht ermittelt werden.',
        'TRO_VERSION_DOWNLOAD'                                          => 'Neue Version (%1$s) herunterladen',
        'TRO_VERSION_INSTALL'                                           => 'Neue Version (%1$s) installieren',
        'TRO_SOFORT_BY_TRONET'                                          => 'Sofort. by tronet GmbH',
        'TRO_CHANGE_LOG_URL_DESCRIPTION'                                => 'ChangeLog anzeigen',

        // general interface :: update routine
        'TRO_SOFORT_UPDATE_VERSION_IS_INSTALLED'                        => 'Die neue Modul-Version %1$s wird installiert. W&auml;hrend der
                                                                            Aktualisierungsroutine werden die u.g. Schritte durchlaufen.
                                                                            Am Ende der Aktualisierung erscheint zwischen den Schritten
                                                                            und der Legende das Ergebnis.',
        'TRO_SOFORT_UPDATE_IMPORTANT_NOTE_LABEL'                        => 'Wichtiger Hinweis:',
        'TRO_SOFORT_UPDATE_IMPORTANT_NOTE_DESCRIPTION'                  => 'W&auml;hrend der Aktualisierungsphase den OXID tmp/* Ordner nicht leeren!',

        'TRO_SOFORT_UPDATE_STEPS_TITLE'                                 => 'Aktualisierungsschritte',
        'TRO_SOFORT_UPDATE_ERROR_HINT_DEFAULT'                          => 'Folgende(r) Fehler sind/ist aufgetreten.',
        'TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED'                => '&Uuml;berpr&uuml;fung: Ob die aktuelle Modul-Version ver&auml;ndert wurde.',
        'TRO_SOFORT_UPDATE_HAS_CORE_FILES_BEEN_MODIFIED_USER_QUESTION'  => 'Achtung:  Es wurden mehrere Dateien in der aktuellen Version ver' . chr(228) . 'ndert. Bei einer Neuinstallation werden die ' . chr(196) . 'nderungen ' . chr(252) . 'berschrieben.\n\nNeues Modul installieren?',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_USER_HINT'          => 'Die folgenden Dateien entsprechen nicht mehr dem Stand der Auslieferung',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_CHANGED'       => 'Datei wurde ge&auml;ndert:',
        'TRO_SOFORT_UPDATE_CORE_FILES_BEEN_MODIFIED_NOTE_DELETED'       => 'Datei wurde entfernt:',
        'TRO_SOFORT_UPDATE_DOWNLOAD_RELEASE_INTO_TMP_DIR'               => 'Neue Version in das Verzeichnis tmp/* herunterladen',
        'TRO_SOFORT_UPDATE_EXTRACT_DOWNLOADED_ARCHIVE'                  => 'Neue Version in das Verzeichnis tmp/* extrahieren',
        'TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION'            => 'Backup von der aktuellen Modul-Version im Verzeichnis export/* erstellen',
        'TRO_SOFORT_UPDATE_CREATE_BACKUP_OF_CURRENT_VERSION_QUESTION'   => 'Achtung: Es konnte von der aktuellen Modul-Version kein Backup erstellt werden.\n\nNeues Modul installieren?',
        'TRO_SOFORT_UPDATE_PERFORM_UPDATE'                              => 'Aktualisierung durchf&uuml;hren	',
        'TRO_SOFORT_UPDATE_REACTIVATE_MODULE'                           => 'Aktualisiertes Modul reaktivieren',
        'TRO_SOFORT_UPDATE_REACTIVATE_MODULE_QUESTION'                  => 'Information: Das Modul konnte nicht reaktiviert werden. Diesen Schritt m' . chr(252) . 'ssen Sie manuell durchf' . chr(252) . 'hren.',
        'TRO_SOFORT_UPDATE_CLEAR_TMP_DIR'                               => 'Verzeichnis tmp/* leeren',
        'TRO_SOFORT_UPDATE_CLEAR_TMP_DIR_QUESTION'                      => 'Information: Das Verzeichnis tmp/* konnte nicht geleert werden. Es wird empfohlen das Verzeichnis manuell zu leeren.',

        'TRO_SOFORT_UPDATE_SUCCESSFUL_USERNOTE'                         => 'Die neueste Modulversion <b>%1$s</b> wurde installiert!	',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED_USERNOTE'                      => 'Die neueste Modulversion <b>%1$s</b> konnte nicht installiert werden!',

        'TRO_SOFORT_UPDATE_LEGEND_TITLE'                                => 'Legende f&uuml;r die Aktualisierungsschritte',
        'TRO_SOFORT_UPDATE_LEGEND_TODO'                                 => 'Dieser Schritt muss noch durchgef&uuml;hrt werden.',
        'TRO_SOFORT_UPDATE_LEGEND_SUCCESSFUL'                           => 'Dieser Schritt ist durchgef&uuml;hrt worden.',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED'                               => 'Die Aktualisierungsroutine wurde entweder aufgrund eines Fehlers durch den Benutzer selbst abgebrochen.',
        'TRO_SOFORT_UPDATE_LEGEND_FAILED_BUT_CONTINUED'                 => 'Es gibt Unstimmigkeiten. Manuelles handeln ist erforderlich.',

        // general interface :: update routine :: json messages
        'TRO_SOFORT_UPDATE_JSON_RETURN_fileNotDownloaded'               => 'Die Datei konnte nicht heruntergeladen werden.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_releaseNotFound'                 => 'Der Release mit der Version %1$s konnte nicht gefunden werden.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_unknownResult'                   => 'Es ist ein bisher nicht bekannter Fehler aufgetreten.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_copyFailed'                      => 'Daten konnten nicht vom OXID tmp/* Ordner kopiert werden.',
        'TRO_SOFORT_UPDATE_JSON_RETURN_couldNotFetchXml'                => 'XML Datei mit den Hash-Werten konnte nicht geladen werden.',

        // general interface :: update routine (manual)
        'TRO_SOFORT_UPDATE_MAIN_INTROTEXT'                              => 'Auf dieser Seite k&ouml;nnen Sie manuell auf eine neue Version pr&uuml;fen.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION'                  => 'jetzt &uuml;berpr&uuml;fen, ob es eine neue Version gibt.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_NEW_VERSION_DONE_HL'          => 'Es wurde auf eine neue Version gepr&uuml;ft',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES'                 => 'jetzt &uuml;berpr&uuml;fen, ob Module-Core-Dateien angepasst wurden.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HL'         => 'Es wurde auf &Auml;nderungen gepr&uuml;ft',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_ERROR'           => 'Es ist ein Fehler aufgetreten.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_HINT'       => '<u>Hinweis:</u> Es wird empfohlen, keine Module-Core-Dateien anzupassen, sondern OXID-konform zu erweitern. Wenn Sie ein Update durchf&uuml;hren, werden die &Auml;nderungen &uuml;berschrieben.',
        'TRO_SOFORT_UPDATE_MAIN_CHECK_FOR_CORE_CHANGES_DONE_NO_CHANGES' => 'Es wurden keine Module-Core-Dateien angepasst.',
        'TRO_SOFORT_UPDATE_MAIN_CHOOSE_ACTION'                          => 'W&auml;hlen Sie eine Aktion aus.',

        // OXID eShop navigation
        'TRONET_MENU_HEAD'                                              => 'tronet',
        'mxtronotifications'                                            => 'Sofortgateway-Log',
        'mxtrosofortueberweisung'                                       => '<img height="10px" style="margin-top: 3px;"  src="../modules/tronet/tronet.gif" alt="[tronet]"/> Sofort.',
        'mxtrosofortueberweisung_dashboard'                             => 'Dashboard',
        'mxtrosofortueberweisung_main'                                  => 'Stamm',

        // cURL error translations
        'TRO_SOFORT_CURLE_UNSUPPORTED_PROTOCOL'     => 'Es gibt Probleme mit dem verwendeten Protokoll.',
        'TRO_SOFORT_CURLE_FAILED_INIT'              => 'CURLE_FAILED_INIT',
        'TRO_SOFORT_CURLE_URL_MALFORMAT'            => 'CURLE_URL_MALFORMAT',
        'TRO_SOFORT_CURLE_URL_MALFORMAT_USER'       => 'CURLE_URL_MALFORMAT_USER',
        'TRO_SOFORT_CURLE_COULDNT_RESOLVE_PROXY'    => 'Es gab ein Fehler mit der Proxy-Konfiguration.',
        'TRO_SOFORT_CURLE_COULDNT_RESOLVE_HOST'     => 'Der Host konnte nicht aufgel&ouml;st werden.',
        'TRO_SOFORT_CURLE_COULDNT_CONNECT'          => 'Es konnte keine Verbindung zu http://sofort.tro.net hergestellt werden. Bitte &Uuml;berpr&uuml;fen Sie Ihre Firewall-Einstellungen.',
        'TRO_SOFORT_CURLE_FTP_WEIRD_SERVER_REPLY'   => 'CURLE_FTP_WEIRD_SERVER_REPLY',
        'TRO_SOFORT_CURLE_REMOTE_ACCESS_DENIED'     => 'Der Zugriff wurde verweigert.',
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
    );

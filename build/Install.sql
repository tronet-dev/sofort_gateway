INSERT IGNORE INTO `oxpayments` SET
        `OXID` = 'trosofortgateway_su', 
        `OXACTIVE` = 1, 
        `OXDESC` = 'Sofort.',
        `OXDESC_1` = 'Online bank transfer.',
        `OXADDSUM` = 0, 
        `OXADDSUMTYPE` = 'abs', 
        `OXADDSUMRULES` = 15, 
        `OXFROMBONI` = 0, 
        `OXFROMAMOUNT` = 0, 
        `OXTOAMOUNT` = 999999, 
        `OXCHECKED` = 1, 
        `OXSORT` = 1, 
        `OXLONGDESC` = '<div id=\"payment_form_sofortueberweisung\" style=\"padding-left: 20px; padding-bottom: 10px;\">
           <ul style=\"list-style-type:disc;\">
             <li style=\"margin-left:15px;\">- Vom T&Uuml;V Saarland zertifiziertes Zahlungssystem mit gepr&uuml;ftem Datenschutz</li>
             <li style=\"margin-left:15px;\">- Keine Registrierung notwendig</li>
             <li style=\"margin-left:15px;\">- Ware / Content kann bei Verf&uuml;gbarkeit SOFORT versendet / freigeschaltet werden</li>
             <li style=\"margin-left:15px;\">- Bitte halten Sie Ihre Online-Banking-Daten (PIN/TAN) bereit</li>
           </ul>
        </div>
        <div class=\"clear\"></div>', 
        `OXLONGDESC_1` = '<div id=\"payment_form_sofortueberweisung\" style=\"padding-left: 20px; padding-bottom: 10px;\">                        
           <ul style=\"list-style-type:disc;\">
             <li style=\"margin-left:15px;\">- Payment system with data protection certified by TÜV Saarland</li>
             <li style=\"margin-left:15px;\">- No registration required</li>
             <li style=\"margin-left:15px;\">- Stock goods / content can be shipped / activated immediately</li>
             <li style=\"margin-left:15px;\">- Please have your online banking details at hand</li>
           </ul>
        </div>
        <div class=\"clear\"></div>';
        
CREATE TABLE IF NOT EXISTS `trogatewaylog` (
    `OXID` varchar(32) collate latin1_general_ci NOT NULL,
    `TRANSACTIONID` varchar(255) collate latin1_general_ci NOT NULL,
    `TRANSACTION` varchar(255) collate latin1_general_ci NOT NULL,
    `STATUS` varchar(255) collate latin1_general_ci NOT NULL,
    `STATUSREASON` varchar(255) collate latin1_general_ci NOT NULL,
    `TIMESTAMP` timestamp NOT NULL default CURRENT_TIMESTAMP,
    PRIMARY KEY  (`OXID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
    
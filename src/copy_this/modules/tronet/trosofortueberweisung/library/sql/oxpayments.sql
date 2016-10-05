-- ----------------------------------
-- author: tronet GmbH
-- ----------------------------------

INSERT IGNORE INTO `oxpayments`
SET
    `OXID`          = 'trosofortgateway_su',
    `OXACTIVE`      = 1,
    `OXDESC`        = 'SOFORT Überweisung',
    `OXDESC_1`      = 'SOFORT Banking',
    `OXADDSUM`      = 0,
    `OXADDSUMTYPE`  = 'abs',
    `OXADDSUMRULES` = 15,
    `OXFROMBONI`    = 0,
    `OXFROMAMOUNT`  = 0,
    `OXTOAMOUNT`    = 999999,
    `OXCHECKED`     = 1,
    `OXSORT`        = 1,
    `OXLONGDESC`    = '<div id="payment_form_sofortueberweisung">
           <ul>
             <li>Vom T&Uuml;V Saarland zertifiziertes Zahlungssystem mit gepr&uuml;ftem Datenschutz</li>
             <li>Keine Registrierung notwendig</li>
             <li>Ware / Content kann bei Verf&uuml;gbarkeit SOFORT versendet / freigeschaltet werden</li>
             <li>Bitte halten Sie Ihre Online-Banking-Daten (PIN/TAN) bereit</li>
           </ul>
        </div>
        <div class="clear"></div>',
    `OXLONGDESC_1`  = '<div id="payment_form_sofortueberweisung">
           <ul>
             <li>Payment system with data protection certified by T&Uuml;V Saarland</li>
             <li>No registration required</li>
             <li>Stock goods / content can be shipped / activated immediately</li>
             <li>Please have your online banking details at hand</li>
           </ul>
        </div>
        <div class="clear"></div>'
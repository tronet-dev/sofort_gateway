-- ----------------------------------
-- author: tronet GmbH
-- ----------------------------------

INSERT IGNORE INTO `oxpayments`
SET
    `OXID`          = 'trosofortgateway_su',
    `OXACTIVE`      = 1,
    `OXDESC`        = 'Sofort.',
    `OXDESC_1`      = 'Pay now.',
    `OXADDSUM`      = 0,
    `OXADDSUMTYPE`  = 'abs',
    `OXADDSUMRULES` = 15,
    `OXFROMBONI`    = 0,
    `OXFROMAMOUNT`  = 0,
    `OXTOAMOUNT`    = 999999,
    `OXCHECKED`     = 1,
    `OXSORT`        = 1,
    `OXLONGDESC`    = '<div id="payment_form_sofortueberweisung">
Mit dem TÜV-zertifizierten Bezahlsystem Sofort kannst du dank PIN & TAN, ohne Registrierung, einfach und sicher mit
deinen gewohnten Online-Banking-Daten zahlen.
        </div>
        <div class="clear"></div>',
    `OXLONGDESC_1`  = '<div id="payment_form_sofortueberweisung">
Direct payment via online banking. Easy, quick and secure – without registration.
Automatic data transfer and the real-time transaction notification enable a smooth payment process and a faster delivery.
        </div>
        <div class="clear"></div>';

CREATE TABLE IF NOT EXISTS `trogatewaylog` (
    `OXID`          VARCHAR(32) COLLATE latin1_general_ci  NOT NULL,
    `TRANSACTIONID` VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `TRANSACTION`   VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `STATUS`        VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `STATUSREASON`  VARCHAR(255) COLLATE latin1_general_ci NOT NULL,
    `TIMESTAMP`     TIMESTAMP                              NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`OXID`)
)
    ENGINE = MyISAM
    DEFAULT CHARSET = latin1
    COLLATE = latin1_general_ci;
    
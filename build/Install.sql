-- ----------------------------------
-- author: tronet GmbH
-- ----------------------------------

INSERT IGNORE INTO `oxpayments`
SET
    `OXID`          = 'trosofortgateway_su',
    `OXACTIVE`      = 1,
    `OXDESC`        = 'Sofort.',
    `OXDESC_1`      = 'Sofort.',
    `OXADDSUM`      = 0,
    `OXADDSUMTYPE`  = 'abs',
    `OXADDSUMRULES` = 15,
    `OXFROMBONI`    = 0,
    `OXFROMAMOUNT`  = 0,
    `OXTOAMOUNT`    = 999999,
    `OXCHECKED`     = 1,
    `OXSORT`        = 1,
    `OXLONGDESC`    = '<div id="payment_form_sofortueberweisung">Einfach und direkt bezahlen per Online Banking.</div>
        <div class="clear"></div>',
    `OXLONGDESC_1`  = '<div id="payment_form_sofortueberweisung">Simple and secure.</div>
        <div class="clear"></div>';

CREATE TABLE IF NOT EXISTS `trogatewaylog` (
    `OXID`          VARCHAR(32) NOT NULL,
    `TRANSACTIONID` VARCHAR(255) NOT NULL,
    `STATUS`        VARCHAR(255) NOT NULL,
    `STATUSREASON`  VARCHAR(255) NOT NULL,
    `TIMESTAMP`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`OXID`)
);

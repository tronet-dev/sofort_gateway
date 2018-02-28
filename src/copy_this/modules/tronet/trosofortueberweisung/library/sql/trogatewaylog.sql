-- ----------------------------------
-- author: tronet GmbH
-- ----------------------------------

CREATE TABLE IF NOT EXISTS `trogatewaylog` (
    `OXID`          VARCHAR(32) NOT NULL,
    `TRANSACTIONID` VARCHAR(255) NOT NULL,
    `STATUS`        VARCHAR(255) NOT NULL,
    `STATUSREASON`  VARCHAR(255) NOT NULL,
    `TIMESTAMP`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`OXID`)
);

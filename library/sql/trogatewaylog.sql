-- ----------------------------------
-- author: tronet GmbH
-- ----------------------------------

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
ALTER TABLE `sisusers`
ADD COLUMN `usrEsAdmin`  bit(1) NULL DEFAULT 0 AFTER `usrToken`;

ALTER TABLE `configuracion`
ADD COLUMN `title1`  varchar(15) NULL AFTER `utilizaordendecompra`,
ADD COLUMN `title2`  varchar(15) NULL AFTER `title1`;

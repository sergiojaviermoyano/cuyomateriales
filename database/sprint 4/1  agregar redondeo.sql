ALTER TABLE `ordendecompra`
ADD COLUMN `redondeo`  decimal(10,2) NULL DEFAULT 0 AFTER `venId`;
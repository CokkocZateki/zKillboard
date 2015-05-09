DROP TABLE IF EXISTS `zz_item_price_lookup`;
CREATE TABLE `zz_item_price_lookup` (
  `typeID` int(11) NOT NULL,
  `priceDate` date NOT NULL DEFAULT '0000-00-00',
  `avgPrice` decimal(16,2) NOT NULL,
  `lowPrice` decimal(16,2) NOT NULL,
  `highPrice` decimal(16,2) NOT NULL,
  PRIMARY KEY (`typeID`,`priceDate`),
  KEY `typeID` (`typeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

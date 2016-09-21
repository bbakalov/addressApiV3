CREATE TABLE `address` (
    `addressid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'The unique address ID.',
    `label` VARCHAR(100) NOT NULL COMMENT 'The name of the person or organisation to which the address belongs.',
    `street` VARCHAR(100) NOT NULL COMMENT 'The name of the street.',
    `housenumber` VARCHAR(10) NOT NULL COMMENT 'The house number (and any optional additions).',
    `postalcode` VARCHAR(6) NOT NULL COMMENT 'The postal code for the address.',
    `city` VARCHAR(100) NOT NULL COMMENT 'The city in which the address is located.',
    `country` VARCHAR(100) NOT NULL COMMENT 'The country in which the address is located.'
)  ENGINE=INNODB , CHARACTER SET UTF8, COLLATE UTF8_GENERAL_CI , COMMENT 'A physical address belonging to a person or organisation.';
INSERT INTO `address` VALUES( NULL, 'testLabel', 'testStreet', 'test012', 'test01', 'testCity', 'testCountry' );
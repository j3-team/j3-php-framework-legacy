CREATE TABLE `j3_lang` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lang_code` VARCHAR(8) NOT NULL,
  `lang_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `lang_code_UNIQUE` (`lang_code` ASC));
  
  
CREATE TABLE `j3_i18n` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `text_code` VARCHAR(255) NOT NULL,
  `lang_code` VARCHAR(45) NOT NULL,
  `text_trans` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  INDEX `text` (`text_code` ASC, `j3_lang_id` ASC));
  

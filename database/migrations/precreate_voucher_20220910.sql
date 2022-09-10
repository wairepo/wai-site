CREATE SCHEMA `voucher` ;

CREATE TABLE `voucher`.`customers` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(255) NULL,
  `last_name` VARCHAR(255) NULL,
  `gender` VARCHAR(50) NULL,
  `date_of_birth` DATE NULL,
  `contact_number` VARCHAR(50) NULL,
  `email` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `voucher`.`purchase_transaction` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `customer_id` BIGINT(20) NOT NULL,
  `total_spent` DECIMAL(10,2) NULL,
  `total_saving` DECIMAL(10,2) NULL,
  `transaction_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `voucher`.`campaign_vouchers` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `campaign_id` INT(11) NULL,
  `customer_id` BIGINT(20) NULL,
  `code` VARCHAR(100) NULL,
  `redeemed_at` TIMESTAMP NULL,
  `locked_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `voucher`.`campaign` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NULL,
  `total_voucher` INT(11) NULL,
  `start_at` DATE NULL,
  `end_at` DATE NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`));

ALTER TABLE `voucher`.`campaign_vouchers` 
ADD UNIQUE INDEX `unique_code` (`campaign_id` ASC, `code` ASC);
;

-- Preset data for testing purposed
INSERT INTO `voucher`.`customers` (`id`, `first_name`, `last_name`) VALUES ('1', 'Kok Wai', 'Tam');
INSERT INTO `voucher`.`customers` (`id`, `first_name`, `last_name`) VALUES ('2', 'John', 'Teo');
INSERT INTO `voucher`.`customers` (`id`, `first_name`, `last_name`) VALUES ('3', 'Gigi', 'Loh');

INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('1', '1', '10.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('2', '1', '1.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('3', '1', '99.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('4', '3', '1.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('5', '3', '1.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('6', '2', '1.00', '2022-09-09 20:11:05');
INSERT INTO `voucher`.`purchase_transaction` (`id`, `customer_id`, `total_spent`, `transaction_at`) VALUES ('7', '3', '1.00', '2022-09-09 20:11:05');

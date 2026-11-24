-- Depot management system schema (structure only, no sample data).
-- Import into an empty MySQL database, then configure database.default in .env.
-- Tables use InnoDB so the stock/cashbook/inventory writes can be transactional.
-- Generated from the legacy phpMyAdmin dump during the CodeIgniter 4 upgrade.
CREATE TABLE `sale_details` (
  `sale_details_id` bigint(20) NOT NULL,
  `fk_sale_id` bigint(20) NOT NULL,
  `fk_product_id` bigint(20) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_quantity` bigint(20) NOT NULL,
  `product_weight` varchar(100) NOT NULL,
  `box_unit` int(8) NOT NULL,
  `sale_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_activities` (
  `pk_activity_id` bigint(20) NOT NULL,
  `activity_type` enum('success','error','warning') NOT NULL,
  `fk_user_id` bigint(20) DEFAULT NULL,
  `activity_name` varchar(200) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_cashbook` (
  `pk_cashbook_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) NOT NULL,
  `fk_product_id` bigint(20) DEFAULT NULL,
  `fk_stock_id` bigint(20) DEFAULT NULL,
  `fk_inventory_id` bigint(20) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `cashbook_description` text NOT NULL,
  `cash_in` double DEFAULT NULL,
  `cash_out` double DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_clients` (
  `pk_client_id` bigint(20) NOT NULL,
  `client_type` enum('client','branch','dealer') NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `client_email` varchar(100) NOT NULL,
  `client_mobile` varchar(20) NOT NULL,
  `client_address` text NOT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_configurations` (
  `pk_configuration_id` bigint(20) NOT NULL,
  `configuration_name` varchar(50) NOT NULL,
  `configuration_setting` varchar(100) NOT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_inventories` (
  `pk_inventory_id` bigint(20) NOT NULL,
  `fk_product_id` bigint(20) NOT NULL,
  `fk_stock_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) DEFAULT NULL,
  `regular_quantity` bigint(20) DEFAULT '0',
  `damage_quantity` bigint(20) DEFAULT '0',
  `free_quantity` bigint(20) DEFAULT '0',
  `buying_price` double NOT NULL,
  `selling_price` double NOT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_products` (
  `pk_product_id` bigint(20) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_weight` varchar(100) DEFAULT NULL,
  `product_unit` int(8) DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_sales` (
  `pk_sale_id` bigint(20) NOT NULL,
  `sale_subtotal` double DEFAULT NULL,
  `sale_discount` double DEFAULT NULL,
  `ground_total` double DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_stocks` (
  `pk_stock_id` bigint(20) NOT NULL,
  `fk_branch_id` bigint(20) NOT NULL,
  `stock_type` enum('regular','damage','free') NOT NULL,
  `fk_product_id` bigint(20) NOT NULL,
  `buying_price` double NOT NULL,
  `selling_price` double NOT NULL,
  `stock_quantity` int(8) NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tbl_users` (
  `pk_user_id` bigint(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_picture` varchar(100) DEFAULT NULL,
  `user_type` enum('superadmin','admin') DEFAULT NULL,
  `user_status` enum('active','inactive') DEFAULT NULL,
  `create_time` time DEFAULT NULL,
  `create_date` date DEFAULT NULL,
  `create_year` year(4) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `update_time` time DEFAULT NULL,
  `update_date` date DEFAULT NULL,
  `update_year` year(4) DEFAULT NULL,
  `updated_by` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`sale_details_id`);

ALTER TABLE `tbl_activities`
  ADD PRIMARY KEY (`pk_activity_id`);

ALTER TABLE `tbl_cashbook`
  ADD PRIMARY KEY (`pk_cashbook_id`);

ALTER TABLE `tbl_clients`
  ADD PRIMARY KEY (`pk_client_id`);

ALTER TABLE `tbl_configurations`
  ADD PRIMARY KEY (`pk_configuration_id`);

ALTER TABLE `tbl_inventories`
  ADD PRIMARY KEY (`pk_inventory_id`);

ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`pk_product_id`);

ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`pk_sale_id`);

ALTER TABLE `tbl_stocks`
  ADD PRIMARY KEY (`pk_stock_id`);

ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`pk_user_id`);

ALTER TABLE `sale_details`
  MODIFY `sale_details_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_activities`
  MODIFY `pk_activity_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_cashbook`
  MODIFY `pk_cashbook_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_clients`
  MODIFY `pk_client_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_configurations`
  MODIFY `pk_configuration_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_inventories`
  MODIFY `pk_inventory_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_products`
  MODIFY `pk_product_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_sales`
  MODIFY `pk_sale_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_stocks`
  MODIFY `pk_stock_id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tbl_users`
  MODIFY `pk_user_id` bigint(20) NOT NULL AUTO_INCREMENT;


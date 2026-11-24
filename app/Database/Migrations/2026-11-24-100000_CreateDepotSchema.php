<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Fresh-install schema matching docs/schema.sql.
 *
 * CREATE TABLE IF NOT EXISTS makes this safe to run against a database that
 * was populated manually from the SQL file: the tables are left untouched and
 * the migration is simply recorded as applied.
 */
class CreateDepotSchema extends Migration
{
    private const TABLES = [
        'sale_details',
        'tbl_activities',
        'tbl_cashbook',
        'tbl_clients',
        'tbl_configurations',
        'tbl_inventories',
        'tbl_products',
        'tbl_sales',
        'tbl_stocks',
        'tbl_users',
    ];

    public function up(): void
    {
        $statements = [
            "CREATE TABLE IF NOT EXISTS `sale_details` (
                `sale_details_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `fk_sale_id` bigint(20) NOT NULL,
                `fk_product_id` bigint(20) NOT NULL,
                `product_name` varchar(100) NOT NULL,
                `product_quantity` bigint(20) NOT NULL,
                `product_weight` varchar(100) NOT NULL,
                `box_unit` int(8) NOT NULL,
                `sale_price` double NOT NULL,
                PRIMARY KEY (`sale_details_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_activities` (
                `pk_activity_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `activity_type` enum('success','error','warning') NOT NULL,
                `fk_user_id` bigint(20) DEFAULT NULL,
                `activity_name` varchar(200) DEFAULT NULL,
                `create_time` time DEFAULT NULL,
                `create_date` date DEFAULT NULL,
                `created_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_activity_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_cashbook` (
                `pk_cashbook_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_cashbook_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_clients` (
                `pk_client_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_client_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_configurations` (
                `pk_configuration_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `configuration_name` varchar(50) NOT NULL,
                `configuration_setting` varchar(100) NOT NULL,
                `create_time` time DEFAULT NULL,
                `create_date` date DEFAULT NULL,
                `created_by` bigint(20) DEFAULT NULL,
                `update_time` time DEFAULT NULL,
                `update_date` date DEFAULT NULL,
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_configuration_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_inventories` (
                `pk_inventory_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_inventory_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_products` (
                `pk_product_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `product_name` varchar(100) NOT NULL,
                `product_weight` varchar(100) DEFAULT NULL,
                `product_unit` int(8) DEFAULT NULL,
                `create_time` time DEFAULT NULL,
                `create_date` date DEFAULT NULL,
                `created_by` bigint(20) DEFAULT NULL,
                `update_time` time DEFAULT NULL,
                `update_date` date DEFAULT NULL,
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_product_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_sales` (
                `pk_sale_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `sale_subtotal` double DEFAULT NULL,
                `sale_discount` double DEFAULT NULL,
                `ground_total` double DEFAULT NULL,
                `create_time` time DEFAULT NULL,
                `create_date` date DEFAULT NULL,
                `created_by` bigint(20) DEFAULT NULL,
                `update_time` time DEFAULT NULL,
                `update_date` date DEFAULT NULL,
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_sale_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_stocks` (
                `pk_stock_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_stock_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
            "CREATE TABLE IF NOT EXISTS `tbl_users` (
                `pk_user_id` bigint(20) NOT NULL AUTO_INCREMENT,
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
                `updated_by` bigint(20) DEFAULT NULL,
                PRIMARY KEY (`pk_user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8",
        ];

        foreach ($statements as $sql) {
            $this->db->query($sql);
        }
    }

    public function down(): void
    {
        foreach (array_reverse(self::TABLES) as $table) {
            $this->db->query('DROP TABLE IF EXISTS `' . $table . '`');
        }
    }
}

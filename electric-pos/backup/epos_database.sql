-- ============================================================
--  EPOS - Multi-Store Retail Management System
--  Database: epos_db
--  Convention: tbl_<tablename>  (single underscore, lowercase)
--  Engine: InnoDB | Charset: utf8mb4 | MySQL 8+
--  Generated: 2026-11-14
-- ============================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `epos_db`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `epos_db`;

-- ============================================================
-- A. BUSINESS / MULTI-STORE
-- ============================================================

CREATE TABLE `tbl_business` (
  `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `name`         VARCHAR(150)    NOT NULL,
  `logo`         VARCHAR(255)    DEFAULT NULL,
  `currency`     VARCHAR(10)     NOT NULL DEFAULT 'PKR',
  `timezone`     VARCHAR(60)     NOT NULL DEFAULT 'Asia/Karachi',
  `date_format`  VARCHAR(20)     NOT NULL DEFAULT 'Y-m-d',
  `financial_yr` VARCHAR(10)     DEFAULT NULL COMMENT 'e.g. 2026-27',
  `address`      TEXT            DEFAULT NULL,
  `phone`        VARCHAR(30)     DEFAULT NULL,
  `email`        VARCHAR(100)    DEFAULT NULL,
  `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_store` (
  `id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `business_id`    INT UNSIGNED    NOT NULL,
  `name`           VARCHAR(150)    NOT NULL,
  `code`           VARCHAR(30)     NOT NULL,
  `address`        TEXT            DEFAULT NULL,
  `city`           VARCHAR(80)     DEFAULT NULL,
  `phone`          VARCHAR(30)     DEFAULT NULL,
  `email`          VARCHAR(100)    DEFAULT NULL,
  `invoice_header` TEXT            DEFAULT NULL,
  `working_hours`  VARCHAR(100)    DEFAULT NULL,
  `default_wh_id`  INT UNSIGNED    DEFAULT NULL COMMENT 'Default warehouse',
  `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_store_code` (`business_id`, `code`),
  KEY `idx_store_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_store_setting` (
  `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `store_id`     INT UNSIGNED    NOT NULL,
  `setting_key`  VARCHAR(80)     NOT NULL,
  `setting_val`  TEXT            DEFAULT NULL,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_store_setting` (`store_id`, `setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_warehouse` (
  `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED    NOT NULL,
  `store_id`     INT UNSIGNED    NOT NULL,
  `name`         VARCHAR(150)    NOT NULL,
  `code`         VARCHAR(30)     NOT NULL,
  `address`      TEXT            DEFAULT NULL,
  `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_wh_code` (`store_id`, `code`),
  KEY `idx_wh_store` (`store_id`),
  KEY `idx_wh_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Stock Location: Shelf / Rack / Bin inside a Warehouse
-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_location` (
  `id`             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `business_id`    INT UNSIGNED    NOT NULL,
  `store_id`       INT UNSIGNED    NOT NULL,
  `warehouse_id`   INT UNSIGNED    NOT NULL,
  `name`           VARCHAR(100)    NOT NULL,
  `code`           VARCHAR(30)     NOT NULL,
  `location_type`  ENUM('shelf','rack','bin','floor','quarantine','damaged','intransit') NOT NULL DEFAULT 'shelf',
  `is_active`      TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_location_code` (`warehouse_id`, `code`),
  KEY `idx_location_wh` (`warehouse_id`),
  KEY `idx_location_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_pos_register` (
  `id`           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `store_id`     INT UNSIGNED    NOT NULL,
  `name`         VARCHAR(80)     NOT NULL,
  `code`         VARCHAR(30)     NOT NULL,
  `is_active`    TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_register_code` (`store_id`, `code`),
  KEY `idx_register_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_pos_device` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `store_id`        INT UNSIGNED    NOT NULL,
  `register_id`     INT UNSIGNED    DEFAULT NULL,
  `device_uuid`     VARCHAR(60)     NOT NULL COMMENT 'Globally unique UUID for offline sync',
  `device_name`     VARCHAR(100)    DEFAULT NULL,
  `last_sync_at`    DATETIME        DEFAULT NULL,
  `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_device_uuid` (`device_uuid`),
  KEY `idx_device_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- B. USERS / SECURITY / RBAC
-- ============================================================

CREATE TABLE `tbl_role` (
  `id`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(60)       NOT NULL,
  `slug`        VARCHAR(60)       NOT NULL,
  `description` VARCHAR(255)      DEFAULT NULL,
  `is_active`   TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_role` (`name`, `slug`) VALUES
  ('Master Admin',    'master_admin'),
  ('Store Manager',   'store_manager'),
  ('POS Cashier',     'pos_cashier'),
  ('Inventory User',  'inventory_user'),
  ('Warehouse User',  'warehouse_user'),
  ('Reports User',    'reports_user');

-- -----------------------------------------------------------

CREATE TABLE `tbl_permission` (
  `id`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `module`      VARCHAR(60)       NOT NULL COMMENT 'e.g. pos, inventory, warehouse',
  `action`      VARCHAR(80)       NOT NULL COMMENT 'e.g. pos.sale.create',
  `label`       VARCHAR(120)      NOT NULL,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permission_action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_permission` (`module`, `action`, `label`) VALUES
  ('pos',       'pos.sale.create',           'Create Sale'),
  ('pos',       'pos.sale.return',            'Sale Return'),
  ('pos',       'pos.sale.discount',          'Apply Discount'),
  ('pos',       'pos.sale.cancel',            'Cancel Sale'),
  ('pos',       'pos.sale.hold',              'Hold Sale'),
  ('inventory', 'inventory.purchase.create',  'Create Purchase Order'),
  ('inventory', 'inventory.purchase.approve', 'Approve Purchase Order'),
  ('warehouse', 'warehouse.transfer.create',  'Create Transfer'),
  ('warehouse', 'warehouse.transfer.dispatch','Dispatch Transfer'),
  ('warehouse', 'warehouse.transfer.receive', 'Receive Transfer'),
  ('reports',   'reports.sales.view',         'View Sales Reports'),
  ('loyalty',   'loyalty.points.adjust',      'Adjust Loyalty Points'),
  ('dayclose',  'dayclose.reopen',            'Reopen Day Close');

-- -----------------------------------------------------------

CREATE TABLE `tbl_role_permission` (
  `id`            INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `role_id`       SMALLINT UNSIGNED NOT NULL,
  `permission_id` SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_perm` (`role_id`, `permission_id`),
  KEY `idx_rp_role` (`role_id`),
  KEY `idx_rp_perm` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user` (
  `id`           INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED      NOT NULL,
  `role_id`      SMALLINT UNSIGNED NOT NULL,
  `username`     VARCHAR(60)       NOT NULL,
  `full_name`    VARCHAR(120)      NOT NULL,
  `email`        VARCHAR(100)      DEFAULT NULL,
  `phone`        VARCHAR(20)       DEFAULT NULL,
  `password`     VARCHAR(255)      NOT NULL COMMENT 'bcrypt hashed',
  `profile_pic`  VARCHAR(255)      DEFAULT NULL,
  `is_active`    TINYINT(1)        NOT NULL DEFAULT 1,
  `last_login`   DATETIME          DEFAULT NULL,
  `created_at`   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_username` (`username`),
  KEY `idx_user_role` (`role_id`),
  KEY `idx_user_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user_permission` (
  `id`            INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `user_id`       INT UNSIGNED      NOT NULL,
  `permission_id` SMALLINT UNSIGNED NOT NULL,
  `granted`       TINYINT(1)        NOT NULL DEFAULT 1 COMMENT '1=grant override, 0=revoke override',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_perm` (`user_id`, `permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user_store_access` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED NOT NULL,
  `store_id`   INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_store` (`user_id`, `store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user_warehouse_access` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`      INT UNSIGNED NOT NULL,
  `warehouse_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_wh` (`user_id`, `warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user_login_log` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT UNSIGNED    NOT NULL,
  `ip_address` VARCHAR(45)     DEFAULT NULL,
  `user_agent` VARCHAR(255)    DEFAULT NULL,
  `status`     ENUM('success','failed','logout') NOT NULL DEFAULT 'success',
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_loginlog_user` (`user_id`),
  KEY `idx_loginlog_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_user_session` (
  `id`           VARCHAR(128)    NOT NULL,
  `user_id`      INT UNSIGNED    NOT NULL,
  `ip_address`   VARCHAR(45)     DEFAULT NULL,
  `user_agent`   VARCHAR(255)    DEFAULT NULL,
  `payload`      TEXT            DEFAULT NULL,
  `last_active`  INT UNSIGNED    NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_session_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_audit_log` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT UNSIGNED    DEFAULT NULL,
  `store_id`    INT UNSIGNED    DEFAULT NULL,
  `module`      VARCHAR(60)     NOT NULL,
  `action`      VARCHAR(80)     NOT NULL,
  `record_id`   BIGINT UNSIGNED DEFAULT NULL,
  `old_values`  JSON            DEFAULT NULL,
  `new_values`  JSON            DEFAULT NULL,
  `ip_address`  VARCHAR(45)     DEFAULT NULL,
  `note`        TEXT            DEFAULT NULL,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_audit_user` (`user_id`),
  KEY `idx_audit_module` (`module`, `action`),
  KEY `idx_audit_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- C. PRODUCTS
-- ============================================================

CREATE TABLE `tbl_product_category` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`INT UNSIGNED NOT NULL,
  `parent_id`  INT UNSIGNED DEFAULT NULL COMMENT 'For future multi-level hierarchy',
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(110) DEFAULT NULL,
  `image`      VARCHAR(255) DEFAULT NULL,
  `sort_order` SMALLINT     NOT NULL DEFAULT 0,
  `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pcat_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_subcategory` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `name`        VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(110) DEFAULT NULL,
  `image`       VARCHAR(255) DEFAULT NULL,
  `sort_order`  SMALLINT     NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_psubcat_cat` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_manufacturer` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `name`        VARCHAR(150) NOT NULL,
  `code`        VARCHAR(40)  DEFAULT NULL,
  `country`     VARCHAR(60)  DEFAULT NULL,
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_mfr_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_brand` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`     INT UNSIGNED NOT NULL,
  `manufacturer_id` INT UNSIGNED DEFAULT NULL,
  `name`            VARCHAR(150) NOT NULL,
  `logo`            VARCHAR(255) DEFAULT NULL,
  `is_active`       TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_brand_business` (`business_id`),
  KEY `idx_brand_mfr` (`manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_tax_rate` (
  `id`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED      NOT NULL,
  `name`        VARCHAR(60)       NOT NULL,
  `rate`        DECIMAL(6,3)      NOT NULL DEFAULT 0.000,
  `is_inclusive`TINYINT(1)        NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_tax_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_unit` (
  `id`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED      NOT NULL,
  `name`        VARCHAR(40)       NOT NULL COMMENT 'e.g. Piece, Pack, Carton, Kg',
  `abbreviation`VARCHAR(10)       NOT NULL,
  `is_active`   TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_unit_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product` (
  `id`               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `business_id`      INT UNSIGNED      NOT NULL,
  `sku`              VARCHAR(60)       NOT NULL,
  `internal_code`    VARCHAR(60)       DEFAULT NULL,
  `name`             VARCHAR(200)      NOT NULL,
  `description`      TEXT              DEFAULT NULL,
  `category_id`      INT UNSIGNED      DEFAULT NULL,
  `subcategory_id`   INT UNSIGNED      DEFAULT NULL,
  `brand_id`         INT UNSIGNED      DEFAULT NULL,
  `manufacturer_id`  INT UNSIGNED      DEFAULT NULL,
  `base_unit_id`     SMALLINT UNSIGNED NOT NULL,
  `tax_id`           SMALLINT UNSIGNED DEFAULT NULL,
  `purchase_price`   DECIMAL(15,2)     NOT NULL DEFAULT 0.00,
  `retail_price`     DECIMAL(15,2)     NOT NULL DEFAULT 0.00,
  `wholesale_price`  DECIMAL(15,2)     NOT NULL DEFAULT 0.00,
  `min_stock`        DECIMAL(15,3)     NOT NULL DEFAULT 0.000,
  `max_stock`        DECIMAL(15,3)     DEFAULT NULL,
  `reorder_level`    DECIMAL(15,3)     NOT NULL DEFAULT 0.000,
  `batch_tracking`   TINYINT(1)        NOT NULL DEFAULT 0,
  `expiry_tracking`  TINYINT(1)        NOT NULL DEFAULT 0,
  `is_weighing`      TINYINT(1)        NOT NULL DEFAULT 0 COMMENT 'Sold by weight',
  `weight_unit`      VARCHAR(10)       DEFAULT NULL COMMENT 'kg, g, litre',
  `price_per_weight` DECIMAL(15,2)     DEFAULT NULL,
  `scale_plu`        VARCHAR(20)       DEFAULT NULL,
  `barcode_wt_prefix`VARCHAR(5)        DEFAULT NULL,
  `decimal_precision`TINYINT           NOT NULL DEFAULT 3,
  `image`            VARCHAR(255)      DEFAULT NULL,
  `is_active`        TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_sku` (`business_id`, `sku`),
  KEY `idx_product_category` (`category_id`),
  KEY `idx_product_brand` (`brand_id`),
  KEY `idx_product_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_barcode` (
  `id`         INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED      NOT NULL,
  `unit_id`    SMALLINT UNSIGNED NOT NULL,
  `barcode`    VARCHAR(60)       NOT NULL,
  `is_default` TINYINT(1)        NOT NULL DEFAULT 0,
  `created_at` DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_barcode` (`barcode`),
  KEY `idx_barcode_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_unit_conversion` (
  `id`              INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  `product_id`      INT UNSIGNED      NOT NULL,
  `from_unit_id`    SMALLINT UNSIGNED NOT NULL,
  `to_unit_id`      SMALLINT UNSIGNED NOT NULL,
  `factor`          DECIMAL(15,6)     NOT NULL COMMENT 'from_unit × factor = to_unit',
  `created_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_unit_conv` (`product_id`, `from_unit_id`, `to_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_image` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` INT UNSIGNED NOT NULL,
  `path`       VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1)   NOT NULL DEFAULT 0,
  `sort_order` SMALLINT     NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pimg_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_product_batch` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id`   INT UNSIGNED NOT NULL,
  `batch_no`     VARCHAR(80)  NOT NULL,
  `mfg_date`     DATE         DEFAULT NULL,
  `expiry_date`  DATE         DEFAULT NULL,
  `cost`         DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Acquisition cost per unit',
  `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_batch` (`product_id`, `batch_no`),
  KEY `idx_batch_expiry` (`expiry_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- D. INVENTORY - STOCK LEDGER & BALANCES
-- ============================================================

CREATE TABLE `tbl_stock_balance` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`       INT UNSIGNED    NOT NULL,
  `store_id`          INT UNSIGNED    NOT NULL,
  `warehouse_id`      INT UNSIGNED    NOT NULL,
  `location_id`       INT UNSIGNED    NOT NULL,
  `product_id`        INT UNSIGNED    NOT NULL,
  `batch_id`          INT UNSIGNED    NOT NULL DEFAULT 0 COMMENT '0 = no batch tracking',
  `quantity`          DECIMAL(15,3)   NOT NULL DEFAULT 0.000,
  `reserved_qty`      DECIMAL(15,3)   NOT NULL DEFAULT 0.000,
  `updated_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_stock_balance` (`store_id`, `location_id`, `product_id`, `batch_id`),
  KEY `idx_sb_product` (`product_id`),
  KEY `idx_sb_store` (`store_id`),
  KEY `idx_sb_warehouse` (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Immutable stock movement ledger
-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_movement` (
  `id`              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`     INT UNSIGNED    NOT NULL,
  `store_id`        INT UNSIGNED    NOT NULL,
  `warehouse_id`    INT UNSIGNED    DEFAULT NULL,
  `location_id`     INT UNSIGNED    DEFAULT NULL,
  `product_id`      INT UNSIGNED    NOT NULL,
  `batch_id`        INT UNSIGNED    DEFAULT NULL,
  `movement_type`   ENUM(
    'PURCHASE_IN','PURCHASE_RETURN_OUT','TRANSFER_OUT','TRANSFER_IN',
    'SALE_OUT','SALE_RETURN_IN','DAMAGE_OUT','WASTAGE_OUT',
    'STOCK_ADJUSTMENT_IN','STOCK_ADJUSTMENT_OUT','SHELF_REPLENISHMENT'
  )                               NOT NULL,
  `qty`             DECIMAL(15,3)  NOT NULL COMMENT 'Positive = in, Negative = out',
  `unit_id`         SMALLINT UNSIGNED DEFAULT NULL,
  `unit_cost`       DECIMAL(15,2)  DEFAULT NULL,
  `reference_type`  VARCHAR(40)    DEFAULT NULL COMMENT 'sale, purchase, transfer, etc.',
  `reference_id`    BIGINT UNSIGNED DEFAULT NULL,
  `note`            VARCHAR(255)   DEFAULT NULL,
  `created_by`      INT UNSIGNED   NOT NULL,
  `created_at`      DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sm_product` (`product_id`),
  KEY `idx_sm_store` (`store_id`),
  KEY `idx_sm_ref` (`reference_type`, `reference_id`),
  KEY `idx_sm_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_transfer` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`     INT UNSIGNED  NOT NULL,
  `transfer_no`     VARCHAR(40)   NOT NULL,
  `from_store_id`   INT UNSIGNED  NOT NULL,
  `from_wh_id`      INT UNSIGNED  NOT NULL,
  `to_store_id`     INT UNSIGNED  NOT NULL,
  `to_wh_id`        INT UNSIGNED  NOT NULL,
  `status`          ENUM('draft','authorized','dispatched','partial_received','received','cancelled') NOT NULL DEFAULT 'draft',
  `dispatch_at`     DATETIME      DEFAULT NULL,
  `received_at`     DATETIME      DEFAULT NULL,
  `note`            TEXT          DEFAULT NULL,
  `created_by`      INT UNSIGNED  NOT NULL,
  `authorized_by`   INT UNSIGNED  DEFAULT NULL,
  `created_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_transfer_no` (`business_id`, `transfer_no`),
  KEY `idx_transfer_from` (`from_store_id`),
  KEY `idx_transfer_to` (`to_store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_stock_transfer_item` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `transfer_id`     INT UNSIGNED  NOT NULL,
  `product_id`      INT UNSIGNED  NOT NULL,
  `batch_id`        INT UNSIGNED  DEFAULT NULL,
  `unit_id`         SMALLINT UNSIGNED NOT NULL,
  `requested_qty`   DECIMAL(15,3) NOT NULL,
  `dispatched_qty`  DECIMAL(15,3) NOT NULL DEFAULT 0.000,
  `received_qty`    DECIMAL(15,3) NOT NULL DEFAULT 0.000,
  `damage_qty`      DECIMAL(15,3) NOT NULL DEFAULT 0.000,
  `short_qty`       DECIMAL(15,3) GENERATED ALWAYS AS (`dispatched_qty` - `received_qty`) STORED,
  PRIMARY KEY (`id`),
  KEY `idx_sti_transfer` (`transfer_id`),
  KEY `idx_sti_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_stock_transfer_receipt` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `transfer_id`  INT UNSIGNED NOT NULL,
  `received_by`  INT UNSIGNED NOT NULL,
  `receipt_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note`         TEXT         DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_str_transfer` (`transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_adjustment` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED NOT NULL,
  `store_id`     INT UNSIGNED NOT NULL,
  `warehouse_id` INT UNSIGNED NOT NULL,
  `adj_no`       VARCHAR(40)  NOT NULL,
  `reason`       VARCHAR(255) NOT NULL,
  `status`       ENUM('draft','posted','cancelled') NOT NULL DEFAULT 'draft',
  `posted_by`    INT UNSIGNED DEFAULT NULL,
  `posted_at`    DATETIME     DEFAULT NULL,
  `created_by`   INT UNSIGNED NOT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_adj_no` (`business_id`, `adj_no`),
  KEY `idx_sadj_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_stock_adjustment_item` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `adjustment_id`  INT UNSIGNED  NOT NULL,
  `product_id`     INT UNSIGNED  NOT NULL,
  `batch_id`       INT UNSIGNED  DEFAULT NULL,
  `location_id`    INT UNSIGNED  NOT NULL,
  `unit_id`        SMALLINT UNSIGNED NOT NULL,
  `qty_before`     DECIMAL(15,3) NOT NULL,
  `qty_adjusted`   DECIMAL(15,3) NOT NULL COMMENT 'Positive=increase, Negative=decrease',
  `reason`         VARCHAR(255)  DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sadji_adj` (`adjustment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_count` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED NOT NULL,
  `store_id`     INT UNSIGNED NOT NULL,
  `warehouse_id` INT UNSIGNED NOT NULL,
  `count_no`     VARCHAR(40)  NOT NULL,
  `status`       ENUM('open','in_progress','posted','cancelled') NOT NULL DEFAULT 'open',
  `counted_by`   INT UNSIGNED DEFAULT NULL,
  `posted_by`    INT UNSIGNED DEFAULT NULL,
  `posted_at`    DATETIME     DEFAULT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_count_no` (`business_id`, `count_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_stock_count_item` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `count_id`     INT UNSIGNED  NOT NULL,
  `product_id`   INT UNSIGNED  NOT NULL,
  `batch_id`     INT UNSIGNED  DEFAULT NULL,
  `location_id`  INT UNSIGNED  NOT NULL,
  `unit_id`      SMALLINT UNSIGNED NOT NULL,
  `system_qty`   DECIMAL(15,3) NOT NULL COMMENT 'System balance at time of count',
  `counted_qty`  DECIMAL(15,3) DEFAULT NULL,
  `difference`   DECIMAL(15,3) GENERATED ALWAYS AS (`counted_qty` - `system_qty`) STORED,
  PRIMARY KEY (`id`),
  KEY `idx_sci_count` (`count_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_stock_reservation` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id`  INT UNSIGNED    NOT NULL,
  `location_id` INT UNSIGNED    NOT NULL,
  `batch_id`    INT UNSIGNED    DEFAULT NULL,
  `qty`         DECIMAL(15,3)   NOT NULL,
  `ref_type`    VARCHAR(40)     NOT NULL,
  `ref_id`      BIGINT UNSIGNED NOT NULL,
  `expires_at`  DATETIME        DEFAULT NULL,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sres_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- E. PURCHASE / SUPPLIER
-- ============================================================

CREATE TABLE `tbl_supplier` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`    INT UNSIGNED NOT NULL,
  `name`           VARCHAR(150) NOT NULL,
  `code`           VARCHAR(30)  DEFAULT NULL,
  `contact_person` VARCHAR(100) DEFAULT NULL,
  `phone`          VARCHAR(30)  DEFAULT NULL,
  `email`          VARCHAR(100) DEFAULT NULL,
  `ntn`            VARCHAR(30)  DEFAULT NULL COMMENT 'Tax registration number',
  `payment_terms`  VARCHAR(80)  DEFAULT NULL,
  `opening_balance`DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_supplier_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_supplier_address` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id` INT UNSIGNED NOT NULL,
  `label`       VARCHAR(40)  NOT NULL DEFAULT 'Main',
  `address`     TEXT         NOT NULL,
  `city`        VARCHAR(80)  DEFAULT NULL,
  `is_default`  TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_sup_addr` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_supplier_ledger` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_id`  INT UNSIGNED    NOT NULL,
  `ref_type`     VARCHAR(40)     NOT NULL COMMENT 'purchase, payment, return, etc.',
  `ref_id`       BIGINT UNSIGNED NOT NULL,
  `debit`        DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `credit`       DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `balance`      DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `note`         VARCHAR(255)    DEFAULT NULL,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sl_supplier` (`supplier_id`),
  KEY `idx_sl_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_purchase_order` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED  NOT NULL,
  `store_id`     INT UNSIGNED  NOT NULL,
  `supplier_id`  INT UNSIGNED  NOT NULL,
  `po_no`        VARCHAR(40)   NOT NULL,
  `po_date`      DATE          NOT NULL,
  `expected_date`DATE          DEFAULT NULL,
  `status`       ENUM('draft','pending','approved','partial','received','cancelled') NOT NULL DEFAULT 'draft',
  `subtotal`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `note`         TEXT          DEFAULT NULL,
  `created_by`   INT UNSIGNED  NOT NULL,
  `approved_by`  INT UNSIGNED  DEFAULT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_po_no` (`business_id`, `po_no`),
  KEY `idx_po_supplier` (`supplier_id`),
  KEY `idx_po_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_purchase_order_item` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `po_id`          INT UNSIGNED  NOT NULL,
  `product_id`     INT UNSIGNED  NOT NULL,
  `unit_id`        SMALLINT UNSIGNED NOT NULL,
  `ordered_qty`    DECIMAL(15,3) NOT NULL,
  `received_qty`   DECIMAL(15,3) NOT NULL DEFAULT 0.000,
  `unit_cost`      DECIMAL(15,2) NOT NULL,
  `tax_id`         SMALLINT UNSIGNED DEFAULT NULL,
  `tax_amount`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `line_total`     DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_poi_po` (`po_id`),
  KEY `idx_poi_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_goods_receipt` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED  NOT NULL,
  `store_id`     INT UNSIGNED  NOT NULL,
  `warehouse_id` INT UNSIGNED  NOT NULL,
  `po_id`        INT UNSIGNED  DEFAULT NULL,
  `supplier_id`  INT UNSIGNED  NOT NULL,
  `grn_no`       VARCHAR(40)   NOT NULL,
  `grn_date`     DATE          NOT NULL,
  `status`       ENUM('draft','posted','cancelled') NOT NULL DEFAULT 'draft',
  `subtotal`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `discount`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tax_amount`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `note`         TEXT          DEFAULT NULL,
  `created_by`   INT UNSIGNED  NOT NULL,
  `posted_at`    DATETIME      DEFAULT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_grn_no` (`business_id`, `grn_no`),
  KEY `idx_grn_supplier` (`supplier_id`),
  KEY `idx_grn_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_goods_receipt_item` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `grn_id`       INT UNSIGNED  NOT NULL,
  `poi_id`       INT UNSIGNED  DEFAULT NULL COMMENT 'PO item reference',
  `product_id`   INT UNSIGNED  NOT NULL,
  `batch_id`     INT UNSIGNED  DEFAULT NULL,
  `location_id`  INT UNSIGNED  NOT NULL,
  `unit_id`      SMALLINT UNSIGNED NOT NULL,
  `received_qty` DECIMAL(15,3) NOT NULL,
  `unit_cost`    DECIMAL(15,2) NOT NULL,
  `tax_id`       SMALLINT UNSIGNED DEFAULT NULL,
  `tax_amount`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `line_total`   DECIMAL(15,2) NOT NULL,
  `expiry_date`  DATE          DEFAULT NULL COMMENT 'Shortcut field; batch table is authoritative',
  PRIMARY KEY (`id`),
  KEY `idx_gri_grn` (`grn_id`),
  KEY `idx_gri_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_purchase_return` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`    INT UNSIGNED  NOT NULL,
  `store_id`       INT UNSIGNED  NOT NULL,
  `supplier_id`    INT UNSIGNED  NOT NULL,
  `grn_id`         INT UNSIGNED  DEFAULT NULL,
  `return_no`      VARCHAR(40)   NOT NULL,
  `return_date`    DATE          NOT NULL,
  `status`         ENUM('draft','posted','cancelled') NOT NULL DEFAULT 'draft',
  `total_amount`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `note`           TEXT          DEFAULT NULL,
  `created_by`     INT UNSIGNED  NOT NULL,
  `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_preturn_no` (`business_id`, `return_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_purchase_return_item` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `return_id`    INT UNSIGNED  NOT NULL,
  `product_id`   INT UNSIGNED  NOT NULL,
  `batch_id`     INT UNSIGNED  DEFAULT NULL,
  `location_id`  INT UNSIGNED  NOT NULL,
  `unit_id`      SMALLINT UNSIGNED NOT NULL,
  `qty`          DECIMAL(15,3) NOT NULL,
  `unit_cost`    DECIMAL(15,2) NOT NULL,
  `line_total`   DECIMAL(15,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pri_return` (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_supplier_payment` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED  NOT NULL,
  `supplier_id`  INT UNSIGNED  NOT NULL,
  `payment_no`   VARCHAR(40)   NOT NULL,
  `payment_date` DATE          NOT NULL,
  `amount`       DECIMAL(15,2) NOT NULL,
  `method`       VARCHAR(40)   NOT NULL DEFAULT 'cash',
  `reference`    VARCHAR(80)   DEFAULT NULL,
  `note`         TEXT          DEFAULT NULL,
  `created_by`   INT UNSIGNED  NOT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_spay_no` (`business_id`, `payment_no`),
  KEY `idx_spay_supplier` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- F. CUSTOMERS
-- ============================================================

CREATE TABLE `tbl_customer` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED NOT NULL,
  `name`         VARCHAR(150) NOT NULL,
  `phone`        VARCHAR(20)  NOT NULL,
  `email`        VARCHAR(100) DEFAULT NULL,
  `dob`          DATE         DEFAULT NULL,
  `gender`       ENUM('male','female','other') DEFAULT NULL,
  `is_member`    TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Loyalty member',
  `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_customer_phone` (`business_id`, `phone`),
  KEY `idx_customer_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_customer_address` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id` INT UNSIGNED NOT NULL,
  `label`       VARCHAR(40)  NOT NULL DEFAULT 'Home',
  `address`     TEXT         NOT NULL,
  `city`        VARCHAR(80)  DEFAULT NULL,
  `is_default`  TINYINT(1)   NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_cust_addr` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- G. POS / SALES
-- ============================================================

CREATE TABLE `tbl_payment_method` (
  `id`          SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED      NOT NULL,
  `name`        VARCHAR(60)       NOT NULL,
  `type`        ENUM('cash','card','digital','credit','other') NOT NULL DEFAULT 'cash',
  `is_active`   TINYINT(1)        NOT NULL DEFAULT 1,
  `sort_order`  SMALLINT          NOT NULL DEFAULT 0,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pm_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_payment_method` (`business_id`, `name`, `type`, `sort_order`) VALUES
  (1, 'Cash',       'cash',    1),
  (1, 'Card',       'card',    2),
  (1, 'EasyPaisa',  'digital', 3),
  (1, 'JazzCash',   'digital', 4);

-- -----------------------------------------------------------

CREATE TABLE `tbl_pos_shift` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `store_id`       INT UNSIGNED  NOT NULL,
  `register_id`    INT UNSIGNED  NOT NULL,
  `cashier_id`     INT UNSIGNED  NOT NULL,
  `opening_cash`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `closing_cash`   DECIMAL(15,2) DEFAULT NULL,
  `expected_cash`  DECIMAL(15,2) DEFAULT NULL,
  `difference`     DECIMAL(15,2) DEFAULT NULL,
  `shift_start`    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `shift_end`      DATETIME      DEFAULT NULL,
  `status`         ENUM('open','closed') NOT NULL DEFAULT 'open',
  PRIMARY KEY (`id`),
  KEY `idx_shift_store` (`store_id`),
  KEY `idx_shift_cashier` (`cashier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_sale` (
  `id`                   BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`          INT UNSIGNED    NOT NULL,
  `store_id`             INT UNSIGNED    NOT NULL,
  `register_id`          INT UNSIGNED    DEFAULT NULL,
  `device_id`            INT UNSIGNED    DEFAULT NULL,
  `shift_id`             INT UNSIGNED    DEFAULT NULL,
  `cashier_id`           INT UNSIGNED    NOT NULL,
  `customer_id`          INT UNSIGNED    DEFAULT NULL,
  `local_txn_uuid`       VARCHAR(60)     NOT NULL COMMENT 'UUID from POS device for idempotency',
  `invoice_no`           VARCHAR(40)     DEFAULT NULL COMMENT 'Server-assigned after sync',
  `sale_date`            DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal`             DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `item_discount`        DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `bill_discount`        DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `discount_amount`      DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `tax_amount`           DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `loyalty_redeemed`     DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `total_amount`         DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `paid_amount`          DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `change_amount`        DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `sale_status`          ENUM('completed','returned','partial_return','cancelled') NOT NULL DEFAULT 'completed',
  `sync_status`          ENUM('synced','pending','failed') NOT NULL DEFAULT 'synced',
  `synced_at`            DATETIME        DEFAULT NULL,
  `note`                 TEXT            DEFAULT NULL,
  `created_at`           DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`           DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sale_uuid` (`local_txn_uuid`),
  UNIQUE KEY `uq_invoice_no` (`business_id`, `invoice_no`),
  KEY `idx_sale_store` (`store_id`),
  KEY `idx_sale_customer` (`customer_id`),
  KEY `idx_sale_cashier` (`cashier_id`),
  KEY `idx_sale_date` (`sale_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_sale_item` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id`          BIGINT UNSIGNED NOT NULL,
  `product_id`       INT UNSIGNED    NOT NULL,
  `batch_id`         INT UNSIGNED    DEFAULT NULL,
  `location_id`      INT UNSIGNED    NOT NULL,
  `unit_id`          SMALLINT UNSIGNED NOT NULL,
  `promotion_id`     INT UNSIGNED    DEFAULT NULL,
  -- Snapshots at time of sale
  `product_name`     VARCHAR(200)    NOT NULL,
  `product_sku`      VARCHAR(60)     NOT NULL,
  `barcode`          VARCHAR(60)     DEFAULT NULL,
  `unit_name`        VARCHAR(40)     NOT NULL,
  --
  `qty`              DECIMAL(15,3)   NOT NULL,
  `unit_price`       DECIMAL(15,2)   NOT NULL,
  `item_discount`    DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `tax_rate`         DECIMAL(6,3)    NOT NULL DEFAULT 0.000,
  `tax_amount`       DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `line_total`       DECIMAL(15,2)   NOT NULL,
  `promotion_disc`   DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `promotion_snap`   JSON            DEFAULT NULL COMMENT 'Promotion details snapshot',
  PRIMARY KEY (`id`),
  KEY `idx_si_sale` (`sale_id`),
  KEY `idx_si_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_sale_payment` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sale_id`          BIGINT UNSIGNED NOT NULL,
  `payment_method_id`SMALLINT UNSIGNED NOT NULL,
  `amount`           DECIMAL(15,2)   NOT NULL,
  `reference`        VARCHAR(80)     DEFAULT NULL COMMENT 'Card auth code, e-wallet ref',
  `created_at`       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_sp_sale` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_sale_return` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`    INT UNSIGNED    NOT NULL,
  `store_id`       INT UNSIGNED    NOT NULL,
  `original_sale_id` BIGINT UNSIGNED NOT NULL,
  `cashier_id`     INT UNSIGNED    NOT NULL,
  `return_no`      VARCHAR(40)     NOT NULL,
  `return_date`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_refund`   DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `loyalty_reversed`DECIMAL(15,2)  NOT NULL DEFAULT 0.00,
  `refund_method_id`SMALLINT UNSIGNED DEFAULT NULL,
  `note`           TEXT            DEFAULT NULL,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sreturn_no` (`business_id`, `return_no`),
  KEY `idx_sret_sale` (`original_sale_id`),
  KEY `idx_sret_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_sale_return_item` (
  `id`               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id`        BIGINT UNSIGNED NOT NULL,
  `sale_item_id`     BIGINT UNSIGNED NOT NULL,
  `product_id`       INT UNSIGNED    NOT NULL,
  `batch_id`         INT UNSIGNED    DEFAULT NULL,
  `restock_location_id` INT UNSIGNED DEFAULT NULL COMMENT 'shelf/warehouse/quarantine/damaged',
  `unit_id`          SMALLINT UNSIGNED NOT NULL,
  `qty`              DECIMAL(15,3)   NOT NULL,
  `unit_price`       DECIMAL(15,2)   NOT NULL,
  `refund_amount`    DECIMAL(15,2)   NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sri_return` (`return_id`),
  KEY `idx_sri_sale_item` (`sale_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_held_sale` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `store_id`     INT UNSIGNED  NOT NULL,
  `register_id`  INT UNSIGNED  DEFAULT NULL,
  `cashier_id`   INT UNSIGNED  NOT NULL,
  `customer_id`  INT UNSIGNED  DEFAULT NULL,
  `hold_ref`     VARCHAR(40)   DEFAULT NULL COMMENT 'Customer name or note',
  `cart_data`    JSON          NOT NULL COMMENT 'Full cart snapshot',
  `held_at`      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resumed_at`   DATETIME      DEFAULT NULL,
  `status`       ENUM('held','resumed','cancelled') NOT NULL DEFAULT 'held',
  PRIMARY KEY (`id`),
  KEY `idx_held_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_cash_transaction` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `shift_id`     INT UNSIGNED  NOT NULL,
  `store_id`     INT UNSIGNED  NOT NULL,
  `type`         ENUM('cash_in','cash_out','cash_refund') NOT NULL,
  `amount`       DECIMAL(15,2) NOT NULL,
  `note`         VARCHAR(255)  DEFAULT NULL,
  `created_by`   INT UNSIGNED  NOT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ct_shift` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- H. PROMOTIONS ENGINE
-- ============================================================

CREATE TABLE `tbl_promotion` (
  `id`               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`      INT UNSIGNED  NOT NULL,
  `name`             VARCHAR(150)  NOT NULL,
  `promo_type`       ENUM('product','brand','category','store','buy_x_get_y','bundle','qty_tier','customer') NOT NULL,
  `discount_type`    ENUM('percent','fixed','free_item') NOT NULL DEFAULT 'percent',
  `discount_value`   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `min_qty`          DECIMAL(15,3) DEFAULT NULL,
  `min_amount`       DECIMAL(15,2) DEFAULT NULL,
  `start_at`         DATETIME      DEFAULT NULL,
  `end_at`           DATETIME      DEFAULT NULL,
  `priority`         SMALLINT      NOT NULL DEFAULT 0 COMMENT 'Higher = applied first',
  `allow_stacking`   TINYINT(1)    NOT NULL DEFAULT 0,
  `usage_limit`      INT UNSIGNED  DEFAULT NULL,
  `usage_per_customer`INT UNSIGNED DEFAULT NULL,
  `is_active`        TINYINT(1)    NOT NULL DEFAULT 1,
  `created_by`       INT UNSIGNED  NOT NULL,
  `created_at`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_promo_business` (`business_id`),
  KEY `idx_promo_dates` (`start_at`, `end_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_store` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED NOT NULL,
  `store_id`     INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_store` (`promotion_id`, `store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_product` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED NOT NULL,
  `product_id`   INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_product` (`promotion_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_brand` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED NOT NULL,
  `brand_id`     INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_brand` (`promotion_id`, `brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_category` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED NOT NULL,
  `category_id`  INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_promo_cat` (`promotion_id`, `category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_rule` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED NOT NULL,
  `rule_key`     VARCHAR(60)  NOT NULL,
  `rule_val`     VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_promo_rule` (`promotion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_promotion_usage` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `promotion_id` INT UNSIGNED    NOT NULL,
  `sale_id`      BIGINT UNSIGNED NOT NULL,
  `customer_id`  INT UNSIGNED    DEFAULT NULL,
  `discount_given`DECIMAL(15,2)  NOT NULL DEFAULT 0.00,
  `used_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pu_promotion` (`promotion_id`),
  KEY `idx_pu_sale` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- I. LOYALTY PROGRAM
-- ============================================================

CREATE TABLE `tbl_loyalty_program` (
  `id`               SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`      INT UNSIGNED      NOT NULL,
  `name`             VARCHAR(100)      NOT NULL,
  `earn_rate`        DECIMAL(8,4)      NOT NULL DEFAULT 1.0000 COMMENT '% of eligible amount',
  `point_value`      DECIMAL(8,2)      NOT NULL DEFAULT 1.00   COMMENT 'Rs per point',
  `min_spend`        DECIMAL(15,2)     NOT NULL DEFAULT 100.00,
  `credit_delay_hrs` SMALLINT          NOT NULL DEFAULT 24,
  `expiry_months`    SMALLINT          NOT NULL DEFAULT 2,
  `min_redemption`   DECIMAL(15,2)     DEFAULT NULL,
  `max_redemption`   DECIMAL(15,2)     DEFAULT NULL,
  `is_active`        TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lp_business` (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_loyalty_program` (`business_id`,`name`,`earn_rate`,`point_value`,`min_spend`,`credit_delay_hrs`,`expiry_months`) 
VALUES (1,'EPOS Loyalty',1.0000,1.00,100.00,24,2);

-- -----------------------------------------------------------

CREATE TABLE `tbl_loyalty_account` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_id`       INT UNSIGNED    NOT NULL,
  `store_id`          INT UNSIGNED    NOT NULL,
  `program_id`        SMALLINT UNSIGNED NOT NULL,
  `available_points`  DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `pending_points`    DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `lifetime_earned`   DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `lifetime_redeemed` DECIMAL(15,2)   NOT NULL DEFAULT 0.00,
  `created_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_loyalty_account` (`customer_id`, `store_id`),
  KEY `idx_la_customer` (`customer_id`),
  KEY `idx_la_store` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_loyalty_points_lot` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id`     BIGINT UNSIGNED NOT NULL,
  `sale_id`        BIGINT UNSIGNED NOT NULL,
  `earned_points`  DECIMAL(15,2)   NOT NULL,
  `remaining_pts`  DECIMAL(15,2)   NOT NULL,
  `available_at`   DATETIME        NOT NULL COMMENT 'earned_at + credit_delay',
  `expires_at`     DATETIME        NOT NULL,
  `status`         ENUM('pending','available','used','expired','reversed') NOT NULL DEFAULT 'pending',
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lot_account` (`account_id`),
  KEY `idx_lot_status` (`status`),
  KEY `idx_lot_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_loyalty_transaction` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id`   BIGINT UNSIGNED NOT NULL,
  `lot_id`       BIGINT UNSIGNED DEFAULT NULL,
  `txn_type`     ENUM('earn','redeem','expire','reverse','adjust') NOT NULL,
  `points`       DECIMAL(15,2)   NOT NULL COMMENT 'Positive=credit, Negative=debit',
  `ref_type`     VARCHAR(40)     DEFAULT NULL,
  `ref_id`       BIGINT UNSIGNED DEFAULT NULL,
  `note`         VARCHAR(255)    DEFAULT NULL,
  `created_by`   INT UNSIGNED    DEFAULT NULL,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lt_account` (`account_id`),
  KEY `idx_lt_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_loyalty_redemption` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id`   BIGINT UNSIGNED NOT NULL,
  `sale_id`      BIGINT UNSIGNED NOT NULL,
  `points_used`  DECIMAL(15,2)   NOT NULL,
  `value_rs`     DECIMAL(15,2)   NOT NULL,
  `redeemed_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lr_account` (`account_id`),
  KEY `idx_lr_sale` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_loyalty_expiration` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id`   BIGINT UNSIGNED NOT NULL,
  `lot_id`       BIGINT UNSIGNED NOT NULL,
  `expired_pts`  DECIMAL(15,2)   NOT NULL,
  `expired_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_le_account` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- J. OFFLINE SYNC
-- ============================================================

CREATE TABLE `tbl_device_sync_log` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id`    INT UNSIGNED    NOT NULL,
  `sync_type`    ENUM('push','pull') NOT NULL,
  `status`       ENUM('success','partial','failed') NOT NULL,
  `records_sent` INT UNSIGNED    NOT NULL DEFAULT 0,
  `records_ok`   INT UNSIGNED    NOT NULL DEFAULT 0,
  `records_fail` INT UNSIGNED    NOT NULL DEFAULT 0,
  `error_msg`    TEXT            DEFAULT NULL,
  `synced_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dsl_device` (`device_id`),
  KEY `idx_dsl_date` (`synced_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_sync_conflict` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id`      INT UNSIGNED    NOT NULL,
  `local_uuid`     VARCHAR(60)     NOT NULL,
  `conflict_type`  VARCHAR(80)     NOT NULL COMMENT 'e.g. oversell, duplicate_uuid',
  `payload`        JSON            NOT NULL,
  `resolution`     VARCHAR(80)     DEFAULT NULL,
  `resolved_by`    INT UNSIGNED    DEFAULT NULL,
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolved_at`    DATETIME        DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sc_device` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_device_catalog_version` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_id`    INT UNSIGNED NOT NULL,
  `catalog_type` VARCHAR(40)  NOT NULL COMMENT 'products, customers, promotions, etc.',
  `version`      VARCHAR(40)  NOT NULL,
  `synced_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dcv` (`device_id`, `catalog_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- K. ADMIN / OPERATIONS
-- ============================================================

CREATE TABLE `tbl_day_close` (
  `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `store_id`        INT UNSIGNED  NOT NULL,
  `register_id`     INT UNSIGNED  NOT NULL,
  `shift_id`        INT UNSIGNED  NOT NULL,
  `cashier_id`      INT UNSIGNED  NOT NULL,
  `close_date`      DATE          NOT NULL,
  `opening_cash`    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `cash_sales`      DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `cash_in`         DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `cash_out`        DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `cash_refunds`    DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `expected_cash`   DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `actual_cash`     DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `difference`      DECIMAL(15,2) GENERATED ALWAYS AS (`actual_cash` - `expected_cash`) STORED,
  `status`          ENUM('open','closed') NOT NULL DEFAULT 'closed',
  `closed_by`       INT UNSIGNED  NOT NULL,
  `closed_at`       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reopen_reason`   TEXT          DEFAULT NULL,
  `reopened_by`     INT UNSIGNED  DEFAULT NULL,
  `reopened_at`     DATETIME      DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_dayclose` (`store_id`, `register_id`, `close_date`),
  KEY `idx_dc_store` (`store_id`),
  KEY `idx_dc_date` (`close_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_day_close_payment` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `dayclose_id`  INT UNSIGNED  NOT NULL,
  `method_id`    SMALLINT UNSIGNED NOT NULL,
  `amount`       DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `idx_dcp_dc` (`dayclose_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_expense_category` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id` INT UNSIGNED NOT NULL,
  `name`        VARCHAR(100) NOT NULL,
  `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_expense` (
  `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED  NOT NULL,
  `store_id`     INT UNSIGNED  NOT NULL,
  `category_id`  INT UNSIGNED  NOT NULL,
  `expense_date` DATE          NOT NULL,
  `amount`       DECIMAL(15,2) NOT NULL,
  `description`  TEXT          DEFAULT NULL,
  `created_by`   INT UNSIGNED  NOT NULL,
  `created_at`   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expense_store` (`store_id`),
  KEY `idx_expense_date` (`expense_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_notification` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED    NOT NULL,
  `store_id`     INT UNSIGNED    DEFAULT NULL,
  `user_id`      INT UNSIGNED    DEFAULT NULL,
  `event_type`   VARCHAR(60)     NOT NULL COMMENT 'low_stock, batch_expiry, day_close_pending, etc.',
  `title`        VARCHAR(150)    NOT NULL,
  `message`      TEXT            NOT NULL,
  `is_read`      TINYINT(1)      NOT NULL DEFAULT 0,
  `ref_type`     VARCHAR(40)     DEFAULT NULL,
  `ref_id`       BIGINT UNSIGNED DEFAULT NULL,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_user` (`user_id`),
  KEY `idx_notif_store` (`store_id`),
  KEY `idx_notif_date` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tbl_notification_log` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_id` BIGINT UNSIGNED NOT NULL,
  `channel`      ENUM('inapp','email','sms','whatsapp') NOT NULL DEFAULT 'inapp',
  `recipient`    VARCHAR(100)    NOT NULL,
  `status`       ENUM('sent','failed','pending') NOT NULL DEFAULT 'pending',
  `provider_resp`TEXT            DEFAULT NULL,
  `sent_at`      DATETIME        DEFAULT NULL,
  `created_at`   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nl_notif` (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_system_setting` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED NOT NULL,
  `store_id`     INT UNSIGNED DEFAULT NULL COMMENT 'NULL = global setting',
  `group_name`   VARCHAR(60)  NOT NULL COMMENT 'general, pos, loyalty, inventory, security, sync',
  `setting_key`  VARCHAR(80)  NOT NULL,
  `setting_val`  TEXT         DEFAULT NULL,
  `updated_by`   INT UNSIGNED DEFAULT NULL,
  `updated_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sys_setting` (`business_id`, `store_id`, `setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------

CREATE TABLE `tbl_holiday` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `business_id`  INT UNSIGNED NOT NULL,
  `store_id`     INT UNSIGNED DEFAULT NULL COMMENT 'NULL = all stores',
  `holiday_date` DATE         NOT NULL,
  `name`         VARCHAR(100) NOT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_holiday_date` (`holiday_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED: Default business & store (sample)
-- ============================================================

INSERT INTO `tbl_business` (`id`, `name`, `currency`, `timezone`) 
VALUES (1, 'EPOS Retail', 'PKR', 'Asia/Karachi');

INSERT INTO `tbl_store` (`id`, `business_id`, `name`, `code`, `city`) 
VALUES 
  (1, 1, 'Main Branch - Lahore',    'LHR01', 'Lahore'),
  (2, 1, 'Branch - Islamabad',      'ISB01', 'Islamabad');

INSERT INTO `tbl_warehouse` (`id`, `business_id`, `store_id`, `name`, `code`) 
VALUES 
  (1, 1, 1, 'Main Warehouse LHR', 'WH-LHR-01'),
  (2, 1, 2, 'Main Warehouse ISB', 'WH-ISB-01');

INSERT INTO `tbl_stock_location` (`business_id`, `store_id`, `warehouse_id`, `name`, `code`, `location_type`) 
VALUES 
  (1, 1, 1, 'Shelf A1', 'SH-A1', 'shelf'),
  (1, 1, 1, 'Rack B1',  'RK-B1', 'rack'),
  (1, 1, 1, 'Quarantine Zone', 'QRNT', 'quarantine'),
  (1, 1, 1, 'Damaged Store',   'DMGD', 'damaged'),
  (1, 2, 2, 'Shelf A1', 'SH-A1', 'shelf');

-- -----------------------------------------------------------
-- REGISTERS SEED
-- -----------------------------------------------------------
INSERT INTO `tbl_pos_register` (`id`, `store_id`, `name`, `code`) VALUES
  (1, 1, 'Counter 1 - Lahore',    'REG-LHR-01'),
  (2, 1, 'Counter 2 - Lahore',    'REG-LHR-02'),
  (3, 2, 'Counter 1 - Islamabad', 'REG-ISB-01');

-- -----------------------------------------------------------
-- ROLE PERMISSIONS SEED
-- -----------------------------------------------------------
INSERT INTO `tbl_role_permission` (`role_id`, `permission_id`) VALUES
  -- Master Admin (role 1): All permissions
  (1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),
  -- Store Manager (role 2): All operational permissions
  (2,1),(2,2),(2,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13),
  -- POS Cashier (role 3): Sales, returns, discounts, holds
  (3,1),(3,2),(3,3),(3,4),(3,5),
  -- Inventory User (role 4): Purchases & reports
  (4,6),(4,7),(4,11),
  -- Warehouse User (role 5): Transfers & movements
  (5,8),(5,9),(5,10),
  -- Reports User (role 6): Sales reports
  (6,11);

-- -----------------------------------------------------------
-- USERS SEED (tbl_user)
-- Passwords:
-- admin: admin123
-- manager_lhr: manager123
-- cashier_lhr: cashier123
-- inventory_user: manager123
-- -----------------------------------------------------------
INSERT INTO `tbl_user` (`id`, `business_id`, `role_id`, `username`, `full_name`, `email`, `phone`, `password`, `is_active`) VALUES
  (1, 1, 1, 'admin',          'System Administrator',     'admin@epos.local',      '03001234567', '$2y$12$yxSiekRpQRXpcFz0gqD7qO9vGaDwgqi5tGM4DiTHUOFNm2vH9BCbe', 1),
  (2, 1, 2, 'manager_lhr',    'Lahore Store Manager',     'manager.lhr@epos.local', '03011234567', '$2y$12$KnCArORcxyjyMiPwkR7NK.AjCf3mfdoqOBUeWrMaVxb2c3Lb1vaOe', 1),
  (3, 1, 3, 'cashier_lhr',    'Ali Cashier (Lahore)',     'cashier.lhr@epos.local', '03021234567', '$2y$12$MngXY84NSfp.HkceboTaXeUdnPkcHGDkD8uIPXJG7opEi23PhHn62', 1),
  (4, 1, 4, 'inventory_user', 'Tariq Inventory Incharge', 'inventory@epos.local',   '03031234567', '$2y$12$KnCArORcxyjyMiPwkR7NK.AjCf3mfdoqOBUeWrMaVxb2c3Lb1vaOe', 1);

-- -----------------------------------------------------------
-- USER STORE ACCESS
-- -----------------------------------------------------------
INSERT INTO `tbl_user_store_access` (`user_id`, `store_id`) VALUES
  (1, 1),
  (1, 2),
  (2, 1),
  (3, 1),
  (4, 1),
  (4, 2);

-- -----------------------------------------------------------
-- USER WAREHOUSE ACCESS
-- -----------------------------------------------------------
INSERT INTO `tbl_user_warehouse_access` (`user_id`, `warehouse_id`) VALUES
  (1, 1),
  (1, 2),
  (2, 1),
  (3, 1),
  (4, 1),
  (4, 2);

-- -----------------------------------------------------------
-- SAMPLE PRODUCTS & INITIAL SHELF STOCK
-- -----------------------------------------------------------
INSERT INTO `tbl_product_category` (`id`, `business_id`, `name`, `slug`) VALUES
  (1, 1, 'Lighting & Bulbs',   'lighting-bulbs'),
  (2, 1, 'Cables & Wiring',    'cables-wiring'),
  (3, 1, 'Switches & Sockets', 'switches-sockets'),
  (4, 1, 'Circuit Breakers',   'circuit-breakers');

INSERT INTO `tbl_product_brand` (`id`, `business_id`, `name`) VALUES
  (1, 1, 'Philips'),
  (2, 1, 'Pakistan Cables'),
  (3, 1, 'Schneider Electric'),
  (4, 1, 'Clipsal');

INSERT INTO `tbl_product` (`id`, `business_id`, `sku`, `internal_code`, `name`, `category_id`, `brand_id`, `base_unit_id`, `purchase_price`, `retail_price`, `wholesale_price`, `min_stock`, `reorder_level`, `is_active`) VALUES
  (1, 1, 'SKU-BULB-12W', 'PHIL-12W', 'Philips LED Bulb 12W Warm White',               1, 1, 1, 280.00,  350.00,  320.00, 10.000, 25.000, 1),
  (2, 1, 'SKU-BULB-18W', 'PHIL-18W', 'Philips LED Bulb 18W Cool Daylight',             1, 1, 1, 420.00,  520.00,  480.00, 10.000, 20.000, 1),
  (3, 1, 'SKU-CBL-3029', 'PC-3029',  'Pakistan Cables 3/0.29 Single Core (90m coil)',  2, 2, 1, 4500.00, 5200.00, 4900.00, 5.000,  10.000, 1),
  (4, 1, 'SKU-CBL-7029', 'PC-7029',  'Pakistan Cables 7/0.29 Single Core (90m coil)',  2, 2, 1, 9800.00, 11200.00, 10500.00, 3.000, 6.000,  1),
  (5, 1, 'SKU-SW-1GANG', 'SCH-1G',   'Schneider 1 Gang 1 Way Light Switch',            3, 3, 1, 180.00,  250.00,  220.00, 15.000, 30.000, 1),
  (6, 1, 'SKU-CB-SP20A', 'SCH-20A',  'Schneider Miniature Circuit Breaker 20A SP',      4, 3, 1, 650.00,  850.00,  780.00, 8.000,  15.000, 1);

INSERT INTO `tbl_product_barcode` (`product_id`, `unit_id`, `barcode`, `is_default`) VALUES
  (1, 1, '896400010012', 1),
  (2, 1, '896400010018', 1),
  (3, 1, '896400023029', 1),
  (4, 1, '896400027029', 1),
  (5, 1, '896400030001', 1),
  (6, 1, '896400040020', 1);

-- Initial Stock on Shelf A1 (location_id=1, store_id=1, warehouse_id=1)
INSERT INTO `tbl_stock_balance` (`business_id`, `store_id`, `warehouse_id`, `location_id`, `product_id`, `batch_id`, `quantity`) VALUES
  (1, 1, 1, 1, 1, 0, 50.000),
  (1, 1, 1, 1, 2, 0, 35.000),
  (1, 1, 1, 1, 3, 0, 12.000),
  (1, 1, 1, 1, 4, 0, 8.000),
  (1, 1, 1, 1, 5, 0, 80.000),
  (1, 1, 1, 1, 6, 0, 25.000);

-- Initial stock movement audit log
INSERT INTO `tbl_stock_movement` (`business_id`, `store_id`, `warehouse_id`, `location_id`, `product_id`, `movement_type`, `qty`, `note`, `created_by`) VALUES
  (1, 1, 1, 1, 1, 'STOCK_ADJUSTMENT_IN', 50.000, 'Initial System Setup Stock', 1),
  (1, 1, 1, 1, 2, 'STOCK_ADJUSTMENT_IN', 35.000, 'Initial System Setup Stock', 1),
  (1, 1, 1, 1, 3, 'STOCK_ADJUSTMENT_IN', 12.000, 'Initial System Setup Stock', 1),
  (1, 1, 1, 1, 4, 'STOCK_ADJUSTMENT_IN', 8.000,  'Initial System Setup Stock', 1),
  (1, 1, 1, 1, 5, 'STOCK_ADJUSTMENT_IN', 80.000, 'Initial System Setup Stock', 1),
  (1, 1, 1, 1, 6, 'STOCK_ADJUSTMENT_IN', 25.000, 'Initial System Setup Stock', 1);

-- Sample Customer for testing Loyalty
INSERT INTO `tbl_customer` (`id`, `business_id`, `name`, `phone`, `email`, `is_member`) VALUES
  (1, 1, 'Zeeshan Ahmed', '03001122334', 'zeeshan@example.com', 1),
  (2, 1, 'Hamza Malik',   '03215566778', 'hamza@example.com',   1);

INSERT INTO `tbl_loyalty_account` (`customer_id`, `store_id`, `program_id`, `available_points`, `pending_points`, `lifetime_earned`, `lifetime_redeemed`) VALUES
  (1, 1, 1, 150.00, 25.00, 200.00, 50.00),
  (2, 1, 1, 50.00,  0.00,  50.00,  0.00);

-- Sample Suppliers
INSERT INTO `tbl_supplier` (`id`, `business_id`, `name`, `code`, `contact_person`, `phone`, `email`, `opening_balance`) VALUES
  (1, 1, 'Siemens Electric Pakistan', 'SUP-SIE-01', 'Usman Qureshi', '04235889900', 'info@siemens.pk',          50000.00),
  (2, 1, 'Pakistan Cables Ltd',       'SUP-PAK-02', 'Farhan Sheikh', '02132567890', 'sales@pakistancables.com', 120000.00);

-- Re-enable foreign key checks
SET foreign_key_checks = 1;

-- ============================================================
-- END OF epos_db.sql
-- ============================================================

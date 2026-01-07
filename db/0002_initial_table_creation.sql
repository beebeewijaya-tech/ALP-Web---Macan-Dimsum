USE macan_dimsum_go;

CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  `updated` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_email` (`email`)
) ENGINE=InnoDB;

CREATE TABLE `status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status_type` VARCHAR(50) NOT NULL,
  `status_desc` VARCHAR(255) NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `product` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `image` TEXT NULL,
  `price` INT NULL,
  `created` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `order` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `status_id` INT NOT NULL,
  `delivery_type` VARCHAR(50) NULL,
  `total_price` DECIMAL(12,2) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NOT NULL,
  `notes` VARCHAR(255) NULL,
  `phone` VARCHAR(30) NOT NULL,
  `created` DATETIME NOT NULL,
  `updated` DATETIME NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_user_id` (`user_id`),
  KEY `idx_order_status_id` (`status_id`),
  CONSTRAINT `fk_order_user`
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_order_status`
    FOREIGN KEY (`status_id`) REFERENCES `status` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `order_item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` INT NOT NULL,
  `created` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_item_order_id` (`order_id`),
  KEY `idx_order_item_product_id` (`product_id`),
  CONSTRAINT `fk_order_item_order`
    FOREIGN KEY (`order_id`) REFERENCES `order` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_order_item_product`
    FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

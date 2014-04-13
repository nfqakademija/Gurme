-- MySQL Script generated by MySQL Workbench
-- Thu 10 Apr 2014 03:02:34 PM EEST
-- Model: New Model    Version: 1.0
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema symfony
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `symfony` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `symfony` ;

-- -----------------------------------------------------
-- Table `symfony`.`ingredients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`ingredients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `alias` MEDIUMTEXT NULL,
  `popularity` INT NULL,
  `icon_url` VARCHAR(2083) NULL,
  `calories` INT(11) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`users` (
  `id` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
COMMENT = 'FOSUserBundle';


-- -----------------------------------------------------
-- Table `symfony`.`recipe_photos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipe_photos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `recipe_id` INT(11) NOT NULL,
  `url` VARCHAR(2083) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `uploaded_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipe_photos_1_idx` (`recipe_id` ASC),
  INDEX `fk_recipe_photos_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_recipe_photos_1`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_photos_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`recipes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `prep_time` TIME NULL,
  `user_id` INT NOT NULL,
  `directions` TEXT NULL,
  `cook_time` TIME NULL,
  `ready_time` TIME NULL,
  `calories` INT(11) NULL,
  `servings` SMALLINT NULL,
  `approved` TINYINT(1) NULL,
  `created_at` DATETIME NULL,
  `rating` DOUBLE NULL,
  `cover_photo_id` INT(11) NULL,
  `private` TINYINT(1) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipes_2_idx` (`cover_photo_id` ASC),
  INDEX `fk_recipes_1_idx` (`user_id` ASC),
  CONSTRAINT `fk_recipes_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipes_2`
    FOREIGN KEY (`cover_photo_id`)
    REFERENCES `symfony`.`recipe_photos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`units`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`units` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `short` VARCHAR(10) NOT NULL,
  `long` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`recipe_ingredients`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipe_ingredients` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `recipe_id` INT(11) NOT NULL,
  `ingredient_id` INT(11) NOT NULL,
  `ammount` VARCHAR(10) NOT NULL,
  `unit_id` INT(11) NULL,
  `note` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipe_ingredients_1_idx` (`ingredient_id` ASC),
  INDEX `fk_recipe_ingredients_2_idx` (`recipe_id` ASC),
  INDEX `fk_recipe_ingredients_3_idx` (`unit_id` ASC),
  CONSTRAINT `fk_recipe_ingredients_1`
    FOREIGN KEY (`ingredient_id`)
    REFERENCES `symfony`.`ingredients` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_ingredients_2`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_ingredients_3`
    FOREIGN KEY (`unit_id`)
    REFERENCES `symfony`.`units` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`recipe_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipe_categories` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `recipe_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipe_categories_1_idx` (`recipe_id` ASC),
  INDEX `fk_recipe_categories_2_idx` (`category_id` ASC),
  CONSTRAINT `fk_recipe_categories_1`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_categories_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `symfony`.`categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`recipe__ratings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipe__ratings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `recipe_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` TINYINT NOT NULL,
  `rated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipe__ratings_1_idx` (`recipe_id` ASC),
  INDEX `fk_recipe__ratings_2_idx` (`user_id` ASC),
  CONSTRAINT `fk_recipe__ratings_1`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe__ratings_2`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`user_favorites`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`user_favorites` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `recipe_id` INT(11) NOT NULL,
  `added_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_favorites_1_idx` (`user_id` ASC),
  INDEX `fk_user_favorites_2_idx` (`recipe_id` ASC),
  CONSTRAINT `fk_user_favorites_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_favorites_2`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `symfony`.`recipe_comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `symfony`.`recipe_comments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `recipe_id` INT(11) NOT NULL,
  `comment` VARCHAR(500) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `flaged` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_recipe_comments_1_idx` (`user_id` ASC),
  INDEX `fk_recipe_comments_2_idx` (`recipe_id` ASC),
  CONSTRAINT `fk_recipe_comments_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `symfony`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_recipe_comments_2`
    FOREIGN KEY (`recipe_id`)
    REFERENCES `symfony`.`recipes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
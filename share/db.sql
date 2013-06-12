SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `AntHill` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `AntHill` ;

-- -----------------------------------------------------
-- Table `AntHill`.`project`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`project` (
  `id_project` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(125) NOT NULL ,
  `content` TEXT NOT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_project`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`user` (
  `id_user` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `first_name` VARCHAR(125) NOT NULL ,
  `last_name` VARCHAR(125) NOT NULL ,
  `password` VARCHAR(64) NOT NULL ,
  `status` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_user`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`category` (
  `id_category` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(125) NOT NULL ,
  PRIMARY KEY (`id_category`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`ticket`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`ticket` (
  `id_ticket` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_project` INT UNSIGNED NOT NULL ,
  `id_user` INT UNSIGNED NOT NULL ,
  `id_category` INT UNSIGNED NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `content` TEXT NOT NULL ,
  `date_created` DATETIME NOT NULL ,
  `duration_start` INT NOT NULL ,
  `duration_type` INT NOT NULL ,
  `type` ENUM('bug','improvment') NOT NULL DEFAULT 'bug' ,
  `significance` ENUM('low','normal','high','rightnow') NOT NULL DEFAULT 'normal' ,
  `status` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id_ticket`, `id_project`, `id_user`, `id_category`) ,
  INDEX `fk_ticket_project1_idx` (`id_project` ASC) ,
  INDEX `fk_ticket_user1_idx` (`id_user` ASC) ,
  INDEX `fk_ticket_category1_idx` (`id_category` ASC) ,
  CONSTRAINT `fk_ticket_project1`
    FOREIGN KEY (`id_project` )
    REFERENCES `AntHill`.`project` (`id_project` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_user1`
    FOREIGN KEY (`id_user` )
    REFERENCES `AntHill`.`user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_category1`
    FOREIGN KEY (`id_category` )
    REFERENCES `AntHill`.`category` (`id_category` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`role` (
  `id_role` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_project` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(125) NOT NULL ,
  PRIMARY KEY (`id_role`, `id_project`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`project_has_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`project_has_user` (
  `id_project` INT UNSIGNED NOT NULL ,
  `id_user` INT UNSIGNED NOT NULL ,
  `id_role` INT UNSIGNED NOT NULL ,
  `date_added` DATETIME NOT NULL ,
  PRIMARY KEY (`id_project`, `id_user`, `id_role`) ,
  INDEX `fk_project_has_user_project_idx` (`id_project` ASC) ,
  INDEX `fk_project_has_user_user1_idx` (`id_user` ASC) ,
  INDEX `fk_project_has_user_role1_idx` (`id_role` ASC) ,
  CONSTRAINT `fk_project_has_user_project`
    FOREIGN KEY (`id_project` )
    REFERENCES `AntHill`.`project` (`id_project` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_has_user_user1`
    FOREIGN KEY (`id_user` )
    REFERENCES `AntHill`.`user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_has_user_role1`
    FOREIGN KEY (`id_role` )
    REFERENCES `AntHill`.`role` (`id_role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`ticket_has_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`ticket_has_user` (
  `id_ticket` INT UNSIGNED NOT NULL ,
  `id_user` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_ticket`, `id_user`) ,
  INDEX `fk_ticket_has_user_ticket1_idx` (`id_ticket` ASC) ,
  INDEX `fk_ticket_has_user_user1_idx` (`id_user` ASC) ,
  CONSTRAINT `fk_ticket_has_user_ticket1`
    FOREIGN KEY (`id_ticket` )
    REFERENCES `AntHill`.`ticket` (`id_ticket` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_has_user_user1`
    FOREIGN KEY (`id_user` )
    REFERENCES `AntHill`.`user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`action`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`action` (
  `id_action` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `id_ticket` INT UNSIGNED NOT NULL ,
  `id_user` INT UNSIGNED NOT NULL ,
  `type` ENUM('add_user','closed','opened','assigned','accepted','started') NOT NULL DEFAULT 'opened' ,
  `date_action` DATETIME NOT NULL ,
  PRIMARY KEY (`id_action`, `id_ticket`, `id_user`) ,
  INDEX `fk_action_ticket1_idx` (`id_ticket` ASC) ,
  INDEX `fk_action_user1_idx` (`id_user` ASC) ,
  CONSTRAINT `fk_action_ticket1`
    FOREIGN KEY (`id_ticket` )
    REFERENCES `AntHill`.`ticket` (`id_ticket` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_action_user1`
    FOREIGN KEY (`id_user` )
    REFERENCES `AntHill`.`user` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`ticket_has_blocking`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`ticket_has_blocking` (
  `id_ticket` INT UNSIGNED NOT NULL ,
  `id_blocking` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_ticket`, `id_blocking`) ,
  INDEX `fk_ticket_has_blocking_ticket1_idx` (`id_ticket` ASC) ,
  INDEX `fk_ticket_has_blocking_ticket2_idx` (`id_blocking` ASC) ,
  CONSTRAINT `fk_ticket_has_blocking_ticket1`
    FOREIGN KEY (`id_ticket` )
    REFERENCES `AntHill`.`ticket` (`id_ticket` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ticket_has_blocking_ticket2`
    FOREIGN KEY (`id_blocking` )
    REFERENCES `AntHill`.`ticket` (`id_ticket` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`project_has_role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`project_has_role` (
  `id_project` INT UNSIGNED NOT NULL ,
  `id_role` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_project`, `id_role`) ,
  INDEX `fk_project_has_role_role1_idx` (`id_role` ASC) ,
  INDEX `fk_project_has_role_project1_idx` (`id_project` ASC) ,
  CONSTRAINT `fk_project_has_role_role1`
    FOREIGN KEY (`id_role` )
    REFERENCES `AntHill`.`role` (`id_role` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_has_role_project1`
    FOREIGN KEY (`id_project` )
    REFERENCES `AntHill`.`project` (`id_project` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `AntHill`.`project_has_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `AntHill`.`project_has_category` (
  `id_project` INT UNSIGNED NOT NULL ,
  `id_category` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id_project`, `id_category`) ,
  INDEX `fk_project_has_category_category1_idx` (`id_category` ASC) ,
  INDEX `fk_project_has_category_project1_idx` (`id_project` ASC) ,
  CONSTRAINT `fk_project_has_category_category1`
    FOREIGN KEY (`id_category` )
    REFERENCES `AntHill`.`category` (`id_category` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_has_category_project1`
    FOREIGN KEY (`id_project` )
    REFERENCES `AntHill`.`project` (`id_project` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `AntHill` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

CREATE  TABLE IF NOT EXISTS `aet`.`acl_roles` (
  `roleID` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `parent` VARCHAR(45) NULL ,
  `child` VARCHAR(45) NULL ,
  PRIMARY KEY (`roleID`) )
ENGINE = MyISAM;

CREATE  TABLE IF NOT EXISTS `aet`.`acl_ressources` (
  `ressourceID` INT(11) NOT NULL ,
  `module` VARCHAR(45) NULL ,
  `controller` VARCHAR(45) NULL ,
  PRIMARY KEY (`ressourceID`) )
ENGINE = MyISAM;

CREATE  TABLE IF NOT EXISTS `aet`.`acl_privileges` (
  `privilegeID` INT(11) NOT NULL ,
  `action` VARCHAR(45) NULL ,
  PRIMARY KEY (`privilegeID`) )
ENGINE = MyISAM;


CREATE  TABLE IF NOT EXISTS `aet`.`acl_rights` (
  `rightID` INT(11) NOT NULL AUTO_INCREMENT ,
  `roleID` INT(11) NOT NULL ,
  `ressourceID` INT(11) NOT NULL ,
  `privilegeID` INT(11) NOT NULL ,
  PRIMARY KEY (`rightID`, `roleID`, `ressourceID`, `privilegeID`) ,
  INDEX `fk_acl_rights_acl_ressources1` (`ressourceID` ASC) ,
  INDEX `fk_acl_rights_acl_privileges1` (`privilegeID` ASC) ,
  INDEX `fk_acl_rights_acl_roles1` (`roleID` ASC) ,
  CONSTRAINT `fk_acl_rights_acl_ressources1`
    FOREIGN KEY (`ressourceID` )
    REFERENCES `aet`.`acl_ressources` (`ressourceID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_rights_acl_privileges1`
    FOREIGN KEY (`privilegeID` )
    REFERENCES `aet`.`acl_privileges` (`privilegeID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acl_rights_acl_roles1`
    FOREIGN KEY (`roleID` )
    REFERENCES `aet`.`acl_roles` (`roleID` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = MyISAM;

INSERT INTO `aet`.`acl_roles` (`roleID`, `name`, `parent`, `child`) VALUES ('1', 'Gast', NULL, NULL);
INSERT INTO `aet`.`acl_roles` (`roleID`, `name`, `parent`, `child`) VALUES ('2', 'User', NULL, NULL);
INSERT INTO `aet`.`acl_roles` (`roleID`, `name`, `parent`, `child`) VALUES ('3', 'administrator', NULL, NULL);
INSERT INTO `aet`.`acl_roles` (`roleID`, `name`, `parent`, `child`) VALUES ('4', 'Moderator', NULL, NULL);

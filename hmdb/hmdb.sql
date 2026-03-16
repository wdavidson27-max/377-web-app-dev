-- Thw hMDB Schema

CREATE TABLE `hmdb`.`movie` (
  `mov_id` INT NOT NULL AUTO_INCREMENT,
  `mov_title` VARCHAR(100) NOT NULL,
  `mov_rating` DECIMAL(3,1) NULL,
  `mov_mpaa` VARCHAR(5) NULL,
  `mov_duration` INT NOT NULL,
  `mov_release_year` INT NULL,
  PRIMARY KEY (`mov_id`));

CREATE TABLE `hmdb`.`actor` (
  `act_id` INT NOT NULL AUTO_INCREMENT,
  `act_first_name` VARCHAR(100) NULL,
  `act_last_name` VARCHAR(100) NULL,
  `act_dob` DATETIME NULL,
  PRIMARY KEY (`act_id`));

CREATE TABLE `hmdb`.`role` (
  `maj_id` INT NOT NULL AUTO_INCREMENT,
  `maj_mov_id` INT NOT NULL,
  `maj_act_id` INT NOT NULL,
  `maj_character` VARCHAR(200) NULL,
  PRIMARY KEY (`maj_id`));

-- Adding data to the hMDB schema

INSERT INTO movie (mov_title, mov_duration) VALUES ('Sinners', 120);

SELECT *
FROM movie;

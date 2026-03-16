DROP TABLE movie;
CREATE TABLE `hmdb`.`movie` (
  `mov_id` INT NOT NULL AUTO_INCREMENT,
  `mov_title` VARCHAR(100) NOT NULL,
  `mov_genre` VARCHAR(100) NULL,
  `mov_rating` DECIMAL(3,1) NULL,
  `mov_mpaa` VARCHAR(5) NULL,
  `mov_duration` INT NOT NULL,
  `mov_release_year` INT NULL,
  PRIMARY KEY (`mov_id`));
  

select * from movie;
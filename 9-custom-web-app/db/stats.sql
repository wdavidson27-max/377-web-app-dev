CREATE TABLE `statsdb`.`nbastats` (
  `stats_id` INT NOT NULL AUTO_INCREMENT,
  `stats_playername` VARCHAR(100) NOT NULL,
  `stats_team` VARCHAR(100) NULL, 
  `stats_points` DECIMAL(3,1) NOT NULL,
  `stats_rebounds` DECIMAL(3,1) NOT NULL,
  `stats_assists` DECIMAL(3,1) NOT NULL,
  `stats_blocks` DECIMAL(2,1) NOT NULL,
  `stats_steals` DECIMAL(2,1) NOT NULL, 
  `stats_fieldgoal` DECIMAL(3,1) NOT NULL,
  `stats_threepoint` DECIMAL(3,1) NOT NULL,
  `stats_freethrow` DECIMAL(3,1) NOT NULL
  PRIMARY KEY (`stats_id`));


INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow) VALUES ('Jayson Tatum' , 'Celtics' 28.9, 7.4, 5.3, 1.1, 1.3, 47.7, 36.5, 83.5)
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Stephen Curry','Warriors',29.4,5.2,6.3,0.4,1.1,48.1,41.5,91.2);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Kevin Durant','Suns',28.7,6.9,5.4,1.2,0.8,52.4,38.7,88.9);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Nikola Jokic','Nuggets',26.1,12.3,9.1,0.7,1.3,57.3,35.6,82.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Joel Embiid','76ers',30.2,11.5,4.8,1.7,1.0,54.2,33.8,85.6);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Luka Doncic','Mavericks',32.4,8.6,8.0,0.5,1.4,49.5,35.1,78.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Giannis Antetokounmpo','Bucks',31.0,11.8,5.7,1.3,1.1,60.2,27.4,70.1);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('LeBron James','Lakers',25.8,7.3,7.9,0.6,1.2,50.8,39.2,74.6);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Anthony Davis','Lakers',24.6,12.5,3.2,2.3,1.1,55.7,29.8,82.3);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Devin Booker','Suns',27.9,4.6,6.8,0.4,1.0,49.8,37.6,86.7);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Damian Lillard','Bucks',28.3,4.2,7.1,0.3,0.9,46.2,37.9,92.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Jimmy Butler','Heat',22.4,5.9,5.3,0.3,1.7,48.9,35.5,85.2);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Kawhi Leonard','Clippers',23.8,6.5,3.9,0.5,1.5,51.3,40.1,88.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Paul George','Clippers',22.9,5.4,4.3,0.4,1.6,47.6,38.8,87.5);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('James Harden','Clippers',21.7,6.1,8.4,0.5,1.2,44.9,36.3,86.9);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Jalen Brunson','Knicks',26.4,3.8,6.7,0.2,0.9,47.2,40.5,84.7);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Donovan Mitchell','Cavaliers',27.5,4.5,5.1,0.4,1.6,48.7,38.2,86.1);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Trae Young','Hawks',26.9,3.1,9.8,0.1,1.2,43.8,35.4,88.7);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Ja Morant','Grizzlies',25.1,5.6,7.4,0.3,1.0,46.3,32.9,75.8);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Shai Gilgeous-Alexander','Thunder',30.5,5.5,6.2,0.9,1.8,53.1,35.7,87.3);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Tyrese Haliburton','Pacers',21.8,3.7,10.4,0.4,1.5,49.4,39.9,87.9);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('DeMar DeRozan','Bulls',24.2,4.3,5.2,0.6,1.1,50.6,31.8,87.1);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Zion Williamson','Pelicans',23.5,6.7,4.1,0.5,1.0,59.3,29.5,71.6);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Brandon Ingram','Pelicans',22.8,5.3,5.7,0.6,0.8,48.4,36.9,85.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Karl-Anthony Towns','Timberwolves',21.9,8.4,3.3,0.7,0.8,52.7,39.4,84.2);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Anthony Edwards','Timberwolves',26.2,5.4,5.1,0.6,1.3,46.8,36.2,83.7);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Jaylen Brown','Celtics',24.8,6.2,3.4,0.4,1.1,49.9,35.8,78.9);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Bam Adebayo','Heat',20.6,9.9,4.0,0.8,1.2,54.5,12.5,80.6);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Kyrie Irving','Mavericks',25.7,5.0,5.6,0.5,1.3,49.1,39.6,90.2);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Pascal Siakam','Pacers',22.3,7.6,4.5,0.6,0.9,51.7,33.7,77.8);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('LaMelo Ball','Hornets',23.1,6.4,8.1,0.3,1.4,44.6,37.1,83.9);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Jaren Jackson Jr.','Grizzlies',22.0,6.8,1.9,2.5,1.0,48.8,35.0,79.5);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Darius Garland','Cavaliers',20.9,2.7,7.8,0.1,1.2,46.5,38.4,86.6);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('CJ McCollum','Pelicans',21.4,4.3,5.0,0.5,0.9,45.7,39.1,82.7);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('DeAaron Fox','Kings',26.7,4.2,6.0,0.4,1.5,48.2,36.0,78.3);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Domantas Sabonis','Kings',19.5,13.1,8.2,0.4,0.9,59.1,37.5,73.4);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Kristaps Porzingis','Celtics',20.8,7.9,2.1,1.8,0.7,51.2,37.8,85.0);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Tyrese Maxey','76ers',25.3,3.7,6.2,0.4,1.0,47.9,37.4,86.5);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Fred VanVleet','Rockets',19.2,3.8,8.0,0.3,1.4,42.7,38.5,87.8);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Alperen Sengun','Rockets',21.1,9.3,5.2,0.8,1.1,53.8,29.9,71.3);
INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)VALUES ('Jamal Murray','Nuggets',21.6,4.0,6.5,0.3,1.0,47.3,39.2,83.8);
SELECT *(stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)
FROM nbastats;(stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)


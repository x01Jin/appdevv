# appdevv

move this folder to htdocs first...
Initialize appdev.php in browser localhost/appdevv/appdev.php

current registered accounts:

admins---------------

username: admin
password: qwertyui

employees------------

username: alex
password: 12345678

username: darreil
password: asdfghjk

username: kalbo
password: kalborithms

username: edward
password: edward69420

Database structure---------------------------------------------

MariaDB [appdevdb]> SHOW CREATE TABLE users

| Table | Create Table                                                          

| users | CREATE TABLE `users` (

  `id` int(11) NOT NULL AUTO_INCREMENT,
  
  `role` enum('admin','employee') NOT NULL DEFAULT 'employee',
  
  `username` varchar(255) NOT NULL,
  
  `email` varchar(255) NOT NULL,
  
  `password` varchar(255) NOT NULL,
  
  PRIMARY KEY (`id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci |


1 row in set (0.001 sec)

MariaDB [appdevdb]>

----------------------------------------------------------------

MariaDB [appdevdb]> show create table tasks

| Table | Create Table

| tasks | CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci |

1 row in set (0.001 sec)

MariaDB [appdevdb]>

# appdevv

Database structure

MariaDB [appdevdb]> SHOW CREATE TABLE users
    -> ;


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
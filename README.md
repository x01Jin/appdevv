# appdevv

"I am the bone of my sword. Steel is my body and fire is my blood. I have created over a 
thousand blades. Unknown to Death, Nor known to Life. Have withstood pain to create many 
weapons. Yet, those hands will never hold anything. So as I pray, Unlimited Blade Works."

-Archer



"I am the bone of my sword. Steel is my body and fire is my blood. I have created over a
thousand blades. Unaware of loss, Nor aware of gain. Withstood pain to create weapons,
waiting for one’s arrival. I have no regrets. This is the only path. My whole life was
Unlimited Blade Works."

-Shiro Emiya

| Table | Create Table

| users | CREATE TABLE `users` (
  `role` enum('adviser','student','headoffice','placeholder') NOT NULL DEFAULT 'student',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `program` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci |

| Table | Create Table

| tasks | CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `status` enum('requested','ongoing','finished') NOT NULL DEFAULT 'requested',
  `completion_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_ibfk_1` (`student_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id_number`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci |

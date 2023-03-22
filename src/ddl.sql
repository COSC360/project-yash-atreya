-- Create user table
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`logged_in` tinyint(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
);


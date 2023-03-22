-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`logged_in` tinyint(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
);

-- Create posts table
CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`url` varchar(50) NULL,
`text` text NULL,
`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`upvotes` int(11) NOT NULL DEFAULT 0,
`comments` int(11) NOT NULL DEFAULT 0,
`username` varchar(50) NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);



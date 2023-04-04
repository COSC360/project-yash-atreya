-- TODO: Update the DDL according to the server phpMyAdmin
-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`logged_in` tinyint(1) NOT NULL DEFAULT 0,
`isAdmin` TINYINT(1) NOT NULL DEFAULT 0,
`isDisabled` TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- Create posts table
CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`title` varchar(50) NULL,
`url` varchar(50) NULL,
`text` text NULL,
`creation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
`upvotes` int(11) NOT NULL DEFAULT 0,
`comments` int(11) NOT NULL DEFAULT 0,
`username` varchar(50) NOT NULL,
`isComment` TINYINT(1) NOT NULL DEFAULT 0,
`in_reply_to_id` int(11) NULL DEFAULT 0,
`parent_id` int(11) NULL DEFAULT 0,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
)ENGINE=InnoDB;

-- Create upvotes table
CREATE TABLE IF NOT EXISTS `upvotes` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`username` varchar(50) NOT NULL,
`post_id` int(11) NOT NULL,
`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)
)ENGINE=InnoDB;


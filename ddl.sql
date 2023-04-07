-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(100) NOT NULL,
`email` varchar(200) NOT NULL,
`password` varchar(200) NOT NULL,
`creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`logged_in` tinyint(1) NOT NULL DEFAULT 0,
`isAdmin` TINYINT(1) NOT NULL DEFAULT 0,
`isDisabled` TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY (`id`)
)ENGINE=InnoDB;

-- Create posts table
CREATE TABLE IF NOT EXISTS `posts` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`title` varchar(1000) NULL,
`url` varchar(1000) NULL,
`text` longtext NULL,
`creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`upvotes` int(11) NOT NULL DEFAULT 0,
`comments` int(11) NOT NULL DEFAULT 0,
`username` varchar(100) NOT NULL,
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
`username` varchar(100) NOT NULL,
`post_id` int(11) NOT NULL,
`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)
)ENGINE=InnoDB;

-- Create userImages table
CREATE TABLE IF NOT EXISTS `userImages` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`username` varchar(100) NOT NULL,
`image` longblob NOT NULL,
`content_type` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
)ENGINE=InnoDB;



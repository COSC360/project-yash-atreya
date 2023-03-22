-- Create user table
CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` varchar(50) NOT NULL,
`email` varchar(50) NOT NULL,
`password` varchar(50) NOT NULL,
`creation_date` datetime NOT NULL,
PRIMARY KEY (`id`)
);


--
-- Table structure for table `products`
--
 
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `image` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- Contenu de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created`, `modified`) VALUES
(1, 'Basketball', 'A ball used in the NBA.', 49.99, '', '2016-08-02 12:04:03', '2016-08-06 05:59:18'),
(3, 'Gatorade', 'This is a very good drink for athletes.', 1.99, '', '2016-08-02 12:14:29', '2016-08-06 05:59:18'),
(4, 'Eye Glasses', 'It will make you read better.', 6, '', '2016-08-02 12:15:04', '2016-08-06 05:59:18'),
(5, 'Trash Can', 'It will help you maintain cleanliness.', 3.95, '', '2016-08-02 12:16:08', '2016-08-06 05:59:18'),
(6, 'Mouse', 'Very useful if you love your computer.', 11.35, '', '2016-08-02 12:17:58', '2016-08-06 05:59:18'),
(7, 'Earphone', 'You need this one if you love music.', 7, '', '2016-08-02 12:18:21', '2016-08-06 05:59:18'),
(8, 'Pillow', 'Sleeping well is important.', 8.99, '', '2016-08-02 12:18:56', '2016-08-06 05:59:18'),
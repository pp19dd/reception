SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stamp` datetime NOT NULL,
  `reception` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lat` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lng` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `zoom` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

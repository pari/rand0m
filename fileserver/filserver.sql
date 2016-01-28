-- phpMyAdmin SQL Dump
-- version 3.5.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 19, 2013 at 05:35 PM
-- Server version: 5.5.29-0ubuntu0.12.04.1
-- PHP Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fileserver`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_apps`
--

CREATE TABLE IF NOT EXISTS `tbl_apps` (
  `app_name` varchar(255) NOT NULL,
  `app_key` varchar(255) NOT NULL,
  `app_homedir` varchar(255) NOT NULL,
  PRIMARY KEY (`app_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_apps`
--

INSERT INTO `tbl_apps` (`app_name`, `app_key`, `app_homedir`) VALUES
('SOMEAPPNAME', 'SOMEKEY', 'SOMEFOLDERNAME'),
('whm_invoices', 'abcdefpoiuyuqwe123', 'plutokmVendorInvoices');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_files`
--

CREATE TABLE IF NOT EXISTS `tbl_files` (
  `fid` int(12) NOT NULL AUTO_INCREMENT,
  `file_app_name` varchar(255) NOT NULL,
  `file_diskName` varchar(255) NOT NULL,
  `file_diskFolder` varchar(255) NOT NULL,
  `accesstoken` varchar(32) NOT NULL,
  `fileSize` int(12) NOT NULL,
  `fhash` varchar(32) NOT NULL,
  `originalFileName` varchar(255) NOT NULL,
  `uploadedOn` datetime NOT NULL,
  `uploadedByAppusername` varchar(255) NOT NULL,
  `fileType` varchar(255) NOT NULL,
  `download_count` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`),
  UNIQUE KEY `uniqAcct_index` (`accesstoken`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_files`
--

INSERT INTO `tbl_files` (`fid`, `file_app_name`, `file_diskName`, `file_diskFolder`, `accesstoken`, `fileSize`, `fhash`, `originalFileName`, `uploadedOn`, `uploadedByAppusername`, `fileType`, `download_count`) VALUES
(1, 'whm_invoices', 'hg8fpm1M3dpcMlFyicaARK4HoXi5ioIF.php', '/fileserver/plutokmVendorInvoices/02_2013', 'c4ca4238a0b923820dcc509a6f75849b', 37815, '0d9a9662a498176148720c4893ef58f9', 'Snoopy.class.php', '2013-02-19 16:35:11', 'CommandPrompt', 'php', 2),
(2, 'whm_invoices', 'nblgxfpmTb6mcElFz9RIsid5Kb1GZ44D.php', '/fileserver/plutokmVendorInvoices/02_2013', 'c81e728d9d4c2f636f067f89cc14862c', 37815, '0d9a9662a498176148720c4893ef58f9', 'Snoopy.class.php', '2013-02-19 16:35:47', 'CommandPrompt', 'php', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

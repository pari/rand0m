-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 23, 2009 at 06:38 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `securitywaittime`
--

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE IF NOT EXISTS `airport` (
  `airport_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `airport_code` varchar(5) NOT NULL,
  `airport_name` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`airport_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=349 ;

--
-- Dumping data for table `airport`
--

INSERT INTO `airport` (`airport_id`, `airport_code`, `airport_name`) VALUES
(1, 'ABE', 'Lehigh Valley International Airport'),
(2, 'ABI', 'Municipal Airport'),
(3, 'ABQ', 'Albuquerque International Airport'),
(4, 'ABR', 'Municipal Airport'),
(5, 'ABY', 'Dougherty County Airport'),
(6, 'ACK', 'Memorial Airport'),
(7, 'ACT', 'Municipal Airport'),
(8, 'ACV', 'Arcata Airport'),
(9, 'ACY', 'Atlantic City International Airport'),
(10, 'ADQ', 'Kodiak Airport'),
(11, 'AEX', 'Alexandria Internation Airport'),
(12, 'AGS', 'Bush Field'),
(13, 'AKN', 'King Salmon Airport'),
(14, 'ALB', 'Albany International Airport'),
(15, 'ALO', 'Waterloo Regional Airport'),
(16, 'ALW', 'Walla Walla Regional Airport'),
(17, 'AMA', 'Rick Husband Airport'),
(18, 'ANC', 'Ted Stevens International Airport'),
(19, 'ANI', 'Aniak Airport'),
(20, 'APF', 'Naples Airport'),
(21, 'ASE', 'Aspen Airport'),
(22, 'ATL', 'Hartsfield-Jackson Airport'),
(23, 'ATW', 'Outagamie County Regional Airport'),
(24, 'AUS', 'Austin Airport'),
(25, 'AVL', 'Asheville Regional Airport'),
(26, 'AVP', 'Wilkes-Barre/Scranton International Airport'),
(27, 'AZO', 'Battle Creek International Airport'),
(28, 'BDL', 'Bradley International Airport'),
(29, 'BED', 'Hanscom Field'),
(30, 'BET', 'Bethel Airport'),
(31, 'BFF', 'Scotts Bluff County Airport'),
(32, 'BFI', 'Boeing Field International Airport'),
(33, 'BFL', 'Meadows Field Airport'),
(34, 'BGM', 'Greater Binghamton Airport'),
(35, 'BGR', 'Bangor International Airport'),
(36, 'BHB', 'Bar Harbor Airport'),
(37, 'BHM', 'Birmingham International Airport'),
(38, 'BIL', 'Billings Airport'),
(39, 'BIS', 'Bismarck Airport'),
(40, 'BJI', 'Bemidji Airport'),
(41, 'BLI', 'Bellingham Airport'),
(42, 'BLV', 'Belleville Airport'),
(43, 'BMI', 'Central Illinois Regional Airport'),
(44, 'BNA', 'Metropolitan Airport'),
(45, 'BOI', 'Gowen Field'),
(46, 'BOS', 'Logan International Airport'),
(47, 'BPT', 'Southeast Texas Regional Airport'),
(48, 'BQK', 'Brunswick Golden Isles Airport'),
(49, 'BRD', 'Crow Wing County Airport'),
(50, 'BRO', 'South Padre Island International Airport'),
(51, 'BRW', 'Wiley Post / Will Rogers Memorial Airport'),
(52, 'BTM', 'Butte Airport'),
(53, 'BTR', 'Ryan Airport'),
(54, 'BTV', 'Burlington International Airport'),
(55, 'BUF', 'Buffalo Niagara International Airport'),
(56, 'BUR', 'Bob Hope Airport'),
(57, 'BWI', 'Baltimore Washington International Airport'),
(58, 'BZN', 'Gallatin Field'),
(59, 'CAE', 'Metropolitan Airport'),
(60, 'CAK', 'Akron/Canton Regional Airport'),
(61, 'CDV', 'Mudhole Smith Airport'),
(62, 'CEC', 'McNamara Field'),
(63, 'CEZ', 'Montezuma County Airport'),
(64, 'CHA', 'Chattanooga Metropolitan Airport - Lovell Field'),
(65, 'CHO', 'Charlottesville Albemarle Airport'),
(66, 'CHS', 'Charleston International Airport'),
(67, 'CIC', 'Chico Airport'),
(68, 'CID', 'Eastern Iowa Airport'),
(69, 'CIU', 'Chippewa County International Airport'),
(70, 'CKB', 'Benedum Airport'),
(71, 'CLE', 'Hopkins International Airport'),
(72, 'CLL', 'Easterwood Field'),
(73, 'CLM', 'Fairchild International Airport'),
(74, 'CLT', 'Charlotte Douglas International Airport'),
(75, 'CMH', 'Port Columbus International Airport'),
(76, 'CMI', 'Willard University Airport'),
(77, 'CMX', 'Houghton County Airport'),
(78, 'COD', 'Yellowstone Regional Airport'),
(79, 'COS', 'Colorado Springs Airport'),
(80, 'COU', 'Columbia Regional Airport'),
(81, 'CPR', 'Natrona County International Airport'),
(82, 'CRP', 'Corpus Christi International Airport'),
(83, 'CRW', 'Yeager Airport'),
(84, 'CSG', 'Columbus Metropolitan Airport'),
(85, 'CVG', 'Cincinnati/Northern Kentucky Airport'),
(86, 'CWA', 'Central Wisconsin Airport'),
(87, 'CYS', 'Cheyenne Airport'),
(88, 'DAB', 'Daytona Beach International Airport'),
(89, 'DAL', 'Love Field'),
(90, 'DAY', 'Dayton International Airport'),
(91, 'DBQ', 'Dubuque Regional Airport'),
(92, 'DCA', 'Ronald Reagan Washington National Airport'),
(93, 'DEC', 'Decatur Airport'),
(94, 'DEN', 'Denver International Airport'),
(95, 'DFW', 'Dallas/Fort Worth International Airport'),
(96, 'DHN', 'Dothan Airport'),
(97, 'DLG', 'Municipal Airport'),
(98, 'DLH', 'Duluth International Airport'),
(99, 'DRO', 'Durango-La Plata Airport'),
(100, 'DRT', 'Del Rio International Airport'),
(101, 'DSM', 'Des Moines Airport'),
(102, 'DTW', 'Detroit Metro Airport'),
(103, 'DUT', 'Emergency Field'),
(104, 'EAT', 'Pangborn Memorial Airport'),
(105, 'EAU', 'Eau Claire Airport'),
(106, 'EGE', 'Eagle County Airport'),
(107, 'EKO', 'Elko Airport'),
(108, 'ELM', 'Corning Airport'),
(109, 'ELP', 'El Paso International Airport'),
(110, 'ENA', 'Kenai Airport'),
(111, 'ERI', 'Erie International Airport/Tom Ridge Field'),
(112, 'ESC', 'Delta County Airport'),
(113, 'EUG', 'Eugene Airport/Mahlon Sweet Field'),
(114, 'EVV', 'Dress Regional Airport'),
(115, 'EWB', 'New Bedford Airport'),
(116, 'EWN', 'New Bern/Craven County Regional Airport'),
(117, 'EWR', 'Newark Liberty International Airport'),
(118, 'EYW', 'Key West International Airport'),
(119, 'FAI', 'Fairbanks International Airport'),
(120, 'FAR', 'Hector International Airport'),
(121, 'FAT', 'Fresno Yosemite International Airport'),
(122, 'FAY', 'Municipal Airport'),
(123, 'FLG', 'Pulliam Field'),
(124, 'FLL', 'Fort Lauderdale/Hollywood International Airport'),
(125, 'FLO', 'Florence Regional Airport'),
(126, 'FMN', 'Municipal Airport'),
(127, 'FNL', 'Fort Collins/Loveland Airport'),
(128, 'FNT', 'Bishop Airport'),
(129, 'FSM', 'Municipal Airport'),
(130, 'FWA', 'Fort Wayne International Airport'),
(131, 'GCC', 'Campbell County Airport'),
(132, 'GCK', 'Municipal Airport'),
(133, 'GCN', 'National Park Airport'),
(134, 'GEG', 'Spokane International Airport'),
(135, 'GFK', 'Grand Forks International Airport'),
(136, 'GGG', 'East Texas Regional Airport'),
(137, 'GJT', 'Walker Field'),
(138, 'GNV', 'Gainesville Regional Airport'),
(139, 'GPT', 'Gulfport-Biloxi International Airport'),
(140, 'GRB', 'Austin-Straubel Field'),
(141, 'GRR', 'Gerald R. Ford International Airport'),
(142, 'GSO', 'Piedmont Triad International Airport'),
(143, 'GSP', 'Greenville-Spartanburg International Airport'),
(144, 'GTF', 'Great Falls International Airport'),
(145, 'GTR', 'Golden Triangle Regional Airport'),
(146, 'GUC', 'Gunnison-Crested Butte Regional Airport'),
(147, 'HDN', 'Yampa Valley Airport'),
(148, 'HIB', 'Chisholm Airport'),
(149, 'HLN', 'Helena Regional Airport'),
(150, 'HNL', 'Honolulu International Airport'),
(151, 'HOM', 'Homer Airport'),
(152, 'HOU', 'Hobby Airport'),
(153, 'HPN', 'Westchester County Airport'),
(154, 'HRL', 'Harlingen/Valley International Airport'),
(155, 'HSV', 'Huntsville International Airport'),
(156, 'HTS', 'Tri-State/Milton Airport'),
(157, 'HVN', 'Tweed New Haven Airport'),
(158, 'HYA', 'Barnstable Airport'),
(159, 'HYS', 'Municipal Airport'),
(160, 'IAD', 'Dulles International Airport'),
(161, 'IAH', 'George Bush Intercontinental Airport'),
(162, 'ICT', 'Wichita Mid-Continent Airport'),
(163, 'IDA', 'Idaho Falls Regional Airport'),
(164, 'IFP', 'Laughlin Bullhead International Airport'),
(165, 'ILM', 'Wilmington International Airport'),
(166, 'IND', 'Indianapolis International Airport'),
(167, 'INL', 'Falls International Airport'),
(168, 'IPL', 'Imperial County Airport'),
(169, 'IPT', 'Williamsport Regional Airport'),
(170, 'ISO', 'Kinston Regional Jetport'),
(171, 'ISP', 'Long Island Macarthur Airport'),
(172, 'ITH', 'Tompkins County Airport'),
(173, 'ITO', 'Hilo International Airport'),
(174, 'IYK', 'Kern County Airport'),
(175, 'JAC', 'Jackson Hole Airport'),
(176, 'JAN', 'Jackson-Evers Airport'),
(177, 'JAX', 'Jacksonville International Airport'),
(178, 'JFK', 'John F. Kennedy International Airport'),
(179, 'JLN', 'Joplin Airport'),
(180, 'JNU', 'Juneau International Airport'),
(181, 'JST', 'Cambria County Airport'),
(182, 'KOA', 'Kona International Airport at Keahole'),
(183, 'KTN', 'Ketchikan International Airport'),
(184, 'LAN', 'Lansing Capital City Airport'),
(185, 'LAR', 'General Brees Field'),
(186, 'LAS', 'McCarran International Airport'),
(187, 'LAW', 'Municipal Airport'),
(188, 'LAX', 'Los Angeles International Airport'),
(189, 'LBB', 'Lubbock Preston Smith International Airport'),
(190, 'LBE', 'Westmoreland County Airport'),
(191, 'LCH', 'Municipal Airport'),
(192, 'LEB', 'White River Airport'),
(193, 'LEX', 'Blue Grass Airport'),
(194, 'LFT', 'Lafayette Regional Airport'),
(195, 'LGA', 'LaGuardia Airport'),
(196, 'LGB', 'Long Beach Municipal Airport'),
(197, 'LIH', 'Lihue Airport'),
(198, 'LIT', 'Little Rock National Airport'),
(199, 'LMT', 'Kingsley Field'),
(200, 'LNK', 'Municipal Airport'),
(201, 'LNY', 'Lanai City Airport'),
(202, 'LRD', 'Laredo International Airport'),
(203, 'LSE', 'Municipal Airport'),
(204, 'LWS', 'Lewiston-Nez Perce County Regional Airport'),
(205, 'LYH', 'Lynchburg Regional Airport'),
(206, 'MAF', 'Odessa Regional Airport'),
(207, 'MBS', 'Saginaw/MBS International Airport'),
(208, 'MCI', 'Kansas City International Airport'),
(209, 'MCN', 'Lewis B Wilson Airport'),
(210, 'MCO', 'Orlando International Airport'),
(211, 'MCW', 'Mason City Airport'),
(212, 'MDT', 'Harrisburg International Airport'),
(213, 'MDW', 'Midway Airport'),
(214, 'MEI', 'Key Field'),
(215, 'MEM', 'Memphis International Airport'),
(216, 'MFE', 'McAllen International Airport'),
(217, 'MFR', 'Medford/Rogue Valley International Airport'),
(218, 'MGM', 'Montgomery Regional Airport - Dannelly Field'),
(219, 'MGW', 'Morgantown Airport'),
(220, 'MHK', 'Municipal Airport'),
(221, 'MHT', 'Manchester Boston Regional Airport'),
(222, 'mia', 'Miami International Airport'),
(223, 'MKE', 'General Mitchell Airport'),
(224, 'MKG', 'Muskegon Airport'),
(225, 'MKK', 'Molokai Airport'),
(226, 'MLB', 'Melbourne International Airport'),
(227, 'MLI', 'Quad City International Airport'),
(228, 'MLU', 'Municipal Airport'),
(229, 'MOB', 'Mobile Municipal Airport'),
(230, 'MOD', 'Municipal Airport'),
(231, 'MOT', 'Minot International Airport'),
(232, 'MRY', 'Monterey Peninsula Airport'),
(233, 'MSN', 'Dane County Regional Airport'),
(234, 'MSO', 'Missoula International Airport'),
(235, 'MSP', 'Minneapolis-St Paul International Airport'),
(236, 'MSY', 'New Orleans International Airport'),
(237, 'MTJ', 'Montrose County Airport'),
(238, 'MVY', 'Marthas Vineyard Airport'),
(239, 'MWA', 'Williamson County Airport'),
(240, 'OAJ', 'Albert J Ellis Airport'),
(241, 'OAK', 'Oakland International Airport'),
(242, 'OGG', 'Kahului Airport'),
(243, 'OKC', 'Will Rogers World Airport'),
(244, 'OMA', 'Eppley Airfield'),
(245, 'OME', 'Nome Airport'),
(246, 'ONT', 'Ontario International Airport'),
(247, 'ORD', 'OHare International Airport'),
(248, 'ORF', 'Norfolk International Airport'),
(249, 'ORH', 'Worcester Airport'),
(250, 'OTH', 'North Bend Airport'),
(251, 'OTZ', 'Kotzebue Airport'),
(252, 'OXR', 'Ventura Airport'),
(253, 'PAH', 'Barkley Regional Airport'),
(254, 'PBI', 'Palm Beach International Airport'),
(255, 'PDX', 'Portland International Airport'),
(256, 'PFN', 'Panama City - Bay County International Airport'),
(257, 'PGV', 'Pitt-Greenville Airport'),
(258, 'PHF', 'Newport News/Williamsburg International Airport'),
(259, 'PHL', 'Philadelphia International Airport'),
(260, 'PHX', 'Sky Harbor International Airport'),
(261, 'PIA', 'Greater Peoria Regional Airport'),
(262, 'PIB', 'Hattiesburg-Laurel Regional Airport'),
(263, 'PIE', 'St Petersburg International Airport'),
(264, 'PIH', 'Pocatello Airport'),
(265, 'PIR', 'Pierre Airport'),
(266, 'PIT', 'Pittsburgh International Airport'),
(267, 'PLN', 'Emmet County Airport'),
(268, 'PNS', 'Pensacola Regional Airport'),
(269, 'PQI', 'Municipal Airport'),
(270, 'PSC', 'Tri-Cities Airport'),
(271, 'PSG', 'Municipal Airport'),
(272, 'PSP', 'Municipal Airport'),
(273, 'PUW', 'Moscow Regional Airport'),
(274, 'PVC', 'Provincetown Airport'),
(275, 'PVD', 'Theodore Francis Green Airport'),
(276, 'PWM', 'Portland International Jetport'),
(277, 'RAP', 'Rapid City Regional Airport'),
(278, 'RDD', 'Redding Airport'),
(279, 'RDM', 'Roberts Field, Redmond Municipal Airport'),
(280, 'RDU', 'Raleigh/Durham Airport'),
(281, 'RFD', 'Greater Rockford Airport'),
(282, 'RHI', 'Oneida County Airport'),
(283, 'RIC', 'Richmond International Airport'),
(284, 'RIW', 'Riverton Airport'),
(285, 'RKD', 'Knox County Regional Airport'),
(286, 'RKS', 'Sweetwater County Airport'),
(287, 'RNO', 'Reno Airport'),
(288, 'ROA', 'Roanoke Regional Airport'),
(289, 'ROC', 'Greater Rochester International Airport'),
(290, 'RST', 'Rochester International Airport'),
(291, 'RSW', 'Southwest Florida International Airport'),
(292, 'SAN', 'Lindberg Field San Diego Airport'),
(293, 'SAT', 'San Antonio International Airport'),
(294, 'SAV', 'Savannah/Hilton Head Airport'),
(295, 'SBA', 'Municipal Airport'),
(296, 'SBN', 'South Bend Regional Airport'),
(297, 'SBP', 'San Luis Obispo County Regional Airport'),
(298, 'SBY', 'Wicomico Regional Airport'),
(299, 'SCC', 'Prudhoe Bay/Deadhorse Airport'),
(300, 'SCK', 'Stockton Airport'),
(301, 'SDF', 'Standiford Field'),
(302, 'SEA', 'Seattle/Tacoma International Airport'),
(303, 'SFB', 'Orlando Sanford International Airport'),
(304, 'SFO', 'San Francisco International Airport'),
(305, 'SGF', 'Springfield/Branson National Airport'),
(306, 'SGU', 'Municipal Airport'),
(307, 'SGY', 'Municipal Airport'),
(308, 'SHR', 'Sheridan Airport'),
(309, 'SHV', 'Regional Airport'),
(310, 'SIT', 'Sitka Airport'),
(311, 'SJC', 'Norman Mineta San Jose International Airport'),
(312, 'SLC', 'Salt Lake City International Airport'),
(313, 'SMF', 'Sacramento International Airport'),
(314, 'SMX', 'Santa Maria Airport'),
(315, 'SNA', 'John Wayne Airport'),
(316, 'SPI', 'Abraham Lincoln Capital Airport'),
(317, 'SRQ', 'Sarasota Bradenton International Airport'),
(318, 'STC', 'Municipal Airport'),
(319, 'STL', 'Lambert-St Louis International Airport'),
(320, 'STS', 'Sonoma County Airport'),
(321, 'SUN', 'Sun Valley Airport'),
(322, 'SUX', 'Sioux Gateway Airport'),
(323, 'SWF', 'Stewart Airport'),
(324, 'SYR', 'Hancock International Airport'),
(325, 'TEX', 'Telluride Regional Airport'),
(326, 'TLH', 'Tallahassee Regional Airport'),
(327, 'TOL', 'Express Airport'),
(328, 'TPA', 'Tampa International Airport'),
(329, 'TRI', 'Tri Cities Regional Airport'),
(330, 'TTN', 'Trenton-Mercer Airport'),
(331, 'TUL', 'Tulsa International Airport'),
(332, 'TUP', 'Tupelo Regional Airport'),
(333, 'TUS', 'Tucson International Airport'),
(334, 'TVC', 'Traverse City Airport'),
(335, 'TWF', 'City County Airport'),
(336, 'TXK', 'Municipal Airport'),
(337, 'TYR', 'Tyler Pounds Regional Airport'),
(338, 'TYS', 'McGhee Tyson Airport'),
(339, 'UNK', 'Unalakleet Airport'),
(340, 'VDZ', 'Municipal Airport'),
(341, 'VGT', 'North Air Terminal'),
(342, 'VLD', 'Regional Airport'),
(343, 'VPS', 'Ft. Walton Beach Airport'),
(344, 'WRG', 'Wrangell Seaplane Base'),
(345, 'XNA', 'Northwest Arkansas Rgn Airport'),
(346, 'YAK', 'Yakutat Airport'),
(347, 'YKM', 'Yakima Air Terminal'),
(348, 'YNG', 'Youngstown-Warren Regional Airport');

-- --------------------------------------------------------

--
-- Table structure for table `airportgates`
--

CREATE TABLE IF NOT EXISTS `airportgates` (
  `gate_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gate` varchar(200) NOT NULL,
  PRIMARY KEY (`gate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `airportgates`
--


-- --------------------------------------------------------

--
-- Table structure for table `airportsecuritywaittimes`
--

CREATE TABLE IF NOT EXISTS `airportsecuritywaittimes` (
  `airport_id` int(11) NOT NULL,
  `gate_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `time_id` int(11) NOT NULL,
  `avg_wait_time` varchar(50) NOT NULL,
  `max_wait_time` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `airportsecuritywaittimes`
--


-- --------------------------------------------------------

--
-- Table structure for table `dayofweek`
--

CREATE TABLE IF NOT EXISTS `dayofweek` (
  `day_id` int(11) NOT NULL AUTO_INCREMENT,
  `day` varchar(50) NOT NULL,
  PRIMARY KEY (`day_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `dayofweek`
--

INSERT INTO `dayofweek` (`day_id`, `day`) VALUES
(1, 'Sunday'),
(2, 'Monday'),
(3, 'Tuesday'),
(4, 'Wednesday'),
(5, 'Thursday'),
(6, 'Friday'),
(7, 'Saturday');

-- --------------------------------------------------------

--
-- Table structure for table `timeofday`
--

CREATE TABLE IF NOT EXISTS `timeofday` (
  `time_id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(200) NOT NULL,
  `to` varchar(200) NOT NULL,
  PRIMARY KEY (`time_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `timeofday`
--

INSERT INTO `timeofday` (`time_id`, `from`, `to`) VALUES
(1, '00:00', '01:00'),
(2, '01:00', '02:00'),
(3, '02:00', '03:00'),
(4, '03:00', '04:00'),
(5, '04:00', '05:00'),
(6, '05:00', '06:00'),
(7, '06:00', '07:00'),
(8, '07:00', '08:00'),
(9, '08:00', '09:00'),
(10, '09:00', '10:00'),
(11, '10:00', '11:00'),
(12, '11:00', '12:00'),
(13, '12:00', '13:00'),
(14, '13:00', '14:00'),
(15, '14:00', '15:00'),
(16, '15:00', '16:00'),
(17, '16:00', '17:00'),
(18, '17:00', '18:00'),
(19, '18:00', '19:00'),
(20, '19:00', '20:00'),
(21, '20:00', '21:00'),
(22, '21:00', '22:00'),
(23, '22:00', '23:00'),
(24, '23:00', '24:00');

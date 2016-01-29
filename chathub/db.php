<?php

$tmp_masterdb = MASTERDB ;
$sql_str = <<<EOD

create database `{$tmp_masterdb}` 
[#]

SET FOREIGN_KEY_CHECKS = 0
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_BookMarks` (
  `bkm_id` int(11) NOT NULL AUTO_INCREMENT,
  `bkm_empl_id` int(11) NOT NULL COMMENT 'BookMarked By Employee Id',
  `bkm_msgId` bigint(20) NOT NULL,
  `bkm_dmsgid` int(11) NOT NULL,
  `bkm_roomId` int(5) NOT NULL,
  `bkm_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`bkm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1
[#]


CREATE TABLE `{$tmp_masterdb}`.`tbl_chatFiles` (
  `fileId` bigint(20) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(500) DEFAULT NULL,
  `fileRandomName` varchar(100) DEFAULT NULL,
  `fileExt` varchar(100) DEFAULT NULL,
  `fileSize` varchar(250) DEFAULT NULL,
  `fileCode` varchar(100) DEFAULT NULL,
  `fileType` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`fileId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_ChatRooms` (
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `saidBy_username` varchar(32) NOT NULL,
  `saidBy_empl_id` int(5) NOT NULL,
  `chatRoom` int(5) NOT NULL DEFAULT '1',
  `message_base64` mediumtext NOT NULL,
  `message_plain_mysqlescaped` mediumtext NOT NULL,
  `msgtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `msgType` char(1) NOT NULL DEFAULT 'M',
  `fileId` bigint(20) NOT NULL,
  `bookmark` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`msgid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_CurrentSessions` (
  `uid` bigint(20) NOT NULL,
  `rid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_DirectMessages` (
  `dmsgid` bigint(20) NOT NULL AUTO_INCREMENT,
  `from_uid` bigint(20) NOT NULL,
  `to_uid` bigint(20) NOT NULL,
  `msg_base64` text NOT NULL,
  `msg_plain` text NOT NULL,
  `msgtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `msgType` char(1) NOT NULL DEFAULT 'M',
  `fileId` bigint(20) NOT NULL DEFAULT '0',
  `bookmark` int(1) NOT NULL DEFAULT '0',
  `msgStatus` char(1) NOT NULL DEFAULT 'N' COMMENT 'N - New , Old - Old or Read',
  PRIMARY KEY (`dmsgid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 
[#]


CREATE TABLE `{$tmp_masterdb}`.`tbl_Invitations` (
  `invi_Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invi_firstName` varchar(200) DEFAULT NULL,
  `invi_lastName` varchar(200) DEFAULT NULL,
  `invi_msg` text,
  `invi_email` varchar(250) DEFAULT NULL,
  `invi_sent_by` varchar(200) DEFAULT NULL,
  `invi_room` bigint(20) DEFAULT '1',
  `invi_status` char(1) DEFAULT '0',
  `invi_key` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`invi_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_privilages` (
  `pid` bigint(20) NOT NULL AUTO_INCREMENT,
  `ptitle` text,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 
[#]

CREATE TABLE `{$tmp_masterdb}`.`tbl_RoomPrivilages` (
  `rid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 
[#]


CREATE TABLE `{$tmp_masterdb}`.`tbl_userPrivilages` (
  `pid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 
[#]


CREATE TABLE `{$tmp_masterdb}`.`tblApp_Variables` (
  `var_name` varchar(250) NOT NULL DEFAULT '0',
  `var_value` varchar(500) NOT NULL DEFAULT '0',
  UNIQUE KEY `var_name` (`var_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 
[#]
INSERT INTO `{$tmp_masterdb}`.`tblApp_Variables` VALUES ('comp_Name', 'Your Company Name'), ('comp_Logo', '1.gif'), ('disk_Space', '16762970')
[#]

CREATE TABLE `{$tmp_masterdb}`.`tblAppUsers` (
  `empl_id` int(5) NOT NULL AUTO_INCREMENT,
  `emplUsername` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `emplPassword` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `emplEmail_id` varchar(240) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emplFullName` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emplMobileNo` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emplDesignation` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emplLastLoginAt` datetime NOT NULL,
  `emplLastPingAt` datetime NOT NULL,
  `TimeZone` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `userImage` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statusMsg` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`empl_id`),
  UNIQUE KEY `emplUsername` (`emplUsername`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci 
[#]
INSERT INTO `{$tmp_masterdb}`.`tblAppUsers` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', null, null, null, null, '0000-00-00 00:00:00', '0000-00-00 00:00:00', null, null, null)
[#]

CREATE TABLE `{$tmp_masterdb}`.`tblRooms` (
  `roomId` int(5) NOT NULL AUTO_INCREMENT,
  `roomName` varchar(250) NOT NULL,
  `roomDesc` text,
  `roomCreatedBy` bigint(20) NOT NULL,
  `roomActive` char(1) NOT NULL DEFAULT 'Y' COMMENT 'Y/N - is this room still being actively used or depricated ',
  PRIMARY KEY (`roomId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 
[#]

EOD;


?>

CREATE TABLE IF NOT EXISTS `journalentries` (
  `jid` int(9) NOT NULL auto_increment,
  `task_day` date NOT NULL,
  `task_user` varchar(32) collate utf8_unicode_ci default NULL,
  `task_mins` int(3) NOT NULL default '10',
  `task_desc` text collate utf8_unicode_ci,
  `task_enteredon` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `wasEmailed` char(1) collate utf8_unicode_ci NOT NULL default 'N',
  PRIMARY KEY  (`jid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=983 ;


CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(32) collate utf8_unicode_ci NOT NULL,
  `user_pwd` varchar(32) collate utf8_unicode_ci NOT NULL,
  `user_email` varchar(250) collate utf8_unicode_ci NOT NULL,
  `user_reportsTo` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `users` (`username`, `user_pwd`, `user_email`, `user_reportsTo`) VALUES ('chandu', 'nppc', 'paripurnachand@gmail.com', 'sanjay');

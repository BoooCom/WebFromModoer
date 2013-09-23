DROP TABLE IF EXISTS modoer_ask_category;
CREATE TABLE modoer_ask_category (
  catid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  pid smallint(5) NOT NULL DEFAULT '0',
  name varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  total mediumint(8) unsigned NOT NULL DEFAULT '0',
  use_area tinyint(1) NOT NULL default '0',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_asks;
CREATE TABLE modoer_asks (
  askid int(10) unsigned NOT NULL auto_increment,
  catid mediumint(8) unsigned NOT NULL default '0',
  city_id mediumint(8) unsigned NOT NULL default '0',
  sid mediumint(8) NOT NULL default '0',
  att tinyint(1) NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  author varchar(20) NOT NULL default '',
  subject char(80) NOT NULL default '',
  keywords varchar(100) NOT NULL default '',
  digest tinyint(1) NOT NULL default '0',
  reward smallint(6) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  expiredtime int(10) unsigned NOT NULL default '0',
  solvetime int(10) unsigned NOT NULL default '0',
  bestanswer int(10) unsigned NOT NULL default '0',
  success tinyint(1) NOT NULL default '0',
  status tinyint(1) NOT NULL default '0',
  listorder smallint(6) NOT NULL default '0',
  comments mediumint(8) unsigned NOT NULL default '0',
  views int(10) unsigned NOT NULL default '0',
  replies mediumint(8) unsigned NOT NULL default '0',
  content mediumtext NOT NULL,
  extra mediumtext NOT NULL,
  PRIMARY KEY  (askid),
  KEY disorder (catid,status,dateline),
  KEY digest (digest),
  KEY expiredtime (expiredtime),
  KEY reward (reward),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_ask_answer;
CREATE TABLE modoer_ask_answer (
  answerid int(10) unsigned NOT NULL auto_increment,
  askid mediumint(8) unsigned NOT NULL default '0',
  ifanswer tinyint(1) NOT NULL default '0',
  bestanswer int(10) unsigned NOT NULL default '0',
  catid smallint(6) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username char(32) NOT NULL default '',
  goodrate smallint(6) unsigned NOT NULL default '0',
  badrate smallint(6) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  feel char(200) NOT NULL default '',
  brief char(200) NOT NULL default '',
  ip char(15) NOT NULL default '',
  content mediumtext NOT NULL,
  status tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (answerid),
  KEY askid (askid),
  KEY uid (uid),
  KEY dateline (askid,dateline)
) TYPE=MyISAM;
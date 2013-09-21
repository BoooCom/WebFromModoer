DROP TABLE IF EXISTS modoer_party;
CREATE TABLE modoer_party (
  partyid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(60) NOT NULL DEFAULT '',
  catid smallint(5) unsigned NOT NULL DEFAULT '0',
  city_id smallint(5) unsigned NOT NULL DEFAULT '0',
  aid smallint(5) NOT NULL DEFAULT '0',
  sid mediumint(8) NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  flag tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  finer tinyint(1) unsigned NOT NULL DEFAULT '0',
  joinendtime int(10) unsigned NOT NULL DEFAULT '0',
  begintime int(10) unsigned NOT NULL DEFAULT '0',
  endtime int(10) unsigned NOT NULL DEFAULT '0',
  plannum varchar(20) NOT NULL,
  sex tinyint(1) unsigned NOT NULL DEFAULT '0',
  age varchar(20) NOT NULL DEFAULT '',
  price int(10) unsigned NOT NULL DEFAULT '0',
  applyprice_type enum('none','rmb','point1','point2','point3','point4','point5','point6') NOT NULL DEFAULT 'none',
  applyprice decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  thumb varchar(255) NOT NULL DEFAULT '',
  linkman varchar(20) NOT NULL DEFAULT '',
  contact varchar(60) NOT NULL DEFAULT '',
  address varchar(255) NOT NULL DEFAULT '0',
  applynum smallint(5) unsigned NOT NULL DEFAULT '0',
  num smallint(5) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  pageview mediumint(8) unsigned NOT NULL DEFAULT '0',
  des text NOT NULL,
  PRIMARY KEY (partyid),
  KEY uid (uid),
  KEY finer (finer),
  KEY `list` (catid,flag,begintime)
) TYPE = MYISAM;

DROP TABLE IF EXISTS modoer_party_apply;
CREATE TABLE modoer_party_apply (
  applyid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  partyid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  linkman varchar(20) NOT NULL DEFAULT '',
  contact varchar(255) NOT NULL DEFAULT '',
  sex tinyint(1) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  isjoin tinyint(1) unsigned NOT NULL DEFAULT '0',
  `comment` varchar(255) NOT NULL DEFAULT '',
  pointtype varchar(15) NOT NULL DEFAULT '',
  price decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  refunded tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (applyid),
  KEY partyid (partyid,uid)
) TYPE = MYISAM;

DROP TABLE IF EXISTS modoer_party_category;
CREATE TABLE modoer_party_category (
  catid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(30) NOT NULL DEFAULT '',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  num int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) TYPE = MYISAM;

INSERT INTO modoer_party_category (catid, name, listorder, num) VALUES(1, '默认分类', 0, 0);

DROP TABLE IF EXISTS modoer_party_comment;
CREATE TABLE modoer_party_comment (
  commentid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  partyid mediumint(8) unsigned NOT NULL DEFAULT '0',
  parentid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  message text NOT NULL,
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  reply text NOT NULL,
  PRIMARY KEY (commentid),
  KEY partyid (partyid,parentid)
) TYPE = MYISAM;

DROP TABLE IF EXISTS modoer_party_content;
CREATE TABLE modoer_party_content (
  partyid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  content mediumtext NOT NULL,
  PRIMARY KEY (partyid)
) TYPE = MYISAM;

DROP TABLE IF EXISTS modoer_party_picture;
CREATE TABLE modoer_party_picture (
  picid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  partyid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  title varchar(60) NOT NULL DEFAULT '',
  pic varchar(255) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (picid),
  KEY partyid (partyid,status)
) TYPE = MYISAM;
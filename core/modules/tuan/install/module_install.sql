DROP TABLE IF EXISTS modoer_tuan;
CREATE TABLE modoer_tuan (
  tid mediumint(8) unsigned NOT NULL auto_increment,
  city_id smallint(5) unsigned NOT NULL default '0',
  catid smallint(5) NOT NULL default '1',
  sid mediumint(8) NOT NULL default '0',
  mode enum('normal','average','wholesale','forestall') NOT NULL default 'normal',
  listorder smallint(5) unsigned NOT NULL default '0',
  subject varchar(255) NOT NULL default '',
  picture varchar(255) NOT NULL default '',
  thumb varchar(255) NOT NULL default '',
  price decimal(9,2) NOT NULL default '0.00',
  real_price decimal(9,2) NOT NULL default '0.00',
  market_price decimal(9,2) NOT NULL default '0.00',
  goods_stock mediumint(8) unsigned NOT NULL default '0',
  sendtype enum('coupon','coupon_ex','express') NOT NULL default 'coupon',
  goods_total mediumint(8) unsigned NOT NULL default '0',
  goods_sell mediumint(8) unsigned NOT NULL default '0',
  goods_min smallint(5) unsigned NOT NULL default '0',
  people_buylimit smallint(5) unsigned NOT NULL default '0',
  virtual_buy_num MEDIUMINT( 10 ) UNSIGNED NOT NULL DEFAULT  '0',
  peoples_sell mediumint(8) unsigned NOT NULL default '0',
  peoples_min mediumint(8) unsigned NOT NULL default '0',
  starttime int(10) NOT NULL default '0',
  endtime int(10) NOT NULL default '0',
  succeedtime int(10) NOT NULL default '0',
  expiretime int(10) unsigned NOT NULL default '0',
  intro text NOT NULL,
  content text NOT NULL,
  notice text NOT NULL,
  review text NOT NULL,
  ask text NOT NULL,
  status enum('new','succeeded','lose','closed') NOT NULL default 'new',
  repeat_buy tinyint(1) NOT NULL DEFAULT '0',
  checked tinyint(1) unsigned NOT NULL default '1',
  use_prices tinyint(1) unsigned NOT NULL default '0',
  prices varchar(255) NOT NULL default '',
  total_price decimal(9,2) NOT NULL default '0.00',
  use_ex_point tinyint(1) unsigned NOT NULL default '0',
  use_ex_price decimal(9,2) unsigned NOT NULL default '0.00',
  coupon_print text NOT NULL,
  sms text NOT NULL,
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(20) NOT NULL default '',
  apply_refund MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT  '0',
  PRIMARY KEY  (tid),
  KEY list (status,checked,listorder),
  KEY plan (endtime,status,city_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_category;
CREATE TABLE modoer_tuan_category (
  catid smallint(8) unsigned NOT NULL auto_increment,
  name varchar(40) NOT NULL default '',
  num mediumint(9) NOT NULL default '0',
  listorder smallint(5) NOT NULL default '0',
  PRIMARY KEY  (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_coupon;
CREATE TABLE modoer_tuan_coupon (
  id mediumint(8) unsigned NOT NULL auto_increment,
  oid mediumint(8) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(20) NOT NULL default '',
  serial char(15) NOT NULL default '',
  passward varchar(10) NOT NULL default '',
  num INT(10) UNSIGNED NOT NULL DEFAULT '1',
  expiretime int(10) unsigned NOT NULL default '0',
  usetime int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  status enum('new','used','expired','invalid','lock') NOT NULL default 'new',
  sms_sendtime int(10) unsigned NOT NULL default '0',
  sms_sendtotal SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  sms_error smallint(5) UNSIGNED NOT NULL DEFAULT  '0',
  op_uid mediumint(8) unsigned NOT NULL default '0',
  op_username varchar(20) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY serial (serial),
  KEY dateline (dateline),
  KEY uid (uid,dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_discuss;
CREATE TABLE modoer_tuan_discuss (
  id mediumint(8) unsigned NOT NULL auto_increment,
  tid mediumint(8) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(25) NOT NULL default '',
  sort tinyint(1) unsigned NOT NULL default '1',
  dateline int(10) unsigned NOT NULL default '0',
  content text NOT NULL,
  reply text NOT NULL,
  status tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY tid (tid,sort,status)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_order;
CREATE TABLE modoer_tuan_order (
  oid int(10) unsigned NOT NULL auto_increment,
  tid int(10) unsigned NOT NULL default '0',
  uid int(10) unsigned NOT NULL default '0',
  username varchar(25) NOT NULL default '',
  num mediumint(8) unsigned NOT NULL default '0',
  price decimal(9,2) NOT NULL default '0.00',
  pay_price decimal(9,2) NOT NULL default '0.00',
  balance_price decimal(9,2) NOT NULL default '0.00',
  ex_price decimal(9,2) unsigned NOT NULL default '0.00',
  ex_point int(10) unsigned NOT NULL default '0',
  ex_pointtype char(6) NOT NULL default'',
  dateline int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  exchangetime int(10) unsigned NOT NULL default '0',
  status enum('new','payed','canceled','overdue','refunded','apply_refund') NOT NULL DEFAULT 'new',
  good_status enum('wait','stockout','sent','canceled') NOT NULL default 'wait',
  mobile varchar(15) NOT NULL default '',
  contact text NOT NULL,
  express varchar(255) NOT NULL default '',
  is_sent tinyint(1) unsigned NOT NULL default '0',
  apply_refund_des VARCHAR( 255 ) NOT NULL DEFAULT  '',
  return_balance_price decimal(9,2) NOT NULL default '0.00',
  return_balance_time int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (oid),
  KEY tid (tid),
  KEY status (status,oid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_undertake;
CREATE TABLE modoer_tuan_undertake (
  id mediumint(8) unsigned NOT NULL auto_increment,
  twid mediumint(8) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(30) NOT NULL default '',
  linkman varchar(60) NOT NULL default '',
  price varchar(30) NOT NULL default '',
  goods_num varchar(30) NOT NULL default '',
  contact varchar(60) NOT NULL default '',
  content text NOT NULL,
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY twid (twid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tuan_wish;
CREATE TABLE modoer_tuan_wish (
  twid mediumint(8) unsigned NOT NULL auto_increment,
  city_id mediumint(8) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  status tinyint(1) unsigned NOT NULL default '1',
  dateline int(10) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(30) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  price decimal(9,2) unsigned NOT NULL default '0.00',
  thumb varchar(255) NOT NULL default '',
  picture varchar(255) NOT NULL default '',
  content text NOT NULL,
  interest mediumint(8) NOT NULL default '0',
  interest_users text NOT NULL,
  undertakers smallint(5) unsigned NOT NULL default '0',
  undertaker varchar(60) NOT NULL default '',
  PRIMARY KEY  (twid),
  KEY list (status,twid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subscibe_email;
CREATE TABLE modoer_subscibe_email (
  id mediumint(8) unsigned NOT NULL auto_increment,
  city_id smallint(5) unsigned NOT NULL default '0',
  sort varchar(20) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  hash varchar(8) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY sort (sort),
  KEY hash (hash)
) TYPE=MyISAM;
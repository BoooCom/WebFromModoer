DROP TABLE IF EXISTS modoer_activity;
CREATE TABLE modoer_activity (
  aid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  username varchar(16) NOT NULL DEFAULT '',
  reviews smallint(5) unsigned NOT NULL DEFAULT '0',
  subjects smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (aid),
  KEY reviews (reviews),
  KEY dateline (dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_admin;
CREATE TABLE modoer_admin (
  adminid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  adminname varchar(24) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  admintype tinyint(3) NOT NULL default '0',
  is_founder char(1) NOT NULL default 'N',
  logintime int(10) NOT NULL default '0',
  loginip varchar(20) NOT NULL default '',
  logincount int(10) unsigned NOT NULL default '0',
  mycitys text NOT NULL,
  mymodules text NOT NULL,
  closed tinyint(1) NOT NULL default '0',
  validtime int(10) NOT NULL default '0',
  PRIMARY KEY  (adminid),
  UNIQUE KEY adminname (adminname)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_admin_session;
CREATE TABLE modoer_admin_session (
  adminid mediumint(8) NOT NULL,
  ipaddress varchar(16) NOT NULL DEFAULT '',
  adminhash varchar(255) NOT NULL DEFAULT '',
  lasttime int(10) NOT NULL DEFAULT '0',
  errno tinyint(1) NOT NULL DEFAULT '0'
) TYPE=MyISAM;


DROP TABLE IF EXISTS modoer_adv_list;
CREATE TABLE modoer_adv_list (
  adid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  apid smallint(5) unsigned NOT NULL default '0',
  city_id mediumint(8) unsigned NOT NULL default '0',
  adname varchar(60) NOT NULL default '',
  sort enum('word','flash','code','img') NOT NULL,
  begintime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  config text NOT NULL,
  code text NOT NULL,
  attr char(10) NOT NULL default '',
  ader varchar(255) NOT NULL default '',
  adtel varchar(255) NOT NULL default '',
  ademail varchar(255) NOT NULL default '',
  enabled char(1) NOT NULL default 'Y',
  listorder smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (adid)
) TYPE=MyISAM;

INSERT INTO modoer_adv_list VALUES ('1','1','0','Modoer2.0发布','img','1289260800','0','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:38:\"/uploads/adv/2010-12/13_1292772219.jpg\";s:9:\"img_width\";s:3:\"708\";s:10:\"img_height\";s:2:\"75\";s:8:\"img_href\";s:22:\"http://www.modoer.com/\";}','<a href=\"http://www.modoer.com/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/2010-12/13_1292772219.jpg\" width=\"708\" height=\"75\" /></a>','','','','','Y','0');
INSERT INTO modoer_adv_list VALUES ('2','2','0','Modoer2.0发布','img','1289260800','0','a:5:{s:9:\"img_title\";s:7:\"Modoer2\";s:7:\"img_src\";s:38:\"/uploads/adv/2010-12/29_1292772237.jpg\";s:9:\"img_width\";s:3:\"958\";s:10:\"img_height\";s:2:\"90\";s:8:\"img_href\";s:22:\"http://www.modoer.com/\";}','<a href=\"http://www.modoer.com/\" alt=\"Modoer2\" target=\"_blank\"><img src=\"/uploads/adv/2010-12/29_1292772237.jpg\" width=\"958\" height=\"90\" /></a>','','','','','Y','0');

DROP TABLE IF EXISTS modoer_adv_place;
CREATE TABLE modoer_adv_place (
  apid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  templateid smallint(5) unsigned NOT NULL default '0',
  name varchar(60) NOT NULL default '',
  des varchar(255) NOT NULL default '',
  template text NOT NULL,
  enabled char(1) NOT NULL default 'Y',
  PRIMARY KEY  (apid),
  UNIQUE KEY name (name)
) TYPE=MyISAM;

INSERT INTO modoer_adv_place VALUES ('1','0','首页_中部广告','首页推荐主题下方广告位','<div class=\"ix_foo\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('2','0','新闻首页_广告','新闻模块的首页中午长条图片广告','<div class=\"art_ix\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>$ad[code]</div>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('3','0','主题内容页_点评间广告','在主题内容页坐下点评列表第二行加入的广告','<div style=\"padding-bottom:10px;border-bottom:1px dashed #ddd;margin-bottom:10px;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');
INSERT INTO modoer_adv_place VALUES ('4','0','主题列表页_列表间广告','在主题模块的列表页面，列表第二层加入一个广告','<div style=\"padding-bottom:5px;border-bottom:1px dashed #ddd;margin:5px 0;\">\r\n{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div style=\"text-align:center;\">$ad[code]</div>\r\n{getempty(ad)}\r\n<center>广告位招租</center>\r\n{/get}\r\n</div>','Y');

DROP TABLE IF EXISTS modoer_album;
CREATE TABLE modoer_album (
  albumid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL default '0',
  sid mediumint(8) unsigned NOT NULL default '0',
  name varchar(200) NOT NULL,
  thumb varchar(255) NOT NULL,
  des text NOT NULL,
  lastupdate int(10) unsigned NOT NULL default '0',
  num mediumint(5) unsigned NOT NULL default '0',
  pageview mediumint(8) unsigned NOT NULL default '0',
  comments mediumint(8) unsigned not null default '0',
  PRIMARY KEY  (albumid),
  KEY sid (sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_announcements;
CREATE TABLE modoer_announcements (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) NOT NULL default '0',
  title varchar(200) NOT NULL default '',
  orders smallint(5) NOT NULL default '0',
  content mediumtext NOT NULL,
  author varchar(50) NOT NULL default '',
  pageview int(10) NOT NULL default '0',
  dateline int(10) NOT NULL default '0',
  available tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_area;
CREATE TABLE modoer_area (
  aid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) unsigned NOT NULL DEFAULT '0',
  attid mediumint(8) unsigned NOT NULL default '0',
  domain varchar(20) NOT NULL default '',
  initial char(1) NOT NULL default '',
  name varchar(16) NOT NULL DEFAULT '',
  mappoint varchar(50) NOT NULL DEFAULT '',
  level tinyint(1) unsigned NOT NULL DEFAULT '0',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  templateid smallint(5) unsigned NOT NULL default '0',
  enabled tinyint(1) unsigned NOT NULL default '1',
  config text NOT NULL,
  PRIMARY KEY (aid),
  KEY pid (pid),
  KEY level (level)
) TYPE=MyISAM;

INSERT INTO modoer_area VALUES ('1','0','4','nb','N','宁波','121.565151,29.877309','1','0','0','1','');
INSERT INTO modoer_area VALUES ('2','1','5','','','江东区','','2','0','0','1','');
INSERT INTO modoer_area VALUES ('5','2','8','','','天伦广场','','3','0','0','1','');
INSERT INTO modoer_area VALUES ('3','1','6','','','海曙区','','2','0','0','1','');
INSERT INTO modoer_area VALUES ('6','3','9','','','东门口','','3','0','0','1','');
INSERT INTO modoer_area VALUES ('4','1','7','','','江北区','','2','0','0','1','');
INSERT INTO modoer_area VALUES ('7','4','10','','','老外滩','','3','0','0','1','');


DROP TABLE IF EXISTS modoer_articles;
CREATE TABLE modoer_articles (
  articleid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL default '0',
  catid smallint(5) unsigned NOT NULL default '0',
  sid varchar(255) NOT NULL default '',
  dateline int(10) NOT NULL default '0',
  att tinyint(1) NOT NULL default '0',
  listorder smallint(5) unsigned NOT NULL default '0',
  havepic tinyint(1) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  author varchar(20) NOT NULL default '',
  subject varchar(60) NOT NULL default '',
  keywords varchar(100) NOT NULL default '',
  pageview mediumint(8) unsigned NOT NULL default '0',
  grade tinyint(1) unsigned NOT NULL default '0',
  digg mediumint(8) NOT NULL default '0',
  closed_comment tinyint(1) unsigned NOT NULL default '0',
  comments mediumint(8) unsigned NOT NULL default '0',
  copyfrom varchar(200) NOT NULL default '',
  introduce varchar(255) NOT NULL default '',
  thumb varchar(255) NOT NULL default '',
  picture varchar(255) NOT NULL default '',
  status tinyint(1) NOT NULL default '1',
  checker varchar(30) NOT NULL default '',
  PRIMARY KEY  (articleid),
  KEY sid (sid),
  KEY uid (uid),
  KEY city_id (city_id,catid)
) TYPE=MyISAM;

INSERT INTO modoer_articles VALUES ('1','0','2','0','1275267913','1','0','0','0','admin','Modoer 点评系统','','0','0','0','0','0','','Modoer 是一款以本地分享，多功能的点评网站管理系统。采用 PHP+MYSQL 开发设计，开放全部源代码。因具有非凡的访问速度和卓越的负载能力而深受国内外朋友的喜爱。','','','1','');

DROP TABLE IF EXISTS modoer_article_category;
CREATE TABLE modoer_article_category (
  catid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  pid smallint(5) NOT NULL DEFAULT '0',
  name varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  total mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

INSERT INTO modoer_article_category VALUES ('1','0','默认分类','0','0');
INSERT INTO modoer_article_category VALUES ('2','1','默认子分类','0','0');

DROP TABLE IF EXISTS modoer_article_data;
CREATE TABLE modoer_article_data (
  articleid mediumint(8) unsigned NOT NULL DEFAULT '0',
  content mediumtext NOT NULL,
  PRIMARY KEY (articleid)
) TYPE=MyISAM;

INSERT INTO modoer_article_data VALUES ('1','\r\n            \r\n            \r\n        \r\n        <div class=\"content\">\r\n            <h3>商铺功能</h3>\r\n            <ul><li>可建立多板块的点评，例如（餐饮，旅游，购物，娱乐，服务等）</li><li>每个板块可以分类，并按类别输出信息（如餐饮板块可以建立火锅，海鲜等，出行/旅游板块可以建立汽车，旅行社\r\n等）</li><li>商铺可以设置，商铺名称，分店名称，主营菜系，地址，电话，手机，店铺标签(Tag)，并可增加分店</li><li>商家可通过认领功能，来管理自己的点评</li><li>商铺自定义风格功能</li><li>会员可补充商铺信息</li><li>已有商铺可增加分店</li><li>商铺可以根据环境，产品或者其他补充图片集展示，图片支持缩略图，水印功能</li><li>可自定义设置商铺封面</li><li>所有会员的提交信息可自动提交和后台管理审核</li><li>自定义城市区域，可以精确到街道</li><li>地图标注和地图报错功能</li><li>商铺视频功能</li><li>会员去过，想去的互动</li></ul>\r\n            <h3>点评功能</h3>\r\n            <ul><li>商铺可以针对各个板块可以自定义点评项名称和评分项数量），喜欢程度，人均消费，消费感受，适合类型进行点评，\r\n会员并可推荐产品以及设置店铺Tag，其他会员可以对点评进行献花和回应，反馈，举报点评</li><li>会员并可推荐产品以及设置店铺 Tag</li><li>其他会员可以对点评进行赠送鲜花和回应，反馈</li><li>可举报点评</li></ul>\r\n            <h3>会员卡模块</h3>\r\n            <ul><li>可自定义设置会员卡名称</li><li>可设置会员卡在商铺的折扣或者优惠活动和备注说明</li><li>可设置推荐加盟商家</li></ul>\r\n            <h3>兑奖中心模块</h3>\r\n            <ul><li>会员可通过点评，登记，回应等一系列互动操作得到金币积分，利用这些积分可对话相应积分的奖品</li><li>后台可添加和设置奖品以及奖品说明</li></ul>\r\n            <h3>优惠券模块</h3>\r\n            <ul><li>会员可上传优惠券，可供其他会员下载打印优惠券</li><li>后台可设置优惠券审核</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>新闻咨询模块</h3>\r\n            <ul><li>发布新闻资讯</li><li>商家可发布店铺的咨询信息</li><li>其他会员可投票是否优惠券是否有用</li></ul>\r\n            <h3>排行榜功能</h3>\r\n            <ul><li>餐厅排行（最佳餐厅、口味最佳、环境最佳、服务最佳）</li><li>最新餐厅（近一周加入、近一月加入、近三月加入）</li></ul>\r\n            <h3>会员功能</h3>\r\n            <ul><li>会员短信功能</li><li>个人主页功能（可以设置、更改个人主页名称和风格）</li><li>好友设置功能</li><li>个人留言版功能</li><li>会员积分功能</li><li>会员鲜花功能</li><li>收藏夹功能</li><li>积分等级</li></ul>\r\n            <h3>模块功能</h3>\r\n            <ul><li>Modoer系统以模块为基础组成</li><li>可以Modoer为平台开发安装模块</li></ul>\r\n            <h3>高级数据调用</h3>\r\n            <ul><li>利用内置的函数和SQL调用方式调用数据</li><li>可设置每个调用的模板和空数据的模板</li><li>调用数据可设置缓存，较少系统数据库资源消耗</li><li>支持本地和JS方式调用数据</li><li>\r\n			<br /></li></ul>\r\n			<h3>插件功能</h3>\r\n            <ul><li>利用插件接口可丰富系统功能</li><li>集成提供多个插件（图片轮换，网站公告）</li></ul>\r\n			<h3>系统整合</h3>\r\n			<ul><li>万能整合API，可与任何PHP程序进行整合</li><li>整合UCenter（账户，短信，好友，积分兑换，Feed推送，个人空间跳转UCH）</li></ul>\r\n			<h3>其他功能</h3>\r\n			<ul><li>词语过滤可设置不同的过滤方式：阻止，替换，审核</li><li>菜单管理可自定义模板显示的菜单，不需要再修改模板</li><li>伪静态功能优化SEO</li></ul>\r\n        </div>');

DROP TABLE IF EXISTS modoer_att_cat;
CREATE TABLE modoer_att_cat (
  catid mediumint(8) NOT NULL AUTO_INCREMENT,
  name varchar(60) NOT NULL default '',
  des varchar(255) NOT NULL default '',
  PRIMARY KEY  (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_att_list;
CREATE TABLE modoer_att_list (
  attid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  type varchar(20) NOT NULL default '',
  catid mediumint(8) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  listorder smallint(5) unsigned NOT NULL default '0',
  icon varchar(60) NOT NULL default '',
  PRIMARY KEY  (attid)
) TYPE=MyISAM;

INSERT INTO modoer_att_list VALUES ('1','category','1','美食','0','');
INSERT INTO modoer_att_list VALUES ('2','category','2','自助餐','0','');
INSERT INTO modoer_att_list VALUES ('3','category','3','海鲜','0','');
INSERT INTO modoer_att_list VALUES ('4','area','1','宁波','0','');
INSERT INTO modoer_att_list VALUES ('5','area','2','江东区','0','');
INSERT INTO modoer_att_list VALUES ('6','area','3','海曙区','0','');
INSERT INTO modoer_att_list VALUES ('7','area','4','江北区','0','');
INSERT INTO modoer_att_list VALUES ('8','area','5','天伦广场','0','');
INSERT INTO modoer_att_list VALUES ('9','area','6','东门口','0','');
INSERT INTO modoer_att_list VALUES ('10','area','7','老外滩','0','');

DROP TABLE IF EXISTS modoer_bcastr;
CREATE TABLE modoer_bcastr (
  bcastr_id smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL default '0',
  groupname varchar(15) NOT NULL default 'index',
  available tinyint(1) NOT NULL default '1',
  itemtitle varchar(100) NOT NULL default '',
  link varchar(255) NOT NULL default '',
  item_url varchar(255) NOT NULL default '',
  orders smallint(3) NOT NULL default '0',
  PRIMARY KEY  (bcastr_id),
  KEY groupname (groupname)
) TYPE=MyISAM;

INSERT INTO modoer_bcastr VALUES ('1','0','index','1','Modoer点评系统','uploads/bcastr/25_1275267815.jpg','http://www.modoer.com','1');

DROP TABLE IF EXISTS modoer_card_apply;
CREATE TABLE modoer_card_apply (
  applyid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  linkman varchar(20) NOT NULL DEFAULT '',
  tel varchar(20) NOT NULL DEFAULT '',
  mobile varchar(20) NOT NULL DEFAULT '',
  address varchar(255) NOT NULL DEFAULT '',
  postcode varchar(10) NOT NULL DEFAULT '',
  num smallint(5) unsigned NOT NULL DEFAULT '1',
  coin int(10) NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  comment text NOT NULL,
  checker varchar(30) NOT NULL DEFAULT '',
  checktime int(10) NOT NULL DEFAULT '0',
  checkmsg text NOT NULL,
  PRIMARY KEY (applyid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_card_discounts;
CREATE TABLE modoer_card_discounts (
  sid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  cardsort enum('both','largess','discount') NOT NULL DEFAULT 'discount',
  discount decimal(4,1) NOT NULL DEFAULT '0.0',
  largess varchar(100) NOT NULL DEFAULT '',
  exception varchar(255) NOT NULL DEFAULT '',
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  available tinyint(1) NOT NULL DEFAULT '1',
  finer tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (sid),
  KEY available (available),
  KEY finer (finer,available)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_category;
CREATE TABLE modoer_category (
  catid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  pid mediumint(8) NOT NULL default '0',
  level tinyint(1) unsigned NOT NULL default '0',
  modelid smallint(5) NOT NULL default '0',
  review_opt_gid smallint(5) unsigned NOT NULL default '0',
  attid mediumint(8) unsigned NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  total int(10) unsigned NOT NULL default '0',
  config text NOT NULL,
  listorder smallint(5) NOT NULL default '0',
  enabled tinyint(1) unsigned NOT NULL default '1',
  subcats varchar(255) NOT NULL,
  nonuse_subcats varchar(255) NOT NULL,
  PRIMARY KEY  (catid),
  KEY pid (pid)
) TYPE=MyISAM;

INSERT INTO modoer_category VALUES ('1','0','1','1','1','1','美食','0','a:35:{s:10:\"enable_add\";s:1:\"1\";s:11:\"relate_root\";s:1:\"0\";s:9:\"gusetbook\";s:1:\"1\";s:5:\"forum\";s:1:\"0\";s:13:\"subject_apply\";s:1:\"1\";s:19:\"subject_apply_uppic\";s:1:\"0\";s:24:\"subject_apply_uppic_name\";s:0:\"\";s:9:\"useeffect\";s:1:\"1\";s:7:\"effect1\";s:6:\"去过\";s:7:\"effect2\";s:6:\"想去\";s:13:\"use_subbranch\";s:1:\"0\";s:18:\"allow_edit_subject\";s:1:\"0\";s:21:\"use_review_upload_pic\";s:1:\"1\";s:8:\"taggroup\";a:1:{i:0;s:1:\"1\";}s:8:\"useprice\";s:1:\"1\";s:17:\"useprice_required\";s:1:\"0\";s:14:\"useprice_title\";s:12:\"人均消费\";s:13:\"useprice_unit\";s:7:\"元/人\";s:13:\"repeat_review\";s:1:\"0\";s:17:\"repeat_review_num\";s:1:\"0\";s:18:\"repeat_review_time\";s:1:\"0\";s:12:\"guest_review\";s:1:\"0\";s:9:\"itemcheck\";s:1:\"0\";s:11:\"reviewcheck\";s:1:\"0\";s:12:\"picturecheck\";s:1:\"0\";s:14:\"guestbookcheck\";s:1:\"0\";s:10:\"templateid\";s:1:\"0\";s:19:\"detail_picture_hide\";s:1:\"0\";s:19:\"detail_content_hide\";s:1:\"0\";s:11:\"displaytype\";s:6:\"normal\";s:9:\"listorder\";s:5:\"finer\";s:4:\"icon\";s:0:\"\";s:15:\"product_modelid\";s:1:\"0\";s:13:\"meta_keywords\";s:6:\"美食\";s:16:\"meta_description\";s:30:\"modoer点评系统美食点评\";}','0','1','2,3','');
INSERT INTO modoer_category VALUES ('2','1','2','1','0','2','自助餐','0','','0','1','','');
INSERT INTO modoer_category VALUES ('3','1','2','1','0','3','海鲜','0','','0','1','','');

DROP TABLE IF EXISTS modoer_comment;
CREATE TABLE modoer_comment (
  cid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  idtype varchar(30) NOT NULL DEFAULT '',
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  grade tinyint(2) NOT NULL DEFAULT '0',
  effect1 int(10) unsigned NOT NULL DEFAULT '0',
  effect2 int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  content text NOT NULL,
  ip varchar(15) NOT NULL DEFAULT '',
  extra_id int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (cid),
  KEY idtype (idtype,id),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_config;
CREATE TABLE modoer_config (
  variable varchar(32) NOT NULL DEFAULT '',
  value text NOT NULL,
  module varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (variable,module)
) TYPE=MyISAM;

INSERT INTO modoer_config VALUES ('point','a:12:{s:11:\"add_subject\";a:7:{s:5:\"point\";i:15;s:6:\"point1\";i:15;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_review\";a:7:{s:5:\"point\";i:10;s:6:\"point1\";i:10;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_picture\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"add_guestbook\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_respond\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:14:\"update_subject\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:13:\"report_review\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_article\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:10:\"add_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:12:\"print_coupon\";a:7:{s:5:\"point\";i:5;s:6:\"point1\";i:5;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:11:\"add_comment\";a:7:{s:5:\"point\";i:2;s:6:\"point1\";i:2;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}s:3:\"reg\";a:7:{s:5:\"point\";i:20;s:6:\"point1\";i:20;s:6:\"point2\";i:0;s:6:\"point3\";i:0;s:6:\"point4\";i:0;s:6:\"point5\";i:0;s:6:\"point6\";i:0;}}','member');
INSERT INTO modoer_config VALUES ('point_group','a:6:{s:6:\"point1\";a:6:{s:7:\"enabled\";s:1:\"1\";s:4:\"name\";s:6:\"金币\";s:4:\"unit\";s:3:\"个\";s:2:\"in\";s:1:\"1\";s:3:\"out\";s:1:\"1\";s:4:\"rate\";s:0:\"\";}s:6:\"point2\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point3\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point4\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point5\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}s:6:\"point6\";a:3:{s:4:\"name\";s:0:\"\";s:4:\"unit\";s:0:\"\";s:4:\"rate\";s:0:\"\";}}','member');
INSERT INTO modoer_config VALUES ('siteclose','0','modoer');
INSERT INTO modoer_config VALUES ('icpno','','modoer');
INSERT INTO modoer_config VALUES ('sitename','Modoer点评系统','modoer');
INSERT INTO modoer_config VALUES ('seccode','0','modoer');
INSERT INTO modoer_config VALUES ('useripaccess','','modoer');
INSERT INTO modoer_config VALUES ('adminipaccess','','modoer');
INSERT INTO modoer_config VALUES ('ban_ip','','modoer');
INSERT INTO modoer_config VALUES ('gzipcompress','0','modoer');
INSERT INTO modoer_config VALUES ('scriptinfo','1','modoer');
INSERT INTO modoer_config VALUES ('picture_upload_size','2000','modoer');
INSERT INTO modoer_config VALUES ('watermark','1','modoer');
INSERT INTO modoer_config VALUES ('jstransfer','1','modoer');
INSERT INTO modoer_config VALUES ('jsaccess','','modoer');
INSERT INTO modoer_config VALUES ('googlesearch','0','modoer');
INSERT INTO modoer_config VALUES ('googlesearch_website','modoer.com','modoer');
INSERT INTO modoer_config VALUES ('tplext','.htm','modoer');
INSERT INTO modoer_config VALUES ('mapapi','http://api.51ditu.com/js/maps.js','modoer');
INSERT INTO modoer_config VALUES ('datacall_dir','./data/datacall','modoer');
INSERT INTO modoer_config VALUES ('datacall_clearinterval','1','modoer');
INSERT INTO modoer_config VALUES ('datacall_cleartime','1','modoer');
INSERT INTO modoer_config VALUES ('search_limit','60','modoer');
INSERT INTO modoer_config VALUES ('search_maxspm','20','modoer');
INSERT INTO modoer_config VALUES ('search_maxresults','500','modoer');
INSERT INTO modoer_config VALUES ('search_cachelife','3600','modoer');
INSERT INTO modoer_config VALUES ('rewrite','0','modoer');
INSERT INTO modoer_config VALUES ('rewritecompatible','1','modoer');
INSERT INTO modoer_config VALUES ('subname','多功能点评系统','modoer');
INSERT INTO modoer_config VALUES ('titlesplit',',','modoer');
INSERT INTO modoer_config VALUES ('meta_keywords','Meta Keywords','modoer');
INSERT INTO modoer_config VALUES ('meta_description','Meta Description','modoer');
INSERT INTO modoer_config VALUES ('headhtml','','modoer');
INSERT INTO modoer_config VALUES ('templateid','0','member');
INSERT INTO modoer_config VALUES ('editor_relativeurl','1','modoer');
INSERT INTO modoer_config VALUES ('page_cachetime','0','modoer');
INSERT INTO modoer_config VALUES ('console_menuid','3','modoer');
INSERT INTO modoer_config VALUES ('closereg','0','member');
INSERT INTO modoer_config VALUES ('censoruser','*admin*\r\n*管理员*','member');
INSERT INTO modoer_config VALUES ('existsemailreg','0','member');
INSERT INTO modoer_config VALUES ('salutatory','1','member');
INSERT INTO modoer_config VALUES ('salutatory_msg','尊敬的$username：\r\n\r\n欢迎您加入$sitename大家庭！\r\n祝你在$sitename过得愉快！\r\n\r\n$sitename运营团队\r\n$time','member');
INSERT INTO modoer_config VALUES ('showregrule','1','member');
INSERT INTO modoer_config VALUES ('regrule','这里填写新用户的注册协议！','member');
INSERT INTO modoer_config VALUES ('pic_width','200','item');
INSERT INTO modoer_config VALUES ('pic_height','150','item');
INSERT INTO modoer_config VALUES ('video_width','250','item');
INSERT INTO modoer_config VALUES ('video_height','200','item');
INSERT INTO modoer_config VALUES ('review_min','10','review');
INSERT INTO modoer_config VALUES ('review_max','1500','review');
INSERT INTO modoer_config VALUES ('respond_min','10','review');
INSERT INTO modoer_config VALUES ('respond_max','500','review');
INSERT INTO modoer_config VALUES ('avatar_review','0','review');
INSERT INTO modoer_config VALUES ('pcatid','9','item');
INSERT INTO modoer_config VALUES ('list_num','20','item');
INSERT INTO modoer_config VALUES ('review_num','5','review');
INSERT INTO modoer_config VALUES ('respond_num','5','review');
INSERT INTO modoer_config VALUES ('classorder','order','item');
INSERT INTO modoer_config VALUES ('thumb','2','item');
INSERT INTO modoer_config VALUES ('show_thumb','1','item');
INSERT INTO modoer_config VALUES ('show_thumb_sort','small','item');
INSERT INTO modoer_config VALUES ('mapapi_charset','','modoer');
INSERT INTO modoer_config VALUES ('main_menuid','1','modoer');
INSERT INTO modoer_config VALUES ('respondcheck','0','item');
INSERT INTO modoer_config VALUES ('pid','1','item');
INSERT INTO modoer_config VALUES ('closenote','正在升级，请稍后访问...','modoer');
INSERT INTO modoer_config VALUES ('gbook','1','space');
INSERT INTO modoer_config VALUES ('gbook_guest','1','space');
INSERT INTO modoer_config VALUES ('gbook_seccode','1','space');
INSERT INTO modoer_config VALUES ('templateid','0','space');
INSERT INTO modoer_config VALUES ('recordguest','1','space');
INSERT INTO modoer_config VALUES ('spacename','{username}的个人空间','space');
INSERT INTO modoer_config VALUES ('spacedescribe','读万卷书模，行万里路！','space');
INSERT INTO modoer_config VALUES ('index_reviews','5','space');
INSERT INTO modoer_config VALUES ('index_gbooks','5','space');
INSERT INTO modoer_config VALUES ('reviews','10','space');
INSERT INTO modoer_config VALUES ('gbooks','10','space');
INSERT INTO modoer_config VALUES ('seccode_review','0','review');
INSERT INTO modoer_config VALUES ('seccode_picupload','1','item');
INSERT INTO modoer_config VALUES ('seccode_guestbook','0','item');
INSERT INTO modoer_config VALUES ('seccode_respond','1','review');
INSERT INTO modoer_config VALUES ('templateid','1','modoer');
INSERT INTO modoer_config VALUES ('foot_menuid','66','modoer');
INSERT INTO modoer_config VALUES ('scoretype','10','review');
INSERT INTO modoer_config VALUES ('decimalpoint','2','review');
INSERT INTO modoer_config VALUES ('seccode_review_guest','1','review');
INSERT INTO modoer_config VALUES ('seccode_subject','0','item');
INSERT INTO modoer_config VALUES ('tag_split_sp','1','item');
INSERT INTO modoer_config VALUES ('menuid','80','space');
INSERT INTO modoer_config VALUES ('space_menuid','80','space');
INSERT INTO modoer_config VALUES ('multi_upload_pic','1','item');
INSERT INTO modoer_config VALUES ('multi_upload_pic_num','10','item');
INSERT INTO modoer_config VALUES ('console_seccode','0','modoer');
INSERT INTO modoer_config VALUES ('console_total','1','modoer');
INSERT INTO modoer_config VALUES ('guest_post','0','comment');
INSERT INTO modoer_config VALUES ('member_seccode','0','comment');
INSERT INTO modoer_config VALUES ('guest_seccode','0','comment');
INSERT INTO modoer_config VALUES ('disable_comment','0','comment');
INSERT INTO modoer_config VALUES ('guest_comment','0','comment');
INSERT INTO modoer_config VALUES ('check_comment','0','comment');
INSERT INTO modoer_config VALUES ('filter_word','1','comment');
INSERT INTO modoer_config VALUES ('list_num','5','comment');
INSERT INTO modoer_config VALUES ('hidden_comment','0','comment');
INSERT INTO modoer_config VALUES ('comment_interval','5','comment');
INSERT INTO modoer_config VALUES ('mapflag','51ditu','modoer');
INSERT INTO modoer_config VALUES ('seccode_reg','0','member');
INSERT INTO modoer_config VALUES ('seccode_login','0','member');
INSERT INTO modoer_config VALUES ('mail_debug','0','modoer');
INSERT INTO modoer_config VALUES ('ownernews','1','exchange');
INSERT INTO modoer_config VALUES ('ownernews_classid','1','exchange');
INSERT INTO modoer_config VALUES ('ownernews_check','0','exchange');
INSERT INTO modoer_config VALUES ('thumb_w','160','exchange');
INSERT INTO modoer_config VALUES ('thumb_h','100','exchange');
INSERT INTO modoer_config VALUES ('exchange_seccode','1','exchange');
INSERT INTO modoer_config VALUES ('keywords','礼品兑换,兑奖中心','exchange');
INSERT INTO modoer_config VALUES ('description','礼品兑换模块用户会员使用金币兑换网站提供的礼品','exchange');
INSERT INTO modoer_config VALUES ('picture_createthumb_level','80','modoer');
INSERT INTO modoer_config VALUES ('keywords','新闻模块','article');
INSERT INTO modoer_config VALUES ('description','文章信息，发布网站信息和主题资讯','article');
INSERT INTO modoer_config VALUES ('editor_image','1','article');
INSERT INTO modoer_config VALUES ('rss','1','article');
INSERT INTO modoer_config VALUES ('owner_post','1','article');
INSERT INTO modoer_config VALUES ('member_post','0','article');
INSERT INTO modoer_config VALUES ('post_check','1','article');
INSERT INTO modoer_config VALUES ('post_filter','1','article');
INSERT INTO modoer_config VALUES ('list_num','20','article');
INSERT INTO modoer_config VALUES ('owner_category','0','article');
INSERT INTO modoer_config VALUES ('member_category','0','article');
INSERT INTO modoer_config VALUES ('post_seccode','0','article');
INSERT INTO modoer_config VALUES ('member_bysubject','0','article');
INSERT INTO modoer_config VALUES ('meta_keywords','新闻模块','article');
INSERT INTO modoer_config VALUES ('meta_description','文章信息，发布网站信息和主题资讯','article');
INSERT INTO modoer_config VALUES ('post_comment','1','article');
INSERT INTO modoer_config VALUES ('att_custom','1|头条(默认显示2条)\r\n2|文字推荐(默认显示7条)\r\n3|图片推荐(默认显示3条)\r\n4|模块首页图片轮换(不宜过多)','article');
INSERT INTO modoer_config VALUES ('meta_keywords','兑奖中心','exchange');
INSERT INTO modoer_config VALUES ('meta_description','兑奖中心模块，用于消费金币','exchange');
INSERT INTO modoer_config VALUES ('map_view_level','2','modoer');
INSERT INTO modoer_config VALUES ('guestbook_min','10','item');
INSERT INTO modoer_config VALUES ('guestbook_max','50','item');
INSERT INTO modoer_config VALUES ('content_min','10','comment');
INSERT INTO modoer_config VALUES ('content_max','200','comment');
INSERT INTO modoer_config VALUES ('meta_keywords','友情链接','link');
INSERT INTO modoer_config VALUES ('meta_description','Modoer点评系统的友情链接模块！','link');
INSERT INTO modoer_config VALUES ('num_logo','5','link');
INSERT INTO modoer_config VALUES ('num_char','20','link');
INSERT INTO modoer_config VALUES ('open_apply','1','link');
INSERT INTO modoer_config VALUES ('apply','1','card');
INSERT INTO modoer_config VALUES ('applyseccode','1','card');
INSERT INTO modoer_config VALUES ('coin','10','card');
INSERT INTO modoer_config VALUES ('applynum','2','card');
INSERT INTO modoer_config VALUES ('applydes','这里填写申请提交时，显示给会员看的申请说明和条例。','card');
INSERT INTO modoer_config VALUES ('subtitle','最优惠的消费折扣卡','card');
INSERT INTO modoer_config VALUES ('meta_keywords','会员卡','card');
INSERT INTO modoer_config VALUES ('meta_description','modoer点评系统会员卡模块','card');
INSERT INTO modoer_config VALUES ('check','1','coupon');
INSERT INTO modoer_config VALUES ('post_item_owner','1','coupon');
INSERT INTO modoer_config VALUES ('watermark','1','coupon');
INSERT INTO modoer_config VALUES ('thumb_width','160','coupon');
INSERT INTO modoer_config VALUES ('thumb_height','100','coupon');
INSERT INTO modoer_config VALUES ('seccode','1','coupon');
INSERT INTO modoer_config VALUES ('listnum','10','coupon');
INSERT INTO modoer_config VALUES ('des','这是是优惠券发布的保证说明！','coupon');
INSERT INTO modoer_config VALUES ('subtitle','最优优惠','coupon');
INSERT INTO modoer_config VALUES ('meta_keywords','优惠券模块','coupon');
INSERT INTO modoer_config VALUES ('meta_description','Modoer点评系统之优惠券模块','coupon');
INSERT INTO modoer_config VALUES ('post_comment','1','coupon');
INSERT INTO modoer_config VALUES ('picture_createthumb_mod','0','modoer');
INSERT INTO modoer_config VALUES ('watermark_postion','0','modoer');
INSERT INTO modoer_config VALUES ('picture_ext','jpg jpeg png gif','modoer');
INSERT INTO modoer_config VALUES ('select_city','1','article');
INSERT INTO modoer_config VALUES ('copyright','&#169; 2007 - 2011 <a href=\"http://www.modoer.com\" target=\"_blank\">陌风软件</a> 版权所有','modoer');
INSERT INTO modoer_config VALUES ('buildinfo','1','modoer');
INSERT INTO modoer_config VALUES ('statement','免责声明：站内会员言论仅代表个人观点，并不代表本站同意其观点，本站不承担由此引起的法律责任。','modoer');
INSERT INTO modoer_config VALUES ('feed_enable','1','member');
INSERT INTO modoer_config VALUES ('watermark_text','Modoer点评系统','modoer');
INSERT INTO modoer_config VALUES ('city_id','1','modoer');
INSERT INTO modoer_config VALUES ('picture_max_width','800','modoer');
INSERT INTO modoer_config VALUES ('picture_max_height','600','modoer');
INSERT INTO modoer_config VALUES ('city_ip_location','0','modoer');
INSERT INTO modoer_config VALUES ('index_digst_rand_num','2','review');
INSERT INTO modoer_config VALUES ('index_pk_rand_num','1','review');
INSERT INTO modoer_config VALUES ('index_show_bad_review','1','review');
INSERT INTO modoer_config VALUES ('index_review_num','5','review');
INSERT INTO modoer_config VALUES ('index_review_gettype','rand','review');
INSERT INTO modoer_config VALUES ('content_min','10','article');
INSERT INTO modoer_config VALUES ('content_max','50000','article');
INSERT INTO modoer_config VALUES ('citypath_without','index/announcement\r\nfenlei/detail\r\nparty/detail\r\nask/detail\r\ntuan/detail\r\nproduct/detail\r\narticle/detail\r\nitem/detail\r\ncoupon/detail\r\nreview/detail\r\nexchange/gift\r\nspace/*\r\ngroup/*','modoer');
INSERT INTO modoer_config VALUES ('sellgroup_pointtype','point1','member');
INSERT INTO modoer_config VALUES ('sellgroup_useday','30','member');
INSERT INTO modoer_config VALUES ('passport_login','0','member');
INSERT INTO modoer_config VALUES ('passport_pw','0','member');
INSERT INTO modoer_config VALUES ('registered_again','0','member');
INSERT INTO modoer_config VALUES ('email_verify','0','member');
INSERT INTO modoer_config VALUES ('mobile_verify','0','member');
INSERT INTO modoer_config VALUES ('mobile_verify_message','$sitename 用户手机认证验证码：$serial','member');
INSERT INTO modoer_config VALUES ('sldomain','0','item');
INSERT INTO modoer_config VALUES ('base_sldomain','','item');
INSERT INTO modoer_config VALUES ('reserve_sldomain','','item');
INSERT INTO modoer_config VALUES ('selltpl_pointtype','point1','item');
INSERT INTO modoer_config VALUES ('selltpl_useday','180','item');
INSERT INTO modoer_config VALUES ('seccode_review','0','item');
INSERT INTO modoer_config VALUES ('seccode_review_guest','0','item');
INSERT INTO modoer_config VALUES ('review_min','10','item');
INSERT INTO modoer_config VALUES ('review_max','2000','item');
INSERT INTO modoer_config VALUES ('respond_min','10','item');
INSERT INTO modoer_config VALUES ('respond_max','500','item');
INSERT INTO modoer_config VALUES ('avatar_review','0','item');
INSERT INTO modoer_config VALUES ('search_location','0','item');
INSERT INTO modoer_config VALUES ('album_comment','1','item');
INSERT INTO modoer_config VALUES ('ajax_taoke','0','item');
INSERT INTO modoer_config VALUES ('review_num','','item');
INSERT INTO modoer_config VALUES ('show_detail_vs_review','0','item');
INSERT INTO modoer_config VALUES ('close_detail_total','0','item');
INSERT INTO modoer_config VALUES ('list_filter_li_collapse_num','','item');
INSERT INTO modoer_config VALUES ('pointgroup','point1','exchange');
INSERT INTO modoer_config VALUES ('pointgroup','point1','card');
INSERT INTO modoer_config VALUES ('topic_check','0','discussion');
INSERT INTO modoer_config VALUES ('reply_check','0','discussion');
INSERT INTO modoer_config VALUES ('topic_content_min','10','discussion');
INSERT INTO modoer_config VALUES ('topic_content_max','5000','discussion');
INSERT INTO modoer_config VALUES ('reply_content_min','10','discussion');
INSERT INTO modoer_config VALUES ('reply_content_max','1000','discussion');
INSERT INTO modoer_config VALUES ('topic_seccode','0','discussion');
INSERT INTO modoer_config VALUES ('reply_seccode','0','discussion');
INSERT INTO modoer_config VALUES ('city_sldomain','0','modoer');
INSERT INTO modoer_config VALUES ('utf8url','0','modoer');
INSERT INTO modoer_config VALUES ('picture_dir_mod','DAY','modoer');
INSERT INTO modoer_config VALUES ('title','主题交流','discussion');
INSERT INTO modoer_config VALUES ('meta_keywords','Modoer讨论组模块,主题交流,讨论组,交流区','discussion');
INSERT INTO modoer_config VALUES ('meta_description','Modoer点评系统的讨论组模块','discussion');
INSERT INTO modoer_config VALUES ('meta_keywords','','review');
INSERT INTO modoer_config VALUES ('meta_description','','review');
INSERT INTO modoer_config VALUES ('respondcheck','1','review');
INSERT INTO modoer_config VALUES ('tag_split_sp','0','review');
INSERT INTO modoer_config VALUES ('default_grade','0','review');
INSERT INTO modoer_config VALUES ('digest_price','10','review');
INSERT INTO modoer_config VALUES ('digest_pointtype','point1','review');
INSERT INTO modoer_config VALUES ('digest_gain','','review');

DROP TABLE IF EXISTS modoer_coupon_category;
CREATE TABLE modoer_coupon_category (
  catid smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(40) NOT NULL DEFAULT '',
  num mediumint(9) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

INSERT INTO modoer_coupon_category VALUES ('1','美食','0','0');
INSERT INTO modoer_coupon_category VALUES ('2','购物','0','0');
INSERT INTO modoer_coupon_category VALUES ('3','休闲','0','0');
INSERT INTO modoer_coupon_category VALUES ('4','女性','0','0');
INSERT INTO modoer_coupon_category VALUES ('5','摄影','0','0');

DROP TABLE IF EXISTS modoer_coupon_print;
CREATE TABLE modoer_coupon_print (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  couponid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  point mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY couponid (couponid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_coupons;
CREATE TABLE modoer_coupons (
  couponid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) NOT NULL default '0',
  catid smallint(5) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  username varchar(30) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  picture varchar(255) NOT NULL DEFAULT '',
  starttime int(10) NOT NULL DEFAULT '0',
  endtime int(10) NOT NULL DEFAULT '0',
  subject varchar(100) NOT NULL DEFAULT '',
  des varchar(50) NOT NULL DEFAULT '',
  content text NOT NULL,
  effect1 mediumint(8) unsigned NOT NULL DEFAULT '0',
  prints mediumint(8) unsigned NOT NULL DEFAULT '0',
  comments mediumint(8) unsigned NOT NULL DEFAULT '0',
  sms_text varchar(255) NOT NULL default '',
  send_num INT(10) NOT NULL DEFAULT  '0',
  grade smallint(5) unsigned NOT NULL DEFAULT '0',
  flag tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  pageview int(10) NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  closed_comment tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (couponid),
  KEY sid (sid),
  KEY uid (uid),
  KEY city_id (city_id,catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_datacall;
CREATE TABLE modoer_datacall (
  callid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  module varchar(60) NOT NULL DEFAULT '',
  calltype varchar(60) NOT NULL DEFAULT '',
  name varchar(50) NOT NULL DEFAULT '',
  fun varchar(60) NOT NULL DEFAULT '',
  var varchar(60) NOT NULL DEFAULT '',
  cachetime mediumint(8) unsigned NOT NULL DEFAULT '0',
  expression text NOT NULL,
  tplname varchar(200) NOT NULL DEFAULT '',
  empty_tplname varchar(200) NOT NULL DEFAULT '',
  closed tinyint(1) unsigned NOT NULL DEFAULT '0',
  hash varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (callid)
) TYPE=MyISAM;

INSERT INTO modoer_datacall VALUES ('5','item','sql','主题_会员参与','sql','mydata','1000','a:6:{s:4:\"from\";s:72:\"{dbpre}membereffect_total mt LEFT JOIN {dbpre}subject s ON (mt.id=s.sid)\";s:6:\"select\";s:58:\"mt.{field:effect} as effect,s.sid,s.catid,s.name,s.subname\";s:5:\"where\";s:77:\"mt.idtype={idtype} AND mt.{field:effect}>0 AND s.city_id IN ({array:city_id})\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:22:\"mt.{field:effect} DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_effect_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('8','item','sql','主题_相关主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:72:\"city_id IN ({array:city_id}) and name={name} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('6','item','sql','主题_同类主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:74:\"city_id IN ({array:city_id}) and catid={catid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('7','item','sql','主题_附近主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:52:\"sid,pid,catid,name,subname,avgsort,pageviews,reviews\";s:5:\"where\";s:37:\"aid={aid} and status=1 and sid!={sid}\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:12:\"addtime DESC\";s:5:\"limit\";s:4:\"0,10\";}','item_subject_li','empty_li','0','');
INSERT INTO modoer_datacall VALUES ('11','item','sql','首页_推荐主题','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}subject\";s:6:\"select\";s:46:\"sid,aid,name,subname,avgsort,thumb,description\";s:5:\"where\";s:67:\"city_id IN ({array:city_id}) AND pid={pid} AND status=1 AND finer>0\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:10:\"finer DESC\";s:5:\"limit\";s:3:\"0,8\";}','index_subject_finer','empty_div','0','');
INSERT INTO modoer_datacall VALUES ('16','product','sql','产品_主题产品','sql','mydata','1000','a:6:{s:4:\"from\";s:14:\"{dbpre}product\";s:6:\"select\";s:59:\"pid,catid,subject,grade,description,thumb,comments,pageview\";s:5:\"where\";s:22:\"sid={sid} AND status=1\";s:5:\"other\";s:0:\"\";s:7:\"orderby\";s:24:\"grade DESC,comments DESC\";s:5:\"limit\";s:4:\"0,10\";}','product_pic_li','empty_li','0','');

DROP TABLE IF EXISTS modoer_digest_pay;
CREATE TABLE modoer_digest_pay (
  payid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  id mediumint(8) unsigned NOT NULL default '0',
  idtype char(15) NOT NULL default '',
  uid mediumint(8) unsigned NOT NULL default '0',
  username char(20) NOT NULL default '',
  price int(10) unsigned NOT NULL default '0',
  pointtype enum('point1','point2','point3','point4','point5','point6') NOT NULL default 'point1',
  dateline int(10) NOT NULL default '0',
  gain_uid mediumint(8) NOT NULL default '0',
  gain_price int(10) NOT NULL default '0',
  PRIMARY KEY  (payid),
  KEY id (id,idtype,uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_category;
CREATE TABLE modoer_exchange_category (
  catid smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(40) NOT NULL DEFAULT '',
  num mediumint(9) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

INSERT INTO modoer_exchange_category VALUES(1, '默认分类', 0, 0);

DROP TABLE IF EXISTS modoer_exchange_gifts;
CREATE TABLE modoer_exchange_gifts (
  giftid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  catid smallint(5) unsigned NOT NULL DEFAULT '0',
  sid smallint(5) unsigned NOT NULL DEFAULT '0',
  city_id smallint(5) unsigned NOT NULL DEFAULT '0',
  name varchar(200) NOT NULL DEFAULT '',
  sort tinyint(1) unsigned NOT NULL DEFAULT '1',
  pattern tinyint(1) unsigned NOT NULL DEFAULT '1',
  reviewed tinyint(1) unsigned NOT NULL DEFAULT '0',
  available tinyint(1) NOT NULL DEFAULT '0',
  starttime int(10) NOT NULL DEFAULT '0',
  endtime int(10) NOT NULL DEFAULT '0',
  randomcodelen tinyint(1) NOT NULL DEFAULT '0',
  randomcode varchar(50) NOT NULL DEFAULT '',
  displayorder tinyint(3) NOT NULL DEFAULT '0',
  description text NOT NULL,
  price int(10) unsigned NOT NULL DEFAULT '0',
  point int(10) unsigned NOT NULL DEFAULT '0',
  point3 int(10) unsigned NOT NULL DEFAULT '0',
  point4 int(10) unsigned NOT NULL DEFAULT '0',
  pointtype enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  pointtype2 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  pointtype3 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  pointtype4 enum('rmb','point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL,
  num int(10) unsigned NOT NULL DEFAULT '0',
  timenum int(10) unsigned NOT NULL DEFAULT '0',
  pageview mediumint(8) unsigned NOT NULL DEFAULT '0',
  thumb varchar(255) NOT NULL DEFAULT '',
  picture varchar(255) NOT NULL DEFAULT '',
  salevolume int(11) unsigned NOT NULL DEFAULT '0',
  allowtime varchar(255) NOT NULL DEFAULT '',
  usergroup varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (giftid),
  KEY available (available)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_log;
CREATE TABLE modoer_exchange_log (
  exchangeid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(25) NOT NULL DEFAULT '',
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  giftname varchar(200) NOT NULL DEFAULT '',
  price int(10) unsigned NOT NULL DEFAULT '0',
  pointtype enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL default 'point1',
  number int(10) unsigned NOT NULL DEFAULT '1',
  pay_style tinyint(1) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  status_extra varchar(255) NOT NULL DEFAULT '',
  exchangetime int(10) NOT NULL DEFAULT '0',
  contact mediumtext NOT NULL,
  checker varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (exchangeid),
  KEY uid (uid),
  KEY giftid (giftid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_lottery;
CREATE TABLE modoer_exchange_lottery (
  lid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  lotterycode varchar(50) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (lid),
  KEY giftid (giftid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_exchange_serial;
CREATE TABLE modoer_exchange_serial (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
  exchangeid mediumint(8) NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  serial varchar(255) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  sendtime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY giftid (giftid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_favorites;
CREATE TABLE modoer_favorites (
  fid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  idtype CHAR( 20 ) NOT NULL DEFAULT  '',
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  addtime int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (fid),
  KEY addtime (addtime),
  KEY uid (uid,addtime),
  KEY idtype (idtype,id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_field;
CREATE TABLE modoer_field (
  fieldid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  idtype varchar(30) NOT NULL DEFAULT '',
  id smallint(5) NOT NULL DEFAULT '0',
  tablename varchar(25) NOT NULL DEFAULT '',
  fieldname varchar(50) NOT NULL DEFAULT '',
  title varchar(100) NOT NULL DEFAULT '',
  unit varchar(100) NOT NULL DEFAULT '',
  style varchar(255) NOT NULL DEFAULT '',
  template text NOT NULL,
  note mediumtext NOT NULL,
  type varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  allownull tinyint(1) unsigned NOT NULL DEFAULT '1',
  enablesearch tinyint(1) unsigned NOT NULL DEFAULT '0',
  iscore tinyint(1) NOT NULL DEFAULT '0',
  isadminfield varchar(1) NOT NULL DEFAULT '0',
  show_list tinyint(1) unsigned NOT NULL DEFAULT '0',
  show_detail tinyint(1) unsigned NOT NULL DEFAULT '1',
  regular varchar(255) NOT NULL DEFAULT '',
  errmsg varchar(255) NOT NULL DEFAULT '',
  datatype varchar(60) NOT NULL DEFAULT '',
  config text NOT NULL,
  disable tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (fieldid),
  KEY tablename (tablename)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_flowers;
CREATE TABLE modoer_flowers (
  fid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  rid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(16) NOT NULL DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (fid),
  KEY reviewid (rid),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_friends;
CREATE TABLE modoer_friends (
  fid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  friend_uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  addtime int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (fid),
  KEY addtime (addtime,uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_gbooks;
CREATE TABLE modoer_gbooks (
  gid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  gbuid mediumint(8) unsigned NOT NULL DEFAULT '0',
  gbusername varchar(16) NOT NULL DEFAULT '',
  content text NOT NULL,
  posttime int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (gid),
  KEY uid (uid,posttime)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_getpassword;
CREATE TABLE modoer_getpassword (
  getpwid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  secode varchar(8) NOT NULL DEFAULT '',
  posttime int(10) NOT NULL DEFAULT '0',
  sort enum('get_password','email_verify') NOT NULL default 'get_password',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (getpwid),
  KEY secode (secode,status)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group;
CREATE TABLE modoer_group (
  gid mediumint(8) NOT NULL AUTO_INCREMENT ,
  `status` tinyint(1) NOT NULL DEFAULT '0' ,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  catid mediumint(8) NOT NULL DEFAULT '0' ,
  groupname char(60) NOT NULL DEFAULT '',
  topics mediumint(8) NOT NULL DEFAULT '0' ,
  replies mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  members mediumint(8) NOT NULL DEFAULT '0' ,
  createtime int(10) NOT NULL DEFAULT '0' ,
  uid mediumint(8) NOT NULL DEFAULT '0' ,
  username char(30) NOT NULL DEFAULT '',
  lastpost int(10) NOT NULL DEFAULT '0' ,
  icon char(255) NOT NULL DEFAULT '' ,
  tags char(100) NOT NULL DEFAULT '',
  des text NOT NULL ,
  PRIMARY KEY (gid),
  KEY groupname (groupname),
  KEY `status` (`status`,members),
  KEY sid (sid,`status`),
  KEY `uid` (`uid`,`status`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_category;
CREATE TABLE modoer_group_category (
  catid mediumint(8) NOT NULL AUTO_INCREMENT,
  pid mediumint(8) NOT NULL DEFAULT '0',
  listorder smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  tags text NOT NULL ,
  title varchar(255) NOT NULL DEFAULT '',
  keywords varchar(255) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_member;
CREATE TABLE modoer_group_member (
  id mediumint(8) NOT NULL AUTO_INCREMENT ,
  gid mediumint(8) NOT NULL DEFAULT '0' ,
  uid mediumint(8) NOT NULL DEFAULT '0' ,
  username varchar(30) NOT NULL DEFAULT '' ,
  `status` tinyint(1) NOT NULL DEFAULT '1' ,
  jointime int(10) NOT NULL DEFAULT '0' ,
  lastpost int(10) unsigned NOT NULL DEFAULT '0' ,
  bantime int(10) unsigned NOT NULL DEFAULT '0' ,
  usertype tinyint(1) NOT NULL DEFAULT '10' ,
  posts mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  applydes text NOT NULL ,
  PRIMARY KEY (id),
  KEY gid (gid,uid),
  KEY `list` (gid,`status`,jointime)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_reply;
CREATE TABLE modoer_group_reply (
  rpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT ,
  tpid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  gid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  username varchar(20) NOT NULL DEFAULT '' ,
  dateline int(10) unsigned NOT NULL DEFAULT '10' ,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' ,
  pictures text NOT NULL,
  content text NOT NULL ,
  PRIMARY KEY (rpid),
  KEY tpid (tpid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_setting;
CREATE TABLE modoer_group_setting (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  gid mediumint(8) NOT NULL DEFAULT '0',
  variable char(30) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (id),
  KEY gid (gid,variable)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_tag;
CREATE TABLE modoer_group_tag (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tagname char(8) NOT NULL DEFAULT '' ,
  gid mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY tagname (tagname),
  KEY gid (gid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_group_topic;
CREATE TABLE modoer_group_topic (
  tpid mediumint(8) unsigned NOT NULL AUTO_INCREMENT ,
  gid mediumint(8) NOT NULL DEFAULT '0' ,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  `subject` varchar(255) NOT NULL DEFAULT '' ,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  username varchar(20) NOT NULL DEFAULT '' ,
  pageview int(10) unsigned NOT NULL DEFAULT '0' ,
  replies mediumint(8) unsigned NOT NULL DEFAULT '0' ,
  replytime int(10) unsigned NOT NULL DEFAULT '0' ,
  top tinyint(1) NOT NULL DEFAULT '0',
  closed tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' ,
  dateline int(10) unsigned NOT NULL DEFAULT '0' ,
  pictures text NOT NULL,
  content text NOT NULL ,
  PRIMARY KEY (tpid),
  KEY `list` (`gid`,`status`,`top`,`replytime`),
  KEY `uid` (`uid`,`status`,`dateline`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_guestbook;
CREATE TABLE modoer_guestbook (
  guestbookid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  uid mediumint(9) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  content text NOT NULL,
  ip varchar(15) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  reply text NOT NULL,
  replytime int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (guestbookid),
  KEY id (sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_hook;
CREATE TABLE modoer_hook (
  hookid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  hook_module varchar(30) NOT NULL DEFAULT '',
  hook_position varchar(60) NOT NULL DEFAULT '',
  module varchar(30) NOT NULL DEFAULT '',
  filename varchar(255) NOT NULL DEFAULT '',
  disable tinyint(1) unsigned NOT NULL DEFAULT '0',
  des varchar(255) NOT NULL DEFAULT '',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (hookid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_feed;
CREATE TABLE modoer_member_feed (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  flag varchar(30) NOT NULL default '',
  city_id smallint(5) unsigned NOT NULL default '0',
  sid MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT  '0',
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(25) NOT NULL default '',
  module varchar(15) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  icon varchar(30) NOT NULL,
  title text NOT NULL,
  body text NOT NULL,
  images text NOT NULL,
  PRIMARY KEY  (id),
  KEY uid (uid),
  KEY dateline (dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_passport_new;
CREATE TABLE modoer_member_passport_new (
  uid mediumint(8) NOT NULL,
  psname enum('weibo','qq','taobao','google','qzone', 'renren') NOT NULL,
  psuid varchar(60) NOT NULL DEFAULT '',
  access_token varchar(60) NOT NULL DEFAULT '',
  expired int(10) unsigned NOT NULL DEFAULT '0',
  isbind tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (uid,psname),
  KEY psname (psname,psuid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_point;
CREATE TABLE modoer_member_point (
  uid mediumint(8) unsigned NOT NULL auto_increment,
  rmb decimal(9,2) unsigned NOT NULL default '0.00',
  point int(10) NOT NULL default '0',
  point1 int(10) NOT NULL default '0',
  point2 int(10) NOT NULL default '0',
  point3 int(10) NOT NULL default '0',
  point4 int(10) NOT NULL default '0',
  point5 int(10) NOT NULL default '0',
  point6 int(10) NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_point_log;
CREATE TABLE modoer_member_point_log (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  out_uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  out_username varchar(25) NOT NULL DEFAULT '',
  out_point varchar(20) NOT NULL DEFAULT '',
  out_value decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  in_uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  in_username varchar(25) NOT NULL DEFAULT '',
  in_point varchar(20) NOT NULL DEFAULT '',
  in_value decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  des text NOT NULL,
  extra varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY out_uid (out_uid,in_uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_profile;
CREATE TABLE modoer_member_profile (
	uid mediumint(8) not null,
	realname varchar(100) not null default '',
	gender tinyint(1) not null default '0',
	birthday date not null default '0000-00-00',
	alipay varchar(255) not null default '',
	qq varchar(255) not null default '',
	msn varchar(255) not null default '',
	address varchar(255) not null default '',
	zipcode varchar(255) not null default '',
	PRIMARY KEY (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_membereffect;
CREATE TABLE modoer_membereffect (
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  idtype varchar(30) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  dateline int(10) NOT NULL DEFAULT '0',
  effect1 tinyint(1) unsigned NOT NULL DEFAULT '0',
  effect2 tinyint(1) unsigned NOT NULL DEFAULT '0',
  effect3 tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id,idtype,uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_membereffect_total;
CREATE TABLE modoer_membereffect_total (
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  idtype varchar(30) NOT NULL DEFAULT '',
  effect1 int(10) unsigned NOT NULL DEFAULT '0',
  effect2 int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_members;
CREATE TABLE modoer_members (
  uid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  email varchar(60) NOT NULL DEFAULT '',
  password varchar(32) NOT NULL DEFAULT '',
  username varchar(16) NOT NULL DEFAULT '',
  mobile char(11) NOT NULL default '',
  rmb decimal(9,2) unsigned NOT NULL default '0.00',
  point int(10) NOT NULL DEFAULT '0',
  point1 int(10) NOT NULL default '0',
  point2 int(10) NOT NULL default '0',
  point3 int(10) NOT NULL default '0',
  point4 int(10) NOT NULL default '0',
  point5 int(10) NOT NULL default '0',
  point6 int(10) NOT NULL default '0',
  newmsg smallint(5) unsigned NOT NULL DEFAULT '0',
  regdate int(10) unsigned NOT NULL DEFAULT '0',
  regip char(15) NOT NULL default '',
  logintime int(10) unsigned NOT NULL DEFAULT '0',
  loginip varchar(16) NOT NULL DEFAULT '',
  logincount mediumint(8) unsigned NOT NULL DEFAULT '0',
  groupid smallint(2) NOT NULL DEFAULT '1',
  nextgroupid smallint(5) unsigned NOT NULL DEFAULT '0',
  nexttime int(10) unsigned NOT NULL DEFAULT '0',
  subjects int(10) unsigned NOT NULL DEFAULT '0',
  reviews int(10) unsigned NOT NULL DEFAULT '0',
  responds int(10) unsigned NOT NULL DEFAULT '0',
  flowers int(10) unsigned NOT NULL DEFAULT '0',
  pictures int(10) unsigned NOT NULL DEFAULT '0',
  follow int(10) UNSIGNED NOT NULL DEFAULT  '0',
  fans int(10) UNSIGNED NOT NULL DEFAULT  '0',
  PRIMARY KEY (uid),
  UNIQUE KEY username (username),
  KEY email (email),
  KEY groupid (groupid),
  KEY point (point),
  KEY point1 (point1),
  KEY regip (regip,regdate),
  KEY mobile (mobile)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_address;
CREATE TABLE modoer_member_address (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  name varchar(20) NOT NULL DEFAULT '',
  addr tinytext NOT NULL,
  postcode varchar(60) NOT NULL DEFAULT '',
  mobile varchar(60) NOT NULL DEFAULT '',
  is_default tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_member_invite;
CREATE TABLE modoer_member_invite (
  id mediumint(8) NOT NULL auto_increment,
  inviter_uid mediumint(8) NOT NULL default '0',
  inviter char(30) NOT NULL default '',
  invitee_uid mediumint(8) NOT NULL default '0',
  invitee char(30) NOT NULL default '',
  add_point tinyint(1) NOT NULL default '0',
  dateline int(10) NOT NULL default '0',
  PRIMARY KEY (id),
  KEY inviter_uid (inviter_uid,dateline,add_point)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_menus;
CREATE TABLE modoer_menus (
  menuid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  parentid smallint(5) unsigned NOT NULL DEFAULT '0',
  isclosed tinyint(1) NOT NULL DEFAULT '0',
  isfolder tinyint(1) unsigned NOT NULL DEFAULT '0',
  title varchar(100) NOT NULL DEFAULT '',
  scriptnav varchar(60) NOT NULL DEFAULT '',
  url varchar(255) NOT NULL DEFAULT '',
  icon varchar(60) NOT NULL DEFAULT '',
  target varchar(15) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (menuid)
) TYPE=MyISAM;

INSERT INTO modoer_menus VALUES ('1','0','0','1','头部菜单','','','','','1');
INSERT INTO modoer_menus VALUES ('49','1','0','0','首页','index','modoer/index','','_self','1');
INSERT INTO modoer_menus VALUES ('3','0','0','1','后台快捷菜单','','','','','5');
INSERT INTO modoer_menus VALUES ('53','3','0','0','调用管理','','?module=modoer&act=datacall&op=list','','main','3');
INSERT INTO modoer_menus VALUES ('54','3','0','0','更新网站缓存','','?module=modoer&act=tools&op=cache','','main','4');
INSERT INTO modoer_menus VALUES ('62','1','0','0','主题','item_category','item/category','','','4');
INSERT INTO modoer_menus VALUES ('80','0','0','1','个人空间菜单','','','','','4');
INSERT INTO modoer_menus VALUES ('75','3','0','0','菜单管理','','?module=modoer&act=menu','','','5');
INSERT INTO modoer_menus VALUES ('66','0','0','1','底部菜单','','','','','2');
INSERT INTO modoer_menus VALUES ('68','66','0','0','联系我们','','#','','','0');
INSERT INTO modoer_menus VALUES ('69','66','0','0','广告服务','','#','','','0');
INSERT INTO modoer_menus VALUES ('70','66','0','0','服务条款','','#','','','0');
INSERT INTO modoer_menus VALUES ('71','66','0','0','网站地图','','#','','','0');
INSERT INTO modoer_menus VALUES ('72','66','0','0','使用帮助','','#','','','0');
INSERT INTO modoer_menus VALUES ('73','66','0','0','诚聘英才','','#','','','0');
INSERT INTO modoer_menus VALUES ('76','3','0','0','主题审核','','?module=item&act=subject_check','','','1');
INSERT INTO modoer_menus VALUES ('77','3','0','0','点评审核','','?module=review&act=review&op=checklist','','','2');
INSERT INTO modoer_menus VALUES ('81','80','0','0','首页','space_index','space/index/uid/(uid)','','','1');
INSERT INTO modoer_menus VALUES ('82','80','0','0','我发表的点评','space_reviews','space/reviews/uid/(uid)','','','2');
INSERT INTO modoer_menus VALUES ('83','80','0','0','我添加的主题','space_subjects','space/subjects/uid/(uid)','','','3');
INSERT INTO modoer_menus VALUES ('84','80','0','0','我的好友','space_friends','space/friends/uid/(uid)','','','4');
INSERT INTO modoer_menus VALUES ('88','1','0','0','礼品','exchange','exchange/index','','','9');
INSERT INTO modoer_menus VALUES ('90','1','0','0','资讯','article','article/index','','','3');
INSERT INTO modoer_menus VALUES ('93','1','0','0','会员卡','card','card/index','','','11');
INSERT INTO modoer_menus VALUES ('94','1','0','0','优惠券','coupon','coupon/index','','','10');
INSERT INTO modoer_menus VALUES ('95','1','0','0','相册','item_album','item/album','','','12');
INSERT INTO modoer_menus VALUES ('97','1','0','0','点评','review','review/index','','','14');
INSERT INTO modoer_menus VALUES ('98','1','0','0','排行榜','item_subject_tops','item/tops','','','15');
INSERT INTO modoer_menus VALUES ('99','1','0','0','小组','group','group/index','','','16');

DROP TABLE IF EXISTS modoer_mobile_verify;
CREATE TABLE modoer_mobile_verify (
  id mediumint(10) NOT NULL AUTO_INCREMENT ,
  uniq char(16) NOT NULL default '',
  mobile char(20) NOT NULL default '',
  serial char(6) NOT NULL default '',
  status tinyint(1) NOT NULL default '0',
  dateline int(10) NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY uniq (uniq)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_model;
CREATE TABLE modoer_model (
  modelid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(30) NOT NULL DEFAULT '',
  tablename varchar(20) NOT NULL DEFAULT '',
  description varchar(255) NOT NULL DEFAULT '',
  usearea tinyint(1) NOT NULL DEFAULT '0',
  item_name varchar(200) NOT NULL DEFAULT '',
  item_unit varchar(200) NOT NULL DEFAULT '',
  tplname_list varchar(200) NOT NULL DEFAULT '',
  tplname_detail varchar(200) NOT NULL DEFAULT '',
  disable tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (modelid)
) TYPE=MyISAM;

INSERT INTO modoer_model VALUES ('1','商铺模型','subject_shops','','1','商铺','户','item_subject_list','item_subject_detail','0');

DROP TABLE IF EXISTS modoer_modules;
CREATE TABLE modoer_modules (
  moduleid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  flag varchar(30) NOT NULL DEFAULT '',
  extra varchar(20) NOT NULL default '',
  iscore tinyint(1) NOT NULL DEFAULT '0',
  listorder smallint(5) unsigned NOT NULL DEFAULT '0',
  name varchar(60) NOT NULL DEFAULT '',
  directory varchar(100) NOT NULL DEFAULT '',
  disable tinyint(1) unsigned NOT NULL DEFAULT '0',
  config text NOT NULL,
  version varchar(60) NOT NULL DEFAULT '',
  releasetime date NOT NULL DEFAULT '0000-00-00',
  reliant varchar(255) NOT NULL DEFAULT '',
  author varchar(255) NOT NULL DEFAULT '',
  introduce text NOT NULL,
  siteurl varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  copyright varchar(255) NOT NULL DEFAULT '',
  checkurl varchar(255) NOT NULL DEFAULT '',
  liccode text NOT NULL,
  PRIMARY KEY (moduleid)
) TYPE=MyISAM;

INSERT INTO modoer_modules VALUES ('1','member','','1','8','会员','member','0','','1.1','2008-09-30','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('2','item','','1','1','主题','item','0','','2.5','2011-05-24','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('3','space','','1','9','个人空间','space','0','','1.1','2008-09-30','','Moufer Studio','','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('4','link','','0','10','友情链接','link','0','','2.0','2010-05-04','','moufer','友情链接模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php','');
INSERT INTO modoer_modules VALUES ('6','comment','','0','6','会员评论','comment','0','','1.0','2010-04-01','','moufer','评论模块可用于其他需要进行评论的模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php','');
INSERT INTO modoer_modules VALUES ('7','exchange','','0','5','礼品兑换','exchange','0','','3.0','2012-05-01','','moufer,轩','使用网站金币兑换礼品，消费金币','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/exchange.php','');
INSERT INTO modoer_modules VALUES ('8','article','','0','3','新闻资讯','article','0','','2.0','2010-04-14','','moufer','文章信息，发布网站信息和主题资讯','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/article.php','');
INSERT INTO modoer_modules VALUES ('9','card','','0','7','会员卡','card','0','','2.0','2010-05-06','item','moufer','会员卡模块用户管理消费类主题提供优惠折扣信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/card.php','');
INSERT INTO modoer_modules VALUES ('10','coupon','','0','4','优惠券','coupon','0','','2.0','2010-05-10','','moufer','优惠券模块，提供分享和打印折扣和优惠信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/coupon.php','');
INSERT INTO modoer_modules VALUES ('11','adv','','0','10','广告','adv','0','','2.0','2010-12-13','','moufer','自定义广告模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/adv.php','');
INSERT INTO modoer_modules VALUES ('12','review','','1','2','点评','review','0','','2.5','2011-05-24','','Moufer Studio','点评模块','http://www.modoer.com','moufer@163.com','Moufer Studio','','');
INSERT INTO modoer_modules VALUES ('13','sms','','0','10','短信发送','sms','0','','1.0','2011-12-06','','moufer','短信发送模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/sms.php','');
INSERT INTO modoer_modules VALUES ('14','pay','','0','10','在线充值','pay','0','','2.2','2012-03-30','','moufer','在线积分充值模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/pay.php','');
INSERT INTO modoer_modules VALUES ('15','group','','0','10','小组','group','0','','1.0','2013-5-28','','moufer','网站会员小组讨论模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/group.php','');
INSERT INTO modoer_modules VALUES ('16','mobile','','0','11','手机浏览','mobile','0','','1.0','2012-10-19','','moufer','基于HTML5的手机浏览模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/mobile.php','');

DROP TABLE IF EXISTS modoer_mylinks;
CREATE TABLE modoer_mylinks (
  linkid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(40) NOT NULL DEFAULT '',
  link varchar(100) NOT NULL DEFAULT '',
  logo varchar(100) NOT NULL DEFAULT '',
  des varchar(255) NOT NULL DEFAULT '',
  displayorder int(8) NOT NULL DEFAULT '0',
  ischeck tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (linkid)
) TYPE=MyISAM;

INSERT INTO modoer_mylinks VALUES ('1','Modoer点评系统','http://www.modoer.com','','Modoer 是一款点评网站管理系统，采用 PHP+MYSQL 设计，开放全部源码','1','1');

DROP TABLE IF EXISTS modoer_mysubject;
CREATE TABLE modoer_mysubject (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  modelid smallint(5) NOT NULL DEFAULT '0',
  expirydate int(10) unsigned NOT NULL default '0',
  lasttime int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (id),
  KEY `uid` (`uid`),
  KEY `sid` (`sid`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_mytask;
CREATE TABLE modoer_mytask (
  uid mediumint(8) unsigned NOT NULL,
  taskid mediumint(8) unsigned NOT NULL default '0',
  username varchar(30) NOT NULL default '',
  progress smallint(3) unsigned NOT NULL default '0',
  dateline int(10) NOT NULL default '0',
  applytime int(10) unsigned NOT NULL default '0',
  total smallint(5) unsigned NOT NULL default '0',
  status tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (uid,taskid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_notice;
CREATE TABLE modoer_notice (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  module varchar(15) NOT NULL default '',
  type varchar(15) NOT NULL default '',
  isread tinyint(1) NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL default '0',
  note text NOT NULL,
  PRIMARY KEY (id),
  KEY uid (uid,isread,dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_pay;
CREATE TABLE modoer_pay (
  payid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  order_flag varchar(30) NOT NULL DEFAULT '',
  orderid int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  order_name varchar(255) NOT NULL DEFAULT '',
  payment_orderid varchar(60) NOT NULL DEFAULT '',
  payment_name varchar(60) NOT NULL DEFAULT '',
  creation_time int(10) NOT NULL DEFAULT '0',
  pay_time int(10) unsigned NOT NULL DEFAULT '0',
  price decimal(9,2) NOT NULL DEFAULT '0.00',
  pay_status tinyint(1) NOT NULL DEFAULT '0',
  my_status tinyint(1) NOT NULL DEFAULT '0',
  notify_url varchar(255) NOT NULL DEFAULT '',
  callback_url varchar(255) NOT NULL DEFAULT '',
  royalty TINYTEXT NOT NULL DEFAULT '',
  goods TINYTEXT NOT NULL DEFAULT '',
  PRIMARY KEY (payid),
  KEY order_flag (order_flag,orderid)
) TYPE=MYISAM;

DROP TABLE IF EXISTS modoer_pay_card;
CREATE TABLE modoer_pay_card (
  cardid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  number varchar(30) NOT NULL DEFAULT '',
  password varchar(30) NOT NULL DEFAULT '',
  cztype VARCHAR( 15 ) default '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  endtime int(10) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  price int(10) unsigned NOT NULL DEFAULT '0',
  usetime int(10) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (cardid),
  UNIQUE KEY number (number),
  KEY status (status,endtime),
  KEY uid (uid)
) TYPE=MYISAM;

DROP TABLE IF EXISTS modoer_pay_log;
CREATE TABLE modoer_pay_log (
  orderid int(10) unsigned NOT NULL AUTO_INCREMENT,
  port_orderid varchar(60) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  point int(10) unsigned NOT NULL DEFAULT '0',
  cztype VARCHAR( 15 ) default '',
  dateline int(10) NOT NULL DEFAULT '0',
  exchangetime int(10) NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  retcode varchar(10) NOT NULL DEFAULT '',
  ip varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (orderid)
) TYPE=MyISAM;


DROP TABLE IF EXISTS modoer_pay_withdraw;
CREATE TABLE modoer_pay_withdraw (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  price decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  charges decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  realname varchar(255) NOT NULL DEFAULT '',
  accounts varchar(255) NOT NULL DEFAULT '',
  pointtype char(6) NOT NULL DEFAULT '',
  applytime int(10) unsigned NOT NULL DEFAULT '0',
  ip varchar(16) NOT NULL default '',
  `status` enum('wait','succeed','fail','cancel') NOT NULL DEFAULT 'wait',
  status_des varchar(255) NOT NULL DEFAULT '',
  optime int(10) unsigned NOT NULL DEFAULT '0',
  opowner varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_pictures;
CREATE TABLE modoer_pictures (
  picid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  albumid mediumint(8) unsigned NOT NULL default '0',
  city_id smallint(5) unsigned NOT NULL default '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(16) NOT NULL DEFAULT '',
  title varchar(60) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  filename varchar(255) NOT NULL DEFAULT '',
  width smallint(5) unsigned NOT NULL DEFAULT '0',
  height smallint(5) unsigned NOT NULL DEFAULT '0',
  size int(10) unsigned NOT NULL DEFAULT '0',
  comments varchar(60) NOT NULL DEFAULT '',
  url varchar(255) NOT NULL default '',
  sort tinyint(1) NOT NULL DEFAULT '0',
  browse int(10) NOT NULL DEFAULT '0',
  addtime int(10) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (picid),
  KEY uid (uid,sid),
  KEY sid (sid,status),
  KEY city_id (city_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_pmsgs;
CREATE TABLE modoer_pmsgs (
  pmid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  senduid mediumint(8) unsigned NOT NULL DEFAULT '0',
  recvuid mediumint(8) unsigned NOT NULL DEFAULT '0',
  content mediumtext NOT NULL,
  subject varchar(60) NOT NULL DEFAULT '',
  posttime int(10) NOT NULL DEFAULT '0',
  new tinyint(1) NOT NULL DEFAULT '1',
  delflag tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (pmid),
  KEY senduid (senduid,posttime),
  KEY recvuid (recvuid,posttime),
  KEY new (new,recvuid,posttime)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_point_log;
CREATE TABLE modoer_point_log (
  id mediumint(8) unsigned NOT NULL auto_increment,
  uid mediumint(8) unsigned NOT NULL default '0',
  username varchar(30) NOT NULL default '',
  flag varchar(20) NOT NULL default '',
  point_flow enum('in','out') NOT NULL,
  point_type enum('point','rmb','point1','point2','point3','point4','point5','point6') NOT NULL default 'point',
  point_value decimal(9,2) unsigned NOT NULL default '0.00',
  amount decimal(9,2) NOT NULL default '0.00',
  dateline int(10) unsigned NOT NULL default '0',
  remark varchar(255) NOT NULL default '',
  extra varchar(30) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY uid (uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_reports;
CREATE TABLE modoer_reports (
  reportid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  rid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(16) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  sort tinyint(1) NOT NULL DEFAULT '0',
  reportcontent mediumtext NOT NULL,
  disposal tinyint(1) NOT NULL DEFAULT '0',
  posttime int(10) NOT NULL DEFAULT '0',
  disposaltime int(10) NOT NULL DEFAULT '0',
  reportremark mediumtext NOT NULL,
  update_point tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (reportid),
  KEY disposal (disposal),
  KEY rid (rid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_responds;
CREATE TABLE modoer_responds (
  respondid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  rid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  content text NOT NULL,
  posttime int(10) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '1',
  ip varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (respondid),
  KEY uid (uid,status),
  KEY reviewid (rid,status)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_review;
CREATE TABLE modoer_review (
  rid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  idtype varchar(30) NOT NULL default '',
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  city_id smallint(5) unsigned NOT NULL default '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  pcatid smallint(5) unsigned NOT NULL DEFAULT '0',
  sort1 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort2 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort3 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort4 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort5 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort6 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort7 tinyint(1) unsigned NOT NULL DEFAULT '0',
  sort8 tinyint(1) unsigned NOT NULL DEFAULT '0',
  price int(10) unsigned NOT NULL DEFAULT '0',
  best tinyint(1) unsigned NOT NULL DEFAULT '0',
  digest tinyint(1) NOT NULL default '0',
  havepic tinyint(1) NOT NULL default '0',
  posttime int(10) NOT NULL DEFAULT '0',
  isupdate tinyint(1) NOT NULL DEFAULT '0',
  flowers int(8) unsigned NOT NULL DEFAULT '0',
  responds int(8) unsigned NOT NULL DEFAULT '0',
  ip varchar(15) NOT NULL DEFAULT '',
  subject varchar(255) NOT NULL default '',
  title varchar(60) NOT NULL DEFAULT '',
  content text NOT NULL,
  taggroup text NOT NULL,
  pictures text NOT NULL,
  PRIMARY KEY (rid),
  KEY sid (id,status),
  KEY uid (uid,status),
  KEY city_id (city_id,status)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_review_opt;
CREATE TABLE modoer_review_opt (
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  gid smallint(5) unsigned NOT NULL default '0',
  flag varchar(10) NOT NULL DEFAULT '',
  name varchar(50) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  enable tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (id)
) TYPE=MyISAM;

INSERT INTO modoer_review_opt VALUES ('1','1','sort1','口味','1','1');
INSERT INTO modoer_review_opt VALUES ('2','1','sort2','服务','2','1');
INSERT INTO modoer_review_opt VALUES ('3','1','sort3','环境','3','1');
INSERT INTO modoer_review_opt VALUES ('4','1','sort4','性价比','4','1');
INSERT INTO modoer_review_opt VALUES ('5','1','sort5','R5','5','0');
INSERT INTO modoer_review_opt VALUES ('6','1','sort6','R6','6','0');
INSERT INTO modoer_review_opt VALUES ('7','1','sort7','R7','7','0');
INSERT INTO modoer_review_opt VALUES ('8','1','sort8','R8','8','0');

DROP TABLE IF EXISTS modoer_review_opt_group;
CREATE TABLE modoer_review_opt_group (
	gid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(60) NOT NULL default '',
	des varchar(255) NOT NULL default '',
	PRIMARY KEY (gid)
) TYPE=MyISAM;

INSERT INTO modoer_review_opt_group VALUES ('1','默认点评项组','系统默认安装');

DROP TABLE IF EXISTS modoer_search_cache;
CREATE TABLE modoer_search_cache (
  ssid mediumint(8) NOT NULL AUTO_INCREMENT,
  keyword varchar(60) NOT NULL DEFAULT '0',
  count mediumint(8) NOT NULL DEFAULT '0',
  total mediumint(8) NOT NULL DEFAULT '0',
  catid smallint(5) NOT NULL DEFAULT '0',
  city_id varchar(10) NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (ssid),
  KEY catid (catid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_spaces;
CREATE TABLE modoer_spaces (
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  space_styleid smallint(3) NOT NULL DEFAULT '0',
  spacename varchar(30) NOT NULL DEFAULT '',
  spacedescribe varchar(50) NOT NULL DEFAULT '',
  pageview int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (uid),
  KEY pageviews (pageview)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subject;
CREATE TABLE modoer_subject (
  sid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  domain char(50) NOT NULL DEFAULT '',
  city_id smallint(5) unsigned NOT NULL default '0',
  aid smallint(5) unsigned NOT NULL DEFAULT '0',
  pid smallint(5) unsigned NOT NULL DEFAULT '0',
  catid smallint(5) unsigned NOT NULL DEFAULT '0',
  sub_catids varchar(255) NOT NULL default '',
  minor_catids varchar(255) NOT NULL default '',
  name varchar(60) NOT NULL DEFAULT '',
  subname varchar(60) NOT NULL DEFAULT '',
  avgsort decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort1 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort2 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort3 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort4 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort5 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort6 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort7 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  sort8 decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  avgprice int(10) unsigned NOT NULL DEFAULT '0',
  best int(10) unsigned NOT NULL DEFAULT '0',
  reviews int(10) unsigned NOT NULL DEFAULT '0',
  guestbooks int(10) unsigned NOT NULL DEFAULT '0',
  pictures int(10) unsigned NOT NULL DEFAULT '0',
  pageviews int(10) unsigned NOT NULL DEFAULT '1',
  products mediumint(8) unsigned NOT NULL DEFAULT '0',
  coupons mediumint(8) unsigned NOT NULL DEFAULT '0',
  favorites mediumint(8) unsigned NOT NULL default '0',
  finer tinyint(3) unsigned NOT NULL DEFAULT '0',
  level tinyint(3) unsigned NOT NULL DEFAULT '0',
  owner varchar(20) NOT NULL DEFAULT '',
  cuid mediumint(8) unsigned NOT NULL DEFAULT '0',
  creator varchar(20) NOT NULL DEFAULT '',
  addtime int(10) unsigned NOT NULL DEFAULT '0',
  video varchar(255) NOT NULL DEFAULT '',
  thumb varchar(255) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '1',
  map_lng decimal(8,5) NOT NULL DEFAULT '0.00000',
  map_lat decimal(8,5) NOT NULL DEFAULT '0.00000',
  description varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (sid),
  KEY name (name),
  KEY doamin (domain),
  KEY catid (catid,city_id,addtime),
  KEY catid_2 (catid),
  KEY aid (pid,aid),
  KEY pid (pid,city_id,addtime),
  KEY addtime (`status`,addtime),
  KEY list_aid (`pid`,`aid`,`status`,`finer`),
  KEY list_city (`pid`,`city_id`,`status`,`finer`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subject_shops;
CREATE TABLE modoer_subject_shops (
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  content mediumtext NOT NULL,
  templateid smallint(5) unsigned NOT NULL DEFAULT '0',
  forumid smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectapply;
CREATE TABLE modoer_subjectapply (
  applyid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(20) NOT NULL DEFAULT '',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  applyname varchar(100) NOT NULL DEFAULT '',
  contact varchar(255) NOT NULL DEFAULT '',
  pic varchar(255) NOT NULL DEFAULT '',
  content mediumtext NOT NULL,
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  checker varchar(30) NOT NULL DEFAULT '',
  returned text NOT NULL,
  PRIMARY KEY (applyid),
  KEY sid (sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectatt;
CREATE TABLE modoer_subjectatt (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	sid mediumint(8) unsigned NOT NULL default '0',
	attid mediumint(8) unsigned NOT NULL default '0',
  type varchar(20) NOT NULL default '',
	att_catid mediumint(8) unsigned NOT NULL default '0',
	PRIMARY KEY  (id),
	KEY sid (sid,attid),
  KEY attid (attid,sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectfield;
CREATE TABLE modoer_subjectfield (
  fieldid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  modelid smallint(5) NOT NULL  DEFAULT '0',
  tablename varchar(25) NOT NULL DEFAULT '',
  fieldname varchar(50) NOT NULL DEFAULT '',
  title varchar(100) NOT NULL DEFAULT '',
  unit varchar(100) NOT NULL DEFAULT '',
  style varchar(255) NOT NULL DEFAULT '',
  template text NOT NULL,
  note mediumtext NOT NULL,
  type varchar(20) NOT NULL DEFAULT '',
  listorder smallint(5) NOT NULL DEFAULT '0',
  allownull tinyint(1) unsigned NOT NULL DEFAULT '1',
  enablesearch tinyint(1) unsigned NOT NULL DEFAULT '0',
  iscore tinyint(1) NOT NULL DEFAULT '0',
  isadminfield varchar(1) NOT NULL DEFAULT '0',
  show_list tinyint(1) unsigned NOT NULL DEFAULT '0',
  show_detail tinyint(1) unsigned NOT NULL DEFAULT '1',
  show_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  regular varchar(255) NOT NULL DEFAULT '',
  errmsg varchar(255) NOT NULL DEFAULT '',
  datatype varchar(60) NOT NULL DEFAULT '',
  config text NOT NULL,
  disable tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (fieldid),
  KEY tablename (tablename)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectgourd;
CREATE TABLE modoer_subjectgourd (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(30) NOT NULL DEFAULT '',
  status enum('grow','harvest','finish') NOT NULL DEFAULT 'grow',
  progress tinyint(3) unsigned NOT NULL DEFAULT '0',
  total smallint(5) unsigned NOT NULL DEFAULT '0',
  num smallint(5) unsigned NOT NULL DEFAULT '0',
  createtime int(10) unsigned NOT NULL DEFAULT '0',
  harvesttime int(10) unsigned NOT NULL DEFAULT '0',
  finishtime int(10) unsigned NOT NULL DEFAULT '0',
  users text NOT NULL,
  PRIMARY KEY (id),
  KEY sid (sid,status)
) TYPE=MyISAM;

INSERT INTO modoer_subjectfield VALUES ('1','1','subject','aid','地区','','','','','area','6','0','1','2','0','0','1','0','/[0-9]+/','未选择地区','varchar(6)','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('2','1','subject','catid','分类','','','','','category','1','0','1','2','0','0','1','0','/[0-9]+/','未选择分类','smallint(5)','a:1:{s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('3','1','subject','name','名称','','','','','text','2','0','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('4','1','subject','subname','子名称','','','','','text','3','1','1','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:80;s:7:\"default\";s:0:\"\";s:4:\"size\";i:20;}','0');
INSERT INTO modoer_subjectfield VALUES ('5','1','subject','mappoint','地图坐标','','','','','mappoint','4','0','0','1','0','0','1','0','/[0-9a-z]+,[0-9a-z]+/i','地图坐标不正确','varchar(60)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('6','1','subject','video','视频地址','','','','','video','5','1','0','1','0','1','1','0','','','varchar(255)','a:2:{s:7:\"default\";s:0:\"\";s:4:\"size\";s:2:\"30\";}','0');
INSERT INTO modoer_subjectfield VALUES ('8','1','subject','description','简介','','','','','text','7','1','0','1','0','1','1','1','','','VARCHAR(255)','a:3:{s:3:\"len\";i:255;s:7:\"default\";s:0:\"\";s:4:\"size\";i:60;}','0');
INSERT INTO modoer_subjectfield VALUES ('10','1','subject','level','等级','','','','','level','92','0','1','1','1','0','1','0','/[0-9]+/','未选择点评对象等级','tinyint(3)','a:1:{s:7:\"default\";i:0;}','0');
INSERT INTO modoer_subjectfield VALUES ('11','1','subject','finer','推荐度','','','','','numeric','91','1','0','1','1','0','0','0','','','SMALLINT(5)','a:4:{s:3:\"min\";i:0;s:3:\"max\";i:255;s:5:\"point\";s:1:\"0\";s:7:\"default\";s:1:\"0\";}','0');
INSERT INTO modoer_subjectfield VALUES ('12','1','subject_shops','content','详细介绍','','','','','textarea','90','0','0','1','0','0','1','0','','','MEDIUMTEXT','a:6:{s:5:\"width\";s:3:\"99%\";s:6:\"height\";s:5:\"200px\";s:4:\"html\";s:1:\"2\";s:7:\"default\";s:0:\"\";s:11:\"charnum_sup\";i:0;s:11:\"charnum_sub\";i:1000;}','0');

DROP TABLE IF EXISTS modoer_subjectimpress;
CREATE TABLE modoer_subjectimpress (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL,
  title varchar(20) NOT NULL,
  total int(8) unsigned NOT NULL,
  dateline int(10) unsigned NOT NULL,
  PRIMARY KEY  (id),
  KEY sid (sid,total)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectlink;
CREATE TABLE modoer_subjectlink (
	id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	flagid mediumint(8) unsigned NOT NULL default '0',
	flag varchar(30) NOT NULL default '',
	sid int(10) unsigned NOT NULL default '0',
	modelid smallint(5) unsigned NOT NULL default '0',
	dateline int(10) unsigned NOT NULL default '0',
	PRIMARY KEY  (id),
	KEY flagid (flagid,flag),
	KEY sid (sid,flag,dateline)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectlog;
CREATE TABLE modoer_subjectlog (
  upid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(16) NOT NULL DEFAULT '',
  email varchar(60) NOT NULL DEFAULT '',
  ismappoint tinyint(1) unsigned NOT NULL DEFAULT '0',
  upcontent mediumtext NOT NULL,
  disposal tinyint(1) NOT NULL DEFAULT '0',
  posttime int(10) NOT NULL DEFAULT '0',
  upremark mediumtext NOT NULL,
  disposaltime int(10) NOT NULL DEFAULT '0',
  update_point tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (upid),
  KEY sid (sid),
  KEY disposal (disposal)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectrelated;
CREATE TABLE modoer_subjectrelated (
  related_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  fieldid smallint(5) unsigned NOT NULL DEFAULT '0',
  modelid smallint(5) unsigned NOT NULL DEFAULT '0',
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  r_sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (related_id),
  KEY fieldid (fieldid,sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectsetting;
CREATE TABLE modoer_subjectsetting (
  sid mediumint(8) NOT NULL default '0',
  variable char(20) NOT NULL default '',
  value text NOT NULL,
  UNIQUE KEY sid (sid,variable)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjectstyle;
CREATE TABLE modoer_subjectstyle (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL default '0',
  templateid smallint(5) NOT NULL default '0',
  buytime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  status tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_subjecttaoke;
CREATE TABLE modoer_subjecttaoke (
  user_id int(10) unsigned NOT NULL,
  sid mediumint(8) unsigned NOT NULL default '0',
  nick varchar(60) NOT NULL default '',
  PRIMARY KEY  (user_id),
  KEY sid (sid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_tag_data;
CREATE TABLE modoer_tag_data (
  stid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  tagid int(10) unsigned NOT NULL DEFAULT '0',
  tgid smallint(5) unsigned NOT NULL DEFAULT '0',
  id mediumint(8) unsigned NOT NULL DEFAULT '0',
  tagname varchar(25) NOT NULL DEFAULT '',
  total int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (stid),
  KEY tagid (tagid),
  KEY id (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_taggroup;
CREATE TABLE modoer_taggroup (
  tgid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(30) NOT NULL DEFAULT '',
  des varchar(200) NOT NULL DEFAULT '',
  sort tinyint(1) unsigned NOT NULL DEFAULT '0',
  options text NOT NULL,
  PRIMARY KEY (tgid)
) TYPE=MyISAM;

INSERT INTO modoer_taggroup VALUES ('1','点评标签','商铺标签说明','1','');

DROP TABLE IF EXISTS modoer_tags;
CREATE TABLE  modoer_tags (
  tagid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  city_id smallint(5) unsigned NOT NULL default '0',
  tagname varchar(20) NOT NULL DEFAULT '',
  closed tinyint(1) NOT NULL DEFAULT '0',
  total int(10) unsigned NOT NULL DEFAULT '0',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (tagid),
  KEY total (total),
  KEY closed (closed),
  KEY tagname (tagname)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_task;
CREATE TABLE modoer_task (
  taskid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  enable tinyint(1) unsigned NOT NULL default '1',
  taskflag varchar(30) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  des text NOT NULL,
  icon varchar(30) NOT NULL default '',
  starttime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  period smallint(5) unsigned NOT NULL default '0',
  period_unit tinyint(1) unsigned NOT NULL default '0',
  pointtype varchar(30) NOT NULL default '',
  point int(10) unsigned NOT NULL default '0',
  access tinyint(1) unsigned NOT NULL default '0',
  access_groupids varchar(255) NOT NULL default '',
  applys int(10) unsigned NOT NULL default '0',
  completes int(10) unsigned NOT NULL default '0',
  listorder smallint(5) unsigned NOT NULL default '0',
  reg_automatic tinyint(1) unsigned NOT NULL DEFAULT '0',
  config text NOT NULL,
  PRIMARY KEY  (taskid)
) TYPE=MyISAM;

INSERT INTO modoer_task VALUES ('1','1','member:avatar','上传头像','注册用户上传一个自己的头像，即可获得积分奖励，赶快来参与吧！','','1315075860','0','0','0','point1','10','0','','0','0','0','0','');
INSERT INTO modoer_task VALUES ('2','1','item:favorite','关注主题','浏览主题，关注自己喜欢和关注的主题，从申请任务起，累计关注 3 个主题，即可获得积分奖励。','','1315075920','0','0','0','point1','6','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');
INSERT INTO modoer_task VALUES ('3','1','review:flower','赠送鲜花','给你认为非常棒的点评信息赠送 3 朵鲜花，就可以获得积分奖励，本任务每周都可以重复申请一次。','','1315075980','0','1','2','point1','5','0','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"3\";s:10:\"time_limit\";s:0:\"\";}');
INSERT INTO modoer_task VALUES ('4','1','review:post','添加主题点评','申请本任务后，选择一个主题，发表自己对这些主题的点评信息，发表 1 篇，即可获得积分奖励，可重复申请。','','1315076040','0','1','1','point1','5','1','','0','0','0','0','a:2:{s:3:\"num\";s:1:\"1\";s:10:\"time_limit\";s:0:\"\";}');

DROP TABLE IF EXISTS modoer_tasktype;
CREATE TABLE modoer_tasktype (
  ttid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  flag varchar(30) NOT NULL,
  title varchar(255) NOT NULL default '',
  copyright varchar(255) NOT NULL default '',
  version varchar(10) NOT NULL default '',
  PRIMARY KEY  (ttid)
) TYPE=MyISAM;

INSERT INTO modoer_tasktype VALUES ('1', 'review:post', '点评类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('2', 'review:flower', '鲜花类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('3', 'member:avatar', '头像类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('4', 'item:subject', '主题类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('5', 'item:picture', '图片类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('6', 'item:favorite', '关注类任务', 'MouferStudio', '1.0');
INSERT INTO modoer_tasktype VALUES ('7', 'article:post', '文章类任务', 'MouferStudio', '1.0');

DROP TABLE IF EXISTS modoer_templates;
CREATE TABLE modoer_templates (
  templateid smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(20) NOT NULL DEFAULT '',
  directory varchar(100) NOT NULL DEFAULT '',
  copyright varchar(45) NOT NULL DEFAULT '',
  tpltype varchar(15) NOT NULL DEFAULT '',
  bind tinyint(1) NOT NULL DEFAULT '0',
  price int(10) NOT NULL default '0',
  pointtype enum('point1','point2','point3','point4','point5','point6','point7','point8') NOT NULL default 'point1',
  PRIMARY KEY (templateid)
) TYPE=MyISAM;

INSERT INTO modoer_templates VALUES ('1','默认模板','default','Moufer Studio','main','1','0','point1');
INSERT INTO modoer_templates VALUES ('2','商铺风格1','store_1','','item','0','10','point1');

DROP TABLE IF EXISTS modoer_travelers;
CREATE TABLE modoer_travelers (
  tid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  tuid mediumint(8) unsigned NOT NULL DEFAULT '0',
  tusername varchar(16) NOT NULL DEFAULT '',
  addtime int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (tid),
  KEY uid (uid,addtime),
  KEY tuid (tuid,addtime)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_usergroups;
CREATE TABLE modoer_usergroups (
  groupid smallint(6) NOT NULL AUTO_INCREMENT,
  grouptype enum('member','special','system') DEFAULT 'member',
  groupname char(16) DEFAULT NULL DEFAULT '',
  point int(10) NOT NULL DEFAULT '0',
  color varchar(7) NOT NULL DEFAULT '',
  price mediumint(8) unsigned not null default '0',
  access text NOT NULL,
  PRIMARY KEY (groupid)
) TYPE=MyISAM;

INSERT INTO modoer_usergroups VALUES ('1','system','游客','0','#808080','0','a:20:{s:16:\"member_forbidden\";s:1:\"0\";s:14:\"tuan_post_wish\";s:1:\"0\";s:23:\"item_allow_edit_subject\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:12:\"article_post\";s:1:\"0\";s:14:\"article_delete\";s:1:\"0\";s:12:\"coupon_print\";s:1:\"0\";s:16:\"exchange_disable\";s:1:\"0\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"1\";s:10:\"card_apply\";s:1:\"0\";s:8:\"ask_post\";s:1:\"0\";s:10:\"ask_delete\";s:1:\"0\";s:10:\"ask_editor\";s:1:\"0\";}');
INSERT INTO modoer_usergroups VALUES ('2','system','禁止访问','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"1\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('3','system','禁止发言','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('4','system','等待验证','0','#0bbfb9','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('10','member','注册会员','0','','0','a:19:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"10\";s:17:\"item_create_album\";s:1:\"1\";s:14:\"tuan_post_wish\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('12','member','青铜会员','100','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('13','member','白银会员','500','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"30\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"0\";}');
INSERT INTO modoer_usergroups VALUES ('14','member','黄金会员','1000','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:0:\"\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');
INSERT INTO modoer_usergroups VALUES ('15','special','VIP会员','0','#FF0000','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:3:\"150\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');

DROP TABLE IF EXISTS modoer_visitor;
CREATE TABLE modoer_visitor (
	vid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	sid mediumint(8) NOT NULL default '0',
	uid mediumint(8) NOT NULL default '0',
	username varchar(30) NOT NULL default '',
	dateline int(10) NOT NULL default '0',
	total int(10) NOT NULL default '0',
	PRIMARY KEY  (vid),
	KEY sid (sid,uid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS modoer_words;
CREATE TABLE modoer_words (
  id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  keyword varchar(255) NOT NULL DEFAULT '',
  expression varchar(255) NOT NULL DEFAULT '',
  admin varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) TYPE=MyISAM;


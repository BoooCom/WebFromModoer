<?php
!defined('IN_MUDDER') && exit('Access Denied');
return array (
  111 => 
  array (
    'fieldid' => '111',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'catid',
    'title' => '分类',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'category',
    'listorder' => '1',
    'allownull' => '0',
    'enablesearch' => '1',
    'iscore' => '2',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '/[0-9]+/',
    'errmsg' => '未选择分类',
    'datatype' => 'smallint(5)',
    'config' => 
    array (
      'default' => '0',
    ),
    'disable' => '0',
  ),
  112 => 
  array (
    'fieldid' => '112',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'name',
    'title' => '中文名',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'text',
    'listorder' => '2',
    'allownull' => '0',
    'enablesearch' => '1',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'VARCHAR(255)',
    'config' => 
    array (
      'len' => 80,
      'default' => '',
      'size' => 20,
    ),
    'disable' => '0',
  ),
  113 => 
  array (
    'fieldid' => '113',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'subname',
    'title' => '英文名',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'text',
    'listorder' => '3',
    'allownull' => '1',
    'enablesearch' => '1',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'VARCHAR(255)',
    'config' => 
    array (
      'len' => 80,
      'default' => '',
      'size' => 20,
    ),
    'disable' => '0',
  ),
  110 => 
  array (
    'fieldid' => '110',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'aid',
    'title' => '地区',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'area',
    'listorder' => '5',
    'allownull' => '0',
    'enablesearch' => '1',
    'iscore' => '2',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '/[0-9]+/',
    'errmsg' => '未选择地区',
    'datatype' => 'MEDIUMINT(8) UNSIGNED',
    'config' => 
    array (
      'default' => '0',
    ),
    'disable' => '0',
  ),
  116 => 
  array (
    'fieldid' => '116',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'description',
    'title' => '地址',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'text',
    'listorder' => '7',
    'allownull' => '1',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'VARCHAR(255)',
    'config' => 
    array (
      'len' => 255,
      'default' => '',
      'size' => 60,
    ),
    'disable' => '0',
  ),
  114 => 
  array (
    'fieldid' => '114',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'mappoint',
    'title' => '地图坐标',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'mappoint',
    'listorder' => '8',
    'allownull' => '1',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '/[\\-\\.0-9a-z]+,[\\-\\.0-9a-z]+/i',
    'errmsg' => '地图坐标不正确',
    'datatype' => 'varchar(255)',
    'config' => 
    array (
      'default' => '',
      'size' => '30',
    ),
    'disable' => '0',
  ),
  119 => 
  array (
    'fieldid' => '119',
    'modelid' => '8',
    'tablename' => 'subject_shop',
    'fieldname' => 'content',
    'title' => '详细介绍',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'textarea',
    'listorder' => '55',
    'allownull' => '0',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'MEDIUMTEXT',
    'config' => 
    array (
      'width' => '99%',
      'height' => '200px',
      'html' => '2',
      'default' => '',
      'upimage' => '1',
      'charnum_sup' => 0,
      'charnum_sub' => 0,
    ),
    'disable' => '0',
  ),
  115 => 
  array (
    'fieldid' => '115',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'video',
    'title' => '视频地址',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'video',
    'listorder' => '90',
    'allownull' => '1',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '1',
    'show_list' => '0',
    'show_detail' => '0',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => '',
    'config' => '',
    'disable' => '0',
  ),
  118 => 
  array (
    'fieldid' => '118',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'finer',
    'title' => '推荐度',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'numeric',
    'listorder' => '91',
    'allownull' => '1',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '1',
    'show_list' => '0',
    'show_detail' => '0',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'INT(10)',
    'config' => 
    array (
      'min' => 0,
      'max' => 255,
      'point' => '0',
      'default' => '0',
    ),
    'disable' => '0',
  ),
  117 => 
  array (
    'fieldid' => '117',
    'modelid' => '8',
    'tablename' => 'subject',
    'fieldname' => 'level',
    'title' => '等级',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'level',
    'listorder' => '92',
    'allownull' => '1',
    'enablesearch' => '1',
    'iscore' => '1',
    'isadminfield' => '1',
    'show_list' => '0',
    'show_detail' => '0',
    'show_side' => '0',
    'regular' => '/[0-9]+/',
    'errmsg' => '未选择点评对象等级',
    'datatype' => 'tinyint(3)',
    'config' => 
    array (
      'default' => 0,
    ),
    'disable' => '0',
  ),
); 
?>
<?php
!defined('IN_MUDDER') && exit('Access Denied');
return array (
  120 => 
  array (
    'fieldid' => '120',
    'modelid' => '9',
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
  121 => 
  array (
    'fieldid' => '121',
    'modelid' => '9',
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
    'show_list' => '1',
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
  122 => 
  array (
    'fieldid' => '122',
    'modelid' => '9',
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
  124 => 
  array (
    'fieldid' => '124',
    'modelid' => '9',
    'tablename' => 'subject',
    'fieldname' => 'description',
    'title' => '联系电话',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'text',
    'listorder' => '7',
    'allownull' => '0',
    'enablesearch' => '0',
    'iscore' => '1',
    'isadminfield' => '0',
    'show_list' => '1',
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
  128 => 
  array (
    'fieldid' => '128',
    'modelid' => '9',
    'tablename' => 'subject_driver',
    'fieldname' => 'c_driver_price',
    'title' => '价格',
    'unit' => '美元',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'numeric',
    'listorder' => '9',
    'allownull' => '1',
    'enablesearch' => '0',
    'iscore' => '0',
    'isadminfield' => '0',
    'show_list' => '0',
    'show_detail' => '1',
    'show_side' => '0',
    'regular' => '',
    'errmsg' => '',
    'datatype' => 'INT(10)',
    'config' => 
    array (
      'min' => 0,
      'max' => 10000,
      'point' => '0',
      'default' => '',
    ),
    'disable' => '0',
  ),
  127 => 
  array (
    'fieldid' => '127',
    'modelid' => '9',
    'tablename' => 'subject_driver',
    'fieldname' => 'content',
    'title' => '详细介绍',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'textarea',
    'listorder' => '90',
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
  126 => 
  array (
    'fieldid' => '126',
    'modelid' => '9',
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
  125 => 
  array (
    'fieldid' => '125',
    'modelid' => '9',
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
  123 => 
  array (
    'fieldid' => '123',
    'modelid' => '9',
    'tablename' => 'subject',
    'fieldname' => 'video',
    'title' => '视频地址',
    'unit' => '',
    'style' => '',
    'template' => '',
    'note' => '',
    'type' => 'video',
    'listorder' => '99',
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
); 
?>
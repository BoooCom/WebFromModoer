<?php

$albumid = _input('albumid',null,MF_INT_KEY);

$A =& $_G['loader']->model('item:album');
if(!$album = $A->read($albumid)) redirect('item_album_empty');

$P =& $_G['loader']->model('item:picture');
$where = array();
$where['albumid'] = $albumid;
list($total, $list) = $P->find('*', $where, array('addtime'=>'DESC'), 0, 0, 1);
if(!$total) redirect('item_picture_empty');

include mobile_template('item_pictures');
?>
<?php

$_G['loader']->helper('sql');

sql_alter_field('members', 'rmb', 'add', "rmb DECIMAL ( 9, 1 ) NOT NULL DEFAULT '0.0' AFTER coin");

?>
<?php
$CFG =& $_G['loader']->model('config');
@$CFG->write_js();
unset($CFG);
?>
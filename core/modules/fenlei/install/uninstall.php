<?php
$query = $_G['db']->query("SHOW TABLE STATUS");
$module_table_pre = $_G['dns']['dbpre'] . 'fenlei_';
$delete_tables = array();
while($info = $query->fetch_array()) {
    if(substr($info['Name'],0,strlen($module_table_pre)) == $module_table_pre) {
        $num = substr($info['Name'],strlen($module_table_pre));
        if(is_numeric($num)) $delete_tables[] = $info['Name'];
    }
}
$query->free_result();
if($delete_tables) foreach ($delete_tables as $delete_table) {
    $_G['db']->exec("DROP TABLE IF EXISTS $delete_table");
}
?>
<?php
// mazani fotek varianty
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

if($_GET['idv'])
{

$data_update = array('foto_var' => '');
$where_update = array('id' => $_GET['idv']);
$query = Db::update('produkty_varianty', $data_update, $where_update);	

// smažeme z disku
unlink('../fotky/produkty/male/'.$_GET['f']);
unlink('../fotky/produkty/velke/'.$_GET['f']);

$data_insert_log = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Varianty - smazání fotky u var s ID: '.intval($_GET['idv']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
$query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

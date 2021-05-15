<?php
// mazani fotek kategorie
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

if($_GET['id_k'])
{

$data_update = array('foto' => '');
$where_update = array('id' => $_GET['id_k']);
$query = Db::update('kategorie', $data_update, $where_update);	

// smažeme z disku
unlink('../fotky/kategorie/male/'.$_GET['f']);
//unlink('../fotky/aktuality/velke/'.$_GET['f']);

$data_insert_log = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Kategorie - smazání fotky u kategorie s ID: '.intval($_GET['id_k']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
$query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

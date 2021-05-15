<?php
// mazani fotek produkt
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

if($_GET['id'])
{

$where_delete = array('id' => $_GET['id']);
$query = Db::delete('produkty_foto', $where_delete);	

// smažeme z disku
unlink('../fotky/produkty/male/'.$_GET['f']);
unlink('../fotky/produkty/velke/'.$_GET['f']);

$data_insert_log = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Produkty - smazání fotky u produktu s ID: '.intval($_GET['id_p']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
$query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

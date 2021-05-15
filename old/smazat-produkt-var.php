<?php
// mazani fotek galerie
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

if($_GET['idv'])
{

$where_delete = array('id' => $_GET['idv']);
$query = Db::delete('produkty_varianty', $where_delete);	



$data_insert_log = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Produkty - varianty - smazání varianty s ID: '.intval($_GET['idv']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
$query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

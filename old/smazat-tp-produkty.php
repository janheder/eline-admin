<?php
// mazani fotek aktuality
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

if($_GET['idp'] && $_GET['id_tp'])
{

	$where_delete = array('id' => $_GET['id_tp']);
	$query = Db::delete('produkty_tech_par', $where_delete); 
	if($query)
	{
		
		$data_insert = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Prodkty - tech. par - smazání záznamu id '.intval($_GET['id_tp']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
			     
		$query_insert = Db::insert('admin_log_udalosti', $data_insert);
		
		 
	}

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

<?php
// hromadná změna stavu produktů
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(4);

// filtr
if($_POST['idp'] && isset($_POST['stav']))
{
	foreach($_POST['idp'] as $idp_k=>$idp_v)
	{
	 $data_update_p = array('aktivni' => intval($_POST['stav']));						
	 $where_update_p = array('id' => intval($idp_v));
	 $query_udate_p = Db::update('produkty', $data_update_p, $where_update_p);
	 
	 // i varianty
	 $data_update_v = array('aktivni_var' => intval($_POST['stav']));						
	 $where_update_v = array('id_produkt' => intval($idp_v));
	 $query_udate_v = Db::update('produkty_varianty', $data_update_v, $where_update_v);
    
    }
    
    
    // log
	$data_insert = array(
    'id_admin' => $_SESSION['admin']['id'],
    'uz_jm' => $_SESSION['admin']['uz_jm'],
    'udalost' => 'Produkty - hromadná změna stavu '.$stav_adm_arr[$_POST['stav']].' - '.count($_POST['idp']),
    'url' => sanitize($_SERVER['HTTP_REFERER']),
    'ip' => sanitize(getip()),
    'datum' => time()
     );

  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
	
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
	
}
else
{
 echo 'Chybí ID produktů';
}
?>
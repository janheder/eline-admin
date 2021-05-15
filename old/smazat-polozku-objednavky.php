<?php
// mazani fotek galerie
define('__WEB_DIR__', '..');
require('../skripty/init.php');
require('./config.php');

Admin::adminLogin(5);

?ido=3&idp=3

if($_GET['ido'] && $_GET['idp'])
{

$where_delete = array('id' => $_GET['idp']);
$query = Db::delete('objednavky_polozky', $where_delete);	


// přepočet cen
$data_cc_bez = Db::queryRow('SELECT sum(pocet * cena_za_ks) AS CELKEM_BEZ FROM objednavky_polozky WHERE id_obj=?  ', array($_GET['ido']));
$data_cc_s = Db::queryRow('SELECT sum(pocet * cena_za_ks * (dph / 100 + 1)) AS CELKEM_S FROM objednavky_polozky WHERE id_obj=?  ', array($_GET['ido']));

// aktualizujeme objednávku
$data_update = array(
'cena_celkem' => $data_cc_bez['CELKEM_BEZ'],
'cena_celkem_s_dph' => $data_cc_s['CELKEM_S']
);
$where_update = array('id' => $_GET['ido']);
$query = Db::update('objednavky', $data_update, $where_update);

$data_insert_log = array(
					    'id_admin' => $_SESSION['admin']['id'],
					    'uz_jm' => $_SESSION['admin']['uz_jm'],
					    'udalost' => 'Objednávka ID - '.$_GET['ido'].' - smazání položky s ID: '.intval($_GET['idp']),
					    'url' => sanitize($_SERVER['HTTP_REFERER']),
					    'ip' => sanitize(getip()),
					    'datum' => time()
					     );
$query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);

}
	            
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>

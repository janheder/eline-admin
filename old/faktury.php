<?php 
// faktury

Admin::adminLogin(4);

// filtr
if(!$_GET['action'])
{
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
				
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 70%;">');
	
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('cislo_faktury','cislo_faktury',sanitize($_GET['cislo_faktury']),'Číslo faktury','','',0,0,'');
	echo $form->inputText('cislo_obj','cislo_obj',sanitize($_GET['cislo_obj']),'Číslo objednávky','','',0,0,'');
	echo $form->inputText('jmeno','jmeno',sanitize($_GET['jmeno']),'Jméno zákazníka','','',0,0,'');
	echo $form->addHTML('</div>');
	
	echo $form->addHTML('</div>');
	
	echo $form->addHTML('<div style="float: left; width: 30%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=objednavky&pp=faktury\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
}


if($_GET['action']=='delete')
{
		if($_GET['id'])
		{	
			$data_f = Db::queryRow('SELECT id_objednavky FROM faktury WHERE id=?  ', array($_GET['id']));
			$where_delete = array('id' => $_GET['id']);
			$query_delete = Db::delete('faktury', $where_delete); 
			if($query_delete)
			{
				// musíme smazat i v objednávce
				 $data_update_o = array(
					'faktura_cislo' => '',
					'faktura_url' => '',
					'faktura_id' => ''
					);
					$where_update_o = array('id' => $data_f['id_objednavky']);
					$query_update_o = Db::update('objednavky', $data_update_o, $where_update_o); 
			
				// log
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Faktury - smazání záznamu id '.intval($_GET['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
				$query_insert = Db::insert('admin_log_udalosti', $data_insert);
				
				echo '<div class="alert-success">Smazáno!<br>Záznam byl smazán</div>';
			}
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu</div>';
		}

}
else
{
	// vypis vsech
	$sql_podminka_jmeno = '';
	$sql_podminka_cislo_faktury = '';
	$sql_podminka_cislo_obj = '';

	if($_GET['jmeno'])
	{		
		$sql_podminka_jmeno .= ' AND (Z.jmeno LIKE "%'.sanitize($_GET['jmeno']).'%" || Z.prijmeni LIKE "%'.sanitize($_GET['jmeno']).'%") ';
	}

	if($_GET['cislo_faktury'])
	{
		$sql_podminka_cislo_faktury .= ' AND F.cislo_faktury="'.sanitize($_GET['cislo_faktury']).'" ';
	}

	if($_GET['cislo_obj'])
	{
		$sql_podminka_cislo_obj .= ' AND F.var_symbol="'.sanitize($_GET['cislo_obj']).'" ';
	}
	
 
	$odkaz_jinam_arr = array('faktura_url','','','faktura');
	
	
	$tabulka = new Vypisy('faktury','','SELECT F.*, CONCAT(Z.jmeno, " ", Z.prijmeni) AS CELE_JMENO 
	FROM faktury F 
	LEFT JOIN zakaznici Z ON Z.id=F.id_zakaznik 
	WHERE 1 '.$sql_podminka_jmeno.$sql_podminka_cislo_faktury.$sql_podminka_cislo_obj.' ','',
	array('F.id','F.cislo_faktury','F.var_symbol','Z.CELE_JMENO','F.cena_celkem_s_dph','F.datum_vystaveni','F.id_stav'),
	array('Id','Číslo faktury','Číslo objednávky','Zákazník','Cena s DPH','Datum vystavení','Stav','Akce'),0,0,1,$odkaz_jinam_arr);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});

</script>

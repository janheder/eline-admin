<?php 
// statické stránky

Admin::adminLogin(3);

if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nadpis,nadpis_menu,obsah';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
						   
						    $obsah = $_POST['obsah'];//str_replace('tinymce/plugins','/admin/tinymce/plugins',$_POST['obsah_cz']);


						    
							$data_update = array(
								'nadpis' => $_POST['nadpis'],
								'nadpis_menu' => $_POST['nadpis_menu'],
								'obsah' => $obsah,
								
								'title' => $_POST['title'],
								'keywords' => $_POST['keywords'],
								'description' => $_POST['description'],
								
								'og_title' => $_POST['og_title'],
								'og_description' => $_POST['og_description'],
								'og_site_name' => $_POST['og_site_name'],
								'og_url' => $_POST['og_url'],
								'og_image' => $_POST['og_image'],
								'indexovani' => $_POST['indexovani'],
								'id_fotogalerie' => $_POST['id_fotogalerie'],
								'typ' => $_POST['typ'],
								'datum' => time(),
								'aktivni' => $_POST['aktivni']
							);
							
							
						    
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('stranky', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Stránky - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br>
							<a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
					   }
					   else
					   {
						   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
						   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
						   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
						   <a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
					   }
				  	     
			
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
			}
			
	}
	else
	{
		if($_GET['id'])
		{
			$data = Db::queryRow('SELECT * FROM stranky WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				
				// fotogalerie arr
				$data_fg = Db::queryAll('SELECT * FROM fotogalerie WHERE 1 ORDER BY id ASC', array());
				$fg_arr = array();
				if($data_fg !== false ) 
				{
					$fg_arr[0] = 'vyberte';
					
				    foreach ($data_fg as $row_fg) 
					{
					  $fg_arr[$row_fg['id']] = $row_fg['nazev'];	
					}
				}
				else
				{
					$fg_arr = false;
				}

				
				echo $form->formOpen();
				echo $form->inputText('nadpis','nadpis',$data['nadpis'],'Nadpis','class="input_req"','',1,0,'');
				echo $form->inputText('nadpis_menu','nadpis_menu',$data['nadpis_menu'],'Nadpis menu','class="input_req"','',1,0,'');
			
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
				echo $form->inputTextarea('obsah','obsah',$data['obsah'],'Obsah','','',0,0,'');

				
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->addHTML('<label for="seoparam"><input type="checkbox" name="seoparam" id="seoparam" value="1" onclick="ZobrazSEO(\'seoparametry\');" > Zobrazit SEO parametry</label><div id="seoparametry" style="display: none;">');	
				
				echo $form->inputText('title','title',$data['title'],'Title','','',0,0,'');
				echo $form->inputText('keywords','keywords',$data['keywords'],'Keywords','','',0,0,'');
				echo $form->inputText('description','description',$data['description'],'Description','','',0,0,'');
				echo $form->inputText('og_title','og_title',$data['og_title'],'OG Title','','',0,0,'');
				echo $form->inputText('og_description','og_description',$data['og_description'],'OG Description','','',0,0,'');
				echo $form->inputText('og_site_name','og_site_name',$data['og_site_name'],'OG SiteName','','',0,0,'');
				echo $form->inputText('og_url','og_url',$data['og_url'],'OG URL','','',0,0,'');
				echo $form->inputText('og_image','og_image',$data['og_image'],'OG Image','','',0,0,'absolutní adresa k obrázku');
				echo $form->inputSelect('indexovani','indexovani',$data['indexovani'],$stav_adm_sluzby_arr,'Indexování','',0,0,'');
				echo $form->addHTML('</div>');
				

				echo $form->inputText('precteno','precteno',$data['precteno'],'Přečteno','readonly','',0,0,'');
				echo $form->inputSelect('id_fotogalerie','id_fotogalerie',$data['id_fotogalerie'],$fg_arr,'Fotogalerie','',0,0,'');
				echo $form->inputSelect('typ','typ',$data['typ'],$stranky_adm_typ_arr,'Typ','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');

				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','checkBtn','Uložit','','','');
				echo $form->formClose();
			}
			else
			{
				echo '<div class="alert-warning">Chyba!<br>Žádný záznam v databázi pro id '.strip_tags($_GET['id']).'<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
			}
		    
			
		}
		else
		{
			echo '<div class="alert-warning">Chyba!<br>Chybí ID záznamu<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		}
		    
			
	}
	


}
elseif($_GET['action']=='insert')
{
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nadpis,nadpis_menu,obsah';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
				$obsah = $_POST['obsah'];//str_replace('tinymce/plugins','/admin/tinymce/plugins',$_POST['obsah_cz']);
		
			    $data_insert = array(
							    'str' => StringUtils::removeAccents($_POST['nadpis']),
								'nadpis' => $_POST['nadpis'],
								'nadpis_menu' => $_POST['nadpis_menu'],
								'obsah' => $obsah,
			 
								'title' => $_POST['title'],
								'keywords' => $_POST['keywords'],
								'description' => $_POST['description'],
								'og_title' => $_POST['og_title'],
								'og_description' => $_POST['og_description'],
								'og_site_name' => $_POST['og_site_name'],
								'og_url' => $_POST['og_url'],
								'og_image' => $_POST['og_image'],
								'indexovani' => $_POST['indexovani'],
								'id_fotogalerie' => $_POST['id_fotogalerie'],
								'typ' => $_POST['typ'],
								'datum' => time(),
								'aktivni' => $_POST['aktivni']

							     );
							     
	     
				$query_insert = Db::insert('stranky', $data_insert);
				
				//řazení
				$data_update = array('razeni' => $query_insert);
				$where_update = array('id' => $query_insert);
				$query = Db::update('stranky', $data_update, $where_update);
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Stránky - přidání záznamu: '.strip_tags($_POST['nadpis']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
	            $query_insert_log = Db::insert('admin_log_udalosti', $data_insert_log);
				
				echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku uložen<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		    }
		    else
		    {
			   echo '<div class="alert-warning">Chyba!<br>Nejsou vyplněny všechny povinné položky<br>';
			   foreach($form->errors as $ke=>$ve){ echo $ve.'<br>'; }
			   echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Přejít zpět</a><br>
			   <a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a></div>';
		    }
	}
	else
	{
				// fotogalerie arr
				$data_fg = Db::queryAll('SELECT * FROM fotogalerie WHERE 1 ORDER BY id ASC', array());
				$fg_arr = array();
				if($data_fg !== false ) 
				{
					$fg_arr[0] = 'vyberte';
					
				    foreach ($data_fg as $row_fg) 
					{
					  $fg_arr[$row_fg['id']] = $row_fg['nazev'];	
					}
				}
				else
				{
					$fg_arr = false;
				}
	 
			    echo $form->formOpen();
				echo $form->inputText('nadpis','nadpis','','Nadpis','class="input_req"','',1,0,'');
				echo $form->inputText('nadpis_menu','nadpis_menu','','Nadpis menu','class="input_req"','',1,0,'');

				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
				echo $form->inputTextarea('obsah','obsah','','Obsah','','',0,0,'');
				
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->addHTML('<label for="seoparam"><input type="checkbox" name="seoparam" id="seoparam" value="1" onclick="ZobrazSEO(\'seoparametry\');" > Zobrazit SEO parametry</label><div id="seoparametry" style="display: none;">');	
				
				echo $form->inputText('title','title','','Title','','',0,0,'');
				echo $form->inputText('keywords','keywords','','Keywords','','',0,0,'');
				echo $form->inputText('description','description','','Description','','',0,0,'');
				echo $form->inputText('og_title','og_title','','OG Title','','',0,0,'');
				echo $form->inputText('og_description','og_description','','OG Description','','',0,0,'');
				echo $form->inputText('og_site_name','og_site_name','','OG SiteName','','',0,0,'');
				echo $form->inputText('og_url','og_url','','OG URL','','',0,0,'');
				echo $form->inputText('og_image','og_image','','OG Image','','',0,0,'absolutní adresa k obrázku');
				echo $form->inputSelect('indexovani','indexovani',1,$stav_adm_sluzby_arr,'Indexování','',0,0,'');
				echo $form->addHTML('</div>');
				
				echo $form->inputSelect('id_fotogalerie','id_fotogalerie','',$fg_arr,'Fotogalerie','',0,0,'');
				echo $form->inputSelect('typ','typ',1,$stranky_adm_typ_arr,'Typ','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');

				echo $form->inputSubmit('submit','checkBtn','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	// kvůli řazení přetažením nemůžeme generovat výpis třídou
	/*
	$odkaz_jinam_arr = array('str','','/','www');
 
	$tabulka = new Vypisy('stranky','systemova=0','','razeni ASC',array('id','nadpis','precteno','typ','razeni','datum','aktivni','str'),
	array('Id','Nadpis','Přečteno','Typ','Pořadí','Datum vytvoření','Stav','Akce'),1,1,0,$odkaz_jinam_arr);
	echo $tabulka->GenerujVypis();*/
	echo 'Stránky lze řadit přetažením řádku na příslušnou pozici. Více info v nápovědě.<br><br>';
	
	echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
		  <tr class="tr-header">
		  <th>Id</th>
		  <th>Nadpis</th>
		  <th>Přečteno</th>
		  <th>Typ</th>
		  <th>Datum vytvoření</th>
		  <th>Stav</th>
		  <th style="width: 90px;">Akce</th>
		  </tr>
		  <tr><td colspan="7" style="text-align: right;"><a href="./index.php?p=staticke-stranky&action=insert">přidat záznam</a></td></tr>
		  <tbody class="row_position">';

	$data_s = Db::queryAll('SELECT * FROM stranky WHERE 1 ORDER BY razeni ASC', array());
	if($data_s !== false ) 
	{
		foreach ($data_s as $row_s) 
		{
			echo '<tr id="'.$row_s['id'].'" '.__TR_BG__.' >
			  <td style="cursor: ns-resize;">'.$row_s['id'].'</td>
			  <td>'.$row_s['nadpis'].'</td>
			  <td>'.$row_s['precteno'].'</td>
			  <td>'.$stranky_adm_typ_arr[$row_s['typ']].'</td>
			  <td>'.date('d.m.Y',$row_s['datum']).'</td>
			  <td>';
			  if($row_s['aktivni']=='1'){echo '<span class="r">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  else{ echo '<span class="b">'.$stav_adm_arr[$row_s['aktivni']].'</span>'; }
			  echo '</td>
			  <td><a href="./index.php?p='.$_GET['p'].'&action=update&id='.$row_s['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/'.$row_s['str'].'" target="_blank">www</a></td>
			  </tr>';
		}
	  
	}
	
	

	echo '</tbody></table>';	
	
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    
    $("#formular").validate({
       
    });
    
    $(function(){
	 $( '#title,#keywords,#description,#og_title,#og_description,#og_site_name,#og_url,#og_image' ).EnsureMaxLength({

	    limit: 255

	  });
	});
});



$(".row_position").sortable({
	delay: 150,
	stop: function() {
		var selectedData = new Array();
		$('.row_position>tr').each(function() {
			selectedData.push($(this).attr("id"));

		});
		updateOrder(selectedData,'razeni_stranky');
	}

});


</script>

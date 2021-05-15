<?php 
// aktuality

Admin::adminLogin(4);



if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('aktuality', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Aktuality - smazání záznamu id '.intval($_GET['id']),
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


if($_GET['action']=='update')
{
	
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 1);
	$form->required = 'nadpis,perex,obsah,datum';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {
						   
						     list($den,$mesic,$rok) = explode(".",$_POST['datum']);
							 $hodina = 12;
							 $minuta = 10;
							 $sekunda = 10;
							 $datum = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							

							$data_update = array(
							'nadpis' => $_POST['nadpis'],
							'perex' => $_POST['perex'],
							'obsah' => $_POST['obsah'],
							'autor' => $_POST['autor'],
							'zdroj' => $_POST['zdroj'],
							'na_uvod' => $_POST['na_uvod'],
							'id_fotogalerie' => $_POST['id_fotogalerie'],
							'keywords' => $_POST['keywords'],
							'title' => $_POST['title'],
							'description' => $_POST['description'],
							'og_title' => $_POST['og_title'],
							'og_description' => $_POST['og_description'],
							'og_site_name' => $_POST['og_site_name'],
							'og_url' => $_POST['og_url'],
							'og_image' => $_POST['og_image'],
							'indexovani' => $_POST['indexovani'],
							'datum' => $datum,
							'aktivni' => $_POST['aktivni']
							);
							
							if($_FILES['foto']['size'])
							{
							   $foto = foto_aktuality('foto',450,1200);
							   $data_update['foto'] = $foto;
							}
							
							
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('aktuality', $data_update, $where_update);
							

							    // log
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Aktuality - úprava záznamu id '.intval($_POST['id']),
							    'url' => sanitize($_SERVER['HTTP_REFERER']),
							    'ip' => sanitize(getip()),
							    'datum' => time()
							     );
					     
							  $query_insert = Db::insert('admin_log_udalosti', $data_insert);
							
							echo '<div class="alert-success">Uloženo!<br>Záznam byl v pořádku změněn<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'">Přejít na přehled</a>
							<br><a href="./index.php?p='.strip_tags($_GET['p']).'&pp='.strip_tags($_GET['pp']).'&action=update&id='.intval($_GET['id']).'">Znovu upravit</a></div>';
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
			$data = Db::queryRow('SELECT * FROM aktuality WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				
				// fotka
				if($data['foto']) 
		        {
				   $foto_html = '<div style="width: auto; clear: both;">';	
				   $foto_html .= '
					   <img src="/fotky/aktuality/male/'.$data['foto'].'" style="width: 150px;" class="fd"><br>
					   <a href="./smazat-foto-aktuality.php?id_a='.$data['id'].'&f='.$data['foto'].'" '.__JS_CONFIRM__.'>smazat  foto</a>';
				   $foto_html .= '</div>';
				}
				else
				{
					$foto_html = false;
				}

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
				
				$datum = date("d.m.Y",$data['datum']);
				
				echo $form->formOpen();
				echo $form->inputText('nadpis','nadpis',$data['nadpis'],'Nadpis','class="input_req"','',1,0,'');
				echo $form->inputTextarea('perex','perex',$data['perex'],'Perex','class="input_req"','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
				echo $form->inputTextarea('obsah','obsah',$data['obsah'],'Obsah','','',1,0,'');
				
				echo $form->inputText('autor','autor',$data['autor'],'Autor','','',0,0,'');
				echo $form->inputText('zdroj','zdroj',$data['zdroj'],'Zdroj','','',0,0,'');
				echo $form->inputCheckbox('na_uvod','na_uvod',1,$data['na_uvod'],'ANO','Na úvod','',0,0,'');
				echo $form->inputText('datum','datepicker',$datum,'Datum','','',1,0,'');
				echo $form->inputFile('foto','foto','','Foto','',0,0,'jpg, png, gif, webp');
				echo $form->addHTML('<canvas id="imageCanvas"></canvas>');
			    
			    echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				echo $form->addHTML($foto_html);	
				echo $form->addHTML('<div class="clear" style="height: 10px;"></div>');	
				

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
				
				echo $form->inputSelect('aktivni','aktivni',$data['aktivni'],$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputHidden('id','id',intval($_GET['id']),'',0);
				echo $form->inputSubmit('submit','submit','Uložit','','','');
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
	$form->required = 'nadpis,perex,obsah,datum';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
					
				list($den,$mesic,$rok) = explode(".",$_POST['datum']);
				 $hodina = 12;
				 $minuta = 10;
				 $sekunda = 10;
				 $datum = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
							
			    $data_insert = array(
								'str' => bez_diakritiky($_POST['nadpis']),
							    'nadpis' => $_POST['nadpis'],
							    'perex' => $_POST['perex'],
							    'obsah' => $_POST['obsah'],
							    'autor' => $_POST['autor'],
							    'zdroj' => $_POST['zdroj'],
							    'na_uvod' => $_POST['na_uvod'],
							    'id_fotogalerie' => $_POST['id_fotogalerie'],
							    'keywords' => $_POST['keywords'],
								'title' => $_POST['title'],
								'description' => $_POST['description'],
								'og_title' => $_POST['og_title'],
								'og_description' => $_POST['og_description'],
								'og_site_name' => $_POST['og_site_name'],
								'og_url' => $_POST['og_url'],
								'og_image' => $_POST['og_image'],
								'indexovani' => $_POST['indexovani'],
							    'datum' => $datum,
							    'aktivni' => $_POST['aktivni']
							     );
							     
							if($_FILES['foto']['size'])
							{
							   $foto = foto_aktuality('foto',450,1200);
							   $data_insert['foto'] = $foto;
							}
							          
				$query_insert = Db::insert('aktuality', $data_insert);
				
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Aktuality - přidání záznamu: '.strip_tags($_POST['nadpis']),
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
				
				$datum = date("d.m.Y");
				
			    echo $form->formOpen();
				echo $form->inputText('nadpis','nadpis','','Nadpis','class="input_req"','',1,0,'');
				echo $form->inputTextarea('perex','perex','','Perex','class="input_req"','',1,0,'');
				echo $form->addHTML('<script type="text/javascript"> aktivujTinyMCE(\'obsah\',300) </script>');
				echo $form->inputTextarea('obsah','obsah','','Obsah','','',1,0,'');
				
				echo $form->inputText('autor','autor','','Autor','','',0,0,'');
				echo $form->inputText('zdroj','zdroj','','Zdroj','','',0,0,'');
				echo $form->inputCheckbox('na_uvod','na_uvod',1,'','ANO','Na úvod','accept="image/x-png,image/gif,image/jpeg"',0,0,'');
				echo $form->inputText('datum','datepicker',$datum,'Datum','','',1,0,'');
				echo $form->inputFile('foto','foto','','Foto','',0,0,'jpg, png, gif, webp');
				echo $form->addHTML('<canvas id="imageCanvas"></canvas>');
		    
				
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
				
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech
	$odkaz_jinam_arr = array('str','','/aktuality/','www');
	
	$tabulka = new Vypisy('aktuality','','','',array('id','nadpis','na_uvod','datum','aktivni','str'),array('Id','Název','Na úvod','Datum','Stav','Akce'),1,1,1,$odkaz_jinam_arr);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});


 $(document).ready(function () {
  
    
    $(function(){
	 $( '#title,#keywords,#description,#og_title,#og_description,#og_site_name,#og_url,#og_image' ).EnsureMaxLength({

	    limit: 255

	  });
	});
});



if($('#foto').length){

var imageLoader = document.getElementById('foto');
    imageLoader.addEventListener('change', handleImage, false);
var canvas = document.getElementById('imageCanvas');
var ctx = canvas.getContext('2d');

}

 
 


</script>

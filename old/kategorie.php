<?php 
// kategorie

Admin::adminLogin(4);

// hromadná změna řazení
if($_POST['submit3'])
{
  //var_dump($_POST);
  foreach($_POST['razeni'] as $r_id=>$r_r)
  {
     
     $data_update = array('razeni' => $r_r);
     $where_update = array('id' => $r_id);
	 $query_update = Db::update('kategorie', $data_update, $where_update);
  
  }
}

if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			// kontrolujeme jestli nemá objednávky
			$pocet_produktu = Db::queryCount2("produkty","id","","id_kat_arr LIKE '%\"".$_GET['id']."\"%"  );
			
			if($pocet_produktu>0)
			{
				echo '<div class="alert-warning">Chyba!<br>Do této kategorie jsou zařazeny produkty. Nelze ji proto smazat. Nejdříve produkty zařaďte do jiné kategorie.</div>';
			}
			else
			{
				
				$where_delete = array('id' => $_GET['id']);
				$query = Db::delete('kategorie', $where_delete); 
				if($query)
				{
					
					$data_insert = array(
								    'id_admin' => $_SESSION['admin']['id'],
								    'uz_jm' => $_SESSION['admin']['uz_jm'],
								    'udalost' => 'Kategorie - smazání záznamu id '.intval($_GET['id']),
								    'url' => sanitize($_SERVER['HTTP_REFERER']),
								    'ip' => sanitize(getip()),
								    'datum' => time()
								     );
						     
					$query_insert = Db::insert('admin_log_udalosti', $data_insert);
					
					echo '<div class="alert-success">Smazáno!<br>Záznam byl smazán</div>';
				}
			
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
	$form->required = 'nazev';
	
	if($form->submit())
	{
		
			if($_POST['id'])
			{		
				 
					   if($form->errors==false)
					   {

							$data_update = array(
							'str' => bez_diakritiky($_POST['nazev']),
							'nazev' => $_POST['nazev'],
							'popis' => $_POST['popis'],
							'vnor' => intval($_POST['vnor']),
							'id_nadrazeneho' => intval($_POST['id_nadrazeneho']),
							'id_sablona' => intval($_POST['id_sablona']),
							'title' => $_POST['title'],
							'keywords' => $_POST['keywords'],
							'description' => $_POST['description'],
							'og_title' => $_POST['og_title'],
							'og_description' => $_POST['og_description'],
							'og_site_name' => $_POST['og_site_name'],
							'og_url' => $_POST['og_url'],
							'og_image' => $_POST['og_image'],
							'indexovani' => $_POST['indexovani'],
							'aktivni' => $_POST['aktivni']
							);
							
							if($_FILES['foto']['size'])
							{
							   $foto = foto_kategorie('foto',450);
							   $data_update['foto'] = $foto;
							}
							
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('kategorie', $data_update, $where_update);
			      
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Kategorie - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM kategorie WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				
				// fotka
				if($data['foto']) 
		        {
				   $foto_html = '<div style="width: auto; clear: both;">';	
				   $foto_html .= '
					   <img src="/fotky/kategorie/male/'.$data['foto'].'" style="width: 150px;" class="fd"><br>
					   <a href="./smazat-foto-kategorie.php?id_k='.$data['id'].'&f='.$data['foto'].'" '.__JS_CONFIRM__.'>smazat  foto</a>';
				   $foto_html .= '</div>';
				}
				else
				{
					$foto_html = false;
				}
				
				// šablony arr
				$data_s = Db::queryAll('SELECT * FROM sablony_tech_par WHERE 1 ORDER BY nazev ASC', array());
				$sablony_arr = array();
				if($data_s !== false ) 
				{
				    $sablony_arr[0] = 'vyberte';	
				    foreach ($data_s as $row_s) 
					{
					  $sablony_arr[$row_s['id']] = $row_s['nazev'];	
					}
				}
				else
				{
					$sablony_arr = false;
				}
 
				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název kategorie','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis',$data['popis'],'Popis','','',0,0,'');
				echo $form->inputSelect('vnor','vnor',$data['vnor'],$kar_vnor_arr,'Úroveň kategorie','onchange="ZobrPodkat(this.value)"',0,0,'');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');	
				echo $form->addHTML('<div id="nadrazena">');
				// pokud máme úroveň 2 nebo 3 tak vygenerujeme formulář
				if($data['vnor']==2 || $data['vnor']==3)
				{
					$vnor_nad = ($data['vnor'] - 1);
					$data_n = Db::queryAll('SELECT id, nazev FROM kategorie WHERE vnor=? ORDER BY razeni ASC', array($vnor_nad));
					if($data_n !== false ) 
					{
						echo $form->addHTML('<label class="control-label" for="_id_nadrazeneho">Nadřazená kategorie:</label><select name="id_nadrazeneho" id="id_nadrazeneho" required >');
						
					    foreach ($data_n as $row_n) 
						{
						   echo $form->addHTML('<option value="'.$row_n['id'].'">'.$row_n['nazev'].'</option>');	
						}
						
						echo $form->addHTML('</select>');
					}
				}
				echo $form->addHTML('</div>');	
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');	
				echo $form->inputSelect('id_sablona','id_sablona',$data['id_sablona'],$sablony_arr,'Šablona tech. par.','',0,0,'');
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
				
				echo $form->inputText('razeni','razeni',$data['razeni'],'Pořadí','','',1,0,'');
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
	$form->required = 'nazev';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
							'str' => bez_diakritiky($_POST['nazev']),
							'nazev' => $_POST['nazev'],
							'popis' => $_POST['popis'],
							'vnor' => intval($_POST['vnor']),
							'id_nadrazeneho' => intval($_POST['id_nadrazeneho']),
							'id_sablona' => intval($_POST['id_sablona']),
							'title' => $_POST['title'],
							'keywords' => $_POST['keywords'],
							'description' => $_POST['description'],
							'og_title' => $_POST['og_title'],
							'og_description' => $_POST['og_description'],
							'og_site_name' => $_POST['og_site_name'],
							'og_url' => $_POST['og_url'],
							'og_image' => $_POST['og_image'],
							'indexovani' => $_POST['indexovani'],
							'datum' => time(),
							'aktivni' => $_POST['aktivni']
							     );
							     
				if($_FILES['foto']['size'])
							{
							   $foto = foto_kategorie('foto',450);
							   $data_insert['foto'] = $foto;
							}			     
							          
							     
				$query_insert = Db::insert('kategorie', $data_insert);
				
				
				//řazení
				$data_update = array('razeni' => $query_insert);
				$where_update = array('id' => $query_insert);
				$query = Db::update('kategorie', $data_update, $where_update);
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Kategorie - přidání záznamu: '.strip_tags($_POST['nazev']),
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

			    // šablony arr
				$data_s = Db::queryAll('SELECT * FROM sablony_tech_par WHERE 1 ORDER BY nazev ASC', array());
				$sablony_arr = array();
				if($data_s !== false ) 
				{
					$sablony_arr[0] = 'vyberte';	
				    foreach ($data_s as $row_s) 
					{
					  $sablony_arr[$row_s['id']] = $row_s['nazev'];	
					}
				}
				else
				{
					$sablony_arr = false;
				}
				
				echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název kategorie','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis','','Popis','','',0,0,'');
				echo $form->inputSelect('vnor','vnor','',$kar_vnor_arr,'Úroveň kategorie','onchange="ZobrPodkat(this.value)"',0,0,'');
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');	
				echo $form->addHTML('<div id="nadrazena"></div>');	
				echo $form->addHTML('<div class="clear" style="height: 5px;"></div>');	
				echo $form->inputSelect('id_sablona','id_sablona','',$sablony_arr,'Šablona tech. par.','',0,0,'');
				
				echo $form->inputFile('foto','foto','','Foto','',0,0,'jpg, png, gif, webp');
				echo $form->addHTML('<canvas id="imageCanvas"></canvas>');
				
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
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	echo 'Kategorie lze řadit přetažením řádku na příslušnou pozici. Více info v nápovědě.<br><br>';
	
	// vypis vsech
echo '<form name="zr" method="post">
<table border="0" class="ta" cellspacing="0" cellpadding="6">

<tr class="tr-header">
<td>ID</td>
<td>Název</td>
<td>Šablona tech. par.</td>
<td>Úroveň</td>
<td>Pořadí</td>
<td>Foto</td>
<td>Stav</td>
<td style="width: 90px;">Akce</td>
</tr>';
echo '<tbody id="sortable" class="row_position" >
<tr><td colspan="8" style="text-align: right;"><a href="./index.php?p=kategorie&pp=&razeni=id&ord=DESC&action=insert">přidat záznam</a></td></tr>';

	$data_1 = Db::queryAll('SELECT K.id, K.nazev, K.vnor, K.razeni, K.foto, K.aktivni, S.nazev AS SABLONA  
	FROM kategorie K
	LEFT JOIN sablony_tech_par S ON S.id=K.id_sablona
	WHERE vnor=1 GROUP BY K.id ORDER BY razeni ASC', array());
	if($data_1 !== false ) 
	{

	    foreach ($data_1 as $row_1) 
		{
		   echo '<tr class="levelone"></tr>';	
			
		   echo '<tr '.__TR_BG__.' class="levelone movable" id="'.$row_1['id'].'">
			<td>'.$row_1['id'].'</td>
			<td>'.$row_1['nazev'].'</td>
			<td>'.$row_1['SABLONA'].'</td>
			<td>'.$row_1['vnor'].'</td>
			<td><input type="text" name="razeni['.$row_1['id'].']" value="'.$row_1['razeni'].'" style="width: 20px; max-width: 20px; min-width: 20px;"></td>
			<td>';
			  if($row_1['foto'])
			  {
				  echo '<img src="/fotky/kategorie/male/'.$row_1['foto'].'" style="width: 50px">';
			  }
			echo '</td>
			<td>';
			if($row_1['aktivni']==1){echo '<span class="r">'.$stav_adm_arr[$row_1['aktivni']].'</span>';}
			else{echo '<span class="b">'.$stav_adm_arr[$row_1['aktivni']].'</span>';}
			echo '</td>
			<td>&nbsp;<a href="./index.php?p=kategorie&action=update&id='.$row_1['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;<a href="./index.php?p=kategorie&action=delete&id='.$row_1['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
			</tr>';
			
			// druhá úroveň
					
					
					$data_2 = Db::queryAll('SELECT K.id, K.nazev, K.vnor, K.razeni, K.foto, K.aktivni, S.nazev AS SABLONA  
					FROM kategorie K
					LEFT JOIN sablony_tech_par S ON S.id=K.id_sablona
					WHERE vnor=2 AND id_nadrazeneho='.$row_1['id'].' GROUP BY K.id ORDER BY razeni ASC', array());
					if($data_2 !== false ) 
					{
				
					    foreach ($data_2 as $row_2) 
						{
						   echo '<tr '.__TR_BG__.' class="leveltwo movable" id="'.$row_2['id'].'">
							<td>'.$row_2['id'].'</td>
							<td>&nbsp;&nbsp;&nbsp;&rarr;&nbsp;'.$row_2['nazev'].'</td>
							<td>'.$row_2['SABLONA'].'</td>
							<td>'.$row_2['vnor'].'</td>
							<td><input type="text" name="razeni['.$row_2['id'].']" value="'.$row_2['razeni'].'" style="width: 20px; max-width: 20px; min-width: 20px;"></td>
							<td>';
							  if($row_2['foto'])
							  {
								  echo '<img src="/fotky/kategorie/male/'.$row_2['foto'].'" style="width: 50px">';
							  }
							echo '</td>
							<td>';
							if($row_2['aktivni']==1){echo '<span class="r">'.$stav_adm_arr[$row_2['aktivni']].'</span>';}
							else{echo '<span class="b">'.$stav_adm_arr[$row_2['aktivni']].'</span>';}
							echo '</td>
							<td>&nbsp;<a href="./index.php?p=kategorie&action=update&id='.$row_2['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;<a href="./index.php?p=kategorie&action=delete&id='.$row_2['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
							</tr>';
							
							// třetí úroveň
							
									$data_3 = Db::queryAll('SELECT K.id, K.nazev, K.vnor, K.razeni, K.foto, K.aktivni, S.nazev AS SABLONA  
									FROM kategorie K
									LEFT JOIN sablony_tech_par S ON S.id=K.id_sablona
									WHERE vnor=3 AND id_nadrazeneho='.$row_2['id'].' GROUP BY K.id ORDER BY razeni ASC', array());
									if($data_3 !== false ) 
									{
								
									    foreach ($data_3 as $row_3) 
										{
										   echo '<tr '.__TR_BG__.' class="levelthre movable" id="'.$row_3['id'].'">
											<td>'.$row_3['id'].'</td>
											<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&rarr;&nbsp;'.$row_3['nazev'].'</td>
											<td>'.$row_3['SABLONA'].'</td>
											<td>'.$row_3['vnor'].'</td>
											<td><input type="text" name="razeni['.$row_3['id'].']" value="'.$row_3['razeni'].'" style="width: 20px; max-width: 20px; min-width: 20px;"></td>
											<td>';
											  if($row_2['foto'])
											  {
												  echo '<img src="/fotky/kategorie/male/'.$row_3['foto'].'" style="width: 50px">';
											  }
											echo '</td>
											<td>';
											if($row_3['aktivni']==1){echo '<span class="r">'.$stav_adm_arr[$row_3['aktivni']].'</span>';}
											else{echo '<span class="b">'.$stav_adm_arr[$row_3['aktivni']].'</span>';}
											echo '</td>
											<td>&nbsp;<a href="./index.php?p=kategorie&action=update&id='.$row_3['id'].'">upravit</a>&nbsp;&nbsp;&nbsp;<a href="./index.php?p=kategorie&action=delete&id='.$row_3['id'].'" '.__JS_CONFIRM__.'>smazat</a></td>
											</tr>';
											
											
										}
									}
					
					
					
						}
					}
			
			
			
		}
	}
			
echo '</tbody></table>
<div class="clear" style="height: 20px"></div>
<div style="float: right">
<input type="submit" name="submit3" id="submit3" value="Hromadně změnit řazení"  >
</div>
</form>';

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


 $(document).ready(function ($) {


    var target  =  $( '#sortable' );

    target.sortable({
        items: 'tr.movable',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: false,
        helper: function (event, item) {

            if (!item.hasClass('ui-state-active')) {
                item.addClass('ui-state-active').siblings().removeClass('ui-state-active');
            }


            if(item.first().hasClass('levelone')) {

                $selected = $(item ).nextUntil('.levelone');
                $selected.addClass('ui-state-active');

                $.each( $selected , function( key, value ) {
                    item.push(value);

                });


            }


            if(item.first().hasClass('leveltwo')) {

                $selected = $(item ).nextUntil('.leveltwo, .levelone');
                $selected.addClass('ui-state-active');

                $.each( $selected , function( key, value ) {
                    item.push(value);
                });
            }

            return item;
        },
        opacity: 0.65,
        placeholder: 'sortable-placeholder',
        start: function( event, ui ) {

            $('tr').removeClass('oki');

            if(ui.item.first().hasClass('leveltwo')) {

                var $parent =  $(ui.item).prevAll(".levelone:first");
                $parent.addClass('oki');
                $selected = $($parent ).nextUntil('.levelone').addClass('oki');
            }

            if(ui.item.first().hasClass('levelthre')) {

                var $parent =  $(ui.item).prevAll(".leveltwo:first");
                $parent.addClass('oki');
                $selected = $($parent ).nextUntil('.levelone, .leveltwo').not('.sortable-placeholder').addClass('oki');

            }


            return event;
        },
        stop: function( event, ui ) {

            var check =  ui.placeholder[0].classList;

            $.each( check, function( key, elm ) {
                if(elm == 'invalid-position' ) {
                    return false;
                }
            });

            if( $('.ui-sortable-placeholder').hasClass('invalid-position')) {
                $(this).sortable('cancel');

            }
            $('.sortable-placeholder').remove();
            $('.movable').removeClass('oki');


            
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));

            });
            console.log(selectedData);
            updateOrder(selectedData,'razeni_kategorie');



        },
        update: function(event, ui) {
            $('.sortable-placeholder').remove();


        },
        beforeStop: function( event, ui ) {

            if( $('.ui-sortable-placeholder').hasClass('invalid-position')) {
                $(this).sortable('cancel');

            }

        },
        change: function(event, ui) {

            $('.ui-sortable-placeholder').removeClass('invalid-position');


            var okacko = false;
            var moznosti = ['levelone', 'leveltwo','levelthre'];
            var pred = $(ui.placeholder[0]).prev();
            var zad = $(ui.placeholder[0]).next();
            var level = '';


            $.each( moznosti, function( key, value ) {
                if( $.inArray( value , ui.item[0].classList ) != -1 ) {
                    level = value;
                }
            });

            var selectable_vpred = pred.hasClass('oki');
            var selectable_vzad = zad.hasClass('oki');

            $.each( moznosti, function( key, value ) {

                if(pred.hasClass(value) ){
                    predemnou = value;
                }

                if(zad.hasClass(value) ){
                    zamnou = value;
                }

            });


            if(typeof zamnou == 'undefined') {
                zamnou = false;
            }

            if(typeof predemnou == 'undefined') {
                predemnou = false;
            }


            switch(level) {
                case 'levelone':

                    if(zamnou) {
                        if( zamnou ==='levelone') {
                            okacko = true;
                        }
                    }

                    break;

                case 'leveltwo':

                    if(((predemnou === 'levelone') && (selectable_vpred))&& ( zamnou ==='leveltwo') ) {
                        okacko = true;
                    }
                    if((predemnou === 'leveltwo') && ( zamnou ==='leveltwo')) {
                        okacko = true;
                    }

                    if(( ( predemnou === 'levelthre') && (selectable_vpred)) && ( zamnou ==='levelone')) {

                        if(zamnou.hasClass('movable')) {
                            okacko = true;
                        }
                    }

                    if((predemnou === 'leveltwo') && ( zamnou ==='levelone')) {
                        okacko = true;
                    }

                    break;

                case 'levelthre':

                    if(selectable_vpred && selectable_vzad ) {
                        okacko = true;
                    }

                    if(selectable_vpred && (( zamnou ==='leveltwo') || ( zamnou ==='levelone')) ) {
                        okacko = true;
                    }

                    break;

                default:

            }


            if(!okacko) {
                $('.ui-sortable-placeholder').addClass('invalid-position');
            }

        }
    });

});


</script>

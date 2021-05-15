<?php 
// fotogalerie

Admin::adminLogin(4);



if($_GET['action']=='delete')
{
		if($_GET['id'])
		{
			$where_delete = array('id' => $_GET['id']);
			$query = Db::delete('fotogalerie', $where_delete); 
			if($query)
			{
				
				$data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Fotogalerie - smazání záznamu id '.intval($_GET['id']),
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
							'aktivni' => $_POST['aktivni']
							);
							$where_update = array('id' => $_POST['id']);
							$query = Db::update('fotogalerie', $data_update, $where_update);
							
							// fotky 
							if($_FILES['foto']['size'][0])
							{
								
								foto_galerie('foto',450,1200,$_POST['id']);
								
							}
							
							// fotky stávající 
							// změna popisu
							if($_POST['_foto_popis'])
							{   
								foreach($_POST['_foto_popis'] as $fp_k=>$fp_v)
								{
									$where_update_fp = array('id' => $fp_k);
									$data_update_fp = array('nazev' => $fp_v);
							        $query_fp = Db::update('fotogalerie_fotky', $data_update_fp, $where_update_fp);
								}
							}
							
					            $data_insert = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Fotogalerie - úprava záznamu id '.intval($_POST['id']),
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
			$data = Db::queryRow('SELECT * FROM fotogalerie WHERE id = ?', array($_GET['id']));
			
			if($data)
			{
				
				
				// fotky 
				$data_f = Db::queryAll('SELECT * FROM fotogalerie_fotky WHERE id_fotogalerie='.$data['id'].' ORDER BY razeni', array());
				if($data_f !== false ) 
		        {
				   $foto_html = 'řazení přetažením<div style="width: auto; clear: both;"><ul id="list_of_tasks" class="ui-sortable"> ';	
				   foreach ($data_f as $row_f) 
					{
					  $foto_html .= '<li id="page_'.$row_f['id'].'"> <div class="datablock">
					   <img src="/fotky/galerie/male/'.$row_f['foto'].'" style="width: 150px;" id="f_'.$row_f['id'].'" class="fd"><br>
					   <a href="./smazat-foto-galerie.php?id='.$row_f['id'].'&id_g='.$data['id'].'&f='.$row_f['foto'].'" '.__JS_CONFIRM__.'>smazat  foto</a>
					   <div class="clear" style="height: 5px;"></div>
					   <input type="text" name="_foto_popis['.$row_f['id'].']" id="_foto_popis'.$row_f['id'].'" style="min-width: 80px;" value="'.$row_f['nazev'].'" placeholder="popis fotky">
					   </div></li>';
						
					}
					
					$foto_html .= '</ul></div>';
				}
				else
				{
					$foto_html = false;
				}

				echo $form->formOpen();
				echo $form->inputText('nazev','nazev',$data['nazev'],'Název fotogalerie','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis',$data['popis'],'Popis','','',0,0,'');
				
				// foto - multi - pole
					    
				echo $form->inputFile('foto[]', 'files', '', 'Přidejte fotografie', 'multiple accept="image/x-png,image/gif,image/jpeg"', 0, 0,'');
			    echo $form->addHTML('<output id="list"></output>');
			    
			    echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				echo $form->addHTML($foto_html);	
				echo $form->addHTML('<div class="clear" style="height: 20px;"></div>');	
				
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
	$form = new Form('', '', 'post', 'formular', 'formular', 'utf-8', '', 0);
	$form->required = 'nazev';
	
	if($form->submit())
	{
			if($form->errors==false)
		    {
	
							
			    $data_insert = array(
								'str' => bez_diakritiky($_POST['nazev']),
							    'nazev' => $_POST['nazev'],
							    'popis' => $_POST['popis'],
							    'datum' => time(),
							    'aktivni' => $_POST['aktivni']
							     );
							          
				$query_insert = Db::insert('fotogalerie', $data_insert);
				
                
                
		        $data_insert_log = array(
							    'id_admin' => $_SESSION['admin']['id'],
							    'uz_jm' => $_SESSION['admin']['uz_jm'],
							    'udalost' => 'Fotogalerie - přidání záznamu: '.strip_tags($_POST['nazev']),
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


			    echo $form->formOpen();
				echo $form->inputText('nazev','nazev','','Název fotogalerie','class="input_req"','',1,0,'');
				echo $form->inputTextarea('popis','popis','','Popis','','',0,0,'');
				echo $form->inputSelect('aktivni','aktivni',1,$stav_adm_arr,'Stav','',0,0,'');
				echo $form->inputSubmit('submit','submit','Uložit','','','');
				echo $form->formClose();
	}
}
else
{
	// vypis vsech

	
	$tabulka = new Vypisy('fotogalerie','','','',array('id','nazev','datum','aktivni'),array('Id','Název','Datum','Stav','Akce'),1,1,1);
	echo $tabulka->GenerujVypis();
}

?>
 <script type="text/javascript">
	 
 $(document).ready(function () {
    $("#formular").validate({
       
    });
});




function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

  if($('#files').length)
  {
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
  }
  
  
  
  // řazení fotek přetažením
$(document).ready(function(){
	$("#list_of_tasks").sortable({
		update: function(event, ui) {
			$.post("_ajax.php?typ=razeni_fotek_galerie", { pages: $('#list_of_tasks').sortable('serialize') });
 
		}
	});
});



</script>

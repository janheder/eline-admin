<?php 
// statistiky grafy

Admin::adminLogin(5);

//$record = geoip_record_by_name('90.180.12.225');
// var_dump($record);
	// filtr
	echo '<fieldset><legend>Filtr</legend>';
	$form = new Form('', '', 'get', 'formular2', 'formular', 'utf-8', '', 0);
	
	
	echo $form->formOpen();
	echo $form->addHTML('<div style="float: left; width: 40%;">');
	echo $form->inputText('datum_od','datepicker_od',sanitize($_GET['datum_od']),'Datum od','','',0,0,'');
	echo $form->inputText('datum_do','datepicker_do',sanitize($_GET['datum_do']),'Datum do','','',0,0,'');
	echo $form->addHTML('</div>');
	echo $form->addHTML('<div style="float: left; width: 45%; margin-top: 20px;">');
	echo $form->inputHidden('p','p',sanitize($_GET['p']),'',0);
	echo $form->inputHidden('pp','pp',sanitize($_GET['pp']),'',0);
	echo $form->inputSubmit('submit','submit','Zobrazit','','','');
	echo $form->inputButton('redir','redir','Zobrazit vše','','onclick="self.location.href=\'index.php?p=statistiky&pp=statistiky-grafy\'"','');
	echo $form->addHTML('</div>');
	echo $form->formClose();

	echo '</fieldset>';
	
	
	$sql_podminka = '';
	
	// pokud nejsou zadané datumy tak se bere poslední měsíc
	
	if($_GET['datum_od'])
	{
				  
		 list($den,$mesic,$rok) = explode(".",$_GET['datum_od']);
		 $hodina = 0;
		 $minuta = 0;
		 $sekunda = 1;
		 $datum_od = mktime($hodina,$minuta,$sekunda,$mesic,$den,$rok);
		 
		$sql_podminka .= ' AND datum >='.intval($datum_od).' ';
	}
	else
	{
		 $hodina = 0;
		 $minuta = 0;
		 $sekunda = 1;
		 $datum_od = mktime($hodina,$minuta,$sekunda,intval(date('n')),1,date('Y'));
		 
		$sql_podminka .= ' AND datum >='.intval($datum_od).' ';
	}
	
	if($_GET['datum_do'])
	{
				  
		 list($den,$mesic,$rok) = explode(".",$_GET['datum_do']);
		 $hodina = 23;
		 $minuta = 59;
		 $sekunda = 59;
		 $datum_do = mktime($hodina,$minuta,$sekunda,intval($mesic),intval($den),$rok);
		 
		$sql_podminka .= ' AND datum <='.intval($datum_do).' ';
	}
	else
	{
	
		 $hodina = 23;
		 $minuta = 59;
		 $sekunda = 59;
		 $datum_do = mktime($hodina,$minuta,$sekunda,intval(date('n')),date('t'),date('Y'));
		 
		$sql_podminka .= ' AND datum >='.intval($datum_do).' ';
	}
	
	
	$dny_arr = array();
	$pocet_obj = array();
	$cena_obj = array();
	$i = $datum_od;
	
	while ($i <= $datum_do) 
	{
       $aktualni_den = date("d.m.",$i);
       
       // musíme zjistit počet objednávek za určitý den
       $aktualni_datum = date("d.m.Y",$i);
       // od
       list($den_od,$mesic_od,$rok_od) = explode(".",$aktualni_datum);
        $hodina = 0;
		$minuta = 0;
		$sekunda = 1;
		$datum_od_sql = mktime($hodina,$minuta,$sekunda,$mesic_od,$den_od,$rok_od);
		// do
		$hodina = 23;
		$minuta = 59;
		$sekunda = 59;
        $datum_do_sql = mktime($hodina,$minuta,$sekunda,$mesic_od,$den_od,$rok_od);
        
        $data_pocet_obj = Db::queryRow('SELECT count(id) AS POCET, sum(cena_celkem_s_dph) AS CENA_CELKEM FROM objednavky WHERE datum>='.$datum_od_sql.' AND datum<='.$datum_do_sql.' ', array());
		if($data_pocet_obj)
		{
			$poc = $data_pocet_obj['POCET'];
			if($data_pocet_obj['CENA_CELKEM'])
			{
				$cena = $data_pocet_obj['CENA_CELKEM'];
			}
			else
			{
			    $cena = 0;
			}
			
		}
		else
		{ 
			$poc = 0;
			$cena = 0;
		}	
		
	   $pocet_obj[] = $poc;	
	   $cena_obj[] = $cena;
	   	
       $dny_arr[] = $aktualni_den;
       $i = ($i+86400);  

	}
	
	//var_dump($cena_obj);
	//var_dump($dny_arr);
	//echo count($dny_arr);
	//var_dump($pocet_obj);
	
	// generování grafů
	require_once ('./jpgraph/src/jpgraph.php');
	require_once ('./jpgraph/src/jpgraph_line.php');
	

	// počet objednávek 
	// pole s počtem objednávek musí mít stejný počet prvků jako počet dnů

	// Setup the graph
	$graph = new Graph(1300,400);
	$graph->SetScale("textlin");
	
	$theme_class=new UniversalTheme;
	
	$graph->SetTheme($theme_class);
	$graph->img->SetAntiAliasing(false);
	$graph->title->Set('Počet objednávek za '.date('d.m.Y',$datum_od).' - '.date('d.m.Y',$datum_do) );
	$graph->SetBox(false);
	
	$graph->img->SetAntiAliasing();
	
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xaxis->SetTickLabels($dny_arr); // dny - datumy
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Create the first line
	$p1 = new LinePlot($pocet_obj);
	$graph->Add($p1);
	$p1->SetColor("#6495ED");
	$p1->SetLegend('Počet objednávek');
	
	
	$graph->legend->SetFrameWeight(1);
	$graph->legend->SetPos(0.5,0.98,'center','bottom');
	
	// Output line
	$img = $graph->Stroke(_IMG_HANDLER);

	ob_start();
	imagepng($img);
	$imageData = ob_get_contents();
	ob_end_clean();
	echo '<img src="data:image/png;base64,'.base64_encode($imageData).'" />';
	

	// cena
	echo '<div class="clear" style="height: 40px;"></div>';
	// Setup the graph
	$graph = new Graph(1300,400);
	$graph->SetScale("textlin");
	
	$theme_class=new UniversalTheme;
	
	$graph->SetTheme($theme_class);
	$graph->img->SetAntiAliasing(false);
	$graph->title->Set('Částky za objednávky '.date('d.m.Y',$datum_od).' - '.date('d.m.Y',$datum_do) );
	$graph->SetBox(false);
	
	$graph->img->SetAntiAliasing();
	
	$graph->yaxis->HideZeroLabel();
	$graph->yaxis->HideLine(false);
	$graph->yaxis->HideTicks(false,false);
	
	$graph->xgrid->Show();
	$graph->xgrid->SetLineStyle("solid");
	$graph->xaxis->SetTickLabels($dny_arr); // dny - datumy
	$graph->xgrid->SetColor('#E3E3E3');
	
	// Create the first line
	$p1 = new LinePlot($cena_obj);
	$graph->Add($p1);
	$p1->SetColor("#10934b");
	$p1->SetLegend('Cena objednávek s DPH');
	
	
	$graph->legend->SetFrameWeight(1);
	$graph->legend->SetPos(0.5,0.98,'center','bottom');
	
	// Output line
	$img = $graph->Stroke(_IMG_HANDLER);

	ob_start();
	imagepng($img);
	$imageData = ob_get_contents();
	ob_end_clean();
	echo '<img src="data:image/png;base64,'.base64_encode($imageData).'" />';

?>


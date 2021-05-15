<?php 
// XML feedy

Admin::adminLogin(3);

echo 'Zboží.cz: <h3 style="display: inline-block;">'.__URL__.'/xml/zbozi_cz.xml</h3>&nbsp;&nbsp;&nbsp;<input type="button" value="přegenerovat" 
onclick="GenerujXML(\'xml_zbozi\')" style="display: inline-block;"><br>';


echo 'Heureka.cz: <h3 style="display: inline-block;">'.__URL__.'/xml/heureka_cz.xml</h3>&nbsp;&nbsp;&nbsp;<input type="button" value="přegenerovat" 
onclick="GenerujXML(\'xml_heureka\')" style="display: inline-block;"><br>';

echo 'Google nákupy: <h3 style="display: inline-block;">'.__URL__.'/xml/google.xml</h3>&nbsp;&nbsp;&nbsp;<input type="button" value="přegenerovat" 
onclick="GenerujXML(\'xml_google\')" style="display: inline-block;"><br>';
?>

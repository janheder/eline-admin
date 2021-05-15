<?php 
// statistiky

Admin::adminLogin(5);


$pocet_produktu_celkem = Db::queryCount2('produkty','id','','1');
$pocet_produktu_aktivni = Db::queryCount2('produkty','id','','aktivni=1');
$pocet_produktu_neaktivni = Db::queryCount2('produkty','id','','aktivni=0');


$pocet_variant_celkem = Db::queryCount2('produkty_varianty','id','','1');
$pocet_variant_aktivni = Db::queryCount2('produkty_varianty','id','','aktivni_var=1');
$pocet_variant_neaktivni = Db::queryCount2('produkty_varianty','id','','aktivni_var=0');

$pocet_vyrobcu = Db::queryCount2('produkty_vyrobci','id','','aktivni=0');

$pocet_kategorii_celkem = Db::queryCount2('kategorie','id','','1');
$pocet_kategorii_aktivni = Db::queryCount2('kategorie','id','','aktivni=1');
$pocet_kategorii_neaktivni = Db::queryCount2('kategorie','id','','aktivni=0');

$pocet_objednavek_celkem = Db::queryCount2('objednavky','id','','1');
$pocet_objednavek_vyrizeno = Db::queryCount2('objednavky','id','','id_stav IN(5,6)');
$pocet_objednavek_storno = Db::queryCount2('objednavky','id','','id_stav=3');
 
$pocet_zakazniku = Db::queryCount2('zakaznici','id','','1');
$pocet_zakazniku_aktivni = Db::queryCount2('zakaznici','id','','aktivni=1');
 
echo '<table border="0" class="ta" cellspacing="0" cellpadding="6">
<tr class="tr-header">
<td>Název</td><td>Hodnota</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet produktů</td><td>'.$pocet_produktu_celkem.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet aktivních produktů</td><td>'.$pocet_produktu_aktivni.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet neaktivních produktů</td><td>'.$pocet_produktu_neaktivni.'</td>
</tr>

<tr '.__TR_BG__.'>
<td>Počet variant</td><td>'.$pocet_variant_celkem.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet aktivních variant</td><td>'.$pocet_variant_aktivni.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet neaktivních variant</td><td>'.$pocet_variant_neaktivni.'</td>
</tr>

<tr '.__TR_BG__.'>
<td>Počet výrobců</td><td>'.$pocet_vyrobcu.'</td>
</tr>



<tr '.__TR_BG__.'>
<td>Počet kategorií</td><td>'.$pocet_kategorii_celkem.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet aktivních kategorií</td><td>'.$pocet_kategorii_aktivni.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet neaktivních kategorií</td><td>'.$pocet_kategorii_neaktivni.'</td>
</tr>


<tr '.__TR_BG__.'>
<td>Počet objednávek</td><td>'.$pocet_objednavek_celkem.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet vyřízených objednávek</td><td>'.$pocet_objednavek_vyrizeno.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet stornovaných objednávek</td><td>'.$pocet_objednavek_storno.'</td>
</tr>



<tr '.__TR_BG__.'>
<td>Počet zákazníků</td><td>'.$pocet_zakazniku.'</td>
</tr>
<tr '.__TR_BG__.'>
<td>Počet aktivních zákazníků</td><td>'.$pocet_zakazniku_aktivni.'</td>
</tr>


</table>';

 

?>


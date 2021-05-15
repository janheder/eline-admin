<?php 
// nápověda

Admin::adminLogin(3);

?>
<a name="top"></a>
<div class="napoveda_pravy_sloupec"><a href="#top"><i class="fas fa-arrow-up" ></i>&nbsp;nahoru</a></div>
Administrace je plně funkční ve všech moderních prohlížečích v posledních verzích: 
<a href="https://www.mozilla.org/cs/firefox/new/" target="_blank">Firefox</a>, 
<a href="https://www.google.com/intl/cs_CZ/chrome/" target="_blank">Chrome</a>, 
<a href="https://www.chromium.org/getting-involved/download-chromium" target="_blank">Chromium</a>, 
<a href="https://www.opera.com/download" target="_blank">Opera</a>, 
<a href="https://brave.com/download/" target="_blank">Brave</a>.
<br>

<ol type="1">
	<li><a href="#produkty">Produkty</a>
		<ol type="I">
			<li><a href="#vyrobci">Výrobci</a></li>
			<li><a href="#dostupnost">Dostupnost</a></li>
			<li><a href="#sablony_tech_par">Šablony technických parametrů</a></li>
			<li><a href="#slevy">Slevy</a></li>
			<li><a href="#sazby_dph">Sazby DPH</a></li>
			<li><a href="#produkty_sklad">Sklad</a></li>
			<li><a href="#produkty_import_xml">Import produktů z XML</a></li>
		</ol>
	</li>
	<li><a href="#kategorie">Kategorie</a></li>
	<li><a href="#objednavky">Objednávky</a>
		<ol type="I">
			<li><a href="#stavy_objednavek">Stavy objednávek</a></li>
			<li><a href="#faktury">Faktury</a></li>
			<li><a href="#slevove_kody">Slevové kódy</a></li>
			<li><a href="#sms_sablony">SMS šablony</a></li>
			<li><a href="#odeslane_sms">Odeslané SMS</a></li>
		</ol>
	</li>
	<li><a href="#zakaznici">Zákazníci</a>
		<ol type="I">
			<li><a href="#skupiny_zakazniku">Skupiny zákazníků</a></li>
		</ol>	
	</li>
	<li><a href="#staticke_stranky">Statické stránky</a></li>
	<li><a href="#fotogalerie">Fotogalerie</a></li>
	<li><a href="#aktuality">Aktuality</a></li>
	<li><a href="#doprava">Doprava</a></li>
	<li><a href="#platba">Platba</a></li>
	<li><a href="#statistiky">Statistiky objednávek</a>
		<ol type="I">
			<li><a href="#statistiky_obecne">Statistiky obecné</a></li>
			<li><a href="#statistiky_grafy">Statistiky grafy</a></li>
			<li><a href="#statistiky_nejprodavanejsi">Statistiky nejprodávanější produkty</a></li>
		</ol>
	</li>
	<li><a href="#obecne_nastaveni">Obecné nastavení</a>
		<ol type="I">
			<li><a href="#obecne_nastaveni_google">Obecné nastavení Google</a></li>
			<li><a href="#obecne_nastaveni_seznam">Obecné nastavení Seznam</a></li>
			<li><a href="#obecne_nastaveni_slevove_kody">Obecné nastavení slevové kódy</a></li>
			<li><a href="#obecne_nastaveni_fakturoid">Obecné nastavení Fakturoid</a></li>
			<li><a href="#obecne_nastaveni_platba_kartou_fio">Obecné nastavení platba kartoi FIO</a></li>
			<li><a href="#obecne_nastaveni_platba_kartou_csob">Obecné nastavení platba kartoi ČSOB</a></li>
			<li><a href="#obecne_nastaveni_platba_kartou_gpwebpay">Obecné nastavení platba kartoi GP WebPay</a></li>
			<li><a href="#obecne_nastaveni_sms">Obecné nastavení SMS</a></li>
		</ol>
	</li>
	<li><a href="#blokovani_ip">Blokování IP</a></li>
	<li><a href="#reklamni_okno">Reklamní okno</a></li>
	<li><a href="#bannery_uvodka">Bannery úvodka</a></li>
	<li><a href="#presmerovani">Přesměrování</a></li>
	<li><a href="#neuspesne_prihlaseni">Neúspěšné přihlášení</a></li>
	<li><a href="#log_udalosti">Log událostí</a></li>
	<li><a href="#admin">Admin</a>
		<ol type="I">
			<li><a href="#admin_login">Admin login</a></li>
		</ol>
	</li>
	<li><a href="#xml_feedy">XML feedy</a></li>
</ol>	
<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<div class="napoveda_text">

<a name="produkty"></a>
<h2 id="produkty">Produkty</h2>
<br>	
Modul <b>Produkty</b> slouží k přidávání nových a editaci stávajících produktů. 
V přehledu lze výpis řadit vzestupně a sestupně klikáním na názvy sloupců. Lze také použít filtr v záhlaví. Vyhledává se dle názvu, katalogového čísla a EAN produktu. 
Dále je možné filtrovat dle stavu, výrobce a kategorie produktu. Lze také vyhledávat dle příznaků akce, novinka, doporučujeme a výprodej. 
Produkt lze smazat. Mažou se i varianty produktu, technické parametry i fotografie.<br>
Každý produkt musí obsahovat alespoň jednu variantu.<br>Povinné položky jsou označeny červenou hzvězdičkou. Validace formuláře probíhá před jeho odesláním.
U nového produktu se zadává název, výrobce, krátký popis, dlouhý popis, DPH, katalogové číslo, EAN, množstevní jednotka, záruka (v měsících nebo letech).<br> 
Dále vybíráte zařazení do kategorií. Produkt může být zařazen v neomezeném množství kategorií. Pokud chcete aby se produkt zobrazoval ve výpise první i druhé úrovně kategorií tak je nutné tyto úrovně zakliknout - nestačí produkt zařadit pouze do poslední úrovně zanoření.<br>
Dále vybíráte příznaky novinka, akce, doporučujeme a výprodej + speciální příznaky doprava zdarma a speciální doprava. 
Dle těchto příznaků lze produkty na prezentační části filtrovat. Příznaky se zobrazují u každého náhledu. 
<br>
Pokud je v košíku produkt s příznakem <b>doprava zdrarma</b> pak dle <a href="./index.php?p=obecne-nastaveni&pp=&action=update&id=124" target="_blank">obecného nastavení</a> je doprava zdarma i pokud jsou v košíku jiné produkty bez tohoto příznaku nebo je doprava zdarma pouze v případě, že v košíku jsou pouze produkty s tímto příznakem<br>
Pokud je v košíku produkt s příznakem <b>speciální doprava</b> pak jsou nabídnuty pouze ty typy dopravy, které mají taktéž příznak speciální doprava.
<br>
Každý z příznaků má i samostatnou stránku s výpisem + dochází k náhodnému výběru produktů s těmito příznaky a jejich zobrazení na úvodní stránce.
Dále je možno vybrat šablonu technických parametrů, tyto šablony je nejdříve nutné nastavit v příslušeném modulu. Po výběru šablony technických parametrů se vygenerují základní parametry produktu.
<br>Další na řadě jsou varianty. Do názvu varianty lze vepsat např. barvu nebo jiný parametr, který bude jednoduše identifikovat danou variantu. 
U varianty lze zadat až 4 cenové skupiny. Běžný zákazník má ceny A. V modulu zákazníci můžete přiřadit určitým zákazníkům jiné cenové hladiny (B,C,D). Lze také systém upravit tak aby byla některá z cenových skupin nastavena zákazníkovi automaticky po regulérní registraci. Povinné je vyplnit pouze cenu A. Pokud nevyplníte ostatní ceny pak tyto přeberou hodnotu z ceny A. 
Všechny ceny se zadávají bez DPH. K variantě lze přidat jednu fotografii, kde jde vidět zřejmá odlišnost od ostatních variant. Dále je možno zadat slevu v % a datum od - do, kdy sleva platí. 
Tato sleva je platná pro všechny běžné zákazníky, kteří mají přiřazeny A ceny. Lze však systém upravit tak, aby tato sleva byla platná pro všechny zákazníky, tedy i ty s cenami B,C,D. 
Dále se zadává katalogové číslo varianty, počet kusů skladem, dostupnost a stav aktivní / neaktivní. Variant může být neomezeně mnoho.
<br> 
Dále lze přidat neomezené množství fotografií k produktu. Tyto fotografie se vybírají hromadně. V jednom kroku lze vložit i 10 fotografií. Názvy k fotkám se pak doplňují při editaci. 
Obdobně lze přidat neomezené množství příloh. Názvy příloh lze přidat při editaci. Při editaci lze také fotky a přílohy řadit pouhým přetažemím myší.
Přo Zboží.cz lze rozkliknout podrobné nastavení a nastavit veškeré možné parametry.
Obdobně lze rozkliknout a nastavit SEO parametry včetně údajů pro Heureku. 
Ke každému produktu lze přidat interní poznámku. Poslední parametrem je stav. Neaktivní produkt se nezobrazuje na prezentační vrstvě v kategoriích ani ve vyhledávání. Neexportuje se ani na porovnávače zboží.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="vyrobci"></a>
<h2 id="vyrobci">Výrobci</h2>
<br>
Nepovinný číselník s vazbou na produkty. Pokud jsou <b>výrobci</b> vyplněni a je u produktu vybrán výrobce pak lze dle výrobců vyhledávat v administraci produktů a na prezentační části je v kategoriích vygenerován filtr s názvy výrobců. Dále je pak generována pro každého výrobce na prezentační části stránka s výpisem všech produktů daného výrobce. Na této stránce se vypisuje v záhlaví i popis výrobce, ktery je možno zadat v příslušené části v administraci výrobců.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="dostupnost"></a>
<h2 id="dostupnost">Dostupnost</h2>
<br>
Povinný číselník. Zadává se textová <b>dostupnost</b> + dostupnost číselná ve dnech pro porovnávače zboží. Dle dostupnosti lze také filtrovat na prezentační části.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="sablony_tech_par"></a>
<h2 id="sablony_tech_par">Šablony technických parametrů</h2>
<br>
<b>Šablony techníckých parametrů</b> zjednoduší a zrychlí zadávání obecných parametrů při tvorbě nových produků. Zadává se název šablony a následně názvy jednotlivých parametrů.
Počet položek v šabloně ani počet samotných šablon není omezen.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="slevy"></a>
<h2 id="slevy">Slevy</h2>
<br>
V modulu <b>slevy</b> lze zadávat hromadné slevy produktů. Sleva se zadává v %. Zadává se také datum od-do kdy je sleva platná. Je možno vybrat kategorii a výrobce. Všem produktům z vybrané kategorie, případně ještě v kombinaci s konkrétním výrobcem bude nastavena daná sleva. Změna je nevratná. Pokud chcete opět hromadně slevu zrušit před vypršením platnosti tak uvedené skupině kategorií / výrobců nastavíte slevu 0%. Tím se ceny vrátí na původní hodnoty. Slevy se propisují do variant produktů stejně jako bychom je zadávali ručně.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="sazby_dph"></a>
<h2 id="sazby_dph">Sazby DPH</h2>
<br>
V modulu jsou přednastaveny všechny aktuálně platné daně. Lze přidat nové. V detailu produktu, v dopravě, platbě se pak vybírá z tohoto číselníku výše <b>DPH</b>.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="produkty_sklad"></a>
<h2 id="produkty_sklad">Sklad</h2>
<br>
Výpis variant všech produktů včetně aktuálních skladových zásob.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="produkty_import_xml"></a>
<h2 id="produkty_import_xml">Import produktů z XML</h2>
<br>
V tomto modulu můžete provést jednorázový <b>import produktů</b> z XML souboru do eshopu. Do kategorií je nutné produkty zařadit ručně.<br>
XML soubor musí být kompatibilní s <a href="https://sluzby.heureka.cz/napoveda/xml-feed/" target="_blank">XML pro Heureku</a>. 





<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="kategorie"></a>
<h2 id="kategorie">Kategorie</h2>
<br>
<b>Kategorie produktů</b> mohou být zanořeny až do tří úrovní. U kategorií se zadává název, popis, úroveň zanoření, u druhé a třetí úrovně nadřazená kategorie, dále šablona technických parametrů, foto, stav a SEO parametry. Popis se zobrazuje v detailu kategorie na prezentační části. Fotografii kategorie lze použít do menu nebo do záhlaví kategorie pro generování boxů se zanořenými kategoriemi. Dle vybrané šablony se pak v kategoriích na prezentační vrstvě může generovat parametrický filtr. Ve výpisu kategorií lze jednoduše hromadně řadit položky změnou čísel řazení a následným uložením. V jednom kroku tak můžete přeřadit více kategorií.
<br>Dalším způsobem řazení je řazení přetažením řádku na příslušnou pozici. Myší najeďte do prvního sloupce, změní se kurzor. Stiskněte levé tlačítko myši, držte ho stisknuté a přesuňte řádek kam je potřeba. Následně pusťte tlačítko. Kategorie lze tímto způsobem řadit pouze v rámci úrovně (první úrovně mezi sebou, druhé a třetí úrovně v rámci nadřazené kategorie). Přetažení je možné jen do šedé zóny. Pokud je zóna kam chcete přesouvat červená pak přesun není možný.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="objednavky"></a>
<h2 id="objednavky">Objednávky</h2>
<br>
<b>Výpis objednávek</b> od nejnovějších. V přehledu lze řadit klikem na název sloupce tak jako ve všech ostatních modulech. Dále lze vyhledávat dle čísla objednávky, stavu objednávky, 
jména a příjmení, emailu, telefonu a obce zákazníka, také dle datumu realizace objednávky.<br>
Z přehledu lze hromadně tisknout vybrané objednávky, dále lze hromadně exportovat objednávky do XML pro import do Pohody, do CSV pro Zásilkovnu a do CSV pro Českou poštu.
<br>
V detailu objednávky lze měnit počet kusů i cenu objednaných produktů, lze smazat položku objednávky, lze přidat novou položku do objednávky. 
V detailu objednávky zadáváte váhu zásilky a udanou cenu (pojištění) zásilky. Lze zadat interní poznámku k dané objednávce.
V záhlaví se lze prokliknout přes jméno zákazníka na jeho detail. Pokud je vygenerovaná faktura ve Fakturoidu pak je na ni v záhlaví odkaz. 
Lze nastavit stav úhrady a stav objednávky. Po kliku na tlačítko "Odeslat zákazníkovi email" se odešle HTML formátovaný email s vybraným stavem objednávky. Tento email se generuje na základě šablony stavů objednávek. Předdefinované parametry v hranatých závorkách [] jsou nahrazeny skutečnými hodnotami. U stavů zboží odesláno a osobní odběr je do emailu vygenerován odkaz na fakturu (pokud je tato faktura k dispozici). Pokud je zakliknut checkbox "přidat slevový kód" pak je vygenerován a do emailu přidán jednorázový slevový kód. Typ, hodnota, platnost a další parametry si nastavujete v Obecném nastavení v sekci Slevové kódy. Tuto funkcionalitu lze deaktivovat. 
<br> 
Při změně stavu objednávky není nutné objednávku ukládat tlačítkem "Uložit provedené úpravy". K uložení změny stavu dochází na pozadí po kliku na tlačítko "Odeslat zákazníkovi email". 
Před samotným odesláním je možno v editačním okně WYSIWYG editoru modifikovat obsah odchozího emailu.<br> 
V detailu můžeme objednávku vytisknout, exportovat do XML pro Pohodu, CSV pro Zásilkovnu a Českou poštu.<br>
Dále je možné vygenerovat fakturu ve Fakturoidu. Nejdříve je však nutné nastavit v Obecném nastavení v sekci Fakturoid udaje k propojení. Při zakliknutí "reverse charge" se faktura generuje v režimu přenesení daňové povinnosti. Vygenerovanou fakturu lze smazat. To se hodí v případě, že chcete upravovat již uzavřenou objednávku.<br>
Dole na stránce je k dispozici historie změn stavů objednávky. Veškeré změny a úpravy ve všech modulech se monitorují a ukládají do logu, který je přístupný v modulu Log událostí.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="stavy_objednavek"></a>
<h2 id="stavy_objednavek">Stavy objednávek</h2>
<br>
Máme předdefinováno 6 základních <b>stavů objednávek</b>. Další stavy lze případně přidat. Každý stav si nese emailovou šablonu s proměnnými k nahrazení. 

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="faktury"></a>
<h2 id="faktury">Faktury</h2>
<br>
Přehled <b>faktur</b> s možností vyhledávání a filtrování. Lze vyhledávat dle čísla faktury, čísla objednávky a jména zákazníka. 
Veškeré faktury se generují přes Fakturoid. V přehledu faktur naleznete odkaz na detail Faktury vedoucí na portál Fakturoidu. 
Odkaz na fakturu je také v detailu objednávky. Stav faktury si můžete změnit v detailu objednávky. Pokud vygenerujete fakturu tak se automaticky nastavuje stav Zaplaceno, protože se 
předpokládá, že fakturu budete generovat až po úplném uhrazení objednávky.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="slevove_kody"></a>
<h2 id="slevove_kody">Slevové kódy</h2>
<br>
V modulu <b>slevové kódy</b> si můžete vytvořit neomezené množství slevových kódů. 
Je zde i přehled slevových kódů, které se automaticky generují s každou vyřízenou objednávkou nad určitou částku - pokud toto máte povoleno v obecném nastavení, v sekci slevové kódy.
Slevové kódy můžete upravovat i mazat. Úprava slevových kódů, které již byly odeslány zákazníkům se nedoporučuje.
<br>U slevového kódu vybíráte typ - kód na pevnou částku nebo %. Dále jestli je kód jednoráznový nebo lze používat opakovaně, výši slevového kódu, platnost od - do a stav.
Ve filtru v záhlaví lze vyhledávat dle kódu. Stačí zadat jen část slevového kódu.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="sms_sablony"></a>
<h2 id="sms_sablony">SMS šablony</h2>
<br>
V modulu <b>SMS šablony</b> si můžete vytvořit neomezené množství SMS šablon včetně proměnných k nahrazení. <br>
Registrace na SMS bráně <a href="https://www.sms-sluzba.cz/users/new?prov_user=<?php echo __SMS_AFF__;?>" target="_blank">zde</a>.<br>

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="odeslane_sms"></a>
<h2 id="odeslane_sms">Odeslané SMS</h2>
<br>
V modulu <b>Odeslané SMS</b> naleznete přehled všech odeslaných SMS z SMS modulu. Evidujeme tel. číslo, text SMS, návratový stav, datum. 
Dle tel. čísla lze v přehledu vyhledávat. 




<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="zakaznici"></a>
<h2 id="zakaznici">Zákazníci</h2>
<br>
V modulu <b>zákazníci</b> evidujete všechny své zákazníky. V přehledu lze opět filtrovat a vyhledávat dle jména, příjmení, emailu, stavu a obce.<br>
Záznamy zákazníků můžete upravovat mazat nebo přidávat. Můžete dodatečně nastavovat cenovou nebo slevovou skupinu, případně přihlášení k odběru newsletteru.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="skupiny_zakazniku"></a>
<h2 id="skupiny_zakazniku">Skupiny zákazníků</h2>
<br>
Jedná se o <b>slevové skupiny</b>, do kterých můžete zákazníky dodatečně zařadit. 
Každý zákazník zařazený do takové skupiny má na všechny produkty nastavenu uvedenou slevu. Vyjímkou jsou produkty, které mají samy nastavenu nějakou slevu v aktuální platnosti.
Ponížené ceny uvidí takový zákazník až po přihlášení. Pokud u slevové skupiny zakliknete checkbox "za registraci" pak je do této skupiny slev zařazen každý zákazník, který provede regulérní registraci. 

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="staticke_stranky"></a>
<h2 id="staticke_stranky">Statické stránky</h2>
<br>
V tomto modulu obsluhujete všechny statické stránky na eshopu. K dispozici je WYSIWYG editor se správcem obrázků a souborů. Text lze libovolně formátovat obdobně jako v textovém editoru Word. 
U obrázků nahraných do knihovny lze provádět základní úpravy, filtry, ořez atd. 
<br>Stránky lze řadit přetažením řádku na příslušnou pozici. Myší najeďte do prvního sloupce, změní se kurzor. Stiskněte levé tlačítko myši, držte ho stisknuté a přesuňte řádek kam je potřeba. 
Následně pusťte tlačítko.
<br>
U každé stránky zadáváte nadpis pro menu, nadpis pro stránku, obsah, SEO parametry, typ = je v top menu nebo není. Ke každé stránce lze "připnout" fotogalerii. Ta se pak generuje ve formě 
náhledů pod obsahovým textem. Poslední položkou je stav aktivní / neaktivní. 
Všechny aktivní stránky se pak vypisují v patičce eshopu. Pokud do editoru vkládáte text z jiných stránek či dokumentů tak vždy vkládejte jako čistý text: <b>upravit -> vložit jako čistý text</b>
<br>
Zde výzam jednotlivých ikonek v editoru:
<br>
<img src="./img/napoveda/zpet.png" style="vertical-align: middle;"> zpět - slouží ke zrušení předchozí akce a návratu na původní stav. Lze použít více kroků<br>
<img src="./img/napoveda/vpred.png" style="vertical-align: middle;"> vpřed - slouží k obnovení zrušení akce tlačítkem zpět. Lze použít více kroků<br>
<img src="./img/napoveda/bold.png" style="vertical-align: middle;"> bold - tlusté písmo<br>
<img src="./img/napoveda/italic.png" style="vertical-align: middle;"> italic - šikmé tenké písmo<br>
<img src="./img/napoveda/zarovnani_vlevo.png" style="vertical-align: middle;"> zarovnání textu vlevo<br>
<img src="./img/napoveda/zarovnani_center.png" style="vertical-align: middle;"> zarovnání textu na střed<br>
<img src="./img/napoveda/zarovnani_vlevo.png" style="vertical-align: middle;"> zarovnání textu vlevo<br>
<img src="./img/napoveda/zarovnani_blok.png" style="vertical-align: middle;"> zarovnání textu do bloku<br>
<img src="./img/napoveda/seznam.png" style="vertical-align: middle;"> seznam nečíslovaný, šipkou vpravo vybíráte typ (kolečko, čtvereček...)<br>
<img src="./img/napoveda/seznam2.png" style="vertical-align: middle;"> seznam číslovaný, šipkou vpravo vybíráte typ (kolečko, čtvereček...)<br>
<img src="./img/napoveda/zmensit_odsazeni.png" style="vertical-align: middle;"> zmenšit odsazení bloku<br>
<img src="./img/napoveda/zvetsit_odsazeni.png" style="vertical-align: middle;"> zvětšit odsazení bloku<br>
<img src="./img/napoveda/vlozit_odkaz.png" style="vertical-align: middle;"> vložit nebo změnit odkaz<br>
<img src="./img/napoveda/vlozit_obrazek.png" style="vertical-align: middle;"> vložit nebo upravit obrázek<br>
<img src="./img/napoveda/vlozit_obrazek.png" style="vertical-align: middle;"> vložit soubor<br>
<img src="./img/napoveda/zdrojovy_kod.png" style="vertical-align: middle;"> zobrazit zdrojový kód<br>
<img src="./img/napoveda/tisk.png" style="vertical-align: middle;"> tisk<br>
<img src="./img/napoveda/nahled.png" style="vertical-align: middle;"> náhled obsahu v novém okně<br>
<img src="./img/napoveda/video.png" style="vertical-align: middle;"> vložit nebo upravit video<br>
<img src="./img/napoveda/barva_textu.png" style="vertical-align: middle;"> barva textu<br>
<img src="./img/napoveda/barva_pozadi.png" style="vertical-align: middle;"> barva pozadí<br>
<img src="./img/napoveda/emotikony.png" style="vertical-align: middle;"> emotikony<br>
<img src="./img/napoveda/vlozit_kod.png" style="vertical-align: middle;"> vložit ukázkový kód se zvýrazněním syntaxe<br>
<img src="./img/napoveda/napoveda.png" style="vertical-align: middle;"> nápověda + klávesové zkratky<br>
<br>

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="fotogalerie"></a>
<h2 id="fotogalerie">Fotogalerie</h2>
<br>
V tomto modulu si můžete připravit neomezené množství <b>fotogalerií</b>, tyto pak můžete přidat ke statické stránce nebo k aktualitě. 
<br>Zadáváte název fotogalerie a popis. Následně hromadně přidáváte fotografie. Po uložení fotografií můžete přidat ke každé fotce popis a řadit fotky přetažením myší.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="aktuality"></a>
<h2 id="aktuality">Aktuality</h2>
<br>
V modulu <b>aktuality</b> můžete přidávat aktuální zprávy z vaší firmy, informovat o různých slevových akcích, novinkách a výprodejích s prolinkováním z textu na konkrétní produkt či kategorii. 
<br>U každé aktuality se zadává název, perex (krátký text), obsah, autor, zdroj (pokud čerpáte odjinud), příznak na úvod - aktualita se zobrazí na úvodní stránce eshopu, dále datum vytvoření aktuality, SEO parametry, fotografii - ta ze zobrazuje v náhledech i v detailu aktuality v záhlaví, dále můžete připnout celou fotogalerii a nakonec stav aktivní / neaktivní.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="doprava"></a>
<h2 id="doprava">Doprava</h2>
<br>
Způsoby <b>dopravy</b>, ze kterých si může zákazník vybírat při realizaci objednávky v eshopu. 
Ke každému typu dopravy si přiřazujete typy plateb, dále popis, příznaky balík na poštu, balík do balíkovny a zásilkovna. Cena se zadává bez DPH.
Typy dopravy je možno rozdělit podle ceny zboží v košíku. Např. pro cenu od 0 do 500 Kč bude PPL doprava za 150 Kč, od 501 do 10000 Kč bude PPL doprava za 90 Kč a od 10001 do 1000000 Kč bude PPL doprava za 0 Kč. Pokud rozdělení podle ceny zboží v košíku nechcete používat pak u všech typů dopravy doplňte cenu od 0 a cenu do 1000000.<br>
I pokud rozdělení podle cen používáte, stále se bere v potaz cena od zdarma (<?php echo __POSTOVNE_ZDARMA__?>), kterou máte <a href="./index.php?p=obecne-nastaveni&pp=&action=update&id=96" target="_blank">nastavenu v obecném nastavení</a>. Vždy, když dojde k překročení ceny zboží v košíku nad tuto nastavenou hodnotu tak dojde k vynulování ceny dopravy.
<br>Příznak <b>speciální doprava</b> - pokud zákazník vloží do košíku produkt s příznakem speciální doprava pak se mu v košíku vygenerují pouze ty typy dopravy, které mají taktéž příznak speciální doprava.<br> 
<br>Způsoby dopravy lze řadit přetažením řádku na příslušnou pozici. Myší najeďte do prvního sloupce, změní se kurzor. Stiskněte levé tlačítko myši, držte ho stisknuté a přesuňte řádek kam je potřeba. Následně pusťte tlačítko.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="platba"></a>
<h2 id="platba">Platba</h2>
<br>
Způsoby <b>platby</b>, ze kterých si může zákazník vybírat při realizaci objednávky v eshopu. 
Zadáváte název, popis, příznaky převodem, karta a dobírka. Cena se zadává bez DPH. 
Pokud máte systém napojen na Fakturoid tak je nutné vyplnit i Fakturoid payment method (card - platba kartou, cod - dobírka, bank - bankovním převodem, cash - hotově).
<br>Způsoby platby lze řadit přetažením řádku na příslušnou pozici. Myší najeďte do prvního sloupce, změní se kurzor. Stiskněte levé tlačítko myši, držte ho stisknuté a přesuňte řádek kam je potřeba. 
Následně pusťte tlačítko.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="statistiky"></a>
<h2 id="statistiky">Statistiky objednávek</h2>
<br>
<b>Přehled objednávek</b> po měsících - počet objednávek a cena celkem. Rozdělení dle refereru - odkud zákazníci na eshop přišli. 
<br>Lze filtrovat dle stavů objednávek a klikem na počet objednávek v buňce se zobrazí v novém okně seznam objednávek za daný měsíc a daný referer s možností prokliku na detail objednávky.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="statistiky_obecne"></a>
<h2 id="statistiky_obecne">Statistiky obecné</h2>
<br>
<b>Základní přehled</b> o počtu produktů, variant, kategorií, zákazníků a objednávek.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="statistiky_grafy"></a>
<h2 id="statistiky_grafy">Statistiky grafy</h2>
<br>
<b>Grafy</b>, které se generují dle zadaného datumu od - do. 
Zobrazují počet objednávek a ceny za objednávky v čase po dnech.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="statistiky_nejprodavanejsi"></a>
<h2 id="statistiky_nejprodavanejsi">Statistiky nejprodávanější produkty</h2>
<br>
Přehled <b>nejprodávanějších produktů</b> za zvolené období. Pokud není zvoleno ve filtru žádné období, jsou zobrazeny nejprodávanější produkty za aktuální měsíc. 





<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni"></a>
<h2 id="obecne_nastaveni">Obecné nastavení</h2>
<br>
<b>Základní nastavení</b> důležitých údajů a parametrů eshopu jako je číslo účtu, email from a to, základní sazba DPH pro orientační výpočty cen s DPH, měna, základní metatagy, počet výpisů v adminu i na prezentační vrstvě, počty novinek, akcí, doporučujeme, výprodej, částka od které je poštovné zdarma atd. Nastavujete zde také typ platby kartou (FIO, ČSOB, GP WebPay).




<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_google"></a>
<h2 id="obecne_nastaveni_google">Obecné nastavení Google</h2>
<br>
<b>Nastavení Google</b> zde zadáváte jakékoliv měřící kódy, dále ID pro generování měření el. obchodu, dále API key pokud používáte další služby jako zjišťování polohy, výpočet vzdáleností mezi místy, překladač atd. Captcha klíče jsou přednastaveny. 



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_seznam"></a>
<h2 id="obecne_nastaveni_seznam">Obecné nastavení Seznam</h2>
<br>
<b>Nastavení Seznam</b> zde zadáváte ID pro generování měřících a sledovacích kódů pro Seznam retargeting, konverze Zboží.cz a konverze Sklik.cz



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_slevove_kody"></a>
<h2 id="obecne_nastaveni_slevove_kody">Obecné nastavení slevové kódy</h2>
<br>
Nastavení pro automaticky generované <b>slevové kódy</b>. Nastavujete jestli chcete automaticky generovat slevové kódy. Od jaké částky objednávky, typ, hodnotu, dobu platnosti, opakované použití. 


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_fakturoid"></a>
<h2 id="obecne_nastaveni_fakturoid">Obecné nastavení Fakturoid</h2>
<br>
Nastavení propojení na fakturační systém <b>Fakturoid</b>.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_platba_kartou_fio"></a>
<h2 id="obecne_nastaveni_platba_kartou_fio">Obecné nastavení Platba kartou FIO</h2>
<br>
Pokud chcete na eshopu používat online platbu kartou přes <b>FIO banku</b> pak vyplňte veškeré údaje.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_platba_kartou_csob"></a>
<h2 id="obecne_nastaveni_platba_kartou_csob">Obecné nastavení Platba kartou ČSOB</h2>
<br>
Pokud chcete na eshopu používat online platbu kartou přes <b>ČSOB</b> pak vyplňte veškeré údaje.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_platba_kartou_gpwebpay"></a>
<h2 id="obecne_nastaveni_platba_kartou_gpwebpay">Obecné nastavení Platba kartou GP WebPay</h2>
<br>
Pokud chcete na eshopu používat online platbu kartou přes <b>GP WebPay</b> pak vyplňte veškeré údaje.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="obecne_nastaveni_sms"></a>
<h2 id="obecne_nastaveni_sms">Obecné nastavení SMS</h2>
<br>
Pokud se zaregistrujete na <a href="https://www.sms-sluzba.cz?prov_user=<?php echo __SMS_AFF__;?>" target="_blank">www.sms-sluzba.cz</a> a údaje vyplníte v Obecném nastavení v sekci SMS pak můžete z detailu objednávky zákazníkům odeslílat <b>SMS zprávy</b>. Šablony SMS zpráv si editujete v <a href="">příslušeném modulu</a>.<br>
Registrace na SMS bráně <a href="https://www.sms-sluzba.cz/users/new?prov_user=<?php echo __SMS_AFF__;?>" target="_blank">zde</a>.<br>



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="blokovani_ip"></a>
<h2 id="blokovani_ip">Blokování IP</h2>
<br>
Zde můžete zablokovat uživatele dle jejich <b>IP adresy</b>. Může se jednat o robota, který se snaží nabourat do adminu (po 3 neúspěšných pokusech ho do této databáze uloží sám systém), 
dále uživatele nebo robota, který provádí opakované nesmyslné registrace či objednávky. Uživatel se zablokovanou IP adresou nemůže dokončit objednávku ani se zaregistrovat.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="reklamni_okno"></a>
<h2 id="reklamni_okno">Reklamní okno</h2>
<br>
<b>Reklamní okno</b> může obsahovat jakékoliv informace. K dispozici je WYSIWYG editor. Lze vložit a formátovat text i obrázek. 
U reklamního okna se nastavuje nadpis, obsah, stav a datum od - do kdy je aktivní. 
Reklamní okno se uživateli zobrazí pouze 1x denně. Můžete pomocí něj upozorňovat na uzavření provozovny, na dovolenou, na nové zboží atd...

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="bannery_uvodka"></a>
<h2 id="bannery_uvodka">Bannery úvodka</h2>
<br>
Správa <b>bannerů</b> zobrazovaných na úvodní stránce ve slideru. 
Zadává se obrázk, text a URL kam má po kliku směřovat. Bannerů může být neomezeně mnoho ale nepodoručuji to s počtem přehánět. Zpomaluje se načítání úvodní stránky. 
Zadávejte max. 6 aktivních bannerů.




<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="presmerovani"></a>
<h2 id="presmerovani">Přesměrování</h2>
<br>
V tomto modulu si můžete nastavit <b>přesměrování</b> z jakékoliv i neexistující URL adresy v rámci webu na jakoukoliv jinou (i externí). 
Například při změně systému eshopu, kdy chcete vuyžít potenciál původních zaindexovaných URL adres. 
<br>
K přesměrování dochází přes header 301 Moved Permanently.



<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="neuspesne_prihlaseni"></a>
<h2 id="neuspesne_prihlaseni">Neúspěšné přihlášení</h2>
<br>
V tomto modulu je přehled <b>neúspěšných pokusů o přihlášení</b> do administrace eshopu. Z přehledu lze rovnou prokliknout na zablokování IP adresy daného útočníka.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="log_udalosti"></a>
<h2 id="log_udalosti">Log událostí</h2>
<br>
V tomto modulu se evidují veškeré <b>události</b> a změny provedené v administraci eshopu. Od přihlášení uživatele do administrace přes přidání produktu, jeho editaci, smazání atd.
Lze vyhledat podle uživatelského jména admina nebo do určitého datumu.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="admin"></a>
<h2 id="admin">Admin</h2>
<br>
Modul pro správu <b>administrátorů</b>. K dispozici jsou tři úrovně práv. Pouze úroveň 5 má přístup do tohoto modulu a může přidávat další administrátory. Taktéž pro přístup do statistik je nutná úroveň 5.

<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="admin_login"></a>
<h2 id="admin_login">Admin login</h2>
<br>
Přehled přihlášení všech administrátorů včetně IP adres, země a otisků prohlížeče. Lze vidět aktivně přihlášené <b>adminy</b> a lze u nich smazat sešnu a tím je odhlásit z administrace.


<div class="clear" style="height:40px;"></div>
<div class="cara"></div>
<div class="clear" style="height:20px;"></div>

<a name="xml_feedy"></a>
<h2 id="xml_feedy">XML feedy</h2>
<br>
Seznam XML feedů s možností okamžitého přegenerování po kliku na příslušné tlačítko.<br>
K automatickému generování XML feedů dochází 1x denně v noci.


</div> 
<br>
<br>



<style>
html 
{
  scroll-behavior: smooth;
}

.napoveda_pravy_sloupec
{
float: right;
margin-left: 1280px;
position: fixed;
z-index: 500;
width: 70px;
height: 25px;
line-height: 25px;
padding-left: 10px;
background-color: #F1F1F1;
}
</style>
<script>
  $('a[href="#top"]').click(function() 
  {
	  $('h2').each(function() 
	  {  
		 var tid = this.id;
		 $('#'+tid).css({'color': '#484849'});
		 
	  });
  });
  
$('a[href*="#"]').click(function() 
  {
	var kotva = $(this).attr('href');
	kotva = kotva.substr(1);
	$('#'+kotva).css({'color': '#ff3c06'});
  });


 
</script>

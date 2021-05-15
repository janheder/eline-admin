<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" name="viewport">
    <title><?php echo StringUtils::capitalize(__DOMENA__);?> - admin</title>
    <meta content="noindex,nofollow,nosnippet,noarchive" name="robots">
    <link rel="stylesheet" href="./css/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="./css/jquery.tagsinput-revisited.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/colorbox.css" />
    <link rel="stylesheet" href="./css/admin.css?ver=<?php echo __JS_VERZE__;?>" type="text/css" media="all" />
    <link rel="stylesheet" href="./css/admin_tisk.css?ver=<?php echo __JS_VERZE__;?>" type="text/css" media="print" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
    <script src="./js/jquery-3.4.1.js"></script>
    <script src="./js/jquery.tagsinput-revisited.min.js"></script>
    <script src="./js/jquery-ui.js"></script>
    <script src="./js/jquery.validate.min.js"></script>
    <script src="./js/localization/messages_cs.min.js"></script>
    <script src="./js/jquery-ensure-max-length.min.js"></script>
    <script src="./js/jquery.colorbox.js"></script>
    <script src="./js/funkce.js?ver=<?php echo __JS_VERZE__;?>"></script>
    <?php 
    // pouze pro admin a zakaznici
    if($_GET['p']=='zakaznici' || $_GET['p']=='admin')
    {
	   echo '<script src="./js/zxcvbn.js"></script>';
	   echo '<script src="./js/zxcvbn-bootstrap-strength-meter.js"></script>';
	}
	// editor pouze do některých stránek
	if($_GET['p']=='staticke-stranky' || $_GET['p']=='aktuality' || $_GET['p']=='produkty' || $_GET['p']=='reklamni-okno' || $_GET['p']=='reklamni-okno'
	 || $_GET['p']=='objednavky')
	{
		echo '<script src="https://cdn.tiny.cloud/1/3var4i7n0a8dc5uup1yl6ceuyngyxd90mw725oecymexnluz/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>';
	}
    ?>
    
    <script>
	
	  $(function() 
	  {
	    $("#datepicker").datepicker({dateFormat: "dd.mm.yy"});
	  });
	  
	   $(function() 
	  {
	    $(".dat").datepicker({dateFormat: "dd.mm.yy"});
	  });
	  
	   $(function() 
	  {
	    $("#datepicker_od").datepicker({dateFormat: "dd.mm.yy"});
	  });
	  
	   $(function() 
	  {
	    $("#datepicker_do").datepicker({dateFormat: "dd.mm.yy"});
	  });
	  

  </script>
  <script>
			$(document).ready(function()
			{
			
				$(".iframe").colorbox({iframe:true, width:"750px", height:"450px"});
				
			});
		</script>
  </head>

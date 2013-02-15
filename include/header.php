<?
echo"
<!DOCTYPE html>
    <html lang=\"it\">
    <head>
    <meta charset=\"utf-8\">
    <title>Agenti</title>
 
    <!--Pannello di gestione -->
    <link href=\"css/admin.css\" rel=\"stylesheet\" type=\"text/css\" />
    <link rel=\"stylesheet\" href=\"css/jquery.ui.all.css\">
	<script src=\"include/jquery-1.9.0.js\"></script>
	<script src=\"include/ui/jquery.ui.core.js\"></script>
	<script src=\"include/ui/jquery.ui.widget.js\"></script>
	<script src=\"include/ui/jquery.ui.datepicker.js\"></script>
	<script>
	$(function() {
		$( \"#datepicker\" ).datepicker({
			showOtherMonths: true,
			selectOtherMonths: true
		});
	});
	</script>
	<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
	<script src=\"include/jquery.horizontalNav.js\"></script>
	<script>
    $(document).ready(function() {
        $('.full-width').horizontalNav();
    });
    </script>
    
    <script language=\"javascript\"> 
	function slideonlyone(thechosenone) {
     $('.newboxes2').each(function(index) {
          if ($(this).attr(\"id\") == thechosenone) {
               $(this).slideDown(200);
          }
          else {
               $(this).slideUp(600);
          }
     });
	};
	</script>
	
</head>
<body>
<img src=\"image\logo.png\" alt=\"Linkspace Logo\" height=\"228\"><br />
";
?>

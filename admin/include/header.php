<?
echo"
<!DOCTYPE html>
<html>
<head>
 
    <title>Collegati per amministrare il sito - Agenti</title>
 
    <!--Pannello di gestione creato da Mel Riccardo-->
    <link href=\"css/admin.css\" rel=\"stylesheet\" type=\"text/css\" />
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
}
	</script>
</head>
<body>
";
?>

<?
session_start();
require ("include/config.php");
include ("include/header.php");
$conn=mysql_connect($dbHost,$dbUser,$dbPassword);
mysql_select_db($dbName);
$codadmin = $_SESSION['admin']; //id cod recuperato nel file di verifica
if (!isset($_SESSION['admin'])){
// se non loggato
      echo "<form id=\"login\" action=\"verifica.php\" method=\"post\">
        <fieldset id=\"inputs\">
            <input id=\"username\" name=\"username\" type=\"text\" placeholder=\"Utente\" autofocus required>
            <input id=\"password\" name=\"password\" type=\"password\" placeholder=\"Password\" required>
        </fieldset>
        <fieldset id=\"actions\">
            <input type=\"submit\" id=\"submit\" value=\"Collegati\">
            <a href=\"index.php\" id=\"back\">Ritorna al sito</a>
        </fieldset>
    </form>";
}
else {
	echo '<script language=javascript>document.location.href="main.php"</script>';
	}
include ("include/footer.php");

?>

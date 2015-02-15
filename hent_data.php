<?Php
require 'sanntidpluss_class.php';
$sanntidpluss=new sanntidpluss;

$proposals=5;
$maxdistrance=1400;
if(isset($_GET['X']) && isset($_GET['Y']))
	$uri="Place/GetClosestStops/?coordinates=(X=".$_GET["X"].",Y=".$_GET["Y"].")&proposals=$proposals"; 
elseif(isset($_GET['navn']))
	$uri='Place/GetPlaces/'.urlencode(utf8_decode($_GET['navn']));

echo $json=$sanntidpluss->get($uri,true);
?>
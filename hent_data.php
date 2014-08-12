<?Php
require '../trafikanten/ruter_rest_class.php';
$ruter=new ruter_rest;
$coordinates='(x=600810,y=6631230)';
$proposals=1;
$maxdistrance=1400;
if(isset($_GET['X']) && isset($_GET['Y']))
	$uri="Place/GetClosestStops/?coordinates=(X=".$_GET["X"].",Y=".$_GET["Y"].")&proposals=5"; 
elseif(isset($_GET['navn']))
	$uri='Place/GetPlaces/'.urlencode(utf8_decode($_GET['navn']));

//print_r($ruter->get('Place/GetClosestStops?coordinates=$coordinates&proposals=$proposals&maxdistance=$maxdistance'));
echo $json=$ruter->get($uri,true);
//print_r(json_decode($json,true));
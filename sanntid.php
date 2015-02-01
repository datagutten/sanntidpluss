<?Php
require 'preferences.php';
require 'ruter_rest_class.php';
$ruter=new ruter_rest;
if(!isset($_GET['stop']))
	$stopname='Ingierkollveien';
elseif(!is_numeric($_GET['stop']))
	$stopname=$_GET['stop'];
else
	$stopid=$_GET['stop'];

if(!isset($stopid))
{
	$stop=$ruter->get('Place/GetPlaces/'.urlencode($stopname));
	$stopid=$stop[0]['ID'];
}

$departures=$ruter->get('StopVisit/GetDepartures/'.$stopid);
$stopinfo=$ruter->get("Place/GetStop/$stopid");
///print_r($stopinfo);
foreach($departures as $departure)
{
	//if($departure['MonitoredVehicleJourney']['Monitored']!==true)
		//continue;
	$linedepartures[$departure['MonitoredVehicleJourney']['LineRef']][$departure['MonitoredVehicleJourney']['DestinationName']][]=$departure;
}
//print_r($departures);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="format-detection" content="telephone=no" />

<title><?Php echo $stopinfo['Name']; ?> Sanntid+</title>
<style type="text/css">
.sanntid {
	display:block;
	float:left;
	color:#fed100;
	font-size:11px;
	margin-right:15px;
	cursor:default;
	margin-bottom:0
}
</style>

<link href="sanntid.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?Php echo "<h1>{$stopinfo['Name']}</h1>\n<h2>{$stopinfo['District']}</h2>\n"; ?>
<section class="realtime">
<ul>
<?Php
//print_r($stop);
//echo $stop[0]
foreach($linedepartures as $line_number=>$line)
{
	foreach($line as $destination_name=>$direction)
	{
		$showheader=true;
		foreach($direction as $departurekey=>$departure)
		{
			//print_r($departure);
			//var_dump($departure['MonitoredVehicleJourney']['Monitored']);
			$arrival=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']);
			if(strlen($departure['MonitoredVehicleJourney']['VehicleRef'])==6)
				$vechile=substr($departure['MonitoredVehicleJourney']['VehicleRef'],2);
			else
				$vechile=$departure['MonitoredVehicleJourney']['VehicleRef'];
			if($departure['MonitoredVehicleJourney']['Monitored']===true)
			{
				if($showheader) //Check if header should be displayed
				{
					if(!empty($departure['Extensions']['LineColour']) && $preferences['show_line_colors'])
						$numbox_style=" style=\"background:#{$departure['Extensions']['LineColour']}\"";
					else
						$numbox_style='';
					echo "\t<li>\n";
					echo "\t\t<div class=\"heading\"><span class=\"numBox\"$numbox_style>$line_number</span><span class=\"time\">$destination_name</span></div>\n";
					echo "\t\t<div class=\"list\">\n";

				$showheader=false;
				}
				
				$class='item';
				echo "\t\t\t<span class=\"$class\">".date('H:i',$arrival).' ('.$departure['MonitoredVehicleJourney']['BlockRef']."/$vechile)</span>\n";
			}
		}
if($showheader===false) //If no departures has been displayed $showheader will still be true
{
	echo "\t\t</div>\n";
	echo "\t</li>\n";
}
	}
}
?>

</ul>
</section>
</body>
</html>
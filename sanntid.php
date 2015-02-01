<?Php
require 'preferences.php';
require 'ruter_rest_class.php';
$ruter=new ruter_rest;
require 'sanntidpluss_class.php';
$sanntidpluss=new sanntidpluss;

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
			$AimedArrivalTime=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['AimedArrivalTime']);
			$ExpectedArrivalTime=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']);

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
				if($AimedArrivalTime!=$ExpectedArrivalTime && $preferences['show_deviation_time']) //The departure is not on schedule
				{
					$ArrivalTimeDiff=$ExpectedArrivalTime-$AimedArrivalTime;
					$deviation_time_fraction=$sanntidpluss->deviation_time_fraction($AimedArrivalTime,$ExpectedArrivalTime);
					$deviation_time_details="Delay: ".date('i:s',$ArrivalTimeDiff)."\\n
											AimedArrivalTime: {$departure['MonitoredVehicleJourney']['MonitoredCall']['AimedArrivalTime']}\\n
											ExpectedArrivalTime: {$departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']}\\n
											Diff: $ArrivalTimeDiff";
					$deviation_time_details=str_replace(array("\r","\n","\t"),'',$deviation_time_details);

					$deviation_time_html="<span style=\"color:#ff0000\" onclick=\"alert('$deviation_time_details')\">$deviation_time_fraction</span> ";
				}
				else
					$deviation_time_html='';

				$class='item';
				
				echo "\t\t\t<span class=\"$class\">".date('H:i',$ExpectedArrivalTime)." $deviation_time_html(".$departure['MonitoredVehicleJourney']['BlockRef']."/$vechile)</span>\n";
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
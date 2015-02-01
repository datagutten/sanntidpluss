<?Php
require '../trafikanten/ruter_rest_class.php';
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

<section class="realtime">
<ul>

<?Php
//print_r($stop);
//echo $stop[0]
echo "<h1>{$stopinfo['Name']}</h1>\n<h2>{$stopinfo['District']}</h2>\n";
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
					?>
					<li>
					<div class="heading">
						<span class="numBox"><?php echo $line_number; ?></span>
						<span class="time"><?Php echo $destination_name; ?></span>
					</div>
					<div class="list">
					<span style="display:none"><?Php print_r($direction); ?></span>
					<?Php
					$showheader=false;
				}
				
				$class='item';
				echo "<span class=\"$class\">".date('H:i',$arrival).' ('.$departure['MonitoredVehicleJourney']['BlockRef']."/$vechile)</span>";
			}
			/*else
			{
				$class='item white';
				echo "<span class=\"$class\">".date('H:i',$arrival)."</span>";

			}*/
			

		}
		?>
        </div>
		</li>
		<?Php
	}
}
?>

</ul>
</section>
</body>
</html>
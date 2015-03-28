<?php
require 'sanntidpluss_class.php';
require 'preferences.php';
$sanntidpluss=new sanntidpluss;

//$departures_by_line=$sanntidpluss->getdepartures($_GET['stop']);
$departures=$sanntidpluss->get('StopVisit/GetDepartures/'.$stopid=$_GET['stop']);
$stopinfo=$sanntidpluss->get("Place/GetStop/$stopid");

require 'domcustom.php';
$dom=new DOMCustom;
$dom->loadHTMLFile('template.htm');

/*$dom = DOMImplementation::createDocument(null, 'html',
    DOMImplementation::createDocumentType("HTML"));*/
$dom->formatOutput = true;

$html=$dom->documentElement;
//$head=$dom->createElement('head');
$head=$dom->getElementsByTagName('head')->item(0);

//<meta name="format-detection" content="telephone=no"/>
$meta_format_detection=$dom->createElement('meta');
$meta_format_detection->setAttribute('name','format-detection');
$meta_format_detection->setAttribute('content','telephone=no');
$head->appendChild($meta_format_detection);


/*<script type="text/javascript" src="sanntid.js"></script>*/
$script=$dom->createElement('script',' ');
$script->setAttribute('type','text/javascript');
$script->setAttribute('src','vechileInfo.js');
$head->appendChild($script);


//<meta name="viewport" content="width=device-width, initial-scale=1">
/*$meta_viewport=$dom->createElement('meta');
$meta_viewport->setAttribute('name','viewport');
$meta_viewport->setAttribute('content','width=device-width, initial-scale=1');
$head->appendChild($meta_viewport);
*/


//Make title
$title=$dom->createElement('title',"Sanntid+ {$stopinfo['Name']} ({$stopinfo['District']})");
//$title->appendChild($dom->createTextNode('Sanntid+'));
$head->appendChild($title);


$body = $dom->createElement('body');
$html->appendChild($body);
$array=$dom->createElement('span',print_r($departures,true));
$array->setAttribute('style','display:none');
$body->appendChild($array);

$body->appendChild($dom->createElement('h1',$stopinfo['Name']));
$body->appendChild($dom->createElement('h2',$stopinfo['District']));

$section_realtime=$dom->createElement('section');
$section_realtime->setAttribute('class','realtime');
$body->appendChild($section_realtime);

$list=$dom->createElement('ul');
$section_realtime->appendChild($list);

foreach($departures as $key=>$departure)
{
	if($departure['MonitoredVehicleJourney']['Monitored']!=1)
		continue;
	$linedirection=$departure['MonitoredVehicleJourney']['LineRef'].'-'.$departure['MonitoredVehicleJourney']['DirectionRef']; //Make a string to use as key for line and direction
	if(!isset($li_departure[$linedirection]))
	{
		$li_departure[$linedirection]=$dom->createElement('li'); //Create a new list element
		$list->appendChild($li_departure[$linedirection]); //Add the list element to the list
		
		$div_header=$dom->createElement('div'); //Create the header div
		$li_departure[$linedirection]->appendChild($div_header); //Add the header div to the list element
		
		$div_header->setAttribute('class','heading');
		//Make the box with the line number
		$span_numbox=$dom->createElement('span',$departure['MonitoredVehicleJourney']['PublishedLineName']);
		$span_numbox->setAttribute('class','numBox');

		if(!empty($departure['Extensions']['LineColour']) && $preferences['show_line_colors'])
			$span_numbox->setAttribute('style',"background:#{$departure['Extensions']['LineColour']}");
		$div_header->appendChild($span_numbox);
		
		//Add the destination name
		$span_destination=$dom->createElement('span',$departure['MonitoredVehicleJourney']['DestinationName']);
		$span_destination->setAttribute('class','time');
		$div_header->appendChild($span_destination);
		//Create the div for the departures
		$div_departures[$linedirection]=$dom->createElement('div');
		$div_departures[$linedirection]->setAttribute('class','list');
		$li_departure[$linedirection]->appendChild($div_departures[$linedirection]);
	}

	$AimedArrivalTime=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['AimedArrivalTime']);
	$ExpectedArrivalTime=strtotime($departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']);

	$span_departure=$dom->createElement('span',date('H:i',$ExpectedArrivalTime).' ');

	if($AimedArrivalTime!=$ExpectedArrivalTime) //Deviation time
	{
		$ArrivalTimeDiff=$ExpectedArrivalTime-$AimedArrivalTime;
		//Popup with debug information
		$popuptext="Delay: $symbol".date('i:s',$ArrivalTimeDiff)."\\n
		AimedArrivalTime: {$departure['MonitoredVehicleJourney']['MonitoredCall']['AimedArrivalTime']}\\n
		ExpectedArrivalTime: {$departure['MonitoredVehicleJourney']['MonitoredCall']['ExpectedArrivalTime']}\\n
		Diff: $ArrivalTimeDiff";
		$popuptext=str_replace(array("\r","\n","\t"),'',$popuptext);

		$deviation_time=$sanntidpluss->deviation_time_fraction($AimedArrivalTime,$ExpectedArrivalTime);
		$span_deviation_time=$dom->createElement_simple('span',$span_departure,array('style'=>'color:#ff0000','onclick'=>"alert('$popuptext')"),$deviation_time.' ');
	}

	if(!empty($departure['MonitoredVehicleJourney']['BlockRef'])) //Add the block ref (vognløp)
		$span_blockref=$dom->createElement_simple('span',$span_departure,array('class'=>'blockref'),'('.$departure['MonitoredVehicleJourney']['BlockRef']);

	if(!empty($departure['MonitoredVehicleJourney']['VehicleRef'])) //Add the vechile ref
	{
		if(strlen($departure['MonitoredVehicleJourney']['VehicleRef'])==6)
			$vechile=substr($departure['MonitoredVehicleJourney']['VehicleRef'],2);
		else
			$vechile=$departure['MonitoredVehicleJourney']['VehicleRef'];
		if(isset($span_blockref))
			$prefix='/';
		else
			$prefix='(';
		$span_vechile=$dom->createElement_simple('span',$span_departure,array('onclick'=>"vechileInfo($vechile)",'class'=>'vechile'),$prefix.$vechile);
		$span_vechile->setAttribute('onclick',"vechileInfo($vechile)");
		$span_departure->appendChild($span_vechile);
	}
	
	$span_departure->appendChild($dom->createElement('span',')'));

	//$span_departure=$dom->createElement('span',date('H:i',$ExpectedArrivalTime)." $deviation_time_html(".$departure['MonitoredVehicleJourney']['BlockRef'].'/'.$departure['MonitoredVehicleJourney']['VehicleRef'].')');
	$span_departure->setAttribute('class','item');
	$div_departures[$linedirection]->appendChild($span_departure);

	//$div_header
}

echo $dom->saveXML($html);
?>
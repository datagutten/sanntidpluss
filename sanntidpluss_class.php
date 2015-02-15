<?Php
require_once 'ruter_rest_class.php';
class sanntidpluss extends ruter_rest
{
	public function __construct()
	{
		parent::__construct();	
	}
	public function deviation_time_fraction($AimedArrivalTime,$ExpectedArrivalTime) //Show the delay with a minute number and a second fraction like on the drivers display
	{
		$ArrivalTimeDiff=$ExpectedArrivalTime-$AimedArrivalTime;
		if($ExpectedArrivalTime<$AimedArrivalTime)
			$symbol='+';
		else
			$symbol='-';

		$seconds=date('s',$ArrivalTimeDiff);
		switch($seconds)
		{
			case 0: $fraction=''; break;
			case($seconds<=15): $fraction='¼'; break;
			case($seconds<=30): $fraction='½'; break;
			case($seconds<=45): $fraction='¾'; break;
			case($seconds>45): $fraction=''; $ArrivalTimeDiff=60; break;
		}

		$delaystring=$symbol;
		$delaystring.=(int)date('i',$ArrivalTimeDiff);
		$delaystring.=$fraction;
		return $delaystring;	
	}
}
?>
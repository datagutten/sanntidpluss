<?Php
class ruter_rest
{
	public $ch;
	function __construct()
	{
		$this->ch=curl_init();
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch,CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch,CURLOPT_HTTPHEADER,array('Accept: application/json,text/json'));
	}
	function get($uri,$nodecode=false)
	{
		$uri=str_replace('+','%20',$uri);
		curl_setopt($this->ch,CURLOPT_URL,'http://reisapi.ruter.no/'.$uri);
		if($nodecode===false)
			return json_decode(curl_exec($this->ch),true);
		else
			return curl_exec($this->ch);
	}
}
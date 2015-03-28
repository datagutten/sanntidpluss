<?Php
class DomCustom extends DOMDocument
{
    function __construct($version = null, $encoding = null) {
        parent::__construct($version, $encoding);
        //$this->registerNodeClass('DOMElement', 'XDOMElement');
    }

	public function createElement_simple($tagName,$parent,$attributes='',$value='')
	{
		$element=$this->createElement($tagName,$value);
		if(is_array($attributes))
		{
			foreach($attributes as $name=>$value)
			{
				$element->setAttribute($name,$value);
			}
		}
		$parent->appendChild($element);
		return $element;
	}
}
?>
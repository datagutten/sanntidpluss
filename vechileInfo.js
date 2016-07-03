function rangecheck(value,low,high)
{
	if(value>=low && value<=high)
		return true;
	else
		return false;
}
function expectedVechile(vechile,line)
{
	var unexpected=false;;
	if(line==83 && vechile!='Volvo 8700')
	{
		unexpected=true;
	}
	return unexpected;
}
function checkVechiles()
{
	var departures=document.getElementsByClassName('item');
	var departure;
	var key;
	var vechile;
	var vechileNumber;
	var vechileInfo;

	for (var i=0; i<departures.length; i++)
	{
		departure=departures.item(i);
		key=departure.id;
		vechile=departure.childNodes.item(4);
		vechileNumber=Number(vechile.textContent);
		vechileInfo=vechileType(vechileNumber);
		
		var lineNumber=departure.parentNode.parentNode.childNodes.item(1).childNodes.item(1).textContent;
		
		if(vechileInfo[1]!==false && vechileInfo[1].indexOf(lineNumber)<0)
		{
			vechile.setAttribute('style','color: #FF0000');
		}
	}
}
function vechile_onclick(vechile,line,operator,object)
{
	var type=vechileType(vechile);
	if(type[1]===false)
	{
		alert(type[0]+"\n");
	}
	else
	{
		if(type[1].indexOf(line)<0)
		{
			alert(type[0]+"\nDenne busstypen er ikke forventet på linje "+line);
		}
		else
		{
			alert(type[0]);
		}
	}
	
}
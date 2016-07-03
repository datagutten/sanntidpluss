function vechileType(vechile)
{
	var type=false;
	var lines=false;
	vechile=Number(vechile);
	
	if(vechile>=513 && vechile<=523)
	{
		lines=['22'];
		type='Volvo solo 2007';
	}
	else if(vechile>=525 && vechile<=572)
	{
		lines=['25','28','34','77'];
		type='MAN solo 2008';
	}
	else if(vechile===584)
	{
		lines=['86'];
		type='Volvo Vest B7RLE 2008';
	}
	else if(vechile>=631 && vechile<=635)
	{
		lines=['28'];
		type='MAN NG313 2003 ledd';
	}
	else if(vechile>=639 && vechile<=640)
	{
		//Reserve for sentrumsanbudet, alltid uventet
		type='MAN NG313 2005 ledd';
	}
	else if(rangecheck(vechile,659,698))
	{
		lines=['20','31','37','54'];
		type='MAN Lions City 2008 ledd';
	}
	else if(rangecheck(vechile,1019,1022))
	{
		lines=['20','31','37','54'];
		type='Volvo ledd 2010';
	}
	else if(rangecheck(vechile,1023,1052))
	{
		lines=['20','31','37','54'];
		type='MAN Lions City 2012 ledd';
	}
	else if(rangecheck(vechile,1053,1080))
	{
		lines=['23','24'];
		type='MAN Lions City 2015 ledd';
	}
	else if(vechile>=1100 && vechile<=1106)
	{
		lines=['71','80E'];
		type='MAN Lions City ledd 2011';
	}
	else if(vechile>=1111 && vechile<=1142)
	{
		lines=['81A','81B','82','83'];
		type='Volvo 8700 15m 2011';
	}
	else if(vechile>=1150 && vechile<=1165)
	{
		lines=['71','76','77'];
		type='Solaris Urbino 12m 2011';
	}
	else if(vechile>=1200 && vechile<=1204) //Bekreftet 1201, 1203, 1204
	{
		lines=['80E'];
		type='Solaris Urbino 18 ledd hybrid';
	}
	else if(rangecheck(vechile,1208,1210))
	{
		lines=['76'];
		type='Volvo 7700 12m hybrid';
	}
	else if(vechile>=1211 && vechile<=1220) //Bekreftet 1214
	{
		lines=['76','84E','85'];
		type='Solaris Urbino 12m hybrid 2011';
	}
	else if(vechile>=1230 && vechile<=1234) //Bekreftet
	{
		lines=['79'];
		type='Van Hool 13m hydrogen 2012';
	}
	else if(vechile>=1250 && vechile<=1271)
	{
		lines=['79'];
		type='Volvo 7700 12m gass 2011';
	}
	else if(vechile>=1272 && vechile<=1286)
	{
		lines=['74','75','78'];
		type='MAN 12m gass 2015';
	}
	else if(rangecheck(vechile,1289,1299))
	{
		lines=['70'];
		type='MAN ledd gass 2015';
	}
	else if(rangecheck(vechile,8850,8870))
	{
		type='Solaris Alpino';
	}
	else
	{
		type='Ukjent: '+vechile;
	}
	return [type,lines];
	/*if(rangecheck(vechile,))
	{
		type='';
	}*/
}
function vechileInfo(vechile)
{
	if(vechile>=1100 && vechile<=1105) //Bekreftet 1102, 1105
		alert('Man Lions City');
	else if(vechile>=1150 && vechile<=1165)
		alert('Solaris Urbino 12');
	else if(vechile>=1200 && vechile<=1209) //Bekreftet 1201, 1203, 1204
		alert('Hybrid (Solaris Urbino 18)');
	else if(vechile>=1210 && vechile<=1220) //Bekreftet 1214
		alert('Hybrid (Solaris Urbino 12)');
	else if(vechile>=1230 && vechile<=1234) //Bekreftet
		alert('Hydrogen (Van Hool)');
	else if(vechile>=1250 && vechile<=1310) //Bekreftet 1256, 1258, 1267, 1307
		alert('Gass (Volvo 7700)');
	else if(vechile<=1200)
		alert('Diesel');
	else
		alert(vechile);
}
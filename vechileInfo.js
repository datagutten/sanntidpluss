function vechileInfo(vechile)
{
	if(vechile>=1200 && vechile<=1210) //Bekreftet 1201, 1203, 1204
		alert('Hybrid (Solaris Urbino 18)');
	else if(vechile>=1230 && vechile<=1234) //Bekreftet
		alert('Hydrogen');
	else if(vechile>=1250 && vechile<=1310) //Bekreftet 1256, 1258, 1267, 1307
		alert('Gass');
	else if(vechile<=1200)
		alert('Diesel');
	else
		alert(vechile);
}
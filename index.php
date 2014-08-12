<!DOCTYPE html>
<html>
<body>
  Holdeplass:
  <input type="text" name="holdeplass" id="holdeplass">
<button onclick="finnholdeplasser_navn()">S&oslash;k</button>

<p id="demo">Trykk p&aring; knappen for &aring; finne holdeplasser n&aelig;r deg</p>
<button onclick="getLocation()">I n&aelig;rheten</button>
<div id="holdeplasser"></div>
<script src="jscoord.js"></script> 
<script>
var x = document.getElementById("demo");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(finnholdeplasser_gps,showError);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

/*function showPosition(position) {
    var latlon = position.coords.latitude+","+position.coords.longitude;
	
    document.getElementById("mapholder").innerHTML = "<h1>'"+hent_data()+"'</h1>";
}*/
function finnholdeplasser_navn()
{
	var holdeplass = document.getElementById('holdeplass');
	getdata("hent_data.php?navn="+holdeplass.value);
}

function finnholdeplasser_gps(position) 
{ 
	var kordinater = new LatLng(position.coords.latitude, position.coords.longitude); 
	var utm = kordinater.toUTMRef(); 
 
	del1 = utm.toString().split(" ")[1]; 
	kordL = del1.toString().split(".")[0]; 

	del2 = utm.toString().split(" ")[2]; 
	kordB = del2.toString().split(".")[0]; 
 
	var url = "hent_data.php?X="+kordL+"&Y="+kordB;
	getdata(url);
}
function getdata(url)
{
	var request = new XMLHttpRequest();
	request.open("Get",url,false); 
	request.onreadystatechange = function () 
	{ 
		if (request.readyState == 4 && request.status == 200) 
		{ 
			var resultat = JSON.parse(request.responseText); 
			var utdata = ""; 

			for(var i=0; i<resultat.length; i++) 
			{ 
			 	utdata+="<a href='sanntid.php?stop="+resultat[i].ID+"' class='button'><div id='linjeLink'>"+resultat[i].Name+"</div></a><hr />";
 			}
 
 			document.getElementById("holdeplasser").innerHTML = utdata;
		} 
	}
	request.send(null); 
}


function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "User denied the request for Geolocation."
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Location information is unavailable."
            break;
        case error.TIMEOUT:
            x.innerHTML = "The request to get user location timed out."
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "An unknown error occurred."
            break;
    }
}

function httpGet(theUrl)
{
    var xmlHttp = null;

    xmlHttp = new XMLHttpRequest();
    xmlHttp.open( "GET", theUrl, false );
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
//http://stackoverflow.com/questions/247483/http-get-request-in-javascript
//http://student.cs.hioa.no/hovedprosjekter/data/2012/13/dokumentasjon.htm
</script>
</body>
</html>

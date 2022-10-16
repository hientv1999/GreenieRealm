// Get user coordinates
var x = document.getElementById("result");

function getCoordintes() {
    var options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0
    };
  
    function success(pos) {
        var crd = pos.coords;
        var lat = crd.latitude.toString();
        var lng = crd.longitude.toString();
        var coordinates = [lat, lng];
        console.log(`Latitude: ${lat}, Longitude: ${lng}`);
        getCity(coordinates);
        return;
  
    }
  
    function error(err) {
        console.warn(`ERROR(${err.code}): ${err.message}`);
    }
  
    navigator.geolocation.getCurrentPosition(success, error, options);
}
  
// Step 2: Get city name
function getCity(coordinates) {
    var xhr = new XMLHttpRequest();
    var lat = coordinates[0];
    var lng = coordinates[1];
  
    // Paste your LocationIQ token below.
    xhr.open('GET', "https://us1.locationiq.com/v1/reverse.php?key=pk.c4ac77552186e21dd75460f1b09be040&lat=" + lat + "&lon=" + lng + "&format=json", true);
    xhr.send();
    xhr.onreadystatechange = processRequest;
    xhr.addEventListener("readystatechange", processRequest, false);
  
    function processRequest(e) {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            city =  response.address.city;
            country = response.address.country;
            weather(city, country);
            return;
        }
    }
}

// get weather
function weather(city, country) {
    window.weatherWidgetConfig =  window.weatherWidgetConfig || [];
    window.weatherWidgetConfig.push({
        selector:".weatherWidget",
        apiKey:"KFNUHJL7NRJ5KG3JNEBDZAM3P", //Sign up for your personal key
        location: city, //Enter an address
        unitGroup:"metric", //"us" or "metric"
        forecastDays:5, //how many days forecast to show
        title: city + "," + country, //optional title to show in the 
        showTitle:true, 
        showConditions:true
    });
    (function() {
        var d = document, s = d.createElement('script');
        s.src = 'https://www.visualcrossing.com/widgets/forecast-simple/weather-forecast-widget-simple.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
    })();
}

getCoordintes();




<?php
include_once 'helper.php';

//check if city and state are provided
if(empty($_GET['city'])){
    error(400, 'City is required');
}
if(empty($_GET['state'])){
    error(400, 'State is required');
}

#convert city, state into geographic coordinates
#using https://www.geoapify.com/geocoding-api/

$api_key = getenv("geoapi_key");
$location = urlencode($_GET['city'].', '.$_GET['state']);
#https://api.geoapify.com/v1/geocode/search?text=...&apiKey=...

$location_data = request("https://api.geoapify.com/v1/geocode/search?text=$location&apiKey=$api_key");

if(empty($location_data) || empty($location_data['features'])){
    error(404, 'Location not found');
}

$lat = $location_data['features'][0]['properties']['lat'];
$lon = $location_data['features'][0]['properties']['lon'];

#get weather data
#using https://api.openweathermap.org/

$weather_key = getenv("weather_key");
$weather_data = request("https://api.openweathermap.org/data/3.0/onecall?lat=$lat&lon=$lon&appid=$weather_key&exclude=minutely,hourly,daily,alerts&units=imperial");

if(isset($weather_data['cod'])){
    error($weather_data['cod'], "OpenWeatherAPIError: " . $weather_data['message']);
}

#make readable (only include relevant data)
echo json_encode([
    "icon" => "https://openweathermap.org/img/wn/" .$weather_data['current']['weather'][0]['icon']. ".png",
    "temp" => $weather_data['current']['temp'],
    "feels_like" => $weather_data['current']['feels_like'],
    "description" => $weather_data['current']['weather'][0]['main'] . ' - ' . $weather_data['current']['weather'][0]['description'],
    "humidity" => $weather_data['current']['humidity']
]);

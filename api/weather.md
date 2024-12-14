## Weather API endpoint
Endpoint: http://localhost:8888/weather.php (GET)

Query Parameters:
- city: The name of the city to get weather information
- state: State code or name to the weather for

Returns:
{
    "icon": "http://openweathermap.org/img/w/ ... .png",
    // URL to an icon representing the current weather
    "temp": 00.00, 
    // Temperature in Fahrenheit
    "feels_like": 00.00, 
    // Feels like temperature in Fahrenheit
    "humidity": 50, 
    // Humidity percent
    "description": " ... " 
    // A short description of the current weather
}

Errors:
Copies error code of the OpenWeather API on error (see: https://openweathermap.org/faq#error401)
404: Location was not found. Check the city and state.
500: Error with third-party request (see error description).

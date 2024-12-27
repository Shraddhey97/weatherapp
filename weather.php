<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['city'])) {
    $city = urlencode($_GET['city']);
    $apiKey = "c69594eea62206e6f328a81ebd98c3a9";
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric";

    $weatherData = file_get_contents($apiUrl);

    if ($weatherData === false) {
        http_response_code(500);
        echo json_encode(["error" => "Unable to fetch weather data"]);
    } else {
        // Process the weather data
        $weatherArray = json_decode($weatherData, true);

        // Store the data in the database
        storeWeatherData($weatherArray);

        // Return the weather data
        echo $weatherData;
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request"]);
}

function storeWeatherData($weatherArray) {
    $conn = new mysqli("localhost", "root", "", "weather_app");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $city = $weatherArray["name"];
    $temperature = $weatherArray["main"]["temp"];
    $humidity = $weatherArray["main"]["humidity"];
    $windSpeed = $weatherArray["wind"]["speed"];
    $pressure = $weatherArray["main"]["pressure"];
    $description = $weatherArray["weather"][0]["description"];
    $icon = $weatherArray["weather"][0]["icon"];

    $sql = "INSERT INTO weather_data (city, temperature, humidity, wind_speed, pressure, description, icon) VALUES ('$city', $temperature, $humidity, $windSpeed, $pressure, '$description', '$icon')";

    if ($conn->query($sql) === TRUE) {
        echo "Weather data successfully stored in the database.";
    } else {
        echo "Error storing weather data: " . $conn->error;
    }

    $conn->close();
}
?>

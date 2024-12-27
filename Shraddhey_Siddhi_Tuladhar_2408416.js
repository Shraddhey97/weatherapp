document.addEventListener("DOMContentLoaded", function () {
  // set default city
  const defaultCity = "Haldia";
  const cityInput = document.getElementById("cityInput");
  cityInput.value = defaultCity;
  // get weather of default city
  getWeather();
});

function getWeather() {
  const cityInput = document.getElementById("cityInput").value;
  const weatherInfo = document.getElementById("weatherInfo");

  // call the PHP backend instead of directly calling OpenWeatherMap API
  const apiUrl = `weather.php?city=${cityInput}`;

  fetch(apiUrl)
    .then((response) => response.json())
    .then((data) => {
      // process the weather data as before
      const temperature = data.main.temp;
      const description = data.weather[0].description;
      const cityName = data.name;
      //add date and day
      const options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      const currentdateTime = new Date().toLocaleString(undefined, options);
      //create html to display weather info
      const weatherHtml = `
<h2>${cityName}</h2>
                <p>Temperature: ${temperature.toFixed(2)}°C</p>
                <p>Humidity: ${data.main.humidity}%</p>
                <p>Wind Speed: ${data.wind.speed} MPH</p>
                <p>pressure: ${data.main.pressure}pHa</p>
                <p>Description: ${description}</p>
                <img src="https://openweathermap.org/img/wn/${
                  data.weather[0].icon
                }@2x.png" alt="weather icon" />
                <p>${currentdateTime}</p>
  `;
      //Display the weather info on the webpage
      weatherInfo.innerHTML = weatherHtml;
    })
    .catch((error) => {
      //handle errors such as wrong city name or internet issue
      console.error("error fetching data:", error);
      weatherInfo.innerHTML = "Error fetching data.Please try again.";
    });
}

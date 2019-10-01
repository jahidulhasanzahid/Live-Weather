<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

        $minutes = 60;
		  $forecast = Cache::remember('forecast', $minutes, function () {
		    Log::info("Not from cache");
		    $app_id = config("here.app_id");
		    $app_code = config("here.app_code");
		    $lat = config("here.lat_default");
		    $lng = config("here.lng_default");
		    $url = "https://weather.api.here.com/weather/1.0/report.json?product=forecast_hourly&latitude=${lat}&longitude=${lng}&oneobservation=true&language=it&app_id=${app_id}&app_code=${app_code}";
		    Log::info($url);
		    $client = new \GuzzleHttp\Client();
		    $res = $client->get($url);
		    if ($res->getStatusCode() == 200) {
		      $j = $res->getBody();
		      $obj = json_decode($j);
		      $forecast = $obj->hourlyForecasts->forecastLocation;
		    }
		    return $forecast;
		  });
		  return view('welcome', ["forecast" => $forecast]);
		    
});

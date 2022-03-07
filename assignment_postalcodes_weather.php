<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Corekara assignment</title>
</head>

<body>
    <div class="container-fluid">
        <form action="" method="post">
            <div class="form-group">
                <label>Zip Code</label>
                <input type="text" class="form-control" name="zipcode" placeholder="Enter Zip Code Ex:160-0022">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php			
			if (isset($_POST['zipcode'])) {
				$sanitize = str_replace("-","",$_POST['zipcode']);
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://postcodejp-api.p.rapidapi.com/postcodes?postcode=".$sanitize,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
						"x-rapidapi-host: postcodejp-api.p.rapidapi.com",
						"x-rapidapi-key: 003aa627efmsha53648762e846dap199164jsncd3669a9c7d2"
					),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);
				$detail = json_decode($response, true);
				$city_name = $detail['data']['0']['town'];
				echo $city_name."<br>";

				$curl_city = curl_init();
				$test = rawurlencode($city_name);
				
				curl_setopt_array($curl_city, array(
					CURLOPT_URL => "http://dataservice.accuweather.com/locations/v1/search?apikey=3AA8DlwtplWiM69SiwseJBtH3hWVutqK&q=".$test,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
				));

				$response_city = curl_exec($curl_city);
				$err_city = curl_error($curl_city);

				curl_close($curl_city);
				$location_key = json_decode($response_city, true);
				$key = $location_key[0]['Key'];
				$localized_city = $location_key[0]['LocalizedName'];
				$prefecture = $location_key[0]['AdministrativeArea']['LocalizedName'];
				echo $localized_city." ".$prefecture."<br>";
				
				$curl_forecast = curl_init();
				
				curl_setopt_array($curl_forecast, array(
					CURLOPT_URL => "http://dataservice.accuweather.com/forecasts/v1/daily/5day/".$key."?apikey=3AA8DlwtplWiM69SiwseJBtH3hWVutqK",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
				));

				$response_forecast = curl_exec($curl_forecast);
				$err_forecast = curl_error($curl_forecast);

				curl_close($curl_forecast);
				$forecast = json_decode($response_forecast, true);
					for ($i = 0; $i <= 2; $i++) {
						
						$number = $i+1;
						echo "Day ".$number."<br>";
						$date = $forecast['DailyForecasts'][$i]['Date'];
						$temp_min = $forecast['DailyForecasts'][$i]['Temperature']['Minimum']['Value'];
						$temp_min_unit = $forecast['DailyForecasts'][$i]['Temperature']['Minimum']['Unit'];
						$temp_max = $forecast['DailyForecasts'][$i]['Temperature']['Maximum']['Value'];
						$temp_max_unit = $forecast['DailyForecasts'][$i]['Temperature']['Maximum']['Unit'];
						$day_icon = $forecast['DailyForecasts'][$i]['Day']['Icon'];
						$day_detail = $forecast['DailyForecasts'][$i]['Day']['IconPhrase'];
						$night_icon = $forecast['DailyForecasts'][$i]['Night']['Icon'];
						$night_detail = $forecast['DailyForecasts'][$i]['Night']['IconPhrase'];
						echo "Date : ".$date."<br>";
						echo "Temperature Minimum : ".$temp_min." ".$temp_min_unit."<br>";
						echo "Temperature Maximum : ".$temp_max." ".$temp_max_unit."<br>";
						echo "Day : <img src='https://www.accuweather.com/images/weathericons/".$day_icon.".svg' width='30px' height='30px'> </img><br>";
						echo "Day Detail : ".$day_detail."<br>";
						echo "Night : <img src='https://www.accuweather.com/images/weathericons/".$night_icon.".svg' width='30px' height='30px' > </img><br>";
						echo "NightDetail : ".$night_detail."<br>";
					}
			}
			?>


</body>

</html>
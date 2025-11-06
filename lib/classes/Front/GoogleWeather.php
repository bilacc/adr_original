<?php
	class GoogleWeather
	{		
		public static function load($city='Zagreb')
		{
			$lng = 'hr';
			
			if( get_conf('multi_language') == 1 )
				$lng = $_SESSION['lng'];
			
			$xml = file_get_contents('http://www.virtus-projekti.com/google_vrijeme_feed/'.mb_strtolower($city).'_'.$lng.'.xml');
			$xml = simplexml_load_string($xml);
			
			$current = $xml->xpath("/xml_api_reply/weather/current_conditions"); // trenutni uvjeti
			
			preg_match('/([a-z_]+)\..{3}$/', $current[0]->icon['data'], $matches); // ikonica
			$data['today']['icon'] = $matches[1];
			$data['today']['temp'] = $current[0]->temp_c['data'];
			$data['today']['condition'] = $current[0]->condition['data'];
			$data['today']['humidity'] = $current[0]->humidity['data'];
			$data['today']['wind_condition'] = $current[0]->wind_condition['data'];
			
			$forecast = $xml->xpath("/xml_api_reply/weather/forecast_conditions"); // prognoza za tri dana od danas
			
			preg_match('/([a-z_]+)\..{3}$/', $forecast[0]->icon['data'], $matches); // ikonica
			$data['day1']['icon'] = $matches[1];
			$data['day1']['day'] = $forecast[0]->day_of_week ['data'];
			$data['day1']['high'] = $forecast[0]->high['data'];
			$data['day1']['low'] = $forecast[0]->low['data'];
			$data['day1']['condition'] = $forecast[0]->condition['data'];
			
			preg_match('/([a-z_]+)\..{3}$/', $forecast[1]->icon['data'], $matches); // ikonica
			$data['day2']['icon'] = $matches[1];
			$data['day2']['day'] = $forecast[1]->day_of_week ['data'];
			$data['day2']['high'] = $forecast[1]->high['data'];
			$data['day2']['low'] = $forecast[1]->low['data'];
			$data['day2']['condition'] = $forecast[1]->condition['data'];
			
			preg_match('/([a-z_]+)\..{3}$/', $forecast[2]->icon['data'], $matches); // ikonica
			$data['day3']['icon'] = $matches[1];
			$data['day3']['day'] = $forecast[2]->day_of_week ['data'];
			$data['day3']['high'] = $forecast[2]->high['data'];
			$data['day3']['low'] = $forecast[2]->low['data'];
			$data['day3']['condition'] = $forecast[2]->condition['data'];
			
			return $data;
		}
	}
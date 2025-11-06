<?php
	class GoogleWeather
	{
		public $city, $lng;
		
		public function __construct($city='Zagreb', $lng='hr')
		{
			$this->city = $city;
			$this->lng = $lng;
		}
		
		public function load_weather()
		{
			$xml = file_get_contents('http://www.google.com/ig/api?weather='.$this->city.'&hl='.$this->lng);
			$xml = iconv("ISO-8859-2", "UTF-8//TRANSLIT", $xml);
			$xml = simplexml_load_string($xml);
			
			$current = $xml->xpath("/xml_api_reply/weather/current_conditions");
			
			//print $current[0]->humidity['data'];
			//echo '<span id="vjetar">'.$current[0]->wind_condition['data'].'</span>';
			
			preg_match('/([a-z_]+)\..{3}$/', $current[0]->icon['data'], $matches);
			
			$html = '<img src="images/vrijeme/'.$matches[1].'.png" alt="" class="weather_img" />';
			$html .= '<br /><span id="stanje">'.$current[0]->condition['data'].'</span> <br />';
			$html .= '<span id="temp">'.$current[0]->temp_c['data'].' °C</span>';
			
			return $html;
		}
	}
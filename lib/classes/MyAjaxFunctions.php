<?php

class MyAjaxFunctions extends MyAjax
{
	public function load_txt($id)
	{	
		$this->out('prepend', 'aa <br/>', 'email_holder');
		
		return false;
	}
	
	public function load_weather($city='Zagreb', $lng='hr')
	{
		$w = new GoogleWeather($city, $lng);
		$html = $w->load_weather();
		
		$this->out('value', $city, 'ttt');
		$this->out('html', $html, 'weather_holder');
		return false;
	}
}
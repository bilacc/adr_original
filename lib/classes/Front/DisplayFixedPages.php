<?php

class DisplayFixedPages {
	
	protected $counter=0, $path = '', $display = '', $menu_html = '', $lng='';
	
	public function __construct()
	{
		if( get_conf('multi_language') == 1 )
		{
			$this->lng = '_'._LNG;
		}
	}
	
	public function fixed_page($field) 
	{
		$sql = 'select '.$field.' from fixed_pages limit 1';	
		//print $sql.'<br />';
		$content = Db::query_one($sql);
		return $content;
	}
}

?>
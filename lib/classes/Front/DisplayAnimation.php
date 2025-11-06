<?php
class DisplayAnimation
{
	protected $counter=0, $path = '', $display = '', $menu_html = '', $lng='';
	
	public function __construct()
	{
		if( get_conf('multi_language') == 1 )
		{
			$this->lng = '_'._LNG;
		}
		else 
		{
			$this->lng = '_hr';
		}
	}
	
		
	public function animation_list()
	{
		$this->content_html = '';
		$sql = 'select id, title'.$this->lng.' as title 
			from animation 
			order by orderby desc '.$sql_dod2.'';
		//print $sql.'<br />';
		$rez = Db::query($sql);
		$numrows = count($rez);
		if($rez)
		{
			$i = 0;
			$j = 0;
			foreach ($rez as $row)
			{
				$id = $row['id'];
				$title = Db::clean($row['title']);

				$sql = "select photo_name 
					from site_photos 
					where table_name = 'animation' and table_id = $id 
					and photo_name != '' 
					order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}
				
				$this->content_html .= '<li><img src="'.$slika_url.'" alt="'.$title.'" /></li>';
			
				$i++;
				$j++;
			}
		}
		$data = $this->content_html;
		
		return $data;
	}
}
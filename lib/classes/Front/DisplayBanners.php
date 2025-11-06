<?php
class DisplayBanners
{
	protected $counter=0, $path = '', $display = '', $content_html = '', $lng='';
	
	public function __construct()
	{
		if( get_conf('multi_language') == 1 )
		{
			$this->lng = '_'._LNG;
		}
	}

	function generiraj_banner($id)
	{
		$this->content_html = '';
		
		if($id == 1)
		{
			$width = 240;
			$height = 167;
		} 
		else if ($id == 2) 
		{
			$width = 240;
			$height = 167;
		} 
		else if ($id == 3) 
		{
			$width = 240;
			$height = 167;
		} 
		else if ($id == 4) 
		{
			$width = 240;
			$height = 167;
		} 
		else if ($id == 5) 
		{
			$width = 240;
			$height = 167;
		}  

		/*if(_LNG == 'en')
		{
			$lang_id = 2;
		}
		else if (_LNG == 'mk')
		{
			$lang_id = 3;
		}
		else if (_LNG == 'sl')
		{
			$lang_id = 4;
		} 
		else 
		{
			$lang_id = 1;
		}
		
		$tablica_dod = ', poveznica_banneri_lang ';
		$sql_dod = ' and banneri.id = poveznica_banneri_lang.banneri_id 
			and poveznica_banneri_lang.lang_id = "'.$lang_id.'" ';*/
		
		$path1 = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER['REQUEST_URI'];
		$path2 = _SITE_URL._LNG;
		if(substr($path1, -1) == '/')
		{
			$path1 = substr($path1, 0, -1);
		}
		//echo $path1.'<br />';
		//echo $path2.'<br />';
		
		if(($path1 == $path2) || ($path1 == $path2.'/home')) 
		{ // za prikaz kod naslovnice
			$site_levels_id = 1;
			//echo 'naslovnica<br />';
		}
		else if( (strstr($_SERVER['REQUEST_URI'], '/'._URL_CATEGORIES_DETAILS.'/')) ) 
		{ // za prikaz kod kategorija 
			$site_levels_id = 2;
		}
		else if(strstr($_SERVER['REQUEST_URI'], '/'._URL_DETAILS.'/')) 
		{ // za prikaz kod clanaka 
			$site_levels_id = 3;
		}
		else 
		{ // za prikaz kod ostalih stranice - staticke itd
			$site_levels_id = 4;
		}
		//echo $site_levels_id.'<br />';
		
		$tablica_dod .= ', poveznica_banneri_site_levels ';
		$sql_dod .= ' and banneri.id = poveznica_banneri_site_levels.banneri_id 
			and poveznica_banneri_site_levels.site_levels_id = "'.$site_levels_id.'" ';
		
		$sql = 'select banneri.id, banneri.link'.$this->lng.' as link, 
			site_files.file_name  
			from banneri, site_files '.$tablica_dod.'  
			where banneri.banner_categories_id = "'.$id.'" 
			and banneri.id = site_files.table_id 
			and site_files.table_name = "banneri" 
			'.$sql_dod.' 
			order by rand() limit 1';
		//print $sql.'<br />';
		$rez = Db::query($sql);
		if($rez)
		{
			foreach ($rez as $row)
			{
				$banner_id = Db::clean($row['id']);
				$link = format_link(Db::clean($row['link']));
				$slika = Db::clean($row['file_name']);
				
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_files/'.$slika;
				}
				
				if(!strstr($slika, ".swf"))
				{
					$this->content_html .= '<a target="_blank" href="'.$link.'" class="skippers_drill"><img src="'.$slika_url.'" alt="'.$link.'" title="'.$link.'" border="0" /></a>';
				} 
				else 
				{
					$this->content_html .= '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="'.$width.'" height="'.$height.'" id="htmlText" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="allowFullScreen" value="false" />
						<param name="movie" value="'.$slika_url.'" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
						<embed src="'.$slika_url.'" quality="high" bgcolor="#ffffff" width="'.$width.'" height="'.$height.'" name="htmlText" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
						</object>
					';
				}
			}
		}
		
		return $this->content_html;
	}
	
}
?>
<?php

class Pagination {
	
	protected $counter=0, $path = '', $display = '', $menu_html = '', $lng='';
	
	public function __construct()
	{
		if( get_conf('multi_language') == 1 )
		{
			$this->lng = '_'._LNG;
		}
	}
	
	public function paginate_front($br_stavki_po_stranici, $pgn, $numrows, $page) 
	{
		$prethodna_label = '&laquo;';
		$sljedeca_label = '&raquo;';
		$zadnja_stranica = ceil($numrows/$br_stavki_po_stranici);
		$zadnja_cnt = $zadnja_stranica;

		/*if(!empty($_POST['search_input']))
		{
			$content_dod .= '&search_input='.trim(mysql_real_escape_string($_POST['search_input']));
		}*/
		//echo $_SERVER['QUERY_STRING'].'<br />';
		if( $_GET['r'] > 0 ) 
		{
			$content_dod .= '&r='.$_GET['r'].'';
		}
		if( $_GET['s'] > 0 ) 
		{
			$content_dod .= '&s='.$_GET['s'].'';
		}
		if(isset($_POST['trazi']))
		{
			$_GET['trazi'] = $_POST['trazi'];
		}
		
		
		if($_GET['trazi'] != '')
		{
			$content_dod .= '&trazi='.$_GET['trazi'].'';
		}
		if($numrows >= $br_stavki_po_stranici) 
		{	
			$dod_h = _SITE_URL.$page.'pg/';
			if($pgn > 0) 
			{
				$content .= ' <li><a href="'.$dod_h.($pgn-1).$content_dod.'">'.$prethodna_label.'</a></li> ';
			}
			$l_prvi = 0;
			$l_continued = 0;
			for($i = 0; $i < floor(($numrows - 1) / $br_stavki_po_stranici) + 1; $i++)
			{
				if($i == $pgn)
				{
					$content .= '<li><a class="pslc" href="javascript:void(0);">'.($i + 1).'</a></li> ';
				}
				else
				{
					if($numrows > 20)
					{
						$l_low = 3;
						$l_high = floor(($numrows - 1) / $br_stavki_po_stranici) + 1 - 3;
						
						if(($i > $l_low) && ($i < $l_high))
						{
							$l_continued = 0;
							if(($i > $pgn - 4) && ($i < $pgn + 4))
							{
							}
							else
							{
								if($l_prvi == 0)
								{
									$content .= '.&nbsp;.&nbsp;';
									$l_prvi = 1;
								}
								if($i % floor($numrows / $br_stavki_po_stranici) == 0)
								{
									$content .= '.&nbsp;';
								}
								$l_continued = 1;
								continue;
							}
						}
						else
						{
							if(($i >= $l_high) && ($l_prvi == 1) && ($l_continued == 1))
							{
								$content .= '.&nbsp;.&nbsp;';
								$l_prvi = 2;
							}
						}
					}
					$content .= '<li><a href="'._SITE_URL.$page.'pg/'.$i.$content_dod.'">'.($i + 1).'</a></li> ';
				}
			}
			if(($zadnja_cnt-1) > $pgn)
			{
				//if($numrows > 0) {
					$content .= '<li><a href="'.$dod_h.($pgn+1).$content_dod.'">'.$sljedeca_label .'</a></li>';
				//}
			}
		}
			
		return $content;
	}
}

?>
<?php
class DisplayNews
{
	protected $counter=0, $path = '', $display = '', $content_html = '', $lng='';
	
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
	
	public function news_list_homepage($type)
	{
		$content = '';
		$sql_img_dod = ' and static = 0 ';
		
		if($type == 'top_box')
		{
			$sql_dod = ' and animation = "y" ';
			$sql_img_dod = ' and static = 1 ';
		}
		else if($type == 'middle_box')
		{
			$sql_dod = ' and on_homepage = "y" ';
		}
		else if($type == 'right_box')
		{
			$sql_dod = ' and on_homepage_2 = "y" ';
		}

		if($type == 'middle_box')
		{
			$sql = 'select id, orderby_2, title'.$this->lng.' as title, text1'.$this->lng.' as text1, on_homepage, orderby, "items" as tablica    
				from items 
				UNION ALL 
				select id, orderby_2, title'.$this->lng.' as title, text1'.$this->lng.' as text1, on_homepage, orderby, "categories" as tablica     
				from categories  
				where 1 = 1 
				'.$sql_dod.'
				order by orderby_2 desc, orderby desc 
				'.$sql_dod2.'';
		}
		else 
		{
			$sql = 'select id, title'.$this->lng.' as title, text1'.$this->lng.' as text1  
				from items 
				where 1 = 1 
				'.$sql_dod.'
				order by orderby desc 
				'.$sql_dod2.'';

		}
		//print $sql.'<br />';
		$rez = Db::query($sql);
		$numrows = count($rez);
		if($rez)
		{
			$i = 0;
			foreach ($rez as $row)
			{
				$id = ($row['id']);
				$title = ($row['title']);
				$text1 = ($row['text1']);
				//$text1 = truncate(strip_tags($text1), 100, $ending = '...', true, true);
				
				if($type == 'middle_box')
				{
					if($row['tablica'] == 'items')
					{
						$url_dod = _URL_DETAILS;
					} 
					else if($row['tablica'] == 'categories')
					{
						$url_dod = _URL_CATEGORIES_DETAILS;
					}
					$link = _SITE_URL._LNG.'/'.$url_dod.'/'.clean_uri($title).'/'.$id;
				}
				else 
				{
					$row['tablica'] = 'items';
					$link = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($title).'/'.$id;
				}
				
				$sql = "select photo_name from site_photos where table_name = '".$row['tablica']."' and table_id = ".$id." ".$sql_img_dod." order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
					
					if($row['tablica'] == 'categories')
					{
						$slika_url = _SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=96&h=72&zc=1';
					}
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}

				if($type == 'right_box')
				{
					/*if($i == 0)
					{
						$li_dod = ' class="first"';
					}
					else*/ 
					if (($numrows - 1) == $i)
					{
						$li_dod = ' class="right_last"';
					}
					else 
					{
						$li_dod = '';
					}
					$content .= '<li'.$li_dod .'><a href="'.$link.'">'.$title.'</a></li>';
				}
				else if($type == 'top_box')
				{
					$text1 = truncate(strip_tags($text1), 300, $ending = '...', true, true);
					$content .= '
						<li>
							<img src="images/slider_mask.png" alt="" class="slider_mask"/>
							<div class="desc_box">
								<h1 class="desc_box_title">
									'.$title.'
								</h1>
								<p>
									'.$text1.' <a href="'.$link.'" class="more">'._MORE.'</a>
								</p>
								
							</div>
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=960&h=250&zc=1" alt="'.$title.'" />
						</li>';
				}
				else if($type == 'middle_box')
				{
					if($row['on_homepage'] == 'y')
					{
						$text1 = truncate(strip_tags($text1), 100, $ending = '...', true, true);
						if ( ($i % 2) != 0)
						{
							$cl_dod = ' class="small_module_last"';
						}
						else 
						{
							$cl_dod = ' class="small_module"';
						}
						$content .= '
							<div'.$cl_dod.'>
								<h2><a href="'.$link.'">'.$title.'</a></h2>
								
								<div class="small_image_holder">
									<a href="'.$link.'" class="image_link">
										<img src="'.$slika_url.'" alt="'.$title.'" />
									</a>
								</div>
								
								<p>
									'.$text1.' <a href="'.$link.'" class="vise_blue">'._MORE.'</a>
								</p>
							</div>';
						if ( ($i % 2) != 0)
						{
							$content .= '<div class="clearfix"></div>';
						}
					}
				}
				
				$i++;
			}
		}
		
		return $content;
	}
	
	
	public function news_list()
	{
		$content = '';
		
		$pagination = new Pagination;
		$br_stavki_po_stranici = 9;
		$br_stavki_po_stranici2 = $br_stavki_po_stranici;
		//echo _su5;

		$pg = _su3;
		
		$prefix = '';
		$cat = _NOVOSTI;
		
		$data['breadcrumbs'] = '';
		//$data['breadcrumbs'] = '<a href="'._SITE_URL.'clanci">Članci</a>';
		
		if($pg == '')
		{
			$pg = 0;
		}
		
		$sql_dod2 = " limit ".($pg * $br_stavki_po_stranici2).", $br_stavki_po_stranici";
		
		$sql2 = 'select count(id) 
			from '.$prefix.'news 
			where 1 = 1 
			'.$sql_dod.'
			order by orderby desc ';
		$numrows_izdv = Db::query_one($sql2);
		
		$sql = 'select id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created
			from '.$prefix.'news 
			where 1 = 1 
			'.$sql_dod.'
			order by orderby desc 
			'.$sql_dod2.'';
		//print $sql.'<br />';
		$rez = Db::query($sql);
		$numrows = count($rez);
		if($rez)
		{
			$i = 0;
			$j = 0;
			foreach ($rez as $row)
			{
				$id = ($row['id']);
				$cat_id = ($row['categories_id']);
				$title = ($row['title']);
				$text = strip_tags($row['text']);

				if(strstr($_SERVER['REQUEST_URI'],"/"._URL_PR_NOVOSTI))
				{
					$link = _SITE_URL._URL_PR_NOVOSTI_DETALJI.'/'.clean_uri($title).'/'.$id;
				}
				else 
				{
					$link = _SITE_URL._URL_NOVOSTI_DETALJI.'/'.clean_uri($title).'/'.$id;
				}
				
				$sql = "select photo_name from site_photos where table_name = '".$prefix."news' and table_id = ".$id." order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}
				

				if($i == 0)
				{
					$cl_dod = ' class="article_s"';
				}
				
				if(($numrows - 1) == $i) 
				{
					$cl_dod = ' class="article_s last"';
				}
				
				$content .= '
					<div'.$cl_dod.'>
						<div class="pic">
							<a href="'.$link.'"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=150&h=113&zc=1" alt=""/></a>
						</div>
						<div class="txt">
							<h2><a href="'.$link.'">'.$title.'</a></h2>
							<p>'.$text.'</p>
							<p class="last"><span>'.$row['created'].'</span> <a href="'.$link.'">'._OPSIRNIJE.'</a></p>
						</div>
						<div class="clear"></div>
					</div>';
				
				$i++;
				$j++;
			}
		}
		if($numrows_izdv > $br_stavki_po_stranici)
		{

			$link_dod = _su1.'/';
			$pag .= '<div class="pag_container">'.$pagination->paginate_front($br_stavki_po_stranici, $pg, $numrows_izdv, $link_dod).'<div class="clear"></div></div>';
		}
		/*if($numrows == 1)
		{
			header("Location: ".$link."");
			exit;
		}*/
		else if($numrows == 0)
		{
			$content = '<div class="articles"><h2>'._TRENUTNO_NEMA_SADRZAJA.'</h2></div>';
		}
		
		$data['content'] = $content;
		$data['title'] = $cat;
		$data['pagination'] = $pag;
		
		return $data;
	}
	
	
	public function news_details()
	{
		$clanak_id = (int)_su3;
		
		$this->content_html = '';
		
		$sql = 'select id, title'.$this->lng.' as title, text1'.$this->lng.' as text1, video as youtube_url, lat_1, lon_1, 
			date_format(created, "%d.%m.%Y") as datum  
			from '.$prefix.'news 
			where id = "'.$clanak_id.'" 
			and title_hr != "" order by orderby desc '.$sql_dod2.'';
		//print $sql.'<br />';
		$row = Db::query_row($sql);
		$numrows = count($row);
		if($numrows > 0)
		{
			if($row)
			{
				$id = $row['id'];
				$title = ($row['title']);
				$datum = Db::clean($row['datum']);
				$tekst = $row['tekst'];
	
				$sql = "select photo_name from site_photos where table_name = '".$prefix."news' and table_id = ".$id." order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
					$slika_url_2 = _SITE_URL.'upload_data/site_photos/'.$slika;
				}
				else 
				{
					//$slika_url = '';
					$slika_url = _SITE_URL.'images/default.jpg';
					$slika_url_2 = _SITE_URL.'images/default.jpg';
				}
				
				$row['slika_url'] = $slika_url;
				$row['slika_url_2'] = $slika_url_2;
				
				$sql = "select photo_name, title from site_photos where table_name = '".$prefix."news' and table_id = ".$id." order by orderby asc, id asc limit 1,10";
				$rez2 = Db::query($sql);
				$numrows2 = count($rez2);
				$slike_dod = '';
				if($rez2)
				{
					$j = 0;
					$k = 0;
					$k2 = 0;
					foreach ($rez2 as $row2)
					{
						$slika = $row2['photo_name'];
						$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
						$slika_url_2 = _SITE_URL.'upload_data/site_photos/'.$slika;
						if($j == 4 || (($numrows2 - 1) == $j ) )
						{
							$cl_dod = ' class="pic last"';
							$j = -1;
						}
						else 
						{
							$cl_dod = ' class="pic"';
						}
						
						if(trim($row2['title']) == '')
						{
							$row2['title'] = $row['title'];
						}
						
						$slike_dod .= '
							<div'.$cl_dod.'>
								<a href="'.$slika_url_2.'" class="fab" rel="1" title="'.$row2['title'].'">
									<img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=114&h=86&zc=1" alt="'.$row2['title'].'" />
								</a>
							</div>';
						
						$j++;
						$k++;
						$k2++;
					}
				}
				$row['galerija'] = $slike_dod;
				
				//$sql = 'select file_name, title from site_files where table_name = "news" and table_id = "'.$id.'" order by orderby asc, id asc limit 1';
				/*$doc = Db::query_row($sql);
				$doc_url = ($doc != '') ? _SITE_URL.'upload_data/site_files/'.$doc['file_name'] : '';
				$doc_name = ($doc['title'] != '') ? $doc['title'] : $doc['file_name'];
				
				$row['dokument_url'] = $doc_url;
				$row['dokument'] = $doc_name;*/
				
				/*$sql = 'select file_name, title from site_files where table_name = "news" and table_id = "'.$id.'" order by orderby asc, id asc limit 10';
				$rez2 = Db::query($sql);
				$numrows2 = count($rez2);
				$doc_dod = '';
				if($rez2)
				{
					$j = 0;
					$k = 1;
					foreach ($rez2 as $row2)
					{
						$doc = $row2['file_name'];
						$doc_title = $row2['title'];
						if($doc_title == '')
						{
							$doc_title = 'Dokument ('.$k.')';
						}
						$doc_url = _SITE_URL.'upload_data/site_files/'.$doc;
						if(($numrows2 - 1) != $j )
						{
							$cl_dod = '';
						}
						else 
						{
							$cl_dod = ' class="last"';
						}
						
						$doc_dod .= '<a'.$cl_dod.' href="'.$doc_url.'">'.$doc_title.'</a>';
						
						$j++;
						$k++;
					}
				}
				$row['dokumenti'] = $doc_dod;
				*/
				if($row['youtube_url'] != '')
				{
					$embed_link = generiraj_youtube_embed_link($row['youtube_url']);
					$row['youtube'] = $embed_link;
				}
			}
		}
		else 
		{
			header("Location: "._SITE_URL."");
			exit;
		}
		
		$row['cat'] = _NOVOSTI;
		$row['cat_link'] = _SITE_URL._URL_NOVOSTI;
		
		return $row;
	}
	
}
?>
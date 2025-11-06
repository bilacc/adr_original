<?php
class DisplayItems
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
	
	
	public function izdvojeno_items_list()
	{
		$content = '';
		
		/*$get_subcat = get_supcat((int)_su3, 'categories');
		$data['breadcrumbs'] = '';
		if($get_subcat['title'] != '')
		{
			$data['breadcrumbs'] = '<a href="'.$get_subcat['link'].'">'.$get_subcat['title'].'</a>';
		}*/
		
		$sql_dod = ' AND on_homepage = "y" ';
		
		$sql = 'select id, categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created 
			from items 
			where 1 = 1 
			'.$sql_dod.'
			order by orderby desc 
			limit 4';
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
				$text = ($row['text']);
				$text = truncate(strip_tags($text), 80, $ending = '...', true, true);
				
				$sql = 'select title'.$this->lng.' as title from categories where id = "'.$cat_id.'"';
				//print $sql.'<br />';
				$cat = Db::query_one($sql);
				//echo $cat.'<br />';

				$link = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($cat).'/'.clean_uri($title).'/'.$id;
				
				$sql = "select photo_name from site_photos where table_name = 'items' and table_id = $id order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}
				

				if($j == 3)
				{
					$cl_dod = ' class="box last"';
				}
				else 
				{
					$cl_dod = ' class="box"';
				}
				
				$content .= '
					<div'.$cl_dod.'>
						<a href="'.$link.'"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=200&h=179&zc=1" alt="'.$title.'"/></a>
						<a class="txt" href="'.$link.'">
							<h2>'.$title.'</h2>
							<p>'.$text.'</p>
						</a>
					</div>';
					
					if($j == 3) 
					{
						$content .= '<div class="clear"></div>';
						$j = -1;
					}
					
				$content_2 .= '
					<div class="article">
						<a href="#"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=50&h=50&zc=1" alt="'.$title.'"/></a>
						<h3><a href="'.$link.'">'.$title.'</a></h3>
						<div class="clear"></div>
					</div>';
				
				$i++;
				$j++;
			}
		}
		
		$data['content'] = $content;
		$data['content_2'] = $content_2;
		return $data;
	}
	
	
	public function items_list()
	{
		$content = '';
		
		$pagination = new Pagination;
		$br_stavki_po_stranici = 9;
		$br_stavki_po_stranici2 = $br_stavki_po_stranici;
		
		$get_subcat = get_supcat((int)_su4, 'categories');
		$data['breadcrumbs'] = '';
		if($get_subcat['title'] != '')
		{
			$data['breadcrumbs'] = '<a href="'.$get_subcat['link'].'">'.$get_subcat['title'].'</a>';
		}
		
		if(is_numeric(_su3))
		{
			//echo _su5;
			$pg = _su5;
			$subcat_dod = get_subcategories_for_sql((int)_su3, 'categories');
			$sql_dod = ' AND categories_id in('.$subcat_dod.') ';
		
			$sql2 = 'select count(id) 
				from items 
				where 1 = 1 
				'.$sql_dod.'
				order by orderby desc ';
			//print $sql2.'<br />';
			$numrows_izdv = Db::query_one($sql2);
		}
		else if(is_numeric(_su4))
		{
			//echo _su6;
			$pg = _su6;
			$sql_dod = ' AND categories_id = '.(int)_su4.' ';
			
			$sql2 = 'select count(id) 
				from items 
				where 1 = 1 
				'.$sql_dod.'
				order by orderby desc ';
			//print $sql2.'<br />';
			$numrows_izdv = Db::query_one($sql2);
		}
		else if(_su2 == '_su2') 
		{
			$sql_dod = ' AND on_homepage = "y" ';
		}
		
		$sql_dod2 = " limit ".($pg * $br_stavki_po_stranici2).", $br_stavki_po_stranici";
		
		if($pg == '')
		{
			$pg = 0;
		}
		
		$sql = 'select id, categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created 
			from items 
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
				$text = ($row['text']);
				$text = truncate(strip_tags($text), 80, $ending = '...', true, true);
				
				$sql = 'select title'.$this->lng.' as title from categories where id = "'.$cat_id.'"';
				//print $sql.'<br />';
				$cat = Db::query_one($sql);
				//echo $cat.'<br />';

				$link = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($cat).'/'.clean_uri($title).'/'.$id;
				
				$sql = "select photo_name from site_photos where table_name = 'items' and table_id = $id order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}
				

				if($j == 2)
				{
					$cl_dod = ' class="box last"';
				}
				else 
				{
					$cl_dod = ' class="box"';
				}
				
				$content .= '
					<div'.$cl_dod.'>
						<a href="'.$link.'"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=200&h=179&zc=1" alt=""/></a>
						<a class="txt" href="'.$link.'">
							<h2>'.$title.'</h2>
							<p>'.$text.'</p>
						</a>
					</div>';
					
					if($j == 2) 
					{
						$content .= '<div class="clear"></div>';
						$j = -1;
					}
				
				$i++;
				$j++;
			}
		}
		if($numrows_izdv > $br_stavki_po_stranici)
		{
			if(is_numeric(_su3))
			{
				$link_dod = _LNG.'/'._su1.'/'._su2.'/'._su3.'/';
			}
			else 
			{
				$link_dod = _LNG.'/'._su1.'/'._su2.'/'._su3.'/'._su4.'/';
			}
			$pag .= '<div class="pag_back"><ul class="pag_container">'.$pagination->paginate_front($br_stavki_po_stranici, $pg, $numrows_izdv, $link_dod).'</ul><div class="clearfix"></div></div>';
		}
		if($numrows == 1)
		{
			header("Location: ".$link."");
			exit;
		}
		else if($numrows == 0)
		{
			$content = '<div class="articles"><h2>'._TRENUTNO_NEMA_SADRZAJA.'</h2></div>';
		}
		
		$data['content'] = $content;
		$data['title'] = $cat;
		if($get_subcat['title'] != '')
		{
			$data['title'] = $cat.' - '.$get_subcat['title'];
		}
		else if(is_numeric(_su3))
		{
			$sql = 'select title_hr from categories where id = "'.(int)_su3.'"';
			//print $sql.'<br />';
			$data['title'] = Db::query_one($sql);
		}
		$data['pagination'] = $pag;
		
		return $data;
	}
	
	public function search_list()
	{
		$content = '';
		
		// $pagination = new Pagination;
		// $br_stavki_po_stranici = 8;
		// $br_stavki_po_stranici2 = $br_stavki_po_stranici;
		// //echo _su5;
		// $pg = _su5;
		
		// $get_subcat = get_supcat((int)_su3, 'categories');
		$data['breadcrumbs'] = '';
		// if($get_subcat['title'] != '')
		// {
			// $data['breadcrumbs'] = '<a href="'.$get_subcat['link'].'">'.$get_subcat['title'].'</a>';
		// }
		
		// if(is_numeric(_su3))
		// {
			// $subcat_dod = get_subcategories_for_sql((int)_su3, 'categories');
			// $sql_dod = ' AND categories_id in('.$subcat_dod.') ';
			
			// $sql_dod2 = " limit ".($pg * $br_stavki_po_stranici2).", $br_stavki_po_stranici";
		
			// $sql2 = 'select count(id) 
				// from items 
				// where 1 = 1 
				// '.$sql_dod.'
				// order by orderby desc ';
			// //print $sql2.'<br />';
			// $numrows_izdv = Db::query_one($sql2);
		// }
		// else if(_su2 == '_su2') 
		// {
			// $sql_dod = ' AND on_homepage = "y" ';
		// }
		
		// if($pg == '')
		// {
			// $pg = 0;
		// }
		
		$sql = 'select id, categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created, "items" table_name, orderby 
			from items 
			where 1 = 1 
			AND title'.$this->lng.' LIKE "%'.$_POST['search_string'].'%" OR text1'.$this->lng.' LIKE "%'.$_POST['search_string'].'%"
			UNION ALL 
			select id, 0 categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created, "news" table_name, orderby
			from news 
			where 1 = 1 
			AND title'.$this->lng.' LIKE "%'.$_POST['search_string'].'%" OR text1'.$this->lng.' LIKE "%'.$_POST['search_string'].'%"
			UNION ALL
			select id, 0 categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text, date_format(created, "%d. %M %Y.") as created, "pr_news" table_name, orderby
			from pr_news 
			where 1 = 1 
			AND title'.$this->lng.' LIKE "%'.$_POST['search_string'].'%" OR text1'.$this->lng.' LIKE "%'.$_POST['search_string'].'%"
			order by orderby desc';
		// print $sql.'<br />';
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
				$text = ($row['text']);
				$text = truncate(strip_tags($text), 150, $ending = '...', true, true);
				
				$sql = 'select title_hr from categories where id = "'.$cat_id.'"';
				//print $sql.'<br />';
				$cat = Db::query_one($sql);
				//echo $cat.'<br />';

				if($row['table_name'] == 'pr_news')
				{
					$link = _SITE_URL._URL_PR_NOVOSTI_DETALJI.'/'.clean_uri($title).'/'.$id;
				}
				elseif($row['table_name'] == 'news')
				{
					$link = _SITE_URL._URL_NOVOSTI_DETALJI.'/'.clean_uri($title).'/'.$id;
				}
				elseif($row['table_name'] == 'items')
				{
					$link = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($cat).'/'.clean_uri($title).'/'.$id;
				}
				
				$sql = "select photo_name from site_photos where table_name = '".$row['table_name']."' and table_id = $id order by orderby asc, id asc limit 1";
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
		// if($numrows_izdv > $br_stavki_po_stranici)
		// {
			// $link_dod = _su1.'/'._su2.'/'._su3.'/';
			// $pag .= '<div class="pag_container">'.$pagination->paginate_front($br_stavki_po_stranici, $pg, $numrows_izdv, $link_dod).'<div class="clear"></div></div>';
		// }
		if($numrows == 1)
		{
			header("Location: ".$link."");
			exit;
		}
		if($numrows == 0)
		{
			$content = '<div class="articles"><h2>'._TRENUTNO_NEMA_SADRZAJA.'</h2></div>';
		}
		
		$data['content'] = $content;
		$data['title'] = $cat;
		// $data['pagination'] = $pag;
		
		return $data;
	}
	
	
	public function items_details()
	{
		$clanak_id = (int)_su3;
		$this->content_html = '';
		
		if(strstr($_SERVER['REQUEST_URI'],_URL_CATEGORIES_DETAILS))
		{
			$table = 'categories';
			$sql = 'select id, title'.$this->lng.' as title, text1'.$this->lng.' as text1,  
				date_format(created, "%d. %M %Y.") as created
				from categories  
				where id = "'.$clanak_id.'" 
				and title'.$this->lng.' != "" order by orderby desc '.$sql_dod2.'';
		}
		else 
		{
			$table = 'items';
			$sql = 'select id, categories_id, title'.$this->lng.' as title, text1'.$this->lng.' as text1, 
				video as youtube_url, video_title'.$this->lng.' as video_title, 
				video_2 as youtube_url_2, video_2_title'.$this->lng.' as video_2_title, 
				video_3 as youtube_url_3, video_3_title'.$this->lng.' as video_3_title, 
				video_4 as youtube_url_4, video_4_title'.$this->lng.' as video_4_title, 
				video_5 as youtube_url_5, video_5_title'.$this->lng.' as video_5_title, 
				lat_1, lon_1, 
				date_format(created, "%d. %M %Y.") as created
				from items 
				where id = "'.$clanak_id.'" 
				and title'.$this->lng.' != "" order by orderby desc '.$sql_dod2.'';
		}
		//print $sql.'<br />';
		$row = Db::query_row($sql);
		$numrows = count($row);
		if($numrows > 0)
		{
			if($row)
			{
				$id = $row['id'];
				$title = ($row['title']);
				$datum = Db::clean($row['created']);
				$tekst = $row['tekst'];
				
				$cat_id = ($row['categories_id']);
				$sql = 'select title_hr from categories where id = "'.$cat_id.'"';
				//print $sql.'<br />';
				$cat = Db::query_one($sql);
				$row['cat'] = $cat;
				
				$row['cat_link'] = _SITE_URL._URL_CATEGORIES.'/'.get_cat_sef_title($cat_id, 'categories', 'up').'/'.clean_uri($cat_id);
				
				$get_supcat = get_supcat($cat_id, 'categories');
				//$content['sup_cat'] = $sup['title'];
				$row['sup_cat'] = '';
				if($get_supcat['title'] != '')
				{
					$row['sup_cat'] = '<a href="'.$get_supcat['link'].'" class="crumb">'.$get_supcat['title'].'</a>';
					$row['sup_cat_title'] = $get_supcat['title'];
				}
				
				
				$sql_dod = ' and categories_id = "'.(int)$cat_id.'" ';
				//}
				//LINK LIJEVO
				$sql = 'select id, categories_id, title'.$this->lng.' as naziv 
					from '.$table.' 
					where id < "'.$clanak_id.'" 
					'.$sql_dod.' 
					and title'.$this->lng.' != "" 
					order by orderby desc limit 1';
				//print $sql.'<br />';
				$row2 = Db::query_row($sql);
				if(trim($row2['naziv']) != '')
				{
					$row['link_lijevo'] = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($cat).'/'.clean_uri($row2['naziv']).'/'.$row2['id'];
					$row['tooltip_lijevo'] = ($row2['naziv']);
				}
				else 
				{
					$row['link_lijevo'] = 'javascript:;';
				}
				
				//LINK DESNO
				$sql = 'select id, categories_id, title'.$this->lng.' as naziv 
					from '.$table.' 
					where id > "'.$clanak_id.'" 
					'.$sql_dod.' 
					and title'.$this->lng.' != "" 
					order by orderby asc limit 1';
				//print $sql.'<br />';
				$row2 = Db::query_row($sql);
				if(trim($row2['naziv']) != '')
				{
					$row['link_desno'] = _SITE_URL._LNG.'/'._URL_DETAILS.'/'.clean_uri($cat).'/'.clean_uri($row2['naziv']).'/'.$row2['id'];
					$row['tooltip_desno'] = ($row2['naziv']);
				}
				else 
				{
					$row['link_desno'] = 'javascript:;';
				}
				
	
				$sql = "select photo_name from site_photos where table_name = ".$table." and table_id = $id and static = 0 order by orderby asc, id asc limit 1";
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
				
				$sql = "select photo_name, title".$this->lng." as title from site_photos where table_name = '".$table."' and table_id = $id and static = 0 order by orderby asc, id asc limit 0,20";
				//print $sql.'<br />';
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
						
						if(!strstr($_SERVER['REQUEST_URI'],_URL_CATEGORIES_DETAILS))
						{
							$slike_dod .= '
								<div class="small_image_holder_2">
									<a class="image_link fancy" rel="1" href="'.$slika_url_2.'" title="'.$row2['title'].'">
										<img alt="'.$row2['title'].'" src="'.$slika_url.'">
									</a>
								</div>';
						}
						else 
						{
							$slike_dod .= '
								<li>
									<img src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=660&h=250&zc=1" alt="'.$title.'" />
								</li>';
						}
						
						$j++;
						$k++;
						$k2++;
					}
				}
				//if(!strstr($_SERVER['REQUEST_URI'],_URL_CATEGORIES_DETAILS))
				//{
					$row['galerija'] = $slike_dod;
				//}
				
				//$sql = 'select file_name, title from site_files where table_name = "items" and table_id = "'.$id.'" order by orderby asc, id asc limit 1';
				/*$doc = Db::query_row($sql);
				$doc_url = ($doc != '') ? _SITE_URL.'upload_data/site_files/'.$doc['file_name'] : '';
				$doc_name = ($doc['title'] != '') ? $doc['title'] : $doc['file_name'];
				
				$row['dokument_url'] = $doc_url;
				$row['dokument'] = $doc_name;*/
				
				$sql = 'select file_name, title'.$this->lng.' as title from site_files where table_name = "'.$table.'" and table_id = "'.$id.'" order by orderby asc, id asc limit 10';
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
							$doc_title = _DOCUMENT.' ('.$k.')';
						}
						$doc_url = _SITE_URL.'upload_data/site_files/'.$doc;
						/*if(($numrows2 - 1) != $j )
						{
							$cl_dod = '';
						}
						else 
						{
							$cl_dod = ' class="last"';
						}*/
						
						$doc_dod .= '<a class="download" href="'.$doc_url.'" target="_blank">'.$doc_title.'</a>';
						
						$j++;
						$k++;
					}
				}
				$row['dokumenti'] = $doc_dod;
				
				$row['video_galerija'] = '';
				if($row['youtube_url'] != '')
				{
					$video_title = ($row['video_title'] != '') ? $row['video_title'] : 'Video (1)';
					$row['video_galerija'] .= '<a href="'.$row['youtube_url'].'" class="video vid_pop">'.$video_title.'</a>'; 
				}
				if($row['youtube_url_2'] != '')
				{
					$video_title = ($row['video_2_title'] != '') ? $row['video_2_title'] : 'Video (2)';
					$row['video_galerija'] .= '<a href="'.$row['youtube_url_2'].'" class="video vid_pop">'.$video_title.'</a>'; 
				}
				if($row['youtube_url_3'] != '')
				{
					$video_title = ($row['video_3_title'] != '') ? $row['video_3_title'] : 'Video (3)';
					$row['video_galerija'] .= '<a href="'.$row['youtube_url_3'].'" class="video vid_pop">'.$video_title.'</a>'; 
				}
				if($row['youtube_url_4'] != '')
				{
					$video_title = ($row['video_4_title'] != '') ? $row['video_4_title'] : 'Video (4)';
					$row['video_galerija'] .= '<a href="'.$row['youtube_url_4'].'" class="video vid_pop">'.$video_title.'</a>'; 
				}
				if($row['youtube_url_5'] != '')
				{
					$video_title = ($row['video_5_title'] != '') ? $row['video_5_title'] : 'Video (5)';
					$row['video_galerija'] .= '<a href="'.$row['youtube_url_5'].'" class="video vid_pop">'.$video_title.'</a>'; 
				}
				
				/*if($row['youtube_url'] != '')
				{
					$embed_link = generiraj_youtube_embed_link($row['youtube_url']);
					$row['youtube'] = $embed_link;
				}*/
			}
		}
		/*else 
		{
			header("Location: "._SITE_URL."");
			exit;
		}*/
		if(!strstr($_SERVER['REQUEST_URI'],_URL_CATEGORIES_DETAILS))
		{
			$row['cat'] = _NEWS;
		}
		else 
		{
			$row['cat'] = $row['title'];
		}
		
		if(strstr($_SERVER['REQUEST_URI'],_URL_CATEGORIES_DETAILS))
		{
			if($clanak_id == 1)
			{
				$row['filter_kind'] = 'Sail boat';
			}
			else if($clanak_id == 2)
			{
				$row['filter_kind'] = 'Motor boat';
			}
			else if($clanak_id == 3)
			{
				$row['filter_kind'] = 'Catamaran';
			}
			else if($clanak_id == 4)
			{
				$row['filter_kind'] = 'Gulet';
			}
			else if($clanak_id == 5)
			{
				$row['filter_kind'] = 'Motoryacht';
			}
			else if($clanak_id == 6)
			{
				$row['filter_kind'] = 'Motoryacht';
			}
		}
		
		return $row;
	}
}
?>
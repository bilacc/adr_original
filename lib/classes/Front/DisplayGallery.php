<?php
class DisplayGallery
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
	
		
	public function gallery_all()
	{
		$pagination = new Pagination;
		$br_stavki_po_stranici = 10000;
		$br_stavki_po_stranici2 = $br_stavki_po_stranici;
		$pg = _su3;
		
		$sql2 = 'select count(id) from gallery where title'.$this->lng.' != "" order by orderby desc ';
		//print $sql2.'<br />';
		$numrows_izdv = Db::query_one($sql2);
		
		$sql_dod2 = " limit ".($pg * $br_stavki_po_stranici2).", $br_stavki_po_stranici";
		if($pg == '')
		{
			$pg = 0;
		}
			
		$sql = 'select id, title'.$this->lng.' as title from gallery where title'.$this->lng.' != "" order by orderby desc '.$sql_dod2.'';
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
				$link = _SITE_URL._LNG.'/'._URL_GALLERY_DETAILS.'/'.clean_uri($title).'/'.$id;

				$sql = "select photo_name from site_photos where table_name = 'gallery' and table_id = $id order by orderby asc, id asc limit 1";
				$slika = Db::query_one($sql);
				if($slika != '')
				{
					$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
				}
				else 
				{
					$slika_url = _SITE_URL.'images/default.jpg';
				}
				
				$this->content_html .= '
					<div class="single_gal">
						<div class="small_image_holder_2">
							<a class="image_link" href="'.$link.'">
								<img alt="" src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=96&h=71&zc=1">
							</a>
						</div>
						<h1 class="single_gal_title"><a href="'.$link.'">'.$title.'</a></h1>
					</div>';
			
				$i++;
				$j++;
			}
		}
		/*if($numrows_izdv > $br_stavki_po_stranici)
		{
			$link_dod = _LNG.'/'._su1.'/';
			$pag .= '<div class="pag_back"><ul class="pag_container">'.$pagination->paginate_front($br_stavki_po_stranici, $pg, $numrows_izdv, $link_dod).'</ul><div class="clearfix"></div></div>';
		}*/
		
		$data['galerija'] = $this->content_html;
		$data['pagination'] = $pag;
		
		return $data;
	}
	
	public function gallery_one()
	{
		$id = (int)_su3;
		
		$this->content_html = '';
		$sql = 'select id, title'.$this->lng.' as title from gallery where id = '.$id.' limit 1';
		//print $sql.'<br />';
		$rez = Db::query($sql);
		$numrows = count($rez);
		if($rez)
		{
			foreach ($rez as $row)
			{
				$title_dod = Db::clean($row['title']);
				$tekst = $row['tekst'];
				
				//$sl_dod = ($this->lng == '_hr') ? '' : $this->lng;
				$sql = "select photo_name, title".$this->lng." as title from site_photos where table_name = 'gallery' and table_id = $id order by orderby asc, id asc";
				//print $sql.'<br />';
				$rez2 = Db::query($sql);
				if($rez2)
				{
					$j = 1;
					$j2 = 0;
					foreach ($rez2 as $row2)
					{
						$slika = $row2['photo_name'];
						$title = $row2['title'];
						//$title = $row2['title'].' ('.$j.')';
						$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
						$slika_url_2 = _SITE_URL.'upload_data/site_photos/'.$slika;
						
						/*if($j2 == 4)
						{
							$cl_dod = ' class="img_gal_cont_last fancy"';
							$j2 = -1;
						}
						else 
						{
							$cl_dod = ' class="img_gal_cont fancy"';
						}*/
						
						$title = ($title != '') ? $title.' ('.$j.')' : $title_dod.' ('.$j.')';
						
						$this->content_html .= '
							<div class="small_image_holder_2">
								<a class="image_link fancy" rel="1" href="'.$slika_url_2.'" title="'.$title.'">
									<img alt="'.$title.'" src="'._SITE_URL.'lib/plugins/thumb.php?src='.$slika_url.'&w=96&h=71&zc=1">
								</a>
							</div>';
						
						$j++;
						$j2++;
					}
				}
			}
		}
		
		$data['title'] = $title_dod;
		$data['galerija'] = $this->content_html;
		
		return $data;
	}
	
	function gallery_list()
	{
		$content = '';
		$sql = 'select id, title'.$this->lng.' as title from gallery where title'.$this->lng.' != "" order by orderby desc '.$sql_dod2.'';
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
				$title = ($row['title']);
				if(_su3 == $id)
				{
					$cl_dod = '';
					$a_cl_dod = ' class="slc"';
					$title_dod = $title;
				}
				else if(($numrows - 1) == $i)
				{
					$cl_dod = ' class="last"';
					$a_cl_dod = '';
				}
				else 
				{
					$cl_dod = '';
					$a_cl_dod = '';
				}
				$link = _SITE_URL._LNG.'/'._URL_PHOTOS.'/'.clean_uri($title).'/'.$id;

				$content .= '<li'.$cl_dod.'><a href="'.$link.'"'.$a_cl_dod.'>'.$title.'</a></li>';
			
				$i++;
				$j++;
			}
		}
		$data['lista'] = $content;
		$data['title'] = $title_dod;
		
		return $data;
	}
}
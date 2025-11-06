<?php
class DisplayVideo
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
	
	
	/*public function video_homepage()
	{
		$sql = 'select id, title'.$this->lng.' as title, video  
			from video 
			where title'.$this->lng.' != "" 
			and on_homepage = "y" 
			order by orderby desc 
			limit 1 ';
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
				$link = Db::clean($row['video']);
				
				$this->content_html .= '
					<div class="video-galerija galerija">
						<a href="'._LNG.'/'._URL_VIDEO_GALERIJA.'">
							<div class="galerija-naslov">
								<h2>'.$title.'</h2>
							</div>
							<div class="galerija-slika">
								<div class="galerija-top"></div>
								<div class="play"></div>';
								$this->content_html .= generiraj_youtube_sliku($link, 0);
							$this->content_html .= '</div>
						</a>
					</div>';
			
				$i++;
				$j++;
			}
		}
		
		$data['video_izdvojeno'] = $this->content_html;
		
		return $data;
	}*/
	
		
	public function video_all()
	{
		$pagination = new Pagination;
		$br_stavki_po_stranici = 2000;
		$br_stavki_po_stranici2 = $br_stavki_po_stranici;
		$pg = (int)_su3;
		
		$sql_dod2 = " limit ".($pg * $br_stavki_po_stranici2).", $br_stavki_po_stranici";
		
		$sql2 = 'select count(id) from categories_video where title'.$this->lng.' != "" ';
		//print $sql2.'<br />';
		$numrows_izdv = Db::query_one($sql2);
		
		$sql = 'select id, title'.$this->lng.' as title  
			from categories_video 
			where title'.$this->lng.' != "" 
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
				$id = $row['id'];
				$title = Db::clean($row['title']);
				$link = _SITE_URL._LNG.'/'._URL_VIDEO_DETAILS.'/'.clean_uri($title).'/'.$id;
				
				$sql = 'select video from video where categories_id = '.$id.' order by orderby desc limit 1';
				$video = Db::query_one($sql);
				
				$yt_img = generiraj_youtube_url($video, 0);
				
				$this->content_html .= '
					<div class="single_gal">
						<div class="small_image_holder_2">
							<a class="image_link" href="'.$link.'">
								<img alt="" src="'._SITE_URL.'lib/plugins/thumb.php?src='.$yt_img.'&w=96&h=71&zc=1">
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
			$pag .= '<ul class="page_numbers">'.$pagination->paginate_front($br_stavki_po_stranici, $pg, $numrows_izdv, $link_dod).'</ul>';
		}*/
		
		$data['video'] = $this->content_html;
		$data['pagination'] = $pag;
		
		return $data;
	}
	
	
	public function video_one()
	{
		$id = (int)_su3;
		
		$sql = 'select title'.$this->lng.' as title from categories_video where id = '.$id.'';
		$title_dod = Db::query_one($sql);
		
		$this->content_html = '';
		$sql = 'select id, title'.$this->lng.' as title, video from video where categories_id = '.$id.' order by orderby desc';
		//print $sql.'<br />';
		$rez = Db::query($sql);
		$numrows = count($rez);
		if($rez)
		{
			foreach ($rez as $row)
			{
				$title = Db::clean($row['title']);
				$tekst = $row['tekst'];
				$video = $row['video'];
				
				$yt_img = generiraj_youtube_url($video, 0);
				
				$this->content_html .= '
					<div class="small_image_holder_2">
						<a class="image_link vid_pop" rel="1" href="'.$video.'" title="'.$title.'">
							<img alt="'.$title.'" src="'._SITE_URL.'lib/plugins/thumb.php?src='.$yt_img.'&w=96&h=71&zc=1">
						</a>
					</div>';
			}
		}
		
		$data['title'] = $title_dod;
		$data['galerija'] = $this->content_html;
		
		return $data;
	}
	
}
<?php
class DisplayMenu
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
	
	public function menu_list()
	{
		if((strstr($_SERVER['REQUEST_URI'],_URL_DETAILS)) && is_numeric(_su4))
		{
			$sql = 'SELECT categories_id FROM items WHERE id = '.(int)_su4.' LIMIT 1 ';
			//print $sql.'<br />';
			$parent_id = Db::query_one($sql);
			/*//echo 'cat item: '.$cat_item.'<br />';
			//echo $_SERVER['REQUEST_URI'];
			$parent_ids = get_parent_categories($cat_id, 'categories', true);
			//echo $parent_ids.'aaaaaaaaa<br />';
			$ids = '';*/
			
			$sql = 'SELECT parent_id FROM categories WHERE id = '.$parent_id.' LIMIT 1 ';
			//print $sql.'<br />';
			$cat_id = Db::query_one($sql);
			$cat_id_2 = $parent_id;
			
			$sql = 'select title'.$this->lng.' as title from categories where id = '.$cat_id.'';
			//print $sql.'<br />';
			$cat_title = Db::query_one($sql);
		}
		else if(is_numeric(_su4))
		{
			$sql = 'select parent_id from categories where id = '.(int)_su4.'';
			$cat_id = Db::query_one($sql);
			$cat_id_2 = (int)_su4;
			
			$sql = 'select title'.$this->lng.' as title from categories where id = '.$cat_id.'';
			$cat_title = Db::query_one($sql);
		}
		else
		{
			$cat_id = (int)_su3;
			$cat_id_2 = (int)_su3;
			
			$sql = 'select title'.$this->lng.' as title from categories where id = '.(int)_su3.'';
			$cat_title = Db::query_one($sql);
		}
		//echo $cat_title.'<br />';
		$content = '';
		$sql = 'select id, title'.$this->lng.' as title from categories 
			where parent_id = '.$cat_id.'
			and title'.$this->lng.' != "" order by orderby desc '.$sql_dod2.'';
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
				if( ($cat_id_2 == $id) )
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
				$link = _SITE_URL._LNG.'/'._URL_CATEGORIES.'/'._su2.'/'.clean_uri($title).'/'.$id;

				$content .= '<li'.$cl_dod.'><a href="'.$link.'"'.$a_cl_dod.'>'.$title.'</a></li>';
			
				$i++;
				$j++;
			}
		}
		$data['lista'] = $content;
		$data['title'] = $title_dod;
		$data['cat_title'] = $cat_title;
		//echo $data['cat_title'].'<br />';
		
		return $data;
	}
	
	public function output($parent, $level)
	{
		$this->counter++;
		
		if( $this->counter > 20 )
			return 'infinite loop';
			
		if(is_numeric(_su4) && (!strstr($_SERVER['REQUEST_URI'],"/"._URL_NOVOSTI_DETALJI)) )
		{
			$cl_id = (int)_su4;
			/*$sql = 'select categories_id from items where id = '.(int)_su4.'';
			$cl_kat_id = Db::query_one($sql);
			//echo $cl_kat_id.'<br />';*/
			
			$sql = 'SELECT categories_id FROM items WHERE id = '.(int)_su4.' LIMIT 1 ';
			//print $sql.'<br />';
			$cat_item = Db::query_one($sql);
			//echo 'cat item: '.$cat_item.'<br />';
			//echo $_SERVER['REQUEST_URI'];
			$parent_ids = get_parent_categories($cat_item, 'categories', true);
			$ids = '';
			/*for($z = 0; $z <sizeof($parent_ids); $z++)
			{
				echo $parent_ids[$z].'<br />';
			}*/
		}
			
		$sql = 'SELECT id, parent_id, title'.$this->lng.' FROM categories WHERE parent_id = '.$parent.' ORDER BY orderby ASC';
		//print $sql.'<br />';
		
		$result = Db::query($sql);
		
		$numrows = count($result);
		
		if($result)
		{
			$this->menu_html .= ($level > 1) ? '<ul class="podmenu" style="display:'.$this->display.';">' : '';
			
			$i = 0;
			
			foreach($result as $r)
			{
				if( $r['active'] == 'n' )
					continue;
				
				$ima = Db::query_one('SELECT COUNT(id) FROM categories WHERE parent_id = '.$r['id']);
				
				
				$cat_link = get_cat_sef_title($r['id'], 'categories', 'up').'/'.$r['id'];
				
				//preg_match('/\d+$/',_su2,$id_arr);
				//$kat_id = $id_arr[0];
				if(is_numeric(_su3) && (!strstr($_SERVER['REQUEST_URI'],"/"._URL_NOVOSTI_DETALJI)) )
				{
					$kat_id = (int)_su3;
				}
				

				/*$sql = 'select id from items where categories_id = '.$r['id'].' and id = '.(int)_su4.'';
				$postoji_id = Db::query_one($sql);
				echo $cl_kat_id.'<br />';*/
				
				$sel = ( ($r['id'] == $kat_id) || (in_array($r['id'], $parent_ids)) ) ? ' class="mslc"' : '' ;
				//$sel = ( ($r['id'] == $kat_id) || ($r['id'] == $cl_kat_id) ) ? ' class="aktiv"' : '' ;
				
				if( $level == 1 )
					$icon = Db::query_one('SELECT file_name FROM site_files WHERE table_name = "categories" AND table_id = '.$r['id']);
				
				if($ima > 0)
				{
					$affected_cats = self::get_affected_cats($kat_id);
					if(in_array($r['id'], $affected_cats) || (in_array($r['id'], $parent_ids)) )
						$this->display = 'block';
					else
						$this->display = 'none';
					
					if($level == 1)
					{
						if(in_array($r['id'], $affected_cats) || (in_array($r['id'], $parent_ids)) )
						{
							$li_dod = ' class="mslc"';
						}
						else
						{
							$li_dod = '';
						}
						/*if($i == 0)
						{
							 $li_dod = ' class="first"';
						}
						else 
						{
							$li_dod = '';
						}*/
						$this->menu_html .= '
							<li'.$li_dod.'>
								<a id="ma_'.$r['id'].'" href="'._SITE_URL._URL_KATEGORIJE.'/'.$cat_link.'" title="'.$r['title'.$this->lng].'"'.$sel.'>'.$r['title'.$this->lng].'</a>
							';
					}
					else
					{
						$this->menu_html .= '
							<li>
								<a id="ma_'.$r['id'].'" href="'._SITE_URL._URL_KATEGORIJE.'/'.$cat_link.'" title="'.$r['title'.$this->lng].'"'.$sel.'>'.$r['title'.$this->lng].'</a>
						';
					}
					
					$this->output($r['id'], $level+1);
					
					$this->menu_html .= '</li>';
				}
				else
				{
					if($level == 1)
					{
						if($i == 0)
						{
							 $li_dod = ' class="first"';
						}
						else if(($numrows - 1) != $i)
						{
							$li_dod = '';
						}
						else 
						{
							$li_dod = ' class="last"';
						}
						$this->menu_html .= '
							<li'.$li_dod.'>
								<a id="ma_'.$r['id'].'" href="'._SITE_URL._URL_KATEGORIJE.'/'.$cat_link.'" title="'.$r['title'.$this->lng].'"'.$sel.'>'.$r['title'.$this->lng].'</a>
						';
					}
					else
					{
						$this->menu_html .= '
						<li>
							<a id="ma_'.$r['id'].'" href="'._SITE_URL._URL_KATEGORIJE.'/'.$cat_link.'" title="'.$r['title'.$this->lng].'"'.$sel.'>'.$r['title'.$this->lng].'</a>
						';
					}
					
					$this->menu_html .= '</li>';
				}
				
				$i++;
			}
			
			$this->menu_html .= ( $level > 1 ) ? '</ul>' : '' ;
		}
		
		return $this->menu_html;
	}
	
	
	public function generate_menu()
	{
		$content = '';
		$sql = 'select id, title'.$this->lng.' as title from categories order by orderby asc, id desc';
		$result = Db::query($sql);
		if($result)
		{
			$i = 0;
			foreach($result as $r)
			{
				$link = _SITE_URL._LNG.'/'._URL_CATEGORIES_DETAILS.'/'.clean_uri($r['title']).'/'.$r['id'];
				$content .= '<li><a href="'.$link.'">'.$r['title'].'</a></li>';
			}
		}
		return $content;
	}
	
	static function get_affected_cats($id)
	{
		$id = (int)$id;
		$sql = 'SELECT k1.id k1id, k2.id k2id, k3.id k3id, k4.id k4id FROM categories k4 LEFT JOIN categories k3 ON k4.parent_id = k3.id LEFT JOIN categories k2 ON k3.parent_id = k2.id LEFT JOIN categories k1 ON k2.parent_id = k1.id WHERE k4.id = "'.$id.'"';
		$cat = Db::query_row($sql);
		
		$ids = array();
		for($i=1; $i<=4; $i++)
		{
			if($cat['k'.$i.'id'])
			{
				$ids[$i] = $cat['k'.$i.'id'];
			}
		}
		
		return $ids;
	}
	
	static function get_cat_link($id) // radi link za kategoriju
	{
		$sql = 'SELECT k1.title'.$this->lng.' k1n, k2.title'.$this->lng.' k2n, k3.title'.$this->lng.' k3n, k4.title'.$this->lng.' k4n, k4.id k4i FROM categories k4 LEFT JOIN categories k3 ON k4.parent_id = k3.id LEFT JOIN categories k2 ON k3.parent_id = k2.id LEFT JOIN categories k1 ON k2.parent_id = k1.id WHERE k4.id = '.(int)$id;
		$cat = Db::query_row($sql);
		
		$path1 = '';
		for($i=1; $i<=4; $i++)
		{
			if($cat['k'.$i.'n'])
			{
				$path1 .= clean_uri($cat['k'.$i.'n']).'-';
			}
		}
		$path1 .= $cat['k4i'];
		
		return $path1;
	}
	
	
	public function output_2($parent, $level)
	{
		$this->menu_html = '';
		$this->counter++;
		
		if( $this->counter > 20 )
			return 'infinite loop';
		
		$sql = 'SELECT id, parent_id, title'.$this->lng.' FROM categories WHERE parent_id = '.$parent.' ORDER BY orderby ASC';
		//print $sql.'<br />';
		$result = Db::query($sql);
		$i = 0;
		
		if(is_numeric(_su3))
		{
			$sql = 'SELECT parent_id FROM categories WHERE id = '.(int)_su3.' ORDER BY orderby ASC';
			//print $sql.'<br />';
			$ima_parent = Db::query_one($sql);
			
			if($ima_parent > 0)
			{
				$sql = 'SELECT parent_id FROM categories WHERE id = '.$ima_parent.' ORDER BY orderby ASC';
				//print $sql.'<br />';
				$ima_parent2 = Db::query_one($sql);
			}
		}
		
		if(is_numeric(_su4))
		{
			$sql = 'SELECT categories_id FROM articles WHERE id = '.(int)_su4.' LIMIT 1 ';
			//print $sql.'<br />';
			$cat_article = Db::query_one($sql);
			//echo 'cat article: '.$cat_article.'<br />';
			//echo $_SERVER['REQUEST_URI'];
			$parent_ids = get_parent_categories($cat_article, 'categories', true);
			$ids = '';
			/*for($z = 0; $z <sizeof($parent_ids); $z++)
			{
				echo $parent_ids[$z].'<br />';
			}*/
		}
		
		if($result)
		{
			//$this->menu_html .= '<ul class="menu_list">';
			foreach($result as $r)
			{
				if($i == 0){
					$class_dod = ' class="first"';
				} else {
					$class_dod = '';
				}
				if( ( (_su3 == $r['id']) || ($ima_parent == $r['id']) || ($ima_parent2 == $r['id']) ) || ( (strstr($_SERVER['REQUEST_URI'],"-detalji/")) && (in_array($r['id'], $parent_ids)) ) )
				{
					$a_dod = ' class="mslc_2"';
					$sub_hidden = '';
				}
				else 
				{
					$a_dod = '';
					$sub_hidden = ' hidden';
				}
				//echo 'r id: '.$r['id'].'<br />';
				//echo 'parent id: '._su3.'<br />';
				
				$cat_link = _SITE_URL.'artikli/'.get_cat_sef_title($r['id'], 'categories', 'up').'/'.$r['id'];
				
				$this->menu_html .= '<li'.$class_dod.'><a href="'.$cat_link.'"'.$a_dod.'>'.$r['title'.$this->lng].'</a>';
				
				$sql = 'SELECT id, parent_id, title'.$this->lng.' FROM categories WHERE parent_id = '.($r['id']).' ORDER BY orderby ASC';
				//print $sql.'<br />';
				$result2 = Db::query($sql);
				if($result2){
					$k = 0;
					foreach($result2 as $r2)
					{
						if($k == 0)
						{
							/*echo 'id: '.$r['id'].'<br />';
								echo 'parent id: '.$r['parent_id'].'<br />';
								echo 'id2: '.$r2['id'].'<br />';
								echo 'parent id2: '.$r2['parent_id'].'<br />';*/
							/*if( (_su3 == $r['id']) || ($r2['parent_id'] ==  $r['id']) )
							{
								$sub_hidden = '';
							}
							else 
							{
								$sub_hidden = ' hidden';
							}*/
							$this->menu_html .= '<ul class="sub'.$sub_hidden.'">';
						}
						if( ( (_su3 == $r2['id']) || ($ima_parent == $r2['id']) ) || ( (strstr($_SERVER['REQUEST_URI'],"-detalji/")) && (in_array($r2['id'], $parent_ids)) ) )
						//if(_su3 == $r2['id'] || ($ima_parent == $r2['id']) )
						{
							$a_dod = ' class="mslc_3"';
						}
						else 
						{
							$a_dod = '';
						}
						$cat_link = _SITE_URL.'artikli/'.get_cat_sef_title($r2['id'], 'categories', 'up').'/'.$r2['id'];
						$this->menu_html .= '<li><a href="'.$cat_link.'"'.$a_dod.'>'.$r2['title'.$this->lng].'</a>';
						
						
							$sql = 'SELECT id, parent_id, title'.$this->lng.' FROM categories WHERE parent_id = '.($r2['id']).' ORDER BY orderby ASC';
							//print $sql.'<br />';
							$result3 = Db::query($sql);
							if($result3){
								$k2 = 0;
								foreach($result3 as $r3)
								{
									if($k2 == 0)
									{
										//echo _su3.'<br />';
										//echo $r3['id'].'<br />';
										if( ( (_su3 == $r2['id']) || (_su3 == $r3['id']) || ($ima_parent == $r2['id']) ) || ( (strstr($_SERVER['REQUEST_URI'],"-detalji/")) && (in_array($r2['id'], $parent_ids)) ) )
										{
											$sub_hidden3 = '';
										} 
										else 
										{
											$sub_hidden3 = ' hidden';
										}
										$this->menu_html .= '<ul class="sub_3'.$sub_hidden3.'">';
									}
									if( (_su3 == $r3['id']) || ( (strstr($_SERVER['REQUEST_URI'],"-detalji/")) && (in_array($r3['id'], $parent_ids)) ) )
									{
										$a_dod3 = ' class="mslc_3"';
									}
									else 
									{
										$a_dod3 = '';
									}
									$cat_link = _SITE_URL.'artikli/'.get_cat_sef_title($r3['id'], 'categories', 'up').'/'.$r3['id'];
									$this->menu_html .= '<li><a href="'.$cat_link.'"'.$a_dod3.'>'.$r3['title'.$this->lng].'</a></li>';
									$k2++;
								}
								$this->menu_html .= '</ul>';
							}
							
							$this->menu_html .= '</li>';
						
						
						$k++;
					}
					$this->menu_html .= '</ul>';
				}
				
				$this->menu_html .= '</li>';
				$i++;
			}
			//$this->menu_html .= '</ul>';
		}
		
		return $this->menu_html;
	}
	
}
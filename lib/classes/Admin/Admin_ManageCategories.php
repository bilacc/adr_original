<?php
class Admin_ManageCategories
{
	private $t_categories = 'categories'; // tablica kategorija
	private $t_items = 'items'; // tablica itema
	public $subcategories = array(); // array koji sadrži id-ove podkategorija i id kategorije koju trenutno gledamo
	private $lng = ''; // ako je site višejezični varijabla će sadržavati _hr
	private $deleted_cats = array(); // idovi obrisanih kategorija koje vraćamo da znamo koje moramo maknuti iz pregleda
	private $id; // id kategorije s kojom trenutno radimo
	private $delete_cat_subcats_items_level = 0; // varijabla koja ograničava rekurziju, ako je iznad 20 levela automatski prekida
	private $tree_select_html = ''; // html stablo kategorija za select box
	
	public function __construct($id=0, $t_categories='', $t_items='')
	{
		if( $t_categories != '' )
			$this->t_categories = $t_categories;
		if( $t_items != '' )
			$this->t_items = $t_items;
		 
		if( get_conf('multi_language') == 1 )
		{
			$this->lng = '_hr';
		}
		else 
		{
			$this->lng = '_hr';
		}
		
		if( $id !== 0 && (int)$id !== 0 )
		{
			$this->id = (int)$id;
			 
			$this->subcategories = get_subcategories($this->id, $this->t_categories, true);
		}
	}
	
	public function delete_cat_subcats_items($id=0)
	{
		if( isset($id) && (int)$id > 0 )
			$id = (int)$id;
		else
			$id = $this->id;
		
		$this->delete_cat_subcats_items_level++;
		if( $this->delete_cat_subcats_items_level > 20 )
			return $this->deleted_cats;
		
		Db::query('DELETE FROM '.$this->t_categories.' WHERE id = '.$id.' LIMIT 1');
		
		$this->deleted_cats[] = $id;
		
		$sub_cats = Db::query('SELECT id FROM '.$this->t_categories.' WHERE parent_id = '.$id);
		if($sub_cats)
		{
			foreach($sub_cats as $sk => $sv)
			{
				$items = Db::query('SELECT * FROM '.$this->t_items.' WHERE parent_id = '.$id);
				if($items)
				{
					foreach($items as $ik => $iv)
					{
						$files = Db::query('SELECT * FROM site_files WHERE table_name = "'.$this->t_items.'" AND table_id = '.$iv['id']);
						if( $files )
						{
							foreach($files as $k => $v)
							{
								if($v['file_name'] != '')
								{
									if(is_file(_SITE_ROOT.'upload_data/site_files/'.$v['file_name']))
										@unlink(_SITE_ROOT.'upload_data/site_files/'.$v['file_name']);
								}
							}
						}
						
						$photos = Db::query('SELECT * FROM site_photos WHERE table_name = "'.$this->t_items.'" AND table_id = '.$iv['id']);
						if( $photos )
						{
							foreach($photos as $k => $v)
							{
								if($v['photo_name'] != '')
								{
									if(is_file(_SITE_ROOT.'upload_data/site_photos/th_'.$v['photo_name']))
										@unlink(_SITE_ROOT.'upload_data/site_photos/th_'.$v['photo_name']);
									if(is_file(_SITE_ROOT.'upload_data/site_photos/big_'.$v['photo_name']))
										@unlink(_SITE_ROOT.'upload_data/site_photos/big_'.$v['photo_name']);
									if(is_file(_SITE_ROOT.'upload_data/site_photos/'.$v['photo_name']))
										@unlink(_SITE_ROOT.'upload_data/site_photos/'.$v['photo_name']);
								}
							}
						}
						
						$q = Db::query('DELETE FROM '.$this->t_items.' WHERE id = '.$v['id'].' LIMIT 1');
					}
				}
				
				$this->delete_cat_subcats_items($sv['id']);
			}
		}
		
		return $this->deleted_cats;
	}
		
	public function display_tree_select($parent, $level, $curr_id=0, $max_level=10)
	{
		if($level <= $max_level){
			$result = Db::query('SELECT k.id, k.title'.$this->lng.' FROM '.$this->t_categories.' k WHERE k.parent_id = '.$parent.' ORDER BY orderby ASC');
			
			if($result)
			{
				$c=0;
				$broj = count($result);
				foreach($result as $r)
				{
					$c++;
					
					$level_prefix = '';
					$user_agent_str = $_SERVER['HTTP_USER_AGENT'];
					if(preg_match("/like\sGecko\)\sChrome\//", $user_agent_str) && !strstr($user_agent_str, 'Iron')){
						for($i = 0;$i < $level;$i++)
						{
							$level_prefix .= '-';
						}
					}
					
					$sel = ( $curr_id == $r['id'] ) ? 'selected="selected"' : '' ;
							
					$this->tree_select_html .= '<option class="level-'.$level.'" value="'.$r['id'].'" '.$sel.'>'.$level_prefix.' '.$r['title'.$this->lng].'</option>';
								
					$this->display_tree_select($r['id'], $level+1, $curr_id, $max_level);
				}
			}
		}
		return $this->tree_select_html;
	}


	public function display_tree_select2($parent, $level, $curr_id=0, $max_level=10)
	{
		if($level <= $max_level){
			$result = Db::query('SELECT k.id, k.title'.$this->lng.' FROM city k WHERE k.parent_id = '.$parent.' ORDER BY orderby ASC');
			
			if($result)
			{
				$c=0;
				$broj = count($result);
				foreach($result as $r)
				{
					$c++;
					
					$level_prefix = '';
					$user_agent_str = $_SERVER['HTTP_USER_AGENT'];
					if(preg_match("/like\sGecko\)\sChrome\//", $user_agent_str) && !strstr($user_agent_str, 'Iron')){
						for($i = 0;$i < $level;$i++)
						{
							$level_prefix .= '-';
						}
					}
					
					$sel = ( $curr_id == $r['id'] ) ? 'selected="selected"' : '' ;
							
					$this->tree_select_html .= '<option class="level-'.$level.'" value="'.$r['id'].'" '.$sel.'>'.$level_prefix.' '.$r['title'.$this->lng].'</option>';
								
					$this->display_tree_select($r['id'], $level+1, $curr_id, $max_level);
				}
			}
		}
		return $this->tree_select_html;
	}


}
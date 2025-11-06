<?php
class Admin_Crud
{
	private $skip_default = array('e_id', 'img_', 'file_', 'spremi1', 'spremi2', 'spremi3', 'created_', 'expires_', 'spec' ,'col', 'dodatna_num' , 'tlocrt','lokacija');
	public $table, $action = 'insert', $id = 0, $img_static_num = 0, $img_static_sizes = array(), $img_num = 0, $img_titles = false, $img_sizes = array(), $files_static_num = 0, $files_num = 0, $files_titles = false, $title_row_name = '', $return_url = false, $skip = array();
	
	public function __construct()
	{
		$user = new Admin_User;
		if( ! $user->is_logged() )
		{
			header('Location: '._SITE_URL.'/admin/login/');
			exit;
		}
	}
	
	public function save_data($data)
	{
		$d = $this->skip_for_sql($data);
		$sql = $this->construct_sql($d, $this->id);
		//echo $sql.'<br />';
		//exit;
		if( $sql != '' )
			$q = Db::query($sql);
		
		if( isset($q) && $q )
		{
			$stats_logg = new Admin_Stats($this->table);
			$stats_logg->set_entry_title_row_name($this->title_row_name);
			
			if( $this->action == 'insert' )
			{
				$this->id = Db::insert_id();
				
				Db::query('UPDATE '.$this->table.' SET orderby = '.$this->id.' WHERE id = '.$this->id); // updateamo orderby broj na id reda kojeg smo unijeli
												
				//$this->add_notification();
				
				$stats_logg->set_action('c');
			}
			else
			{
				
				$stats_logg->set_action('e');				
			}


			if(isset($_POST['created_godina']) && isset($_POST['created_mjesec']) && isset($_POST['created_dan'])){				
				$created = $_POST['created_godina'].'-'.sprintf('%1$02d', $_POST['created_mjesec']).'-'.sprintf('%1$02d', $_POST['created_dan']).' '.date('H:i:s');
				$expires = $_POST['expires_godina'].'-'.sprintf('%1$02d', $_POST['expires_mjesec']).'-'.sprintf('%1$02d', $_POST['expires_dan']);
				
				Db::query('UPDATE '.$this->table.' SET created = "'.$created.'", expires = "'.$expires.'" WHERE id = '.$this->id);
				
				if(date("d.m.Y") < date("d.m.Y", strtotime($created)))
				{
					Db::query('UPDATE '.$this->table.' SET status = "zakazano" WHERE id = '.$this->id);
				}elseif($_POST['status'] == 'zakazano'){
					Db::query('UPDATE '.$this->table.' SET status = "da" WHERE id = '.$this->id);
				}
			}

			if($this->table == 'items'){
				Db::query('DELETE FROM item_specifikacije WHERE item_id = '.$this->id);

				foreach($_POST['spec'] as $k => $v)
				{
					if($_POST['spec_vrijednost_hr'][$v] != ""){
						Db::query('INSERT INTO item_specifikacije SET item_id = '.$this->id.', specifikacija_id = '.$v.', spec_vrijednost_hr = "'.$_POST['spec_vrijednost_hr'][$v].'"');
					}
				}

			}
			if($this->table == 'items'){
				$lokacija = $_POST['lokacija'];
				$lok = explode(", ",$lokacija);
				$i=1;
				foreach ($lok as $red) {
					if($i==1){
						$lon = substr($red, 1); 
						Db::query('UPDATE '.$this->table.' SET gmap_lat_1 = "'.$lon.'" WHERE id = '.$this->id);
					}else{
						$lat = substr($red, 0, -1);
						Db::query('UPDATE '.$this->table.' SET gmap_lon_1 = "'.$lat.'" WHERE id = '.$this->id);
					}
					
					$i++;
				}
				
			}

						
			$this->handle_uploads();
			$this->update_img_titles($data);
			$this->update_files_titles($data);
			
			$stats_logg->set_entry_id($this->id);
			$stats_logg->create_activity_logg();
		}
		else
		{
			$this->add_err('Dogodila se greška. Upit nije uspio! er.2');
		}
		
		$this->redirect($data);
	}
	
	public function handle_uploads()
	{
		if($this->img_num > 0 || $this->img_num == null)
		{
			if($this->img_num == null)
			{
				$max = count($_SESSION['images']) - 1;
			}else{
				$max = $this->img_num; 
			}
						
			for($i = 1;$i <= $max;$i++)
			{		
				
				if(isset($_SESSION['images'][$i]['name']) && $_SESSION['images'][$i]['name'] != '')
				{
					$original_name = clean_uri($_SESSION['images'][$i]['name']);
					$folder_s = 'upload_data/site_photos';
					$folder_t = 'upload_data/tmp';
					
					if(is_file(_SITE_ROOT.$folder_s.'/'.$original_name))
						$file_name = rand().rand().rand().'_'.$original_name;
					else
						$file_name = $original_name;
					
					if(strtolower(substr($file_name,-3)) == 'jpg' || strtolower(substr($file_name,-4)) == 'jpeg' || strtolower(substr($file_name,-3)) == 'png' || strtolower(substr($file_name,-3)) == 'gif')
					{
						rename(_SITE_ROOT.$folder_t.'/'.$original_name, _SITE_ROOT.$folder_s.'/tmp_'.$file_name);
						
						$input_file_name = _SITE_ROOT.$folder_s.'/tmp_'.$file_name;
						$destination_file_name1 = _SITE_ROOT.$folder_s.'/th_'.$file_name;
						$destination_file_name2 = _SITE_ROOT.$folder_s.'/'.$file_name;
												
						image_resize_to($input_file_name, $destination_file_name1, $this->img_sizes[0], $this->img_sizes[1], 100);
						
						if($this->img_sizes[2] && $this->img_sizes[3])
							image_resize_to($input_file_name, $destination_file_name2, $this->img_sizes[2], $this->img_sizes[3], 100);
						else
							rename(_SITE_ROOT.$folder_s.'/tmp_'.$file_name, _SITE_ROOT.$folder_s.'/'.$file_name);
												
						@unlink($input_file_name);
						
						$q = Db::query('INSERT INTO site_photos SET table_name = "'.$this->table.'", table_id = '.$this->id.', photo_name = "'.$file_name.'"');
						$i_id = Db::insert_id();
						$i_q = Db::query('UPDATE site_photos SET orderby = '.$i_id.' WHERE id = '.$i_id.' LIMIT 1');
					}
				}
			}
		}
				
		if($this->files_num > 0 || $this->files_num == null)
		{	
			$cn = lang_data($this->table, 'title\_');
			$column_name = $cn['column_name'];
			$lang_label = $cn['lang_label'];
						
			for($i = 0; $i < sizeof($column_name); $i++)
			{
				if($this->files_num == null)
				{
					$max = count($_SESSION['file-'.strtolower($lang_label[$i])]) - 1;
				}else{
					$max = $this->files_num;
				}
				
				for($j = 1;$j <= $max;$j++)
				{	
					if(isset($_SESSION['file-'.strtolower($lang_label[$i])][$j]['name']) && $_SESSION['file-'.strtolower($lang_label[$i])][$j]['name'] != '')
					{
						$original_name = clean_uri($_SESSION['file-'.strtolower($lang_label[$i])][$j]['name']);
						$folder_s = 'upload_data/site_files';
						$folder_t = 'upload_data/tmp';
						
						if(is_file(_SITE_ROOT.$folder_s.'/'.$original_name))
							$file_name = rand().rand().rand().'_'.$original_name;
						else
							$file_name = $original_name;
							
						rename(_SITE_ROOT.$folder_t.'/'.$original_name, _SITE_ROOT.$folder_s.'/'.$file_name);
						
						Db::query('INSERT INTO site_files_'.strtolower($lang_label[$i]).' SET table_name = "'.$this->table.'", table_id = '.$this->id.', file_name = "'.$file_name.'"');
						$last_id = Db::insert_id();
						Db::query('UPDATE site_files_'.strtolower($lang_label[$i]).' SET orderby = '.$last_id.' WHERE id = '.$last_id.' LIMIT 1');						
					}
				}
			}
		}
		
		if($this->table == 'newsletter')
		{
			for($i = 1;$i <= 5;$i++)
			{
				if(isset($_SESSION['newsletter_'.$i][1]['name']) && $_SESSION['newsletter_'.$i][1]['name'] != '')
				{
					$original_name = clean_uri($_SESSION['newsletter_'.$i][1]['name']);
					$folder_s = 'upload_data/newsletter_photos';
					$folder_t = 'upload_data/tmp';
					
					if(is_file(_SITE_ROOT.$folder_s.'/'.$original_name))
						$file_name = rand().rand().rand().'_'.$original_name;
					else
						$file_name = $original_name;
					
					if(strtolower(substr($file_name,-3)) == 'jpg' || strtolower(substr($file_name,-4)) == 'jpeg' || strtolower(substr($file_name,-3)) == 'png' || strtolower(substr($file_name,-3)) == 'gif')
					{
						rename(_SITE_ROOT.$folder_t.'/'.$original_name, _SITE_ROOT.$folder_s.'/tmp_'.$file_name);
						
						$input_file_name = _SITE_ROOT.$folder_s.'/tmp_'.$file_name;
						$destination_file_name1 = _SITE_ROOT.$folder_s.'/th_'.$file_name;
						$destination_file_name2 = _SITE_ROOT.$folder_s.'/'.$file_name;
												
						image_resize_to($input_file_name, $destination_file_name1, $this->img_sizes[0], $this->img_sizes[1], 100);
						
						if($this->img_sizes[2] && $this->img_sizes[3])
							image_resize_to($input_file_name, $destination_file_name2, $this->img_sizes[2], $this->img_sizes[3], 100);
						else
							rename(_SITE_ROOT.$folder_s.'/tmp_'.$file_name, _SITE_ROOT.$folder_s.'/'.$file_name);
												
						@unlink($input_file_name);
						
						Db::query('UPDATE newsletter SET image'.$i.' = "'.$file_name.'" WHERE id = '.$this->id);	
					}
				}
			}
		}
		
		$files = glob(_SITE_ROOT.'upload_data/tmp/*'); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
			unlink($file); // delete file
		}
	}
		
	public function update_img_titles($data)
	{
		if( ! $this->img_titles )
			return false;
		
		$cn = lang_data('site_photos', 'title\_');
		$column_name = $cn['column_name'];
		$lang_label = $cn['lang_label'];
				
		for($i = 0; $i < sizeof($column_name); $i++)
		{
			foreach($data as $k => $v)
			{
				if( substr($k,0,13) == 'img_title_'.strtolower($lang_label[$i]).'_')
				{
					$id = (int)substr($k,13);
					
					if( $id > 0 )
						Db::query('UPDATE site_photos SET title_'.strtolower($lang_label[$i]).' = "'.Db::clean($v).'" WHERE id = '.$id);
				}
			}		
		}
	}
	
	
	public function update_files_titles($data)
	{
		if( ! $this->files_titles )
			return false;
		
		$cn = lang_data($this->table, 'title\_');
		$column_name = $cn['column_name'];
		$lang_label = $cn['lang_label'];
				
		for($i = 0; $i < sizeof($column_name); $i++)
		{
			foreach($data as $k => $v)
			{
				if( substr($k,0,14) == 'file_title_'.strtolower($lang_label[$i]).'_')
				{
					$id = (int)substr($k,14);
					
					if( $id > 0 )
						Db::query('UPDATE site_files_'.strtolower($lang_label[$i]).' SET title = "'.Db::clean($v).'" WHERE id = '.$id);
				}
			}		
		}
	}
	
	public function get_data()
	{
		$data = Db::query_row('SELECT * FROM '.$this->table.' WHERE id = '.(int)$this->id.' LIMIT 1');
		
		$imgs = array();
		$imgs_static = array();
		$files = array();
		
		$cn = lang_data($this->table, 'title\_');
		$column_name = $cn['column_name'];
		$lang_label = $cn['lang_label'];
		
		if( $this->img_num > 0 || $this->img_num == null)
		{
			$imgs = Db::query('SELECT * FROM site_photos WHERE table_name = "'.$this->table.'" AND table_id = '.$this->id.' ORDER BY orderby ASC');
		}
				
		if( $this->files_num > 0 || $this->files_num == null)
		{
			for($i = 0; $i < sizeof($column_name); $i++)
			{
				$files[strtolower($lang_label[$i])] = Db::query('SELECT * FROM site_files_'.strtolower($lang_label[$i]).' WHERE table_name = "'.$this->table.'" AND table_id = '.$this->id.' ORDER BY orderby ASC');
			}
		}
		
		return array('data'=>$data, 'imgs'=>$imgs, 'files'=>$files);
	}
	
	private function construct_sql($data, $id=false)
	{
		$sql = '';
		
		if( count($data) > 0 )
		{
			foreach($data as $k => $v)
			{
				$sql_arr[] = $k.' = "'.$v.'"';
			}
			
			if( $this->action == 'insert' )
			{
				$sql = '
					INSERT INTO '.$this->table.' 
						SET '.implode(', ',$sql_arr).', created = "'.date('Y-m-d H:i:s').'"
				';
			}
			else if( $this->action == 'update' )
			{
				$sql = '
					UPDATE '.$this->table.' 
						SET '.implode(', ',$sql_arr).'
					WHERE id = '.(int)$id.'
				';
			}
			//print $sql.'<br />';
			return $sql;
		}
		else
		{
			$this->add_err('Greška! Ništa nije proslijeđeno za generiranje sqla. er.1');
			return false;
		}
	}
	
	private function skip_for_sql($d)
	{
		if( is_array($d) )
		{
			$to_skip = array();
			
			foreach($this->skip_default as $ks => $vs)
			{
				foreach($d as $kd => $vd)
				{
					if( substr($kd, 0, strlen($vs)) == $vs )
						$to_skip[] = $kd;
				}
			}
			
			foreach($d as $k => $v)
			{
				if( ! in_array($k, $this->skip) && ! in_array($k, $to_skip) )
				{
					if(is_array($v))
					{
						$tmp = '';
						$tmp = implode(",", $v);
						$data[Db::clean($k)] = Db::clean($tmp);
					}else{
						$data[Db::clean($k)] = Db::clean($v);
					}
				}
			}
			
			if( isset($data) )
				return $data;
		}
		else
		{
			return false;
		}
	}
	
	public function redirect($data)
	{
		if( isset($data['spremi3']) ) // save
		{
			$return_to = ( $this->return_url != '' ) ? $this->return_url : $this->table.'_unos.php' ;
			
			if( isset($_SESSION['err_collector']) && count($_SESSION['err_collector']) > 0 )
			{	
				if( $this->action == 'insert' )
					header('Location: '.$return_to.'?action='.$this->action.'&err=3&s=1&e=');
				else
					header('Location: '.$return_to.'?action='.$this->action.'&id='.$this->id.'&err=3');
			}
			else
			{
				header('Location: '.$return_to.'?action='.$this->action.'&id='.$this->id.'&status=success');
			}
		}
		else if( isset($data['spremi2']) ) // save & all
		{
			$return_to = ( $this->return_url != '' ) ? $this->return_url : $this->table.'_pregled.php' ;
			
			if( isset($_SESSION['err_collector']) && count($_SESSION['err_collector']) > 0 )
			{
				if( $this->action == 'insert' )
					header('Location: '.$return_to.'?action='.$this->action.'&err=2');
				else
					header('Location: '.$return_to.'?action='.$this->action.'&id='.$this->id.'&err=2');
			}
			else
			{
				header('Location: '.$return_to.'?action='.$this->action.'&status=success');
			}
		}
		else // save & new
		{
			$return_to = ( $this->return_url != '' ) ? $this->return_url : $this->table.'_unos.php' ;
			
			if( isset($_SESSION['err_collector']) && count($_SESSION['err_collector']) > 0 )
			{
				if( $this->action == 'insert' )
					header('Location: '.$return_to.'?action='.$this->action.'&err=1');
				else
					header('Location: '.$return_to.'?action='.$this->action.'&id='.$this->id.'&err=1');
			}
			else
			{
				header('Location: '.$return_to.'?action='.$this->action.'&status=success');
			}
		}
	}
	
	private function add_notification()
	{
		if( $_SESSION['admin']['user_type'] == 'admin2' )
		{
			$sql = 'INSERT INTO admin_notifications SET admin_users_id = '.(int)$_SESSION['admin']['id'].', table_name = "'.$this->table.'", table_id = '.$this->id.', created = NOW()';
			$a = Db::query($sql);
			if( !$a )
				$this->add_err($sql);
		}
	}
	
	private function add_err($err)
	{
		$_SESSION['err_collector'] = null;
		$_SESSION['err_collector'][] = $err;
	}
	
	
	public function generiraj_grupu_checkboxova($polje, $tablica, $tablica_2, $broj_stupaca, $get_id)
	{
		$sql = "select id, $polje as naziv from $tablica order by id asc";
		$rez = Db::query($sql);
		$j = 1;
		$k = 0;
		if($rez)
		{
			if($tablica == $tablica_2)
			{
				$vezano_dod = '_vezano';
			}
			else 
			{
				$vezano_dod = '';
			}
			$content = '<table border="0" cellpadding="2" cellspacing="2"><tr>';
			foreach($rez as $row)
			{
			   $item_id = $row['id'];
			   $naslov = stripslashes($row['naziv']);
			   if(isset($get_id)){
	           	   $sql = 'select count('.$tablica.'_id) from poveznica_'.$tablica_2.'_'.$tablica.' where '.$tablica_2.'_id'.$vezano_dod.' = "'.$get_id.'" and '.$tablica.'_id = "'.$item_id.'"';
	           	   //print $sql.'<br />';
				   $numrows_tag_od = Db::query_one($sql);
	           	   if($numrows_tag_od > 0){
	           	   		$cheked_tagovi[$j] = 'checked="checked"';
	           	   } else {
	           	   		$cheked_tagovi[$j] = '';
	           	   }
			   }
			   if($k % $broj_stupaca == 0)
			   {
			   		$content .= '</tr><tr><td valign="top">';
			   } 
			   else 
			   {
			   		$content .= '<td valign="top">';
			   }
				$content .= '<input id="'.$tablica.'_'.$j.'" name="'.$tablica.'_'.$tablica_2.'[]" value="'.$item_id.'" type="checkbox" '.$cheked_tagovi[$j].' /> <label for="'.$tablica.'_'.$tablica2.''.$j.'">'.$naslov.'</label>&nbsp;&nbsp;';
				//$content .= '<input type="hidden" name="'.$tablica.'_naziv'.$item_id.'[]" value="'.$naslov.'"  /> ';
				$content .= '</td>';
				$j++;
				$k++;
			}
			$content .= '</tr></table>';
		}
		
		return $content;
	}
	
	public function poveznica_punjenje($ime_polja, $ime_polja_2, $vrijednost_polja) 
	{
		$sql = "delete from poveznica_".$ime_polja."_".$ime_polja_2." where ".$ime_polja."_id = '$vrijednost_polja' ";
		//print $sql.'<br />';
		Db::query($sql);
		$polje = $_POST[$ime_polja_2.'_'.$ime_polja];
		//echo 'polje: '.$polje.'<br />';
		if ($polje){
			if($ime_polja == $ime_polja_2)
			{
				$vezano_dod = '_vezano';
			}
			else 
			{
				$vezano_dod = '';
			}
			foreach ($polje as $t){
				$sql = "insert into poveznica_".$ime_polja."_$ime_polja_2 (".$ime_polja."_id, ".$ime_polja_2."_id".$vezano_dod.") values ('$vrijednost_polja', '$t')";
				//print $sql.'<br />';
				if(Db::query($sql)) {
					//echo 'Stručna sprema: ',$t,'<br />';
				} else {
					//$ima_error++;
				}
			}
		}
	}
}
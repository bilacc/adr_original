<?php
class Admin_Operations
{
	private $table, $id;
	
	public function __construct($table, $id)
	{
		$exists = Db::query('SELECT id FROM '.Db::clean($table).' WHERE id = '.(int)$id);
		if( ! $exists )
		{
			return false;
		}
		else
		{
			$this->id = (int)$id;
			$this->table = Db::clean($table);
		}
	}
	
	public function delete_user()
	{
		$q = Db::query('DELETE FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		
		$data = Db::query('SELECT id FROM orders WHERE users_id = '.$this->id);
		
		if( $data )
		{
			foreach($data as $k => $v)
			{
				Db::query('DELETE FROM orders_data WHERE id = '.$v['id']);
				Db::query('DELETE FROM orders WHERE users_id = '.$this->id);
			}
		}
		
		if( $q )
			return true;
		else
			return false;
	}
	
	public function delete_entry()
	{
		$files = Db::query('SELECT * FROM site_files WHERE table_name = "'.$this->table.'" AND table_id = '.$this->id);
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
		
		$photos = Db::query('SELECT * FROM site_photos WHERE table_name = "'.$this->table.'" AND table_id = '.$this->id);
		
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
		
		if($this->table == 'newsletter')
		{
			for($i = 1;$i <= 5;$i++)
			{
				$photo = Db::query_one('SELECT image'.$i.' FROM '.$this->table.' WHERE id = '.$this->id);
				
				if($photo != '')
				{
					if(is_file(_SITE_ROOT.'upload_data/newsletter_photos/th_'.$photo))
						@unlink(_SITE_ROOT.'upload_data/newsletter_photos/th_'.$photo);
					if(is_file(_SITE_ROOT.'upload_data/newsletter_photos/big_'.$photo))
						@unlink(_SITE_ROOT.'upload_data/newsletter_photos/big_'.$photo);
					if(is_file(_SITE_ROOT.'upload_data/newsletter_photos/'.$photo))
						@unlink(_SITE_ROOT.'upload_data/newsletter_photos/'.$photo);
				}
			}
		}
		
		$q = Db::query('DELETE FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		
		if( $q )
			return true;
		else
			return false;
	}
	
	public function delete_image()
	{
		$photo = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$this->id.' LIMIT 1');
		
		if( $photo )
		{
			if(is_file(_SITE_ROOT.'upload_data/site_photos/th_'.$photo))
				@unlink(_SITE_ROOT.'upload_data/site_photos/th_'.$photo);
			if(is_file(_SITE_ROOT.'upload_data/site_photos/big_'.$photo))
				@unlink(_SITE_ROOT.'upload_data/site_photos/big_'.$photo);
			if(is_file(_SITE_ROOT.'upload_data/site_photos/'.$photo))
				@unlink(_SITE_ROOT.'upload_data/site_photos/'.$photo);
				
			$q = Db::query('DELETE FROM site_photos WHERE id = '.$this->id.' LIMIT 1');
			
			if( $q )
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}
		
	public function delete_file()
	{
		$file = Db::query_one('SELECT file_name FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		
		if( $file )
		{
			if(is_file(_SITE_ROOT.'upload_data/site_files/'.$file))
				@unlink(_SITE_ROOT.'upload_data/site_files/'.$file);
				
			$q = Db::query('DELETE FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
			
			if( $q )
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}
				
	public function active_status_change()
	{
		$id = $this->id;
		$table = $this->table;
		
		$status = Db::query_one('SELECT active FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		$new_status = ( $status == 'n' ) ? 'y' : 'n' ;
		
		$q = Db::query('UPDATE '.$this->table.' SET active = "'.$new_status.'" WHERE id = '.$this->id.' LIMIT 1');
		
		if( $q )
			return $new_status;
		else
			return false;
	}
	public function set_zc()
	{
		$id = $this->id;
		$table = 'site_photos';
		
		$zc = Db::query_one('SELECT zc FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		$new_zc = ( $zc == '2' ) ? '1' : '2' ;
		
		$q = Db::query('UPDATE '.$this->table.' SET zc = "'.$new_zc.'" WHERE id = '.$this->id.' LIMIT 1');
		
		if( $q )
			return $new_zc;
		else
			return false;
	}
	public function set_tlocrt()
	{
		$id = $this->id;
		$table = 'site_photos';
		
		$tlocrt = Db::query_one('SELECT tlocrt FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		$new_tlocrt = ( $tlocrt == 'ne' ) ? 'da' : 'ne' ;
		
		$q = Db::query('UPDATE '.$this->table.' SET tlocrt = "'.$new_tlocrt.'" WHERE id = '.$this->id.' LIMIT 1');
		
		if( $q )
			return $new_tlocrt;
		else
			return false;
	}
	public function set_aktivno($column)
	{
		$id = $this->id;
		$table = $this->table;
		
		$aktivno = Db::query_one('SELECT '.$column.' FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');
		$new_aktivno = ( $aktivno == 'da' ) ? 'ne' : 'da' ;
		
		$q = Db::query('UPDATE '.$this->table.' SET '.$column.' = "'.$new_aktivno.'" WHERE id = '.$this->id.' LIMIT 1');
		var_dump('SELECT '.$column.' FROM '.$this->table.' WHERE id = '.$this->id.' LIMIT 1');exit;
		if( $q )
			return $new_aktivno;
		else
			return false;
	}
	
}
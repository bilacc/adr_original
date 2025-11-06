<?php
class Admin_Stats
{
	private $user_id;
	private $ip;
	private $table;
	private $entry_title;
	private $entry_id;
	private $entry_title_row_name;
	private $action;
	
	public function __construct($table=false)
	{
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->user_id = $_SESSION['admin']['id'];
		$this->table = $table;
	}
	
	private function set_entry_title()
	{
		$sql = 'SELECT '.$this->entry_title_row_name.' FROM '.$this->table.' WHERE id = '.$this->entry_id;
		
		$title = Db::query_one($sql);
		
		if(!$title)
			$this->entry_title = 'bez naslova';
		else
			$this->entry_title = $title;
	}
	
	public function set_entry_title_row_name($entry_title_row_name)
	{
		$this->entry_title_row_name = $entry_title_row_name;
	}
	
	public function set_action($action)
	{
		if($action == 'c')
			$this->action = 'create';
		elseif($action == 'e')
			$this->action = 'edit';
		elseif($action == 'd')
			$this->action = 'delete';
		else
			throw new Exception('e:1');
	}
	
	public function set_entry_id($entry_id)
	{
		if((int)$entry_id < 1)
			throw new Exception('e:2');
		else
			$this->entry_id = (int)$entry_id;
	}
	
	public function create_activity_logg()
	{
		$this->set_entry_title();
		
		$sql = 'INSERT INTO stats_activities SET admin_users_id = '.$this->user_id.', ip = "'.$this->ip.'", table_name = "'.$this->table.'", entry_title = "'.Db::clean($this->entry_title).'", action_type = "'.$this->action.'"';
		$q = Db::query($sql);
		
		if(!$q)
			throw new Exception('e:3 - '.$sql);
	}
	
	public function create_login_logg()
	{
		$user_id = (int)$this->user_id;
		
		$ip = $this->ip;
		
		$sql = 'INSERT INTO stats_logging SET admin_users_id = '.$user_id.', ip = "'.$ip.'"';
		Db::query($sql);
	}
}
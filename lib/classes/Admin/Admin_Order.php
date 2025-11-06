<?php
class Admin_Order extends WebShop
{
	private $users_id=0;
	
	public function __construct($order_id=0, $users_id=0)
	{
		if( get_conf('multi_language') == 1 )
		{
			$lng = get_conf('languages');
			$this->lng = '_'.$lng[0];
		}
		
		$this->users_id = $users_id;
		$this->order_id = $order_id;
		$this->update_order_data();
	}
	
	public function ordering_type()
	{
		$data['users_id'] = $this->users_id;
		$data['select'] = 'users_id';
		
		return $data;
	}
}
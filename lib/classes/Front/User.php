<?php
class User
{
	public function delete_profile()
	{
		$id = self::info('id');
		
		Db::query('DELETE FROM users WHERE id = '.$id.' LIMIT 1');
		// Db::query('DELETE FROM reported_users WHERE reported_users_id = '.$id);
		
		self::logout();
	}
	
	public static function validate_registration($data1, $required)
	{
		$data = array();
		$err = array();
		
		foreach($data1 as $k => $v)
		{
			if( in_array(Db::clean($k,true), $required) && Db::clean(trim($v),true) == '' )
				$err[] = $k;
			
			if( $k != 'password1' && $k != 'password2' )
				$data[Db::clean($k,true)] = Db::clean($v,true);
		}
		
		$data['password1'] = $data1['password1'];
		$data['password2'] = $data1['password2'];
		
		foreach($required as $k => $v)
		{
			if( ! array_key_exists($v, $data1) )
				$err[] = $v;
		}
		
		if( in_array('username', $required) )
		{
			if( $data['username'] == '' )
			{
				$err[] = 'username1';
			}
			else if( !preg_match('/^[A-Za-z0-9\+_-]+$/',$data['username'],$match) )
			{
				$err[] = 'username2';
			}
			else if( strlen($data['username']) < 4 || strlen($data['username']) > 20 )
			{
				$err[] = 'username4';
			}
			else
			{
				$is_user = Db::query_one('SELECT id FROM users WHERE username = "'.$data['username'].'" LIMIT 1');
				if( $is_user )
				{
					$err[] = 'username3';
				}
			}
		}
		
		if( $data['email'] == '' )
		{
			$err[] = 'email';
		}
		else if( ! valid_email($data['email']) )
		{
			$err[] = 'email';
		}
		else
		{
			$is_email = Db::query_one('SELECT id FROM users WHERE email = "'.$data['email'].'" LIMIT 1');
			if( $is_email )
			{
				$err[] = 'email_exists';
			}
		}
		
		if( $data['password1'] != $data['password2'] )
		{
			$err[] = 'password1';
			$err[] = 'password2';
			$err[] = 'passwords_dont_match';
		}
		
		if( strlen($data['password1']) < 4 )
		{
			$err[] = 'password1';
			$err[] = 'password2';
			$err[] = 'password_short';
		}
		
		return $err;
	}
	
	public static function registration($data1, $required=array(), $user_id)
	{
		$data = array();
		//var_dump($data1).'<br />';
		$err = array();
		
		foreach($data1 as $k => $v)
		{
			if( in_array(Db::clean($k), $required) && Db::clean(trim($v)) == '' )
				$err[] = $k;
			
			if (is_numeric($user_id))
			{
				$data[Db::clean($k)] = Db::clean($v);
			} else {
				if( $k != 'password1' && $k != 'password2' )
				$data[Db::clean($k)] = Db::clean($v);
			}
		}
		
		if (!is_numeric($user_id))
		{
			$data['password1'] = $data1['password1'];
			$data['password2'] = $data1['password2'];
		}
		
		foreach($required as $k => $v)
		{
			if( ! array_key_exists($v, $data) )
				$err[] = $v;
		}
		
		if (!is_numeric($user_id))
		{
			if( ! valid_email($data['email']) )
			{
				$err[] = 'email';
			}
			else
			{
				$is_email = Db::query_one('SELECT id FROM users WHERE email = "'.Db::clean($data['email']).'" LIMIT 1');
				if( $is_email )
				{
					$err[] = 'email';
					$err[] = 'email_exists';
					$err[] = 'show_txt';
				}
			}
			
			if( $data['password1'] != $data['password2'] )
			{
				$err[] = 'password1';
				$err[] = 'password2';
				$err[] = 'passwords_dont_match';
				$err[] = 'show_txt';
			}
			
			if( strlen($data['password1']) < 4 )
			{
				$err[] = 'password1';
				$err[] = 'password_short';
				$err[] = 'show_txt';
			}
		} 
		else 
		{
			$lozinka_dod = '';
			if( ($data['password1'] != '') || ($data['password2'] != '') )
			{
				if( $data['password1'] != $data['password2'] )
				{
					$err[] = 'password1';
					$err[] = 'password2';
					$err[] = 'passwords_dont_match';
					$err[] = 'show_txt';
				}
				
				if( strlen($data['password1']) < 4 )
				{
					$err[] = 'password1';
					$err[] = 'password_short';
					$err[] = 'show_txt';
				}
				$lozinka_dod = ' password = "'.self::calc_password($data['password1']).'", ';
			}
		}
		
		if( count($err) > 0 )
		{
			return array('status' => array_unique($err));
		}
		else
		{
			$birthdate = $data['god'].'-'.$data['mj'].'-'.$data['dan'].' 00:00:00';
			if (is_numeric($user_id))
			{
				$sql = '
				UPDATE users SET 
					tip_osobe_id = "'.$data['tip_osobe_id'].'", 
					spol_id = "'.$data['gender'].'", 
					fname = "'.$data['fname'].'", 
					lname = "'.$data['lname'].'", 
					birthdate = "'.$birthdate.'", 
					company = "'.$data['company'].'",
					oib = "'.$data['oib'].'", 
					'.$lozinka_dod.'  
					address = "'.$data['address'].'", 
					address_shipping = "'.$data['address_shipping'].'", 
					phone = "'.$data['phone'].'", 
					fax = "'.$data['fax'].'", 
					wanted_work = "'.$data['zeljezna_zanimanja'].'", 
					mphone = "'.$data['mphone'].'", 
					city = "'.$data['city'].'",
					post = "'.$data['post'].'",
					country = "'.$data['country'].'",
					contact_email = "'.$data['contact_email'].'",
					created = "'.date('Y-m-d H:i:s').'" 
					WHERE id = "'.$user_id.'"
					';
			}
			else 
			{
				$sql = '
				INSERT INTO users SET 
					tip_osobe_id = "'.$data['tip_osobe_id'].'", 
					spol_id = "'.$data['gender'].'", 
					fname = "'.$data['fname'].'", 
					lname = "'.$data['lname'].'", 
					birthdate = "'.$birthdate.'", 
					company = "'.$data['company'].'", 
					oib = "'.$data['oib'].'", 
					email = "'.$data['email'].'", 
					password = "'.self::calc_password($data['password1']).'", 
					address = "'.$data['address'].'", 
					address_shipping = "'.$data['address_shipping'].'", 
					phone = "'.$data['phone'].'", 
					fax = "'.$data['fax'].'", 
					wanted_work = "'.$data['zeljezna_zanimanja'].'", 
					mphone = "'.$data['mphone'].'", 
					city = "'.$data['city'].'",
					post = "'.$data['post'].'",
					country = "'.$data['country'].'",
					contact_email = "'.$data['contact_email'].'",
					created = "'.date('Y-m-d H:i:s').'"
				';
			}
			
			//print $sql.'<br />';
			
			$q = Db::query($sql);
			
			if( $q )
			{
				if (!is_numeric($user_id))
				{
					$id = Db::insert_id();
					
					$hash = self::calc_hash($data['email'], $id);
					
					Db::query('UPDATE users SET activation_hash = "'.$hash.'" WHERE id = '.$id);
					
					// ako je checkirana opcija newsletter onda email korisnika upisujemo u tablicu za newsletter
					if( $data['newsletter'] == 'y' )
					{
						Db::query('INSERT INTO newsletter_email SET email = "'.Db::clean($data['email']).'", fname = "'.Db::clean($data['fname']).'", lname = "'.Db::clean($data['lname']).'" ');
					} 
				}
				else 
				{
					$id = $user_id;
				}
				
				//$this->poveznica_punjenje('users', 'vrste_zanimanja', $data1['vrste_zanimanja'], $id);
				//$this->poveznica_punjenje('users', 'zupanije', $data1['zupanije'], $id);
				//$this->poveznica_punjenje('users', 'lang', $data1['lang'], $id);
				
				if (!is_numeric($user_id))
				{
					$mail = '
						<p>
							'._REGISTRACIJA_EMAIL_PORUKA1.'
							<br/>
							<a href="'._SITE_URL.$_SESSION['lng'].'/aktivacija/'.$hash.'">'._SITE_URL.$_SESSION['lng'].'/aktivacija/'.$hash.'</a>
							<br/>
							'._SITE_TITLE.'
						</p>
					';
					
					$m = send_html_mail(_DOMENA_EMAIL, $data['email'], _REGISTRACIJA_EMAIL_SUBJECT.' '._SITE_URL, $mail);
					//$m = 1;
					if( $m )
					{
						return array('status' => 'ok');
					}
					else
					{
						return array('status' => 'er1');
					}
				}
				return array('status' => 'ok');
			}
			else
			{
				return array('status' => 'er2');
			}
		}
	}
	
	public function activate($hash)
	{
		$is_user = Db::query_one('SELECT id FROM users WHERE activation_hash = "'.Db::clean($hash).'" LIMIT 1');
		
		if( $is_user )
		{
			$q = Db::query('UPDATE users SET activation_hash = 1 WHERE id = '.$is_user.' LIMIT 1');
			
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
	
	
	public function login($data)
	{
		$sql = 'SELECT * FROM users WHERE activation_hash = "1" AND email = "'.Db::clean($data['email']).'" AND password = "'.$this->calc_password($data['password']).'" LIMIT 1';
		$correct = Db::query_row($sql);
		
		$_SESSION['user'] = null;
		
		if($correct)
		{
			// if($correct['aktivan'] == '0')
			// {
				// return 'n_a';
			// }
			// else 
			{
				//$_SESSION['user']['id'] = $correct['id'];
				//$_SESSION['user']['email'] = $correct['email'];
				//$_SESSION['user']['countries_id'] = $correct['countries_id'];
				//$_SESSION['user']['login_hash'] = $this->calc_hash($correct['email'], $correct['id']);
				
				$this->set_session($correct);
				$this->set_vars();
				
				// ako je checkirana opcija newsletter onda email korisnika upisujemo u tablicu za newsletter
				if( $data['newsletter'] == 'y' )
				{
					Db::query('INSERT INTO newsletter_email SET email = "'.Db::clean($data['email']).'"');
				}
				
				if( $data['remember_me'] == 'y' )
				{
					$this->set_remember_me($correct['email'], $correct['id']);
				}
				
				// budući da je korisnik sada ulogiran onda ćemo transferirati njegovu narudžbu ako je ima sa njegovog cookie_id-a na njegov users_id
				//$this->transfer_order();
				
				$q = Db::query('UPDATE users SET last_login = "'.date('Y-m-d H:i:s').'", last_seen = "'.date('Y-m-d H:i:s').'" WHERE id = '.$correct['id'].' LIMIT 1');
				
				return 'y';
			}
		}
		else
		{
			$sql = 'SELECT * FROM users WHERE activation_hash != "1" AND email = "'.Db::clean($data['email']).'" AND password = "'.$this->calc_password($data['password']).'" LIMIT 1';
			//print $sql.'<br />';
			$correct = Db::query_row($sql);
			
			if( $correct )
				return 'n_a';
			else
				return false;
		}
	}
	
	public function login_on_remember_me()
	{
		if( isset($_COOKIE['rm']) )
		{
			$correct = Db::query_row('SELECT id, fb_id, fname, lname, email FROM users WHERE activation_hash = "1" AND remember_me_hash = "'.Db::clean($_COOKIE['rm'],true).'" LIMIT 1 ');
			
			if( $correct )
			{
				self::update_last_seen($correct['id']);
				self::set_session($correct);
				
				return true;
			}
			else
			{
				self::logout();
				
				return false;
			}
		}
	}
	
	public function fb_login()
	{
		$correct = Db::query_row('SELECT id, fb_id, username, email, user_type, notifications_mail, notifications_sound FROM users WHERE fb_id = '.(int)$_SESSION['fb']['id']);
		if( $correct )
		{
			self::update_last_seen($correct['id']);
			self::set_session($correct);
			
			$_SESSION['fb'] = null;
			
			return true;
		}
		else
		{
			// provjerava dali postoji već to korisničko ime, ako da onda doda još na to neke random brojeve
			$username = clean_uri($_SESSION['fb']['first_name']).'_'.clean_uri($_SESSION['fb']['last_name']);
			$is_username = Db::query_one('SELECT id FROM users WHERE username = "'.$username.'"');
			if( $is_username || strlen($username) < 4 )
			{
				$username = $username.rand(100,1000).rand(100,1000);
			}
			
			$is_user = Db::query_one('SELECT fb_id FROM users WHERE fb_id = '.(int)$_SESSION['fb']['id']);
			
			if( !$is_user )
			{
				$sql = '
					INSERT INTO users SET
						fb_id = "'.(int)$_SESSION['fb']['id'].'",
						email = "'.$_SESSION['fb']['email'].'",
						username = "'.$username.'",
						activation_hash = "1",
						created = NOW()
				';
				
				$q = Db::query($sql);
				if( $q )
				{
					$id = Db::insert_id();
					
					$correct = Db::query_row('SELECT id, fb_id, username, email, user_type, notifications_mail, notifications_sound FROM users WHERE id = '.$id);
					
					self::update_last_seen($correct['id']);
					self::set_session($correct);
					
					$_SESSION['fb'] = null;
				}
			}
		}
	}
	
	private function set_vars()
	{
		$this->id = $_SESSION['user']['id'];
		$this->email = $_SESSION['user']['email'];
	}
	
	private static function set_session( $data )
	{
		$_SESSION['user']['id'] = $data['id'];
		$_SESSION['user']['fb_id'] = $data['fb_id'];
		// $_SESSION['user']['username'] = $data['username'];
		$_SESSION['user']['email'] = $data['email'];
		
		$_SESSION['user']['fname'] = $data['fname'] ;
		$_SESSION['user']['lname'] = $data['lname'] ;
		
		$_SESSION['user']['login_hash'] = self::calc_hash($data['email'], $data['id']);
	}
	
	public static function check_subscription()
	{
		if( self::is_logged() && ( ! isset($_SESSION['user']['subscription_checked']) || (time() - $_SESSION['user']['subscription_checked']) >= 60 ) )
		{
			$is_subscribed = Db::query_one('SELECT end_date FROM subscriptions WHERE users_id = '.(int)self::info('id').' AND end_date > NOW() ORDER BY end_date DESC LIMIT 1');
			
			$_SESSION['user']['subscription_checked'] = time();
			
			if( $is_subscribed )
			{
				$_SESSION['user']['subscribed'] = true;
				$_SESSION['user']['subscription_expires'] = $is_subscribed;
			}
			else
			{
				$_SESSION['user']['subscribed'] = false;
				$_SESSION['user']['subscription_expires'] = false;
			}
		}
	}
	
	private static function update_last_seen($id)
	{
		$q = Db::query('UPDATE users SET last_seen = NOW() WHERE id = '.(int)$id.' LIMIT 1');
	}
	
	public static function resend_activation_email($email)
	{
		$hash = Db::query_one('SELECT activation_hash FROM users WHERE email = "'.Db::clean($email).'" LIMIT 1');
		
		if( $hash && $hash != '1' )
		{
			$mail = '
				<p>
					Thank you for your registration. To activate your profile and start using it please click on the link below.
					<br/>
					<a href="'._SITE_URL.'activation/'.$hash.'">'._SITE_URL.'activation/'.$hash.'</a>
					<br/>
					<br/>
					'._SITE_TITLE.'
				</p>
			';
			
			$m = send_html_mail(get_conf('from_mail'), $email, 'Profile activation on '._SITE_URL, $mail);
			
			if( $m )
				return 'y';
			else
				return 'er_mail';
		}
		else
		{
			return 'er';
		}
	}
	
	public static function save_new_password($p)
	{
		Db::query('UPDATE users SET password = "'.self::calc_password($p).'" WHERE id = '.self::info('id'));
		
		return true;
	}
	
	public static function send_new_password($email)
	{
		if( ! valid_email($email) )
			return false;
		
		$is_user = Db::query_row('SELECT id, email FROM users WHERE email = "'.Db::clean($email).'" LIMIT 1');
		
		if( $is_user )
		{
			$new = self::generate_password();
			
			Db::query('UPDATE users SET password = "'.self::calc_password($new).'" WHERE id = '.$is_user['id'].' LIMIT 1');
			
			$subject = 'Vaša nova lozinka za '._SITE_DOMAIN;
			$message = '
				<p style="color:#333">
					Zatražili ste promjenu lozinke na '._SITE_DOMAIN.'
				</p>
				<p>
					Vaša nova lozinka glasi :
					<br/>
					<br/>
					'.$new.'
					<br/>
				</p>
			';
			
			$m = send_html_mail('no-reply@'._SITE_DOMAIN, $is_user['email'], $subject, $message);
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function info($what)
	{
		if( array_key_exists($what,$_SESSION['user']) )
			return $_SESSION['user'][$what];
		else
			return false;
	}
	
	public static function get_info($type)
	{
		if( self::is_logged() )
			return Db::query_one('SELECT '.Db::clean($type).' FROM users WHERE id = '.(int)$_SESSION['user']['id'].' LIMIT 1');
		else
			return false;
	}
	
	public static function is_subscribed()
	{
		if( User::is_logged() && User::info('subscribed') === true )
			return true;
		else
			return false;
	}
	
	public static function is_admin()
	{
		if( User::is_logged() && User::info('user_type') > 0 )
		{
			return User::info('user_type');
		}
		else
		{
			return false;
		}
	}
	
	public static function is_logged()
	{
		if( is_array($_SESSION['user']) && isset($_SESSION['user']['email']) && isset($_SESSION['user']['id']) && self::calc_hash($_SESSION['user']['email'], $_SESSION['user']['id']) == $_SESSION['user']['login_hash'])
			return true;
		else
			return false;
	}
	
	public static function logout()
	{
		$_SESSION['user'] = null;
		$_SESSION['fb'] = null;
		setcookie('rm', '', time()-(60*60*24*14), '/');
	}
	
	public static function set_remember_me($email, $id)
	{
		$remember_me_hash = self::calc_remember_me_hash($email, $id);
		
		// setcookie('rm', $remember_me_hash, (time()+60*60*24*14), '/', _COOKIE_DOMAIN, false, true); // remember me cookie
		setcookie('rm', $remember_me_hash, time()+(60*60*24*14), '/');
		
		$q = Db::query('UPDATE users SET remember_me_hash = "'.$remember_me_hash.'" WHERE id = '.(int)$id.' LIMIT 1');
	}
	
	public static function calc_remember_me_hash($email, $id)
	{
		return md5($email._SITE_SALT.$id.'_r_m');
	}
	
	private static function calc_hash($email, $id)
	{
		return md5($email._SITE_SALT.$id);
	}
	
	public static function calc_password($password)
	{
		return md5(_SITE_SALT.$password._SITE_SALT);
	}
	
	public static function generate_password()
	{
		$letters = range('a','z');
		$new_pas = '';
		for($i=1; $i<=6; $i++)
		{
			$num = mt_rand(0,25);
			$oj = mt_rand(0,1);
			$letter = ( $i % 2 == 0 ) ? strtoupper($letters[$num]) : $letters[$num] ;
			$new_pas .= ( $oj == 1 ) ? $letter : $num ;
		}
		
		return $new_pas;
	}
	
	public static function toggle_noti($type)
	{
		$now = Db::query_one('SELECT notifications_'.$type.' FROM users WHERE id = '.User::info('id').' LIMIT 1');
		$new = ( $now == 'y' ) ? 'n' : 'y' ;
		
		Db::query('UPDATE users SET notifications_'.$type.' = "'.$new.'" WHERE id = '.User::info('id').' LIMIT 1');
		
		$_SESSION['user']['noti_'.$type] = ( $new == 'y' ) ? true : false ;
		
		return $new;
	}
}
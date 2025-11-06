<?php

class SAjaxFunctions extends SAjax
{
	// FRONT AJAX FUNKCIJE
	
	public function send_payment_data($payment_type)
	{
		if( get_conf('multi_language') == 1 )
			load_lang();
		
		$shop = new WebShop;
		$send = $shop->send_payment_data($payment_type);
		
		if( $send )
		{
			$this->out('script', '$("#all_holder").html("<div class=\"success\">Poštovani,<br/> uspješno ste poslali narudžbu. Kontaktirati ćemo Vas u najkraćem mogućem roku.</div>")');
			$this->out('script', '$(".success").fadeIn()');
		}
		else
		{
			$this->out('script', 'alert("'._NARUDZBA_SLANJE_GRESKA_MSG.'");');
		}
		
		return false;
	}
	
	public function logout_user()
	{
		$user = new User;
		$logged = $user->logout();
		$this->out('script','window.location.href="'._SITE_URL.'"');
		
		return false;
	}
	
	public function login_user($data)
	{
		$user = new User;
		$logged = $user->login($data);
		
		if( $logged == 'y' )
		{
			$this->out('script','window.location.href="'._SITE_URL.'"');
		}
		else if( $logged == 'n_a' )
		{
			$this->out('script','$(".error").fadeOut();');
			$this->out('script','$("#n_a_error").fadeIn();');
		}
		else
		{
			$this->out('script','$(".error").fadeOut();');
			$this->out('script','$("#s_error").fadeIn();');
			$this->out('script','$("#email, #lozinka").addClass("txt_error");');
		}
		
		return false;
	}
	
	public function registration($data, $user_id=false)
	{
		if( get_conf('multi_language') == 1 )
			load_lang();
		
		if(is_numeric($user_id))
		{
			$req = array('ime','prezime','address','city','country','post','phone');
		}
		else 
		{
			$req = array('ime','prezime','password1','password2','email','adresa','uvjeti','city','post');
		}
		
		$user = new User;
		if(is_numeric($user_id))
		{
			$status = $user->registration($data, $req, $user_id);
		}
		else 
		{
			$status = $user->registration($data, $req, $user_id);
		}
		
		$this->out('script', '$("input, textarea").removeClass("txt_error");');
		$this->out('script', '$(".txt_error_img").fadeOut(100);');
		
		if( $status['status'] == 'ok' )
		{
			if(is_numeric($user_id))
			{
				$this->out('html','<div class="r-success">Uspješno ste editirali svoj profil!</div>','f_registration_holder');
			}
			else 
			{
				$this->out('html','<div class="success">Uspješno ste registrirani! <br/>Na e-mail adresu koju ste upisali poslali smo aktivacijski link. Kliknite na link kako bi potvrdili registraciju!</div>','f_registration_holder');
			}
			$this->out('script','$(".success").fadeIn();');
			$this->out('script','$(".error_container").fadeOut();');
			$this->out('script','$(".error").fadeOut();');
		}
		else if( $status['status'] == 'er2' )
		{
			$this->out('html','<div class="error">Dogodila se greška prilikom registracije! Pokušajte ponovno! Ako se greška ponovi kontaktirajte nas na e-mail.</div>','f_registration_holder');
		}
		else if( $status['status'] == 'er1' )
		{
			$this->out('html','<div class="r-success">Dogodila se greška prilikom registracije! Aktivacijski e-mail nije mogao biti poslan! Kako bi aktivirali Vaš račun molimo kontaktirajte nas.</div>','f_registration_holder');
		}
		else
		{
			foreach($status['status'] as $k => $v)
			{
				$this->out('script','$("#'.$v.'").addClass("txt_error");');
				$this->out('script','$("#'.$v.':eq(0)").siblings().fadeIn(300);');
			}
			
			$this->out('html',_REG_ERR1,'error_container');
			
			if(!is_numeric($user_id))
			{
				if( in_array('email_exists',$status['status']) )
					$this->out('append','<br>'._REG_EMAIL_ERR,'error_container');
				if( in_array('passwords_dont_match',$status['status']) )
					$this->out('append','<br>'._REG_PASS_ERR1,'error_container');
			}
			
			$this->out('script','$("#error_container").fadeIn()');
		}
		
		return false;
	}
	

		
	public function del_from_cart($id)
	{
		$shop = new WebShop;
		$del = $shop->del_from_cart($id);
		
		if( $del )
		{
			$this->out('script','$("#num_items1").text("'.$shop->num_items.'")');
			$this->out('script','$("#price1, #price2").text("'.number_format($shop->price, 2, ',','.').' kn")');
			$this->out('script','$("#price3").text("'.number_format($shop->price-$shop->pdv, 2, ',','.').' kn")');
			$this->out('script','$("#pdv").text("'.number_format($shop->pdv, 2, ',','.').' kn")');
			$this->out('script','$("#item_'.$id.'").val("'.$shop->item_num[$id].'")');
			$this->out('script','$("#price_'.$id.'").text("'.number_format($shop->price_item[$id], 2, ',','.').'")');
			
			$this->out('script','$("#item_k_'.$id.'").fadeOut(100, function(){ $(this).remove(); });');
			
			if( $shop->num_items == 0 )
			{
				$this->out('script','$(".order-btn").remove();');
			}
		}
		else
		{
			$this->out('script','alert("Dogodila se greška!");');
		}
	}
	
	public function add_to_cart($id, $num = false)
	{
		$shop = new WebShop;
		$add = $shop->add_to_cart($id, $num);
		
		if( $add )
		{
			$shop->update_order_data();
			
			$this->out('script','$("#num_items1").text("'.$shop->num_items.'")');
			$this->out('script','$("#price1, #price2").text("'.number_format($shop->price, 2, ',','.').' kn")');
			$this->out('script','$("#item_'.$id.'").val("'.$shop->item_num[$id].'")');
			$this->out('script','$("#price_'.$id.'").text("'.number_format($shop->price_item[$id], 2, ',','.').'")');
			$this->out('script','$("#price3").text("'.number_format($shop->price-$shop->pdv, 2, ',','.').' kn")');
			$this->out('script','$("#pdv").text("'.number_format($shop->pdv, 2, ',','.').' kn")');
			
			$this->out('script','$(".dodaj").addClass("dodano").text("Dodano u košaricu");');
			$this->out('script','setTimeout(function(){$(".dodaj").removeClass("dodano").text("Dodaj u košaricu");},1600);');
			$this->out('script','$("#dodaj'.$id.'").slideUp().delay(1600).slideDown();');
			$this->out('script','$("#dodano_success'.$id.'").slideDown().delay(1600).slideUp();');
		}
		else 
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovno.");');
			
			// number_format($shop->price, 2, ',','.')
		}
		
		return false;
	}
	
	public function remove_from_cart($id, $num)
	{
		$shop = new WebShop;
		
		if($shop->item_num[$id] > 1){
		
			$add = $shop->remove_from_cart($id, $num);
			
			if( $add )
			{
				$shop->update_order_data();
				
				$this->out('script','$("#num_items1").text("'.$shop->num_items.'")');
				$this->out('script','$("#price1, #price2").text("'.number_format($shop->price, 2, ',','.').' kn")');
				$this->out('script','$("#price3").text("'.number_format($shop->price-$shop->pdv, 2, ',','.').' kn")');
				$this->out('script','$("#pdv").text("'.number_format($shop->pdv, 2, ',','.').' kn")');
				$this->out('script','$("#item_'.$id.'").val("'.$shop->item_num[$id].'")');
				$this->out('script','$("#price_'.$id.'").text("'.number_format($shop->price_item[$id], 2, ',','.').'")');
				
				if( $shop->num_items == 0 )
				{
					$this->out('script','$(".order-btn").hide();');
				}
			}
			else
			{
				$this->out('script','alert("Dogodila se greška! Pokušajte ponovno.");');
			}
		}
		
		return false;
	}
	
	public function update_cart_items($id, $num)
	{
		if( get_conf('multi_language') == 1 )
			load_lang();
		
		$shop = new WebShop;
		$shop->update_order_items($id, $num);

		$shop->update_order_data();
			
		$this->out('script','$("#num_items1").text("'.$shop->num_items.'")');
		$this->out('script','$("#price1, #price2").text("'.number_format($shop->price, 2, ',','.').' kn")');
		$this->out('script','$("#price3").text("'.number_format($shop->price-$shop->pdv, 2, ',','.').' kn")');
		$this->out('script','$("#pdv").text("'.number_format($shop->pdv, 2, ',','.').' kn")');
		$this->out('script','$("#item_'.$id.'").val("'.$shop->item_num[$id].'")');
		$this->out('script','$("#price_'.$id.'").text("'.number_format($shop->price_item[$id], 2, ',','.').'")');
		
		if( $shop->num_items == 0 )
		{
			$this->out('script','$(".order-btn").hide();');
		}
		
		return false;
	}
	
	public function finish_order($data, $user_id = 0)
	{

		$this->out('script', '$("input, textarea").removeClass("txt_error");');
		$this->out('script', '$(".txt_error_img").fadeOut(100);');
		

		$req = array('ime','prezime','email','address','city','post');
			
		
		foreach($req as $k => $v)
		{
			if( ! array_key_exists($v, $data) || $data[$v] == "")
			{
				if($v == 'uvjeti')
				{
					$err[] = $v.'-box';
				}else{
					$err[] = $v;
				}
			}
		}
	
		if( !valid_email($data['email']) )
		{
			$err[] = 'email';
			$err[] = 'email_not_valid';
			$err[] = 'show_txt';
		}
		
		if( count($err) > 0 )
		{
			$status = array('status' => array_unique($err));
			
			foreach($status['status'] as $k => $v)
			{
				$this->out('script','$("#'.$v.'").addClass("txt_error");');
			}
			
			$this->out('html','Označena polja moraju biti popunjena!','error_container');
			
			
			if( in_array('email_not_valid',$status['status']) )
				$this->out('append','<br>E-mail nije ispravno upisan.','error_container');
			if( in_array('passwords_dont_match',$status['status']) )
				$this->out('append','<br>Upisane lozinke se ne podudaraju.','error_container');
			if( in_array('password_short',$status['status']) )
				$this->out('append','<br>Upisana lozinka je prekratka. Lozinka mora sadržavati barem 4 znaka.','error_container');
				
			$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			$this->out('script','$("#error_container").addClass("error")');
			
		}
		else
		{
		
			$user = new User;
			$status = $user->user_update($data, $user_id);
					
			if( $status )
			{
				$shop = new WebShop;
	
				$send = $shop->send_payment_data();
			
				if( $send )
				{
					$this->out('script', '$("#spec_holder").fadeOut()');
					$this->out('script', '$("#all_holder").html("<div class=\"success\"><p>Narudžba je uspješno zaprimljena! Na Vašu e-mail adresu primiti ćete informacije o narudži i upute kako uplatiti. Hvala na kupovini.</p></div>")');
					$this->out('script', '$(".success").fadeIn()');
				}
				else if($send == false)
				{
					$this->out('script', 'alert("Došlo je do greške pri slanju narudžbe! Molimo vas pokušajte kasnije. Ako se ovaj problem ponovi molimo Vas kontaktirajte nas sa problemom. ");');
				}
					
				
				
				
				

			}
			else
			{
				$this->out('html','<div class="error">Došlo je do greške pri ažuriranju podataka! Molimo vas pokušajte kasnije. <br/> Ako se ovaj problem ponovi molimo Vas kontaktirajte nas sa problemom.</div>','error_container');			
				$this->out('script','$("#error_container").fadeIn();');
				$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
				 
			}
		}
		
		return false;
	}	
	public function finish_order_register($data, $payment_type)
	{
		if( get_conf('multi_language') == 1 )
			load_lang();
			
		$req = array('ime','prezime','email','address','city','country','post','uvjeti');
		
		foreach($req as $k => $v)
		{
			if( ! array_key_exists($v, $data) || $data[$v] == "")
			{
				if($v == 'uvjeti')
				{
					$err[] = $v.'-box';
				}else{
					$err[] = $v;
				}
			}
		}
	
		if( !valid_email($data['email']) )
		{
			$err[] = 'email';
			$err[] = 'email_not_valid';
			$err[] = 'show_txt';
		}
		else
		{
			if( $data['tmp'] != 'tmp'){
				$is_email = Db::query_one('SELECT id FROM users WHERE email = "'.Db::clean($data['email']).'" AND tmp != "tmp" LIMIT 1');
				if( $is_email )
				{
					$err[] = 'email';
					$err[] = 'email_exists';
					$err[] = 'show_txt';
				}
			}
		}
		
		// if( $data['tmp'] != 'tmp'){
		// 	if($data['password1'] != $data['password2'] || $data['password1'] == '' || $data['password2'] == '' )
		// 	{
		// 		$err[] = 'password1';
		// 		$err[] = 'password2';
		// 		$err[] = 'passwords_dont_match';
		// 		$err[] = 'show_txt';
		// 	}
			
		// 	if( strlen($data['password1']) < 4 )
		// 	{
		// 		$err[] = 'password1';
		// 		$err[] = 'password_short';
		// 		$err[] = 'show_txt';
		// 	}
			
		// 	$activation = 'activation_hash = "1",';
		// }else{
		// 	$activation = '';
		// }
				
		if( count($err) > 0 )
		{
			$status = array('status' => array_unique($err));
			
			foreach($status['status'] as $k => $v)
			{
				$this->out('script','$("#'.$v.'").addClass("txt_error");');
			}
			
			$this->out('html',_REG_ERR1,'error_container');
			
			
			// if( in_array('email_exists',$status['status']) )
			// 	$this->out('append','<br>'._REG_EMAIL_ERR,'error_container');

			if( in_array('email_not_valid',$status['status']) )
				$this->out('append','<br>'._REG_EMAIL_ERR2,'error_container');

			// if( in_array('passwords_dont_match',$status['status']) )
			// 	$this->out('append','<br>'._REG_PASS_ERR1,'error_container');

			// if( in_array('password_short',$status['status']) )
			// 	$this->out('append','<br>'._REG_PASS_ERR2,'error_container');
				
			$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			$this->out('script','$("#error_container").addClass("error")');
			
		}
		else
		{		
			$sql = '
				INSERT INTO users SET 
					ime = "'.$data['ime'].'", 
					prezime = "'.$data['prezime'].'",
					email = "'.$data['email'].'", 
					address = "'.$data['address'].'", 
					phone = "'.$data['phone'].'", 
					city = "'.$data['city'].'",
					post = "'.$data['post'].'",
					country = "'.$data['country'].'",
					napomena = "'.$data['napomena'].'",
					tmp = "'.$data['tmp'].'",
					created = "'.date('Y-m-d H:i:s').'"
				';

			/*$sql = '
				INSERT INTO users SET 
					ime = "'.$data['ime'].'", 
					prezime = "'.$data['prezime'].'",
					email = "'.$data['email'].'", 
					password = "'.$data['password1'].'", 
					address = "'.$data['address'].'", 
					phone = "'.$data['phone'].'", 
					city = "'.$data['city'].'",
					post = "'.$data['post'].'",
					country = "'.$data['country'].'",
					primatelj_d = "'.$data['primatelj_d'].'",
					address_d = "'.$data['address_d'].'",
					city_d = "'.$data['city_d'].'",
					country_d = "'.$data['country_d'].'",
					post_d = "'.$data['post_d'].'",
					phone_d = "'.$data['phone_d'].'",
					naziv_tvrtke = "'.$data['naziv_tvrtke'].'",
					adresa_tvrtke = "'.$data['adresa_tvrtke'].'",
					oib_tvrtke = "'.$data['oib_tvrtke'].'",
					tmp = "'.$data['tmp'].'",
					'.$activation.'
					created = "'.date('Y-m-d H:i:s').'"
				';*/
				
			$q = Db::query($sql);
			
			$user_id = Db::insert_id();
			
			$_SESSION['user']['id'] = $user_id;
			
			$shop = new WebShop;
			$send = $shop->send_payment_data($payment_type, $user_id);
		
			if( $send )
			{
				$this->out('script', '$("#all_holder").html("<div class=\"success\">'._NARUDZA_USPJESNO_POSLANA_MSG.'</div>")');
				$this->out('script', '$(".success").fadeIn()');
				$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			}
			else
			{
				$this->out('script', 'alert("'._NARUDZBA_SLANJE_GRESKA_MSG333.'");');
				$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			}
				
			
			
			
			/*$shop = new WebShop;
			$send = $shop->send_payment_data($payment_type, $user_id);
		
			if( $send )
			{
				$this->out('script', '$("#all_holder").html("<div class=\"success\">'._NARUDZA_USPJESNO_POSLANA_MSG.'</div>")');
				$this->out('script', '$(".success").fadeIn()');
				$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			}
			else
			{
				$this->out('script', 'alert("'._NARUDZBA_SLANJE_GRESKA_MSG.'");');
				$this->out('script','$("html, body").animate({scrollTop: $("#error_container").offset().top - 25}, 100);');
			}*/
		}
					
		return false;
	}
	
	// ADMIN AJAX FUNKCIJE
	public function on_page($broj_redova, $page)
	{
		$_SESSION['on-page'] = $broj_redova;
		$this->out('script', 'window.location.href="'.$page.'";');
	}
	
	public function del_user($id)
	{
		$ao = new Admin_Operations('users', $id);
		
		if( ! $ao )
			return false;
		
		$d = $ao->delete_user();
		
		if( $d )
		{
			$this->out('script','$("#row_'.$id.'").fadeOut();');
			$this->out('script','$("#row_line_'.$id.'").fadeOut();');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}
		
		return false;
	}
		
	public function del_nl_photo($id, $rb, $photo)
	{
		if(is_file(_SITE_ROOT.'upload_data/site_photos/'.$photo))
		{
			@unlink(_SITE_ROOT.'upload_data/site_photos/'.$photo);
		}
		
		$sql = 'UPDATE newsletter_content SET photo = "" WHERE id = '.(int)$id;
		Db::query($sql);

		$input_polje = '<input type="file" name="file'.$rb.'" id="file'.$rb.'" type="text" class="browse" />';
		
		$this->out('html',$input_polje,'sl'.$rb);
		
		return false;
	}
	
	public function del_entry($table, $id)
	{
		$ao = new Admin_Operations($table, $id);
		
		if( ! $ao )
			return false;
		
		$d = $ao->delete_entry();
		
		if( $d )
		{
			$this->out('script','$("#row_'.$id.'").fadeOut("fast", function(){$(this).remove();});');
			$this->out('script','$("#row_line_'.$id.'").fadeOut("fast", function(){$(this).remove();});');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}
		
		return false;
	}
	
	public function del_img($id)
	{
		$ao = new Admin_Operations('site_photos', $id);
		
		if( ! $ao )
			return false;
		
		$d = $ao->delete_image();
		
		if( $d )
		{
			$this->out('script','$("#img_holder_'.$id.'").fadeOut("fast", function(){$(this).remove();});');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}
		
		return false;
	}
		
	public function del_file($id, $lng)
	{
		$ao = new Admin_Operations('site_files_'.$lng, $id);
		
		if( ! $ao )
			return false;
		
		$d = $ao->delete_file();
		
		if( $d )
		{
			$this->out('script','$("#file_holder_'.$lng.'_'.$id.'").fadeOut();');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}
		
		return false;
	}
	
	public function del_avatar_img($table, $id)
	{
		$photos = Db::query('SELECT photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id);
		
		foreach($photos as $p)
		{
			if(is_file(_SITE_ROOT.'upload_data/site_photos/th_'.$p['photo_name']))
				@unlink(_SITE_ROOT.'upload_data/site_photos/th_'.$p['photo_name']);
			if(is_file(_SITE_ROOT.'upload_data/site_photos/'.$p['photo_name']))
				@unlink(_SITE_ROOT.'upload_data/site_photos/'.$p['photo_name']);
		}
		
		Db::query('DELETE FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id);
	}
	
	public function del_newsletter_img($field, $id)
	{
		$photo = Db::query_one('SELECT '.$field.' FROM newsletter WHERE id = '.$id);
		
		if(is_file(_SITE_ROOT.'upload_data/newsletter_photos/th_'.$photo))
			@unlink(_SITE_ROOT.'upload_data/newsletter_photos/th_'.$photo);
		if(is_file(_SITE_ROOT.'upload_data/newsletter_photos/'.$photo))
			@unlink(_SITE_ROOT.'upload_data/newsletter_photos/'.$photo);
		
		Db::query('UPDATE newsletter SET '.$field.' = "" WHERE id = '.$id);
	}
	
	public function save_order($data, $table)
	{
		$broj = count($data);	
		
		$i = 0;
		foreach($data as $k => $v)
		{

			//if($table == 'categories'){
			if(substr($table,0,10) == 'categories'){
				$id = substr($v, 7);

			}elseif($table == 'site_photos'){
				$id = substr($v, 11);
			}elseif($table == 'city'){
				$id = substr($v, 7);
			}elseif(substr($table,0,11) == 'site_files_'){
				$id = substr($v, 15);
			}else{
				$id = substr($v, 4);
			}
			$order[$i] = Db::query_one('SELECT orderby FROM '.$table.' WHERE id = '.(int)$id);	
			$i++;
		}		
	
		if(substr($table,0,10) == 'categories' || substr($table,0,4) == 'city' || $table == 'site_photos' || substr($table,0,11) == 'site_files_'){
			sort($order);
		}else{
			rsort($order);
		}
					
		$i = 0;
		foreach($data as $k => $v)
		{
			if(substr($table,0,10) == 'categories'){
				$id = substr($v, 7);
			}elseif(substr($table,0,4) == 'city'){
				$id = substr($v, 7);
			}elseif($table == 'site_photos'){
				$id = substr($v, 11);
			}elseif(substr($table,0,11) == 'site_files_'){
				$id = substr($v, 15);
			}else{
				$id = substr($v, 4);
			}
			
			Db::query('UPDATE '.$table.' SET orderby = '.(int)$order[$i].' WHERE id = '.(int)$id);
			var_dump('UPDATE '.$table.' SET orderby = '.(int)$order[$i].' WHERE id = '.(int)$id);
			$i++;
		}	
	
		
		// if(substr($table,0,10) == 'categories'){
		// 	$this->out('script', 'location.reload();');
		// }
	}
	
	public function open_upload($class)
	{				
		$this->out('script', '$(".'.$class.'").show();');
		$this->out('script', '$(".'.$class.'").find(".save-uploads").hide();');
	}
	
	public function close_upload($class)
	{				
		$this->out('script', '$(".'.$class.'").hide();');
		if($class == 'img-selection')
		{
			$this->out('script', '$(".'.$class.'").remove();');
		}
	}
	
	public function open_img_selection($table, $id)
	{
		$slike = Db::query('SELECT id, photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id.' ORDER BY orderby ASC');
		
		if($slike)
		{
			$html = '<div class="upload-box img-selection">
						<div class="upload-container">
							<div class="img-selection-container" id="img-selection-container" style="overflow-y:auto;">';
				
			foreach($slike as $red)
			{
				$html .= '<div class="img-select" onclick="$(\'.img-select\').removeAttr(\'style\'); $(\'.img-select\').children(\'input\').removeAttr(\'checked\'); $(this).css(\'background\',\'#7AC143\'); $(this).children(\'input\').attr(\'checked\', \'checked\'); $(\'.set-image\').removeAttr(\'style\');">
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/th_'.$red['photo_name'].'&w=275&h=207&zc=1" alt="'.$red['photo_name'].'" />
							<input type="radio" name="featured" value="'.$red['id'].'" style="display:none;"/>
						</div>';
			}
			
			$html .= '		</div>
							<div class="set-image" style="display:none;">
								<a href="javascript:;" onclick="sjx(\'set_featured_image\', $(\'input:radio[name=featured]:checked\').val()); return false;">Postavi istaknutu sliku</a>
							</div>
						</div>
						<a href="javascript:;" onclick="sjx(\'close_upload\',\'img-selection\'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
					</div>';
			
			$this->out('append',$html,$table);
		}else{
			$this->out('script','alert("Molimo vas unesite prvo slike kako bi od njih mogli odabrati istaknutu sliku.");');
		}
				
	}


	public function open_img_selection2($table, $id)
	{
		$slike2 = Db::query('SELECT id, photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id.' ORDER BY orderby ASC');
		
		if($slike2)
		{
			$html = '<div class="upload-box img-selection">
						<div class="upload-container">
							<div class="img-selection-container" id="img-selection-container" style="overflow-y:auto;">';
				
			foreach($slike2 as $red)
			{
				$html .= '<div class="img-select" onclick="$(\'.img-select\').removeAttr(\'style\'); $(\'.img-select\').children(\'input\').removeAttr(\'checked\'); $(this).css(\'background\',\'#7AC143\'); $(this).children(\'input\').attr(\'checked\', \'checked\'); $(\'.set-image\').removeAttr(\'style\');">
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/th_'.$red['photo_name'].'&w=275&h=207&zc=1" alt="'.$red['photo_name'].'" />
							<input type="radio" name="featured" value="'.$red['id'].'" style="display:none;"/>
						</div>';
			}
			
			$html .= '		</div>
							<div class="set-image" style="display:none;">
								<a href="javascript:;" onclick="sjx(\'set_featured_image2\', $(\'input:radio[name=featured]:checked\').val()); return false;">Postavi istaknutu sliku</a>
							</div>
						</div>
						<a href="javascript:;" onclick="sjx(\'close_upload\',\'img-selection\'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
					</div>';
			
			$this->out('append',$html,$table);
		}else{
			$this->out('script','alert("Molimo vas unesite prvo slike kako bi od njih mogli odabrati istaknutu sliku.");');
		}
				
	}

		public function open_img_selection3($table, $id)
	{
		$slike3 = Db::query('SELECT id, photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id.' ORDER BY orderby ASC');
		
		if($slike3)
		{
			$html = '<div class="upload-box img-selection">
						<div class="upload-container">
							<div class="img-selection-container" id="img-selection-container" style="overflow-y:auto;">';
				
			foreach($slike3 as $red)
			{
				$html .= '<div class="img-select" onclick="$(\'.img-select\').removeAttr(\'style\'); $(\'.img-select\').children(\'input\').removeAttr(\'checked\'); $(this).css(\'background\',\'#7AC143\'); $(this).children(\'input\').attr(\'checked\', \'checked\'); $(\'.set-image\').removeAttr(\'style\');">
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/th_'.$red['photo_name'].'&w=275&h=207&zc=1" alt="'.$red['photo_name'].'" />
							<input type="radio" name="featured" value="'.$red['id'].'" style="display:none;"/>
						</div>';
			}
			
			$html .= '		</div>
							<div class="set-image" style="display:none;">
								<a href="javascript:;" onclick="sjx(\'set_featured_image3\', $(\'input:radio[name=featured]:checked\').val()); return false;">Postavi istaknutu sliku</a>
							</div>
						</div>
						<a href="javascript:;" onclick="sjx(\'close_upload\',\'img-selection\'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
					</div>';
			
			$this->out('append',$html,$table);
		}else{
			$this->out('script','alert("Molimo vas unesite prvo slike kako bi od njih mogli odabrati istaknutu sliku.");');
		}
				
	}

		public function open_img_selection5($table, $id)
	{
		$slike5 = Db::query('SELECT id, photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id.' ORDER BY orderby ASC');
		
		if($slike5)
		{
			$html = '<div class="upload-box img-selection">
						<div class="upload-container">
							<div class="img-selection-container" id="img-selection-container" style="overflow-y:auto;">';
				
			foreach($slike5 as $red)
			{
				$html .= '<div class="img-select" onclick="$(\'.img-select\').removeAttr(\'style\'); $(\'.img-select\').children(\'input\').removeAttr(\'checked\'); $(this).css(\'background\',\'#7AC143\'); $(this).children(\'input\').attr(\'checked\', \'checked\'); $(\'.set-image\').removeAttr(\'style\');">
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/th_'.$red['photo_name'].'&w=275&h=207&zc=1" alt="'.$red['photo_name'].'" />
							<input type="radio" name="featured" value="'.$red['id'].'" style="display:none;"/>
						</div>';
			}
			
			$html .= '		</div>
							<div class="set-image" style="display:none;">
								<a href="javascript:;" onclick="sjx(\'set_featured_image5\', $(\'input:radio[name=featured]:checked\').val()); return false;">Postavi istaknutu sliku</a>
							</div>
						</div>
						<a href="javascript:;" onclick="sjx(\'close_upload\',\'img-selection\'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
					</div>';
			
			$this->out('append',$html,$table);
		}else{
			$this->out('script','alert("Molimo vas unesite prvo slike kako bi od njih mogli odabrati istaknutu sliku.");');
		}
				
	}


	public function open_img_selection4($table, $id)
	{
		$slike4 = Db::query('SELECT id, photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$id.' ORDER BY orderby ASC');
		
		if($slike4)
		{
			$html = '<div class="upload-box img-selection">
						<div class="upload-container">
							<div class="img-selection-container" id="img-selection-container" style="overflow-y:auto;">';
				
			foreach($slike4 as $red)
			{
				$html .= '<div class="img-select" onclick="$(\'.img-select\').removeAttr(\'style\'); $(\'.img-select\').children(\'input\').removeAttr(\'checked\'); $(this).css(\'background\',\'#7AC143\'); $(this).children(\'input\').attr(\'checked\', \'checked\'); $(\'.set-image\').removeAttr(\'style\');">
							<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/site_photos/th_'.$red['photo_name'].'&w=275&h=207&zc=1" alt="'.$red['photo_name'].'" />
							<input type="radio" name="featured" value="'.$red['id'].'" style="display:none;"/>
						</div>';
			}
			
			$html .= '		</div>
							<div class="set-image" style="display:none;">
								<a href="javascript:;" onclick="sjx(\'set_featured_image4\', $(\'input:radio[name=featured]:checked\').val()); return false;">Postavi istaknutu sliku</a>
							</div>
						</div>
						<a href="javascript:;" onclick="sjx(\'close_upload\',\'img-selection\'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
					</div>';
			
			$this->out('append',$html,$table);
		}else{
			$this->out('script','alert("Molimo vas unesite prvo slike kako bi od njih mogli odabrati istaknutu sliku.");');
		}
				
	}


	
	public function set_featured_image($id)
	{
		$featured_img = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$id);
		
		$html = '<a href="javascript:;" onclick="$(\'#image\').val(\'0\');$(\'#featured_holder\').empty();" class="del_img">
					<img src="images/icon-delete-round.png" alt="Briši" />
				</a>
				<img class="featured_img" src="../upload_data/site_photos/th_'.$featured_img.'" alt="'.$featured_img.'" />';
		
		$this->out('html',$html,'featured_holder');
		$this->out('script', '$(".img-selection").remove();');
		$this->out('script', '$("#image").val("'.$id.'");');
	}
	public function set_featured_image2($id)
	{
		$featured_img2 = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$id);
		
		$html = '<a href="javascript:;" onclick="$(\'#image2\').val(\'0\');$(\'#featured_holder2\').empty();" class="del_img">
					<img src="images/icon-delete-round.png" alt="Briši" />
				</a>
				<img class="featured_img2" src="../upload_data/site_photos/th_'.$featured_img2.'" alt="'.$featured_img2.'" />';
		
		$this->out('html',$html,'featured_holder2');
		$this->out('script', '$(".img-selection").remove();');
		$this->out('script', '$("#image2").val("'.$id.'");');
	}
		
			public function set_featured_image3($id)
	{
		$featured_img3 = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$id);
		
		$html = '<a href="javascript:;" onclick="$(\'#image3\').val(\'0\');$(\'#featured_holder3\').empty();" class="del_img">
					<img src="images/icon-delete-round.png" alt="Briši" />
				</a>
				<img class="featured_img3" src="../upload_data/site_photos/th_'.$featured_img3.'" alt="'.$featured_img3.'" />';
		
		$this->out('html',$html,'featured_holder3');
		$this->out('script', '$(".img-selection").remove();');
		$this->out('script', '$("#image3").val("'.$id.'");');
	}
	public function set_featured_image4($id)
	{
		$featured_img4 = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$id);
		
		$html = '<a href="javascript:;" onclick="$(\'#image4\').val(\'0\');$(\'#featured_holder4\').empty();" class="del_img">
					<img src="images/icon-delete-round.png" alt="Briši" />
				</a>
				<img class="featured_img4" src="../upload_data/site_photos/th_'.$featured_img4.'" alt="'.$featured_img4.'" />';
		
		$this->out('html',$html,'featured_holder4');
		$this->out('script', '$(".img-selection").remove();');
		$this->out('script', '$("#image4").val("'.$id.'");');
	}
	public function set_featured_image5($id)
	{
		$featured_img5 = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$id);
		
		$html = '<a href="javascript:;" onclick="$(\'#image5\').val(\'0\');$(\'#featured_holder5\').empty();" class="del_img">
					<img src="images/icon-delete-round.png" alt="Briši" />
				</a>
				<img class="featured_img5" src="../upload_data/site_photos/th_'.$featured_img5.'" alt="'.$featured_img5.'" />';
		
		$this->out('html',$html,'featured_holder5');
		$this->out('script', '$(".img-selection").remove();');
		$this->out('script', '$("#image5").val("'.$id.'");');
	}
	public function save_uploads($table, $id, $sizes)
	{
		$crud = new Admin_Crud;
		$crud->table = $table;
		$crud->id = $id;
		if($table == 'newsletter')
		{
			$crud->img_num = 0;
			$crud->file_num = 0;
		}else{
			$crud->img_num = null;
			$crud->file_num = null;
		}
		$crud->img_sizes = explode("-", $sizes);
		
		$crud->handle_uploads();
				
		$this->out('script', 'location.reload();');
		
	}
	
	public function preview_uploads($type)
	{
		if(substr($type,0,10) == 'newsletter')
		{
			$i = substr($type,11);
			
			$html = '<a href="javascript:;" onclick="$(\'#image'.$i.'\').val(\'\');$(\'#newsletter_'.$i.'_holder\').empty();" class="del_img">
						<img src="images/icon-delete-round.png" alt="Briši" />
					</a>
					<img class="featured_img" src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/tmp/'.clean_uri($_SESSION[$type][1]['name']).'&w=250&h=188&zc=1" alt="'.clean_uri($_SESSION[$type][1]['name']).'"/>';
			
			$this->out('html',$html,$type.'_holder');
			$this->out('script', '$(".upload-box").hide();');
			$this->out('script', '$("#image'.$i.'").val("'.clean_uri($_SESSION[$type][1]['name']).'");');
		
			
		}else{
			$html = '<div class="no_entry" style="font-size:14px;">Preneseni dokumenti nisu u potpunosti pohranjeni. Ako ih želite trajno pohraniti molimo vas spremite stranicu na gumb "Spremi".</div>';
			$cnt = count($_SESSION[$type]) - 1;
			
			if($type != 'images') 
			{
				$next_id = Db::query_one('SELECT max(AUTO_INCREMENT) FROM information_schema.TABLES WHERE TABLE_SCHEMA = "'.get_conf('database_name').'" AND TABLE_NAME = "site_files_'.substr($type, 5).'"');
			}
			
			for($i = 1;$i <= $cnt;$i++)
			{
				$html .= '<div class="unos-'.($type == 'images' ? 'slika':'dokument').' no-sort">';
					
					if($type == 'images'){
						$html .= '<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.'upload_data/tmp/'.clean_uri($_SESSION[$type][$i]['name']).'&w=250&h=188&zc=1" alt="'.$_SESSION[$type][$i]['name'].'" style="cursor:auto;"/>';
					}else{
						$html .= '<a href="../upload_data/tmp/'.clean_uri($_SESSION[$type][$i]['name']).'" target="_blank"><img src="images/doc.png" alt="" /></a>
								  <input type="text" name="file_title_'.substr($type, 5).'_'.$next_id.'"  placeholder="naslov dokumenta..."/>';
					}
						
				$html .= '<a href="javascript:;" onclick="$(this).parent().fadeOut();sjx(\'del_preview_doc\',\''.$type.'\', '.$i.');return false;" class="del_img">
							<img src="images/icon-delete-round.png" alt="Briši" />
						</a>
					 </div>';
					 
				if($type != 'images') 
				{
					$next_id++;
				}
			}			
			
			$this->out('html',$html,$type.'-holder');
			$this->out('script', '$(".upload-box").hide();');
		}
	}
	
	public function del_preview_doc($type, $i)
	{
		$_SESSION[$type][$i] = null;
	}
	
	public function del_category($id, $table='')
	{
		$mc = new Admin_ManageCategories($id, $table);
		
		if( ! $mc )
		{
			$this->out('script', 'alert("Dogodila se greška. pokušajte ponovno! er.1");');
			return false;
		}
		
		$deleted_cats = $mc->delete_cat_subcats_items();
		
		if( is_array($deleted_cats) )
		{
			foreach($deleted_cats as $k => $v)
			{
				$this->out('script', '$("#cat_id_'.$v.'").fadeOut();');
			}
		}
		
		return false;
	}
	
	public function send_newsletter($type, $id)
	{
		include(_SITE_ROOT.'include/newsletter.php');
		
		$data = Db::query_row('SELECT * FROM newsletter WHERE id = '.$id);
		
		$headers= "MIME-Version: 1.0\n";
		$headers.= "Content-type: text/html; charset=utf-8\n";
		$headers.= "From: "._SITE_TITLE." <"._FIRMA_EMAIL.">\n";
		$headers.= "X-Sender: <"._SITE_DOMAIN.">\n";
		$headers.= "X-Mailer: Updater <"._SITE_URL.">\n"; 
		$headers.= "Return-Path: <"._FIRMA_EMAIL.">\n";
		
		$content = construct_newsletter($data);
				
		$err = false;
		if($type == 'real')
		{
			$email = Db::query('SELECT email FROM newsletter_email');
			foreach($email as $k => $v)
			{
				$send = mail($v['email'], _SITE_TITLE.' Newsletter : '.$data['title_hr'], $content, $headers);
				
				if(!$send)
				{
					$err = true;
				}
			}
			
		}else{
			$send = mail(get_conf('email'), _SITE_TITLE.' Newsletter : '.$data['title_hr'], $content, $headers);
			if(!$send)
			{
				$err = true;
			}
		}
		
		if($err)
		{
			$this->out('script', '$("#nl-error").show();');
		}else{
			$this->out('script', '$("#nl-success").show();');
		}
	}	
	
	// custom funkcije
		
	public function generate_weather($city, $id)
	{
		$content = '';
		$grad = $city;
		$vrijeme = file_get_contents('http://www.virtus-projekti.com/yahoo_weather/'.$grad.'.txt');
		if( $vrijeme )
		{
			$weather = json_decode(file_get_contents('http://www.virtus-projekti.com/yahoo_weather/'.$grad.'.txt'), true);
		
			$danas = date('N');
			$sutra = date('N', mktime( 0,0,0,date('m'),date('d')+1,date('Y') ) );
		}
		if( $weather ) 
		{
			$content .= '<img src="http://www.virtus-projekti.com/yahoo_weather/img/d'.$weather['now_code'].'.png" class="vrijeme_ikona" />
				<div class="vrijeme_info">
					<span class="temp">'.$weather['now_temp'].' °C</span>';
					//$content .= $dan[$sutra].', min:'.$weather['tomorow_low'].'°C max:'.$weather['tomorow_low'].'°C';
				$content .= '</div>';
		}
		
		$this->out('html',$content,$id);
	}
	
	public function updateFrontPage($id, $value, $column, $table)
	{
		Db::query('UPDATE '.$table.' SET '.$column.' = "'.$value.'" WHERE id = '.$id);
		
		if($table == 'items')
		{
			if($value == 'da'){
				$product = Db::query_one('SELECT categories_id FROM items WHERE id = '.$id);
				Db::query('UPDATE products SET '.$column.' = "'.$value.'" WHERE id = '.$product);
			}else{
				$cat_id = Db::query_one('SELECT categories_id FROM items WHERE id = '.$id);
				$rest = Db::query('SELECT id FROM items WHERE categories_id = '.$cat_id.' AND '.$column.' = "da"');
				if(!$rest)
				{
					Db::query('UPDATE products SET '.$column.' = "ne" WHERE id = '.$cat_id);
				}
			}
		}
	}
	
	public function checkCode($id, $value)
	{
		$check = Db::query_one('SELECT id FROM items WHERE code = "'.$value.'" AND id != '.$id.' ORDER BY orderby DESC LIMIT 1');
		
		if($check)
		{
			$html = '<span class="red">Šifra koju ste unijeli već postoji u bazi!!!</span>';
			$this->out('html',$html,'warning-holder');
			$this->out('script', '$("#code").css("border","1px solid #ff2222");');
		}else{
			$this->out('script', '$("#warning-holder").empty();');
			$this->out('script', '$("#code").removeAttr("style");');
		}
	}

	public function loadSlider($container)
	{
		if( get_conf('multi_language') == 1 )
			load_lang();
		
		switch($container)
		{
			case 'tab1_content':
				$type = 'bestseller';
				$products = Db::query('SELECT title_'.$_SESSION['lng'].', id FROM products WHERE '.$type.' = "da" AND status = "da" ORDER BY orderby DESC LIMIT 10');
				break;
			case 'tab2_content':
				$type = 'hit';
				$products = Db::query('SELECT title_'.$_SESSION['lng'].', id FROM products WHERE '.$type.' = "da" AND status = "da" ORDER BY orderby DESC LIMIT 10');
				break;
			case 'tab3_content':
				$type = 'ww_bestseller';
				$products = Db::query('SELECT title_'.$_SESSION['lng'].', id FROM products WHERE '.$type.' = "da" AND status = "da" ORDER BY orderby DESC LIMIT 10');
				break;
		}
		
		$html = '<ul class="bxslider slider">';
		
		foreach($products as $red)
		{
			$subproducts = Db::query('SELECT id,size,price,action_price FROM items WHERE categories_id = '.$red['id'].' AND '.$type.' = "da" AND status = "da" ORDER BY orderby DESC');
			if($subproducts)
			{
				foreach($subproducts as $sp)
				{
					$slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$sp['id'].' ORDER BY orderby ASC LIMIT 1');
					$slika = ($slika)? 'upload_data/site_photos/'.$slika:'images/default.png';
										
					$html .= '<li>
								<a href="'.$_SESSION['lng'].'/'._URL_ARTIKL.'/'.categories_uri($red['id']).'/'.clean_uri($red['title_'.$_SESSION['lng']]).'-'.$sp['size'].'-'.$red['id'].'" class="box4">
									<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.$slika.'&w=275&h=247&zc=2" alt="" />
									<div class="box-title">
										<span class="title">'.$red['title_'.$_SESSION['lng']].' '.$sp['size'].'ml</span>';
										
										if($sp['action_price'] != '' && $sp['action_price'] > 0){
										
											$html .= '<div class="price">
														<span class="old">
															'.number_format($sp['price'], 2, ',', '.').' kn
														</span>
														<span>
															'.number_format($sp['action_price'], 2, ',', '.').' kn
														</span>
													</div>';
						
										}else{
							
											$html .= '<div class="price">
														<span>
															'.number_format($sp['price'], 2, ',', '.').' kn
														</span>
													</div>';
										}
										
										$html .= '<span class="details">
											'._POGLEDAJ_DETALJE.'
										</span>
									</div>
								</a>
							  </li>';
				}				
			}else{
				$subproduct = Db::query_row('SELECT id,size,price,action_price FROM items WHERE categories_id = '.$red['id'].' AND status = "da" ORDER BY size ASC LIMIT 1');
				$slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "items" AND table_id = '.$subproduct['id'].' ORDER BY orderby ASC LIMIT 1');
				$slika = ($slika)? 'upload_data/site_photos/'.$slika:'images/default.png';
				
				$html .= '<li>
							<a href="'.$_SESSION['lng'].'/'._URL_ARTIKL.'/'.categories_uri($red['id']).'/'.clean_uri($red['title_'.$_SESSION['lng']]).'-'.$subproduct['size'].'-'.$red['id'].'" class="box4">
								<img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.$slika.'&w=275&h=247&zc=2" alt="" />
								<div class="box-title">
									<span class="title">'.$red['title_'.$_SESSION['lng']].' '.$subproduct['size'].'ml</span>';
									
									if($sp['action_price'] != '' && $sp['action_price'] > 0){
										
										$html .= '<div class="price">
													<span class="old">
														'.number_format($sp['price'], 2, ',', '.').' kn
													</span>
													<span>
														'.number_format($sp['action_price'], 2, ',', '.').' kn
													</span>
												</div>';
					
									}else{
						
										$html .= '<div class="price">
													<span>
														'.number_format($sp['price'], 2, ',', '.').' kn
													</span>
												</div>';
									}
									
									$html .= '<span class="details">
										'._POGLEDAJ_DETALJE.'
									</span>
								</div>
							</a>
						  </li>';
			}
			
			$i++;
		}
		
		$html .= '</ul>';
		
		$this->out('html',$html,$container);
		$this->out('script', '$(".slider").bxSlider({
						        slideWidth: 274,
						        minSlides: 1,
						        maxSlides: 3,
						        moveSlides: 1,
								infiniteLoop: false,
						        slideMargin: 20
						      });');

	}
	
	public function setPrice($price, $action_price)
	{
		if($action_price != '' && $action_price > 0){
			$this->out('script', '$(".akcijska-cjena").html("'.number_format($price, 2, ',', '.').' kn");');
			$this->out('script', '$(".cjena-detalji").html("'.number_format($action_price, 2, ',', '.').' kn");');
		}else{
			$this->out('script', '$(".cjena-detalji").html("'.number_format($price, 2, ',', '.').' kn");');
		}
	}
	
	public function setVersion($type)
	{
		if($type == 'desktop'){
			$_SESSION['desktopmode'] = 'true';
		}else{
			$_SESSION['desktopmode'] = 'false';
		}
		$this->out('script','window.location.href="'._SITE_URL.'"');
	}





	public function addDodatno($num)
	{

	 $html_dodatno .= '<div class="relative" id="dodatno-'.($num+1).'"><table><tbody><tr class="box-100">
	 					<td><input placeholder="'.$data['th1_hr'].'" type="text" name="col1_td_hr[]" id="col1_td_hr" value="'.$redovi[$num]['col1_td_hr'].'"/></td>

	 					<td><input placeholder="'.$data['th2_hr'].'" type="text" name="col2_td_hr[]" id="col2_td_hr" value="'.$redovi[$num]['col2_td_hr'].'"/></td>

	 					<td><input placeholder="'.$data['th3_hr'].'" type="text" name="col3_td_hr[]" id="col3_td_hr" value="'.$redovi[$num]['col3_td_hr'].'"/></td>

	 					<td><input placeholder="'.$data['th4_hr'].'" type="text" name="col4_td_hr[]" id="col4_td_hr" value="'.$redovi[$num]['col4_td_hr'].'"/></td>

	 					<td><input placeholder="'.$data['th5_hr'].'" type="text" name="col5_td_hr[]" id="col5_td_hr" value="'.$redovi[$num]['col5_td_hr'].'"/></td>

						<td><input placeholder="'.$data['th6_hr'].'" type="text" name="col6_td_hr[]" id="col6_td_hr" value="'.$redovi[$num]['col6_td_hr'].'"/></td>

						<td><input placeholder="'.$data['th7_hr'].'" type="text" name="col7_td_hr[]" id="col7_td_hr" value="'.$redovi[$num]['col7_td_hr'].'"/></td>

	 					<td><input placeholder="'.$data['th8_hr'].'" type="text" name="col8_td_hr[]" id="col8_td_hr" value="'.$redovi[$num]['col8_td_hr'].'"/></td>

 					</tr></tbody></table>
 					<a href="javascript:;" class="delete delete-table" title="Izbriši" onclick="if(confirm(\'Jeste li sigurni da želite obrisati zapis?\')){ sjx(\'del_dodatno\','.($num+1).'); return false;}"><img src="images/icon-delete.png" alt="Izbriši" /></a></div>';

	 	$this->out('append',$html_dodatno,'dodatna_polja');
	 	$this->out('script', '$("#dodatna_num").val('.($num+1).');');

	}

	public function del_dodatno($num)
	{
		$this->out('script', '$("#dodatno-'.$num.'").remove();');
	}


	public function addDodatno2($num)
	{

	 $html_dodatno .= '<div class="relative" id="dodatno-'.($num+1).'"><table><tbody><tr class="box-100">
	 					<td><input placeholder="'.$data['th1_en'].'" type="text" name="col1_td_en[]" id="col1_td_en" value="'.$redovi[$num]['col1_td_en'].'"/></td>

	 					<td><input placeholder="'.$data['th2_en'].'" type="text" name="col2_td_en[]" id="col2_td_en" value="'.$redovi[$num]['col2_td_en'].'"/></td>

	 					<td><input placeholder="'.$data['th3_en'].'" type="text" name="col3_td_en[]" id="col3_td_en" value="'.$redovi[$num]['col3_td_en'].'"/></td>

	 					<td><input placeholder="'.$data['th4_en'].'" type="text" name="col4_td_en[]" id="col4_td_en" value="'.$redovi[$num]['col4_td_en'].'"/></td>

	 					<td><input placeholder="'.$data['th5_en'].'" type="text" name="col5_td_en[]" id="col5_td_en" value="'.$redovi[$num]['col5_td_en'].'"/></td>

						<td><input placeholder="'.$data['th6_en'].'" type="text" name="col6_td_en[]" id="col6_td_en" value="'.$redovi[$num]['col6_td_en'].'"/></td>

						<td><input placeholder="'.$data['th7_en'].'" type="text" name="col7_td_en[]" id="col7_td_en" value="'.$redovi[$num]['col7_td_en'].'"/></td>

	 					<td><input placeholder="'.$data['th8_en'].'" type="text" name="col8_td_en[]" id="col8_td_en" value="'.$redovi[$num]['col8_td_en'].'"/></td>

 					</tr></tbody></table>
 					<a href="javascript:;" class="delete delete-table" title="Izbriši" onclick="if(confirm(\'Jeste li sigurni da želite obrisati zapis?\')){ sjx(\'del_dodatno2\','.($num+1).'); return false;}"><img src="images/icon-delete.png" alt="Izbriši" /></a></div>';

	 	$this->out('append',$html_dodatno,'dodatna_polja2');
	 	$this->out('script', '$("#dodatna_num2").val('.($num+1).');');

	}

	public function del_dodatno2($num)
	{
		$this->out('script', '$("#dodatno2-'.$num.'").remove();');
	}
	public function newsletter_sign_in($data)
	{
	
		$this->out('script', '$("input, textarea").removeClass("error");');
		$required = array('ime','email','kod');
		$errors = array();
		$errors_status = array();
		
		foreach($data as $k => $v)
		{
			if( in_array(Db::clean($k), $required) && Db::clean(trim($v)) == '' )
				$errors[] = $k;
		}
		
		if( ! valid_email($data['email']) )
		{
			$errors[] = 'email';
			$errors_status[] = 'mail_not_ok';
			
		}else{
		
			$is_email = Db::query_one('SELECT id FROM newsletter_email WHERE email = "'.Db::clean($data['email']).'" LIMIT 1');
			if( $is_email )
			{
				$errors[] = 'email';
				$errors_status[] = 'email_exists';
			}
			
		}
		
		if( $data['kod'] != $data['captch'] )
		{
			$errors[] = 'kod';
			$errors_status[] = 'captcha_not_ok';
		}
		
		
		if($errors)
		{
			foreach($errors as $err){
			
				$this->out('script','$("#'.$err.'").addClass("txt_error");');
				
			}
			
			$this->out('html','Please complete all marked fields.','error_container');
			
			if( in_array('email_exists',$errors_status) )
				$this->out('append','<br>Your e-mail already exists!','error_container');
			if( in_array('mail_not_ok',$errors_status) )
				$this->out('append','<br>The entered e-mail address is not valid!','error_container');
			if( in_array('captcha_not_ok',$errors_status) )
				$this->out('append','<br>Security check is not correct!','error_container');
			
			
			$this->out('script','$("#error_container").fadeIn()');
		}
		else
		{
			
			Db::query('INSERT INTO newsletter_email SET ime = "'.$data['ime'].'", email = "'.Db::clean($data['email']).'"');
			
			$this->out('script','$(".news-success.success").fadeIn();');
			$this->out('script','$(".news-error.error").fadeOut();');
			$this->out('script','$("#newsletter-forma").fadeOut()');
		}
		
		return false;
	}
	
	public function newsletter_sign_out($data)
	{
	
		$this->out('script', '$("input, textarea").removeClass("error");');
		$required = array('email');
		$errors = array();
		$errors_status = array();
		
		foreach($data as $k => $v)
		{
			if( in_array(Db::clean($k), $required) && Db::clean(trim($v)) == '' )
				$errors[] = $k;
		}
		
		if( ! valid_email($data['email']) )
		{
			$errors[] = 'email';
			$errors_status[] = 'mail_not_ok';
			
		}
		
		if( $data['kod'] != $data['captch'] )
		{
			$errors[] = 'kod';
			$errors_status[] = 'captcha_not_ok';
		}
		
		
		if($errors)
		{
			foreach($errors as $err){
			
				$this->out('script','$("#'.$err.'").addClass("error");');
				
			}
			
			$this->out('html','Please complete all marked fields.','error_container');
			
			if( in_array('mail_not_ok',$errors_status) )
				$this->out('append','<br>The entered e-mail address is not valid!','error_container');
			if( in_array('captcha_not_ok',$errors_status) )
				$this->out('append','<br>Security check is not correct!','error_container');
			
			
			$this->out('script','$("#error_container").fadeIn()');
		}
		else
		{
			
			Db::query('DELETE FROM newsletter_email WHERE email = "'.Db::clean($data['email']).'"');
			
			$this->out('script','$(".success").fadeIn();');
			$this->out('script','$(".error").fadeOut();');
			$this->out('script','$("#forma-off").fadeOut()');
		}
		
		return false;
	}
	public function set_zc($id)

	{
		$ao = new Admin_Operations('site_photos', $id);
		if( ! $ao )
			return false;
		$d = $ao->set_zc();
		if( $d )
		{
			$this->out('script','$("#zc_'.$id.'").prop("checked", true));');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}

		return false;
	}
	public function set_tlocrt($id)
	{
		$ao = new Admin_Operations('site_photos', $id);
		if( ! $ao )
			return false;
		$d = $ao->set_tlocrt();
		if( $d )
		{
			$this->out('script','$("#tlocrt_'.$id.'").prop("checked", true));');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}

		return false;

	}

	public function set_aktivno($table, $id, $column)
	{
		$ao = new Admin_Operations($table, $id);
		if( ! $ao )
			return false;
		$d = $ao->set_aktivno($column);
		if( $d )
		{
			$this->out('script','$("#'.$column.'_'.$id.'").prop("checked", true));');
		}
		else
		{
			$this->out('script','alert("Dogodila se greška! Pokušajte ponovo.");');
		}
		return false;
	}


public function favorits($id,$table,$lng)
	{
		$provjera = Db::query_one('SELECT id FROM favoriti WHERE '.$table.'_id='.$id.' and cookie="'.$_COOKIE[_STORE_COOKIE_NAME].'"');

		

		if($provjera){
			
			Db::query('DELETE FROM favoriti WHERE '.$table.'_id='.$id.' AND cookie="'.$_COOKIE[_STORE_COOKIE_NAME].'"');
		
			$this->out('script','$("#dodano_'.$id.'").removeClass("added");');
			
			if($lng=='hr'){
				$this->out('script','$("#wish_txt_'.$id.'").text("Dodaj u favorite");');
			}elseif($lng=='en'){
				$this->out('script','$("#wish_txt_'.$id.'").text("Add to favorits");');
			}elseif($lng=='de'){

			}	
		}else{
			
			Db::query('INSERT INTO favoriti SET '.$table.'_id='.$id.', cookie="'.$_COOKIE[_STORE_COOKIE_NAME].'"');	
			$this->out('script','$("#dodano_'.$id.'").addClass("added");');		
			if($lng=='hr'){
				$this->out('script','$("#wish_txt_'.$id.'").text("Dodano u favorite");');
			}elseif($lng=='en'){
				$this->out('script','$("#wish_txt_'.$id.'").text("Added to favorits");');
			}elseif($lng=='de'){

			}
		}
		// $this->out('script','$("#dodano_'.$id.'").addClass("added");');
		// $this->out('script','$("#dodano_'.$id.'").parent().removeClass("nije_dodan");');
		// $this->out('script','$("#favorits").addClass("visible");');
		// $this->out('script','$("#dodano_'.$id.'").addClass("disabled");');
		// $this->out('script','$("#dodano_'.$id.'").removeClass("visible");');
	}

	public function generateSublocations($id,$lng)
	{
$this->out('script','alert('.$id.');');
	$items_cats_ids = Db::query('SELECT DISTINCT city_id FROM items WHERE city_id != 0 ORDER BY orderby DESC'); 
	$i=1;
	foreach ($items_cats_ids as $aaa) {
	    $lokacije_i .= ($i==1)?'':',';
	    $lokacije_i .= $aaa['city_id'];
	$i++;
	}

		$kategorija = Db::query_row('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id='.$id);
		$sub_cats =  Db::query('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id IN ('.$lokacije_i.') AND parent_id='.$id);
		$this->out('script','$("#lokacija_data").val("'.$id.'");');
		if($sub_cats){
			if($kategorija['id']==1){
				$triggerTxt = 'Odaberite općinu';
			}else{
				$triggerTxt = 'Odaberite regiju';
			}
			
			$html .= '
				<span class="next-arrow"></span>
				<div class="select-frame inactive search-frame select">
                    <input name="sl" type="hidden" id="sublokacija_data" value="">
                    <a href="javascript:;" class="select-trigger2"><span class="triggerText">'.$triggerTxt.' <span class="arrow"></span></a>
                    <select id="sublokacija" class="hidden-select" onchange="sjx(\'generateSubSublocations\',$(this).val(),\''.$lng.'\',\''.$kategorija['id'].'\');return false;">
                    <option id="sublokacija0" value="">'.$triggerTxt.'</option>';
                        foreach ($sub_cats as $sub) {
                        	$html .= '<option id="sublokacija'.$sub['id'].'" '.(($cat_id=='1')?'selected':'').' value="'.$sub['id'].'">'.$sub['title_'.$lng].'</option>';
                        }  
                    $html .= '</select>
                    <div class="select-frame-max">
                        <ul class="select-ul">
                        <li'.(($cat_id=='0')?' class="active"':'').'>
                                <a rel="sublokacija0" data-title="'.$triggerTxt.'">'.$triggerTxt.'</a>
                            </li>';
                        foreach ($sub_cats as $sub) {
                        	$html .= '
                            <li'.(($cat_id=='1')?' class="active"':'').'>
                                <a rel="sublokacija'.$sub['id'].'" data-title="'.$sub['title_'.$lng].'">'.$sub['title_'.$lng].'</a>
                            </li>';
                        }
                        $html .= '</ul>
                    </div>
                </div>';
                 $this->out('html',$html,'sub1');
        
        $this->out('script','
        	$("#lokacija_data").val("'.$id.'");
        	$("#location-select").addClass("w100");
        	$("#sub1").addClass("sub-selected");
        	
        	$("#sub1").addClass("next-selected");
        	$("#sub2").removeClass("sub-selected");
        	$("#sub2 .select-frame").remove();
        
        	$(".select-trigger2").click(function(){
		      if($("#sub1 .select-frame").hasClass("inactive")) {
		        $("#sub1 .select-frame").removeClass("inactive");
		      } else {
		        $(this).parent(".select-frame").addClass("inactive");
		      }
			});
			$(".select-ul li a").click(function(){
			    var id_selectboxa = $(this).parent().parent().parent().prev().attr("id");
			    $("#" + id_selectboxa).parent().addClass("active-select");

			    var id_optiona = $(this).attr("rel");
			    var title = $(this).attr("data-title");
			    
			    $("#" + id_selectboxa + " option").removeAttr("selected");
			    $("#" + id_selectboxa + " option#" + id_optiona).attr("selected", true);
			   
			    $(".active-select li").removeClass("active");
			    $(this).parent().addClass("active");
			    $(this).parent().parent().parent().prev().prev().children().text(title);
			    $(".active-select").addClass("inactive");
			    $(".active-select").removeClass("active-select");
			    $("#" + id_selectboxa).change();
			});
			$(".search-frame").click(function(e){
			    e.stopPropagation();
			});
			$(document).click(function(){
			    $(".search-frame").addClass("inactive");
			});');

		}else{

			 $this->out('script','$("#sub1").empty();$("#sub2").empty();$("#location-select").removeClass("w100");');
			
		}
       
	}
	public function addValue($id)
	{
		$this->out('script','$("#subsublokacija_data").val("'.$id.'")');
	}
	
	public function generateSubSublocations($id,$lng,$main_cat_id)
	{
	$items_cats_ids = Db::query('SELECT DISTINCT city_id FROM items WHERE city_id != 0 ORDER BY orderby DESC'); 
	$broj = count($items_cats_ids);
	$i=1;
	$lokacije_i = '';
	foreach ($items_cats_ids as $aaa) {
	    $lokacije_i .= ($i==1)?'':',';
	    $lokacije_i .= $aaa['city_id'];
	$i++;
	}

	$this->out('script','$("#sublokacija_data").val("'.$id.'");');
		
	$html = '';
		$kategorija = Db::query_row('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id='.$id);
		$p_kategorija = Db::query_row('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id='.$kategorija['parent_id']);
		
			$sub_cats =  Db::query('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id IN ('.$lokacije_i.') AND parent_id='.$id);

		
		// $sub_cats =  Db::query('SELECT id,parent_id,title_'.$lng.' FROM city WHERE id IN ('.$lokacije_i.') AND parent_id='.$id);
		// var_dump($sub_cats);exit;
		if($sub_cats){
			if($main_cat_id==1){
				$triggerTxt = 'Odaberite kvart';
			}else{
				$triggerTxt = 'Odaberite grad';
			}
			$html .= '
				<span class="next-arrow"></span>
				<div class="select-frame inactive search-frame select">
                    <input name="ssl" type="hidden" id="subsublokacija_data" value="">
                    <a href="javascript:;" class="select-trigger3"><span class="triggerText">'.$triggerTxt.'</a>
                    <select  id="subsubsublokacija" class="hidden-select" onchange="sjx(\'addValue\',$(this).val());return false;">
                    <option id="subsublokacija0"  value="">'.$triggerTxt.'</option>
                        ';
                        foreach ($sub_cats as $sub) {
                        	$html .= '<option id="subsublokacija'.$sub['id'].'" '.(($cat_id=='1')?'selected':'').' value="'.$sub['id'].'">'.$sub['title_'.$lng].'</option>';
                        }  
                    $html .= '</select>
                    <div class="select-frame-max">
                        <ul class="select-ul">
                        	<li><a rel="subsublokacija0" data-title="'.$triggerTxt.'">'.$triggerTxt.'</a></li>
                        ';
                        foreach ($sub_cats as $sub) {
                        	$html .= '
                            <li'.(($cat_id=='1')?' class="active"':'').'>
                                <a rel="subsublokacija'.$sub['id'].'" data-title="'.$sub['title_'.$lng].'">'.$sub['title_'.$lng].'</a>
                            </li>';
                        }
                        $html .= '</ul>
                    </div>
                </div>';
		
        $this->out('html',$html,'sub2');
        $this->out('script','

        	$("#location-select").addClass("w100");
        	$("#sub2").addClass("sub-selected");
        	$(".select-trigger3").click(function(){
		      if($(this).parent(".select-frame").hasClass("inactive")) {
		        $(this).parent(".select-frame").removeClass("inactive");
		      } else {
		        $(this).parent(".select-frame").addClass("inactive");
		      }
			});
			$(".select-ul li a").click(function(){
			    var id_selectboxa = $(this).parent().parent().parent().prev().attr("id");
			    $("#" + id_selectboxa).parent().addClass("active-select");

			    var id_optiona = $(this).attr("rel");
			    var title = $(this).attr("data-title");
			    
			    $("#" + id_selectboxa + " option").removeAttr("selected");
			    $("#" + id_selectboxa + " option#" + id_optiona).attr("selected", true);
			   
			    $(".active-select li").removeClass("active");
			    $(this).parent().addClass("active");
			    $(this).parent().parent().parent().prev().prev().children().text(title);
			    $(".active-select").addClass("inactive");
			    $(".active-select").removeClass("active-select");
			    $("#" + id_selectboxa).change();
			});
			$(".search-frame").click(function(e){
			    e.stopPropagation();
			});
			$(document).click(function(){
			    $(".search-frame").addClass("inactive");
			});');
		}else{
			// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
			// $this->out('script','alert();');
			$html = '<input name="ssl" type="hidden" id="subsublokacija_data" value="">';
			$this->out('html',$html,'sub2');

		}


	}


	public function comment($data){
		$err = array();
		if( get_conf('multi_language') == 1 )
			load_lang();
		$req = array('ime_comm','message_comm','kod_comm');

		$this->out('script', '$("#comment input,#comment textarea").removeClass("txt_error");');
		
		$errori='';
		if(!valid_email($data['email_comm']) ){
			$this->out('script','$("#comment_error").empty();$("#email_comm").addClass("txt_error");');
			$this->out('append','<br>'._EMAIL_ERR,'comment_error');
			$errori .= 'mail,';
		}
		foreach($req as $k => $v)
		{
			if($data[$v]==''){
				$this->out('script', '$("#'.$v.'").addClass("txt_error");');
				$errori .= $v.',';
			}else{
				if($data['kod_comm']!=$data['captch']){
					$errori .= $v.',';
					$this->out('append','<br>'._SIGURNOSNA_PROVJERA,'comment_error');
				}
			}
		}
		if($errori==''){
			$this->out('script','$("#comment_error").hide();');
			$this->out('script','$(".news-success").show();');
			$this->out('script','$("#comment-forma").hide();');
			$sql = '
				INSERT INTO komentari SET 
					autor_hr  = "'.$data['ime_comm'].'",
					email = "'.$data['email_comm'].'",
					text_hr = "'.$data['message_comm'].'",
					anoniman = "'.$data['no-name'].'",
					created = "'.date('Y-m-d H:i:s').'"
				';
			$q = Db::query($sql);
			$id = Db::insert_id();

			$update_order = '
				UPDATE komentari SET 
					orderby  = "'.$id.'" WHERE id='.$id.';
				';
			$q2 = Db::query($update_order);

		}else{
			$this->out('append','<br>'._ISPUNITE_POLJA,'comment_error');
		}

		// još kod
		
	}
}
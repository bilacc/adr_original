<?php
class WebShop
{
	public $html='', $html_k='', $html_mail='', $price=0, $price_total=0, $price_no_discount=0, $pdv, $shipping_price=0, $num_items=0, $order_id=0, $weight=0, $lng='';
	private $ordering_type;
	
	public function __construct()
	{
		load_lang();
		if( isset($_SESSION['lng']) && in_array($_SESSION['lng'], get_conf('languages')) )
			$this->lng = '_'.$_SESSION['lng'];
		else
			$this->lng = ( get_conf('multi_language') == 1 ) ? '_'._LNG : '' ;
		
		$this->ordering_type = $this->ordering_type();
		$this->order_id = Db::query_one('SELECT id FROM orders WHERE '.$this->ordering_type['select'].' = "'.$this->ordering_type['users_id'].'" AND status = "active"');
		$this->update_order_data();
	}
	
	
	public function send_payment_data($payment_type)
	{
		$user = new User;
		
		if( ! $user->is_logged() )
			return false;
		
		if( ! in_array($payment_type,array('2','3')) )
			return false;
		
		$broj_ponude = $_SESSION['user']['id'].'-'.$this->order_id;
		
		// prvi subject je za korisnika koji je naručio a drugi za administratora koji dobiva obavijest o narudžbi
		$subject1 = get_conf('app_name').' - '._PONUDA_BR.'. '.$broj_ponude.' - '._PODACI_ZA_PLACANJE;
		$subject2 = get_conf('app_name').' Korisnik '.$user->get_info('fname').' '.$user->get_info('lname').' je poslao ponudu : br. '.$broj_ponude;
		
		// podaci o korisniku koji je naručio
		$user_data = '
			<table width="600">
				<tr>
					<td>Korisnik <strong>'.$user->get_info('fname').' '.$user->get_info('lname').'</strong> je poslao ponudu</td>
				</tr>
				<tr>
					<td>Ponuda br.: <strong>'.$broj_ponude.'</strong></td>
				</tr>
				<tr>
					<td>Datum ponude: <strong>'.date('d.m.Y').'</strong></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		';
		
		// narudžba
		$msg = '
			<table width="600" border="0" style="border: 1px solid #ccc; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#7f7f7f; padding:5px 10px 5px 10px">
				<tr>
					<td width="181" height="125" valign="middle"><a href="http://'._DOMENA.'"><img src="'._SITE_URL.'images/logic.jpg" alt="" /></a></td>
					<td width="238" valign="middle"></td>
					<td width="165" valign="middle"><span style="color:#7f7f7f;"><strong style="font-size:14px;">'._FIRMA_NAZIV.'</strong><br />'._FIRMA_ADRESA.'<br />tel: '._FIRMA_TELEFON.'<br />OIB: '._FIRMA_OIB.'<br />Žiro račun: '._FIRMA_ZIRO_RACUN.'<br /><a style="color:#0789CF;" href="mailto:'._DOMENA_EMAIL.'">'._DOMENA_EMAIL.'</a></span></td>
				</tr>
				<tr>
					<td height="48" colspan="3"><p style="text-align:center; border-top: 1px solid #7f7f7f; padding: 20px 0 0 0;">'._MSG_SEND1.' <a href="http://'._DOMENA.'" style="color:#0789CF;">'._DOMENA.'</a> '._MSG_SEND2.':</p></td>
				</tr>
				<tr>
					<td height="274" colspan="3">
						<table width="580" border="0" style="border-collapse:collapse; border: 1px solid #ccc;">
							<tr>
									<td style="padding:5px; border: 1px solid #ccc;" width="344"><strong style="font-size:14px;">'._NARUCENI_PROIZVODI.'</strong></td>
									<td style="padding:5px; border: 1px solid #ccc;" width="110"><strong style="font-size:14px;">'._KOLICINA.'</strong></td>
									<td style="padding:5px; border: 1px solid #ccc; text-align:right;" width="110"><strong style="font-size:14px;">'._CIJENA_U_KN.'</strong></td>
							</tr>
							'.$this->html_mail.'
							<tr>
									<td colspan="2" style="text-align:right; padding:5px;">'._UKUPAN_IZNOS.':</td>
									<td style="text-align:right; border: 1px solid #ccc;">'.number_format($this->price, 2, ',','.').' kn</td>
							</tr>
							<tr>
									<td colspan="2" style="text-align:right; padding:5px;">'._CIJENA_DOSTAVE.':</td>
									<td style="padding:5px; border: 1px solid #ccc; text-align:right;">'.number_format($this->shipping_price, 2, ',','.').' kn</td>
							</tr>
							<tr>
									<td colspan="2" style="text-align:right; padding:5px;">'._PDV_2.':</td>
									<td style="padding:5px; border: 1px solid #ccc; text-align:right;">'.number_format($this->pdv, 2, ',','.').' kn</td>
							</tr>
							<tr>
									<td colspan="2" style="text-align:right; padding:5px;"><strong><u>'._SVEUKUPAN_IZNOS_ZA_UPLATU.':</u></strong></td>
									<td style="padding:5px; border: 1px solid #ccc; text-align:right;"><strong><u>'.number_format($this->price_total, 2, ',','.').' kn</u></strong></td>
							</tr>
						</table>
						<p>'._UPLATU_IZVRSITE_NA.' <span style="color:#000;">'._ZIRO_RACUN_BROJ.':    <strong><u>'._FIRMA_ZIRO_RACUN.'</u></strong></span>   <br /><span style="color:#000;">'._U_POLJE_POZIV_NA_BROJ.' '.$broj_ponude.'</span></p>
						<p>'._SEND_MSG1.'</p>
						<p>'._SEND_MSG2.'</p>
						<p>'._SEND_MSG3.' </p>
						<p style="color:#1f6bc1;">'._FIRMA_NAZIV.' - <a style="color:#0080c8;" href="http://'._DOMENA.'/">'._DOMENA.'</a></p>
					</td>
				</tr>
			</table>
		';
		
		$m1 = send_html_mail(get_conf('from_mail'), $_SESSION['user']['email'], $subject1, $msg);
		$m2 = send_html_mail(get_conf('from_mail'), get_conf('email'), $subject2, $user_data.$msg);
		
		$payment_type[1] = 'credit_card';
		$payment_type[2] = 'post';
		$payment_type[3] = 'on_delivery';
		
		//$m1 = 1;
		if( $m1 )
		{
			$q = Db::query('UPDATE orders SET status = "ordered", payment_type = "'.$payment_type[$paymenttype].'" WHERE id = '.$this->order_id);
			
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
	
	public function update_order_items($data)
	{
		// var_dump($data);
		
		foreach($data as $k => $v)
		{
			if( substr($k,0,10) == 'items_num_' && $this->order_id )
			{
				$items_id = (int)substr($k,10);
				
				// print $items_id.' : '.$v."\n";
				
				if( $items_id > 0 )
				{
					if( (int)$v == 0 )
					{
						Db::query('DELETE FROM orders_data WHERE items_id = '.$items_id.' AND orders_id = '.$this->order_id);
					}
					else
					{
						$num_items = Db::query_one('SELECT COUNT(id) FROM orders_data WHERE items_id = '.$items_id.' AND orders_id = '.$this->order_id);
						
						if( $num_items > $v )
						{
							$to_delete = $num_items - $v;
							
							// print 'id:'.$items_id.' num:'.$v.' cnum:'.$num_items.' todel:'.$to_delete."\n";
							
							Db::query('DELETE FROM orders_data WHERE items_id = '.$items_id.' AND orders_id = '.$this->order_id.' LIMIT '.$to_delete);
						}
						else if( $num_items < $v )
						{
							$to_add = $v - $num_items;
							
							for($i=1; $i<=$to_add; $i++)
							{
								Db::query('INSERT INTO orders_data SET items_id = '.$items_id.', orders_id = '.$this->order_id);
							}
						}
					}
				}
			}
		}
	}
	
	public function update_order_data()
	{
		$this->price = 0;
		$this->price_no_discount = 0;
		$this->num_items = 0;
		$this->weight = 0;
		
		$sql = 'SELECT od.items_id, COUNT(od.items_id) cnt, i.categories_id, i.price, i.price_discount, i.title'.$this->lng.' title,  
			c.title'.$this->lng.' ctitle, c.id cid 
			FROM orders_data od 
			LEFT JOIN items i ON i.id = od.items_id 
			LEFT JOIN categories c ON c.id = i.categories_id 
			WHERE od.orders_id = '.$this->order_id.' GROUP BY od.items_id';
		//print $sql.'<br />';
		$items = Db::query($sql);
		
		if( $items )
		{
			$this->num_items = Db::query_one('SELECT COUNT(id) FROM orders_data WHERE orders_id = '.$this->order_id);
			
			foreach($items as $k => $v)
			{
				$c = ($v['price_discount'] > 0) ? $v['cnt'] * $v['price_discount'] : $v['cnt'] * $v['price'] ;
				
				$this->price = $this->price + $c;
				$this->price_no_discount = $this->price_no_discount + ($v['cnt'] * $v['price']);
				
				$this->weight = $this->weight + ($v['tezina'] * $v['cnt']);
				
				$sql = 'select title'.$this->lng.' from categories where id = "'.$v['categories_id'].'"';
				//print $sql.'<br />';
				$cat = Db::query_one($sql);
				//echo $cat.'<br />';
				
				$link_id = ( $v['items_id_link'] > 0 ) ? $v['items_id_link'] : $v['items_id'] ;
				$link = _SITE_URL.'detalji/'.clean_uri($cat).'/'.clean_uri($v['title']).'/'.$link_id;
				
				$this->html .= '
					<div class="right_kosarica_artikl" id="art_kos_'.$v['items_id'].'">
						<h4><a href="'.$link.'">'.$v['title'].'</a></h4>
						<div class="right_kosarica_artikl_kol">Količina: '.$v['cnt'].'</div>
						<a href="javascript:;" onclick="xajax_remove_product('.$v['items_id'].');"><img src="images/kosarica_del.gif" alt="" class="right_kosarica_del" /></a>
						<div class="right_kosarica_artikl_cij">'.number_format($c,2,',','.').' Kn</div>
					</div>
				';
				
				/*$this->html_k .= '
					<tr id="item_c_k_'.$v['items_id'].'">
						<td><a href="'.$link.'">'.$v['title'].'</a></td>
						<td class="kosarica_kolicina"><input type="text" class="kosarica_kolicina" value="'.$v['cnt'].'" id="items_num_'.$v['items_id'].'" name="items_num_'.$v['items_id'].'"></td>
						<td class="kosarica_cijena">'.number_format($c,2,',','.').'</td>
						<td class="kosarica_cijena">'.$v['pdv'].'</td>
						
						<td class="kosarica_opcije">
							<input type="button" class="kosarica_delete" value="&nbsp;" onclick="if(confirm(\'Jeste li sigurni da želite obrisati artikl iz košarice?\')) sjx(\'del_from_cart\','.$v['items_id'].');">
						</td>
					</tr>';*/
				
					$sql = "select photo_name from site_photos where table_name = 'items' and table_id = ".$v['items_id']." order by orderby asc, id asc limit 1";
					$slika = Db::query_one($sql);
					if($slika != '')
					{
						$slika_url = _SITE_URL.'upload_data/site_photos/th_'.$slika;
					}
					else 
					{
						$slika_url = _SITE_URL.'images/default.jpg';
					}
					
					$this->html_k .= '<tr>
						 <td valign="middle" id="item_c_k_'.$v['items_id'].'">
							<table class="second">
								<tr>
									<td>
										<div class="pic">
											<a href="'.$link.'"><img src="'.$slika_url.'" alt=""/></a>
										</div>
									</td>
									<td class="article_name"><a href="'.$link.'">'.$v['title'].'</a></td>
								</tr>
							</table>
						 </td>
						 <td class="kosarica_kolicina"><input type="text" class="kosarica_kolicina" value="'.$v['cnt'].'" id="items_num_'.$v['items_id'].'" name="items_num_'.$v['items_id'].'"></td>
						 <td colspan="1" class="kosarica_cijena">'.number_format($c,2,',','.').'</td>
						 
						 <td class="kosarica_opcije">
						 <input type="submit" class="kosarica_delete" value="&nbsp;" name="" onclick="if(confirm(\'Jeste li sigurni da želite obrisati artikl iz košarice?\')) sjx(\'del_from_cart\','.$v['items_id'].');">
						 </td>
					 </tr>';
				
				$this->html_mail .= '
					<tr>
						<td style="padding:5px; border: 1px solid #ccc;"><a style="font-size:12px; color:#0789CF;" href="'._SITE_URL.$link.'">'.$v['title'].'</a></td>
						<td style="padding:5px; border: 1px solid #ccc;">'.$v['cnt'].'</td>
						<td style="padding:5px; border: 1px solid #ccc; text-align:right">'.number_format($c,2,',','.').'</td>
					</tr>
				';
				
				$this->html_admin .= '
					<tr>
						<td>&nbsp;</td>
						<td>'.$v['title'].'</td>
						<td>'.$v['cnt'].'</td>
						<td>'.number_format($c,2,',','.').'</td>
					</tr>
				';
			}
		}
		
		$this->calc_shipping_price();
		$this->pdv = $this->price - ( $this->price / _PDV_RACUNANJE );
		
		/*if( $this->price > 1500 )
		{
			$this->shipping_price = 0;
			$this->price_total = $this->price;
		}
		else
		{*/
			$this->price_total = $this->price + $this->shipping_price;
		//}
	}
	
	public function add_to_cart($id, $num)
	{
		// provjerava dali postoji artikl za id koji smo mu proslijedili
		$is_item = Db::query_one('SELECT id FROM items WHERE id = '.(int)$id.' LIMIT 1');
		if( ! $is_item )
			return false;
		
		$id = (int)$id;
		$num = ( (int)$num > 0 ) ? (int)$num : 1 ;
		
		// provjeri dali postoji aktivna narudzba i ako ne postoji onda kreira novu narudžbu
		//print 'ovo je this order: : '.$this->order_id.'<br />';
		if( ! $this->order_id )
		{
			$create_order = $this->create_order();
			if( ! $create_order )
				return false;
		}
		
		// doda u košaricu onoliko artikala koliko smo mu rekli
		for($i=1; $i<=$num; $i++)
		{
			$sql = 'INSERT INTO orders_data SET orders_id = '.$this->order_id.', items_id = '.$id;
			//print $sql.'<br />';
			Db::query($sql);
		}
		
		return true;
	}
	
	public function del_from_cart($id)
	{
		$sql = 'SELECT o.id FROM orders_data od LEFT JOIN orders o ON o.id = od.orders_id WHERE od.items_id = '.(int)$id.' AND o.'.$this->ordering_type['select'].' = "'.$this->ordering_type['users_id'].'" LIMIT 1';
		
		$can_do_it = Db::query_one($sql);
		
		//print $sql;
		
		if( $can_do_it )
		{
			$q = Db::query('DELETE FROM orders_data WHERE items_id = '.(int)$id);
			$this->update_order_data();
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function create_order()
	{
		$sql = 'INSERT INTO orders SET '.$this->ordering_type['select'].' = "'.$this->ordering_type['users_id'].'"';
		//print $sql.'<br />';
		$q = Db::query($sql);
		
		if( $q )
		{
			$this->order_id = Db::insert_id();
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function make_item_link($id)
	{
		$path = '';
		$parents = get_parent_categories($id, 'categories');
		if( count($parents) > 0 )
		{
			foreach($parents as $k => $v)
			{
				$path .= Db::query_one('SELECT title'.$this->lng.' FROM categories WHERE id = '.$v);
				$path .= ' ';
			}
		}
		
		$path = '/'.clean_uri($path).'-'.$id;
		
		return $path;
	}
	
	public function ordering_type()
	{
		$user = new User;
		
		if( $user->is_logged() )
		{
			$data['users_id'] = $_SESSION['user']['id'];
			$data['select'] = 'users_id';
		}
		elseif($_COOKIE[_STORE_COOKIE_NAME])
		{
			$data['users_id'] = $_COOKIE[_STORE_COOKIE_NAME];
			$data['select'] = 'cookie_id';
		}
		
		return $data;
	}
	
	public function calc_shipping_price()
	{
		// cijene dostave ovisno o težini
		/*$dp[1] = 18;
		$dp[2] = 21;
		$dp[5] = 23;
		$dp[10] = 28;
		$dp[15] = 32;
		$dp[20] = 40;
		$dp[25] = 45;
		$dp[30] = 50;
		$dp[35] = 55;
		$dp[40] = 62;
		$dp[45] = 68;
		$dp[50] = 75;
		$dp[100] = 140;
		
		foreach($dp as $k => $v)
		{
			if( $this->weight < $k )
			{
				$gorivo = $v * _GORIVO / 100;
				$this->shipping_price = $v + $gorivo;
				break;
			}
		}
		
		if( $this->shipping_price == 0 )
		{
			$gorivo = $dp[100] * _GORIVO / 100;
			$this->shipping_price = $dp[100] + $gorivo;
		}
		
		$this->shipping_price = $this->shipping_price + ($this->shipping_price * _PDV / 100);*/
		
		$this->shipping_price = 0;
	}
}
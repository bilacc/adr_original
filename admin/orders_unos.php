<?php 
	require_once('../lib/functions.php');
			
	$crud = new Admin_Crud;
	$crud->table = 'orders';
	$crud->title_row_name = 'ime';
		
	$show['text'] = true; 				//tekst
	$show['status'] = true; 			//mogućnost postavljanja statusa
	$show['date'] = true; 				//datum
	
	$payment_type['credit_card'] = 'Kreditna kartica';
	$payment_type['post'] = 'Uplatnica';
	$payment_type['on_delivery'] = 'Pouzećem';

	$status['ordered'] = 'Naručena';
	$status['paid'] = 'Plaćena';
	$status['shipped'] = 'Poslana';
			
	if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ) // ako je GET znači da trebamo dohvatiti podatke za id koji je naveden
	{
		$id = (int)$_GET['id'];
		
		$data = Db::query('SELECT od.items_id, COUNT(items_id) cnt, i.price, i.price_discount, i.title, c.title ctitle, c.id cid FROM orders_data od LEFT JOIN items i ON i.id = od.items_id LEFT JOIN categories c ON c.id = i.categories_id WHERE od.orders_id = '.(int)$id.' GROUP BY od.items_id');
		$user_data = Db::query_row('SELECT u.*, o.status, o.payment_type, o.note FROM orders o LEFT JOIN users u ON o.users_id = u.id WHERE o.id = '.(int)$id);
		$user_id = $user_data['id'];
		
		$order = new Admin_Order((int)$_GET['id'], $user_id);
	
	}
	else if( isset($_POST) && count($_POST) > 0 ) // ako je POST onda spremamo podatke, unosimo ili updateamo
	{
		if( isset($_POST['e_id']) && (int)$_POST['e_id'] > 0 )
		{
			$crud->id = (int)$_POST['e_id'];
			$crud->action = 'update';
		}
		
		$crud->save_data($_POST);
	}
		
	include('include/php/header.php');
	
	if( isset($_GET['err']) )
	{
		echo '<div class="error">';
		
		foreach($_SESSION['err_collector'] as $k => $v)
		{
			echo $v.'<br/>';
		}
		
		echo '</div>';
		
		$_SESSION['err_collector'] = null;
	}
	else if( isset($_GET['status']) && $_GET['status'] == 'success' )
	{
		echo '<div class="success">Uspješno ste spremili podatke!</div>';
	}
?>

	<h1>Pregled detalja narudžbe</h1>
	
	<form name="<?php echo $crud->table; ?>" id="<?php echo $crud->table; ?>" class="unos" action="" enctype="multipart/form-data" method="post">
		<input type="hidden" name="e_id" id="e_id" value="<?php if( isset($_GET['id']) ){ echo $_GET['id']; } ?>"/>		
		
		<div class="box-75">
		
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
				<h3>Podaci narudžbe</h3>
				
				<div class="box-25">
					<h4>Broj ponude:</h4>
					<p><?php echo $user_id.'-'.(int)$id; ?></p>
				</div>
				
				<div class="box-25 drugi">
					<h4>Broj narudžbe:</h4>
					<p><?php echo (int)$id; ?></p>
				</div>
				
				<div class="box-25">
					<h4>Način plaćanja:</h4>
					<p><?php echo $payment_type[$user_data['payment_type']]; ?></p>
				</div>
				
				<div class="box-25 last">
					<h4>Status:</h4>
					<p><?php echo $status[$user_data['status']]; ?></p>
				</div>
			</div>
			
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
				<h3>Podaci o korisniku</h3>
				
				<div class="box-25">
					<h4>Ime:</h4>
					<p><?php echo $user_data['ime']; ?></p>
				</div>
				
				<div class="box-25 drugi">
					<h4>Prezime:</h4>
					<p><?php echo $user_data['prezime']; ?></p>
				</div>
				
				<div class="box-25">
					<h4>Adresa:</h4>
					<p><?php echo $user_data['address'].', '.$user_data['post'].', '.$user_data['city']; ?></p>
				</div>
				
				<div class="box-25 last">
					<h4>E-mail:</h4>
					<p><?php echo $user_data['email']; ?></p>
				</div>
				
				<div class="box-25">
					<h4>Telefon:</h4>
					<p><?php echo $user_data['phone']; ?></p>
				</div>
				
				<div class="box-25 last">
					<h4>Mobitel:</h4>
					<p><?php echo $user_data['mobile']; ?></p>
				</div>
			</div>
			
			<?php if($user_data['naziv_tvrtke'] != ""){ ?>
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
				<h3>Podaci o tvrtki (R-1 račun)</h3>
				
				<div class="box-25">
					<h4>Naziv tvrtke:</h4>
					<p><?php echo $user_data['naziv_tvrtke']; ?></p>
				</div>
				
				<div class="box-25 drugi">
					<h4>Adresa:</h4>
					<p><?php echo $user_data['adresa_tvrtke']; ?></p>
				</div>
				
				<div class="box-25">
					<h4>OIB:</h4>
					<p><?php echo $user_data['oib_tvrtke']; ?></p>
				</div>
			</div>
			<?php } ?>
			
			<?php if($user_data['address_d'] != ""){ ?>
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
				<h3>Podaci o primatelju narudžbe</h3>
				
				<div class="box-25">
					<h4>Primatelj:</h4>
					<p><?php echo $user_data['primatelj_d']; ?></p>
				</div>
				
				<div class="box-25 drugi">
					<h4>Adresa:</h4>
					<p><?php echo $user_data['address_d']; ?></p>
				</div>
				
				<div class="box-25">
					<h4>Država:</h4>
					<p><?php echo $user_data['country_d']; ?></p>
				</div>
				
				<div class="box-25 last">
					<h4>Telefon:</h4>
					<p><?php echo $user_data['phone_d']; ?></p>
				</div>
			</div>
			<?php } ?>
			
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
				<h3>Naručeni proizvodi</h3>
				
				<table>
					<thead>
						<tr>
							<th>Proizvod</th>
							<th>Količina</th>
							<th>Cijena ( Kn )</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if($order)
						{
							echo $order->html_admin;
						?>
								<tr >
								   <td>Ukupan iznos:</td>
								   <td>&nbsp;</td>
								   <td><?php echo number_format($order->price, 2, ',','.'); ?> Kn</td>
								</tr>
								<tr >
								   <td>Cijena dostave:</td>
								   <td>&nbsp;</td>
								   <td><?php echo number_format($order->shipping_price, 2, ',','.'); ?> Kn</td>
								</tr>
								<tr >
								   <td>Ukupan iznos za uplatu:</td>
								   <td>&nbsp;</td>
								   <td><strong><?php echo number_format($order->price_total, 2, ',','.'); ?> Kn</strong></td>
								</tr>
						<?php
						}
						else
						{
							echo '
								<tr>
									<td colspan="3">
										<strong>Nema artikala.</strong>
									</td>
								</tr>
							';
						}
						?>
					</tbody>	
				</table>
			</div>
		</div>
		

		<?php
		if($show['status'] || $show['date']){
		?>
		<div class="box-25 light">
			<a class="toggle" href="javascript:;">Otvori / Zatvori</a>	
			<h3>Karakteristike</h3>
			
			<?php
			if($show['date']){
			?>
			<h4>Naručeno:</h4>
			<div class="datum">
				<select name="created_dan" id="">
					<?php
					for($i = 1; $i <= 31; $i++){
						if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
						?>
							<option value="<?php echo $i;?>" <?php echo (date("d", strtotime($data['created'])) == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
						<?php 
						}else{
						?>
							<option value="<?php echo $i;?>" <?php echo (date("d") == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php 
						}
					} 
					?>
				</select>
				
				<select name="created_mjesec" id="">
					<?php
					for($i = 1; $i <= 12; $i++){
						if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
						?>
							<option value="<?php echo $i;?>" <?php echo (date("m", strtotime($data['created'])) == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
						<?php 
						}else{
						?>
							<option value="<?php echo $i;?>" <?php echo (date("m") == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php 
						}
					} 
					?>
				</select>
				
				<select name="created_godina" id="">
					<?php
					for($i = 1990; $i <= 2050; $i++){
						if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
						?>
							<option value="<?php echo $i;?>" <?php echo (date("Y", strtotime($data['created'])) == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
						<?php 
						}else{
						?>
							<option value="<?php echo $i;?>" <?php echo (date("Y") == $i)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php 
						}
					} 
					?>
				</select>
			</div>
			<?php
			}
			?>
			
			<?php
			if($show['status']){
			?>
			<h4>Status:</h4>
			<select name="status" id="status">
				<option value="ordered" <?php echo ($data['status'] == 'ordered')? 'selected="selected"':'';?>>Naručeno</option>
				<option value="paid" <?php echo ($data['status'] == 'paid')? 'selected="selected"':'';?>>Plaćeno</option>
				<option value="shipped" <?php echo ($data['status'] == 'shipped')? 'selected="selected"':'';?>>Poslano</option>
			</select>
			<?php
			}
			?>	

			<h4>Napomena:</h4>
			<textarea name="note" id="note"><?php echo $data['note']; ?></textarea>
		</div>
		<?php } ?>
	
		<div class="clearfix"></div>
				
		<div class="save">
			<div class="submit-wrapper">
				<input type="submit" name="spremi3" class="spremi_s" value="Spremi" />
			</div>
		</div>
	</form>
<?php include('include/php/footer.php'); ?>
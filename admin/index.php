<?php 

		require_once('../lib/functions.php');
	$user = new Admin_User;
	if( ! $user->is_logged() )
	{
		//echo 'test'.$_SESSION['admin']['level_id'];
		if( ($_SESSION['admin']['level_id'] == 6) )
		{
			header('Location:'._SITE_URL.'admin/news_unos.php');
			exit;
		}
	}

	include('include/php/header.php');
	$err = false;
	
	if($_POST['posalji_upit'])
	{	
		if($_POST['naslov'] != 'Naslov poruke' && $_POST['poruka'] != 'Koji je vaš upit?')
		{			
			$send = send_html_mail(_FIRMA_EMAIL, 'info@adresar.net', $_POST['naslov'],  nl2br($_POST['poruka']));		
			
			if($send)
			{
				echo '<div class="success">Poruka uspješno poslana. Kontaktirat ćemo vas uskoro.</div>';
			}else{
				echo '<div class="error">Poruka nije uspješno poslana. Pokušajte ponovno kasnije.</div>';
			}
		}else{
			$err = true;
		}
	}
	
	if($err){
		echo '<div class="error">Sva polja moraju biti popunjena!</div>';
	}
?>	
	<h1>Nadzorna ploča</h1>
	
	<!--[if lte IE 9]>
	<div class="warning">
		<h2>Upozorenje! Vaš internet preglednik je zastario!</h2>
		
		<p>Da biste dobili najbolje moguće iskustvo u korištenju naše web stranice, preporučujemo vam da nadogradite svoje web preglednike na novije verzije ili neki drugi web preglednik. Popis najpopularnijih web preglednika mogu se naći u nastavku.</p>
		<a href="http://www.mozilla.com/en-US/products/download.html?product=firefox-3.6.15&amp;os=win&amp;lang=en-US" target="_blank">
			<img src="../warning/browser_firefox.gif" width="50" height="50" border="0" />
		</a> 
		<a href="http://windows.microsoft.com/hr-HR/internet-explorer/downloads/ie-8" target="_blank">
			<img src="../warning/browser_ie.gif" width="50" height="50" border="0" />
		</a> 
		<a href="http://www.google.com/chrome/eula.html?hl=hr&amp;brand=CHMB&amp;utm_campaign=hr&amp;utm_source=hr-ha-emea-hr-sk&amp;utm_medium=ha&amp;installdataindex=homepagepromo" target="_blank">
			<img src="../warning/browser_chrome.gif" width="50" height="50" border="0" />
		</a> 
		<a href="http://www.opera.com/download/" target="_blank">
			<img src="../warning/browser_opera.gif" width="50" height="50" border="0" />
		</a> 
		<a href="http://www.apple.com/safari/download/" target="_blank">
			<img src="../warning/browser_safari.gif" width="50" height="50" border="0" />
		</a>
	</div>
	<![endif]-->	
	
	<div class="box-50 light dobrodosli">
		<h2>Dobrodošli!</h2>
		
		<p>Dobrodošli na sučelje za administraciju web stranica <a href="http://adresar.net" target="_blank">adresar.net</a>.
		Ovdje možete pregledati statistike najnovijih logiranja i aktivnosti, kao i ukupne statistike stranice.</p>
		
	</div>
	
	<div class="box-50 light last">
		<h2>Uputstva za korištenje</h2>
		
		<div class="uputstva">
			<a href="http://virtus-projekti.com/tutorials/stranice_unos.swf" target="_blank">Unos stranica/novosti</a>
			<a href="http://virtus-projekti.com/tutorials/kategorije_unos.swf" target="_blank">Unos kategorija</a>
			<a href="http://virtus-projekti.com/tutorials/slike_unos.swf" target="_blank">Unos slika i dokumenata</a>
		</div>
		
		<div class="uputstva">
			<a href="http://virtus-projekti.com/tutorials/stranice_pregled.swf" target="_blank">Pregled stranica</a>
			<a href="http://virtus-projekti.com/tutorials/kategorije_pregled.swf" target="_blank">Pregled kategorija</a>
			<a href="http://virtus-projekti.com/tutorials/newsletter.swf" target="_blank">Newsletter</a>
		</div>
		
		<div class="uputstva">
			<a href="http://virtus-projekti.com/tutorials/profil.swf" target="_blank">Profil</a>
		</div>
	</div>
	
	<div class="box-50 gray zadnji-unosi">
		<h3>Zadnji unos</h3>
		
		<?php
		$logs = Db::query('SELECT sa.*, u.name FROM stats_activities sa LEFT JOIN admin_users u ON u.id = sa.admin_users_id ORDER BY sa.id DESC LIMIT 10');
		
		if($logs)
		{
		?>
		
		<table>
			<thead>
				<tr>
					<th>Datum i vrijeme</th>
					<th>Korisnik</th>
					<th>Cjelina</th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				foreach($logs as $k => $v)
				{
					echo '
					<tr>
						<td>'.date('d.m.Y H:i:s', strtotime($v['created'])).'</td>
						<td>'.$v['name'].'</td>
						<td>'.$v['entry_title'].'</td>
					</tr>
					';
				}
				?>
			</tbody>
		</table>
		
		<?php
		}else{
		?>
		<p>Trenutno nema zapisa o unosima u bazi.</p>
		<?php
		}
		?>
	</div>
	
	<div class="box-25 gray statistika">
		<h3>Statistika</h3>
		
		<?php
		$tables['categories'] = 'Kategorije';
		$tables['pages'] = 'Stranice';
		$tables['newsletter'] = 'Newsletter';
		$tables['orders'] = 'Narudžbe';
		?>
		<table>			
			<tbody>
				<?php
					foreach($tables as $k => $v)
					{
						$cnt = Db::query_one('SELECT COUNT(id) FROM '.$k.' LIMIT 1');
						
						echo '<tr><td>'.$v.' : <span class="zeleno">'.$cnt.'</span></td><td><a href="'.$k.'_pregled.php">Pogledaj sve</a></td></tr>';
					}
				?>
			</tbody>
		</table>
	</div>
	
	<div class="box-25 green last">
		<h3>Kontaktirajte nas<br/>
		<span>Imate li trenutačno neki upit što se tiče vaše web stranice? Pošaljite upit i odgovoriti ćemo vam u najbržem mogućem roku.</span>
		</h3>
		
		<div class="kontakt-forma">
			<form action="index.php" method="post">
				<div class="pregrada">
					<input name="naslov" type="text" class="" id="naslov" value="Naslov poruke"  onblur="if(value=='') value = 'Naslov poruke'" onfocus="if(value=='Naslov poruke') value = ''"/> 
					<div class="clearfix"></div>
				</div>
				<div class="pregrada">
					<textarea name="poruka" class="" id="poruka" cols="" rows="10" onblur="if(value=='') value = 'Koji je vaš upit?'" onfocus="if(value=='Koji je vaš upit?') value = ''">Koji je vaš upit?</textarea>  
					<div class="clearfix"></div>
				</div>
				<input class="button" type="submit" name="posalji_upit" value="Pošalji upit" />
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
	
	<div class="clearfix"></div>
<?php include('include/php/footer.php'); ?>
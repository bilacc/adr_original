<?php 
	include('include/php/header.php');
	
	$table = 'city';
	
	$show['title'] = true;
	$show['date'] = false;
	$show['image'] = false;
	
	if($_GET['order']=='date1')
	{
		$order = ' created ASC';
	}
	elseif($_GET['order'] == 'date2')
	{
		$order = ' created DESC';
	}
	elseif($_GET['order'] == 'title1')
	{
		$order = ' title_hr ASC';
	}
	elseif($_GET['order'] == 'title2')
	{
		$order = ' title_hr DESC';
	}
	else
	{
		$order = ' orderby ASC';
	}

	if( isset($_GET['status']) && $_GET['status'] == 'success' )
	{
		echo '<div class="success">Uspješno ste spremili podatke.</div>';
	}
	elseif( isset($_GET['status']) && $_GET['status'] == 'delete_ok')
	{
		echo '<div class="success">Uspješno obrisali podatke.</div>';
	}
?>
	
<h1>Lokacije <br/><a href="<?php echo $table; ?>_unos.php" class="gumb">Dodaj novu</a></h1>
	
<input type="hidden" id="table" value="<?php echo $table; ?>" />
<div class="cat-list-header">
	<?php if($show['title']){ ?><div class="naslov"><a href="<?php echo $table; ?>_pregled.php?order=<?php echo($_GET['order'] == 'title1')? 'title2':'title1'; ?>">Naslov</a></div><?php } ?>
	<?php if($show['date']){ ?><div class="datum"><a href="<?php echo $table; ?>_pregled.php?order=<?php echo($_GET['order'] == 'date1')? 'date2':'date1'; ?>">Datum</a></div><?php } ?>
	<?php if($show['image']){ ?><div class="slika">Slika</div><?php } ?>
</div>
	
<ul class="cat-list">
	<?php
	function display_children($table, $parent, $level, $show, $order)
	{
		$result = Db::query('SELECT k.id, k.title_hr, k.created, k.image FROM '.$table.' k WHERE k.parent_id = '.$parent.' ORDER BY'.$order);
		
		if($result)
		{
			
			if($level > 0)
			{
			echo '<ul class="sort">';
			}
			
			$c=0;
			$broj = count($result);
			foreach($result as $r)
			{
				$c++;
				
				if($show['image']){
					$slika = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$r['image']);
					$slika = ($slika)? 'upload_data/site_photos/th_'.$slika : 'admin/images/default.jpg';
				}
				
				if($level == 0)
				{
					echo '<li class="level-'.$level.'" id="cat_id_'.$r['id'].'">';
					
					if($show['title']){ 						
						echo '<div class="naslov">
								<a href="'.$table.'_unos.php?action=update&id='.$r['id'].'">'.$r['title_hr'].'</a>
								<div class="opcije">
									<a href="'.$table.'_unos.php?action=update&id='.$r['id'].'" class="edit" title="Uredi"><img src="images/icon-edit.png" alt="Uredi" /></a> 
									<a href="javascript:;" class="delete" title="Izbriši" onclick="if(confirm(\'Dali ste sigurni da želite obrisati kategoriju '.$r['title_hr'].'?\n\r\n\rOprez, brisanjem ove kategorije obrisati će te i sve podkategorije i proizvode koje ova kategorija sadrži!\')) sjx(\'del_category\','.$r['id'].', \''.$table.'\'); return false;"><img src="images/icon-delete.png" alt="Izbriši" /></a>
								</div>
							</div>';
					}
					
					if($show['date']){
						if(date("d.m.Y") >= date("d.m.Y", strtotime($r['created']))){
							echo '<div class="datum">'.date("d.m.Y.", strtotime($r['created'])).'</div>';
						}else{
							echo '<div class="datum"><strong>'.date("d.m.Y.", strtotime($r['created'])).'</strong></div>';
						}
					}
					
					if($show['image']){
						echo '<div class="slika"><a href="'.$table.'_unos.php?action=update&id='.$r['id'].'"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.$slika.'&w=40&h=40&zc=1" alt="" /></a></div>';
					}
					
					echo '<div class="clearfix"></div></li>';
					
				}
				else
				{
					echo '<li class="level-'.$level.'" id="cat_id_'.$r['id'].'">';
					if($show['title']){ 						
						echo '<div class="naslov">
								<a href="'.$table.'_unos.php?action=update&id='.$r['id'].'">'.$r['title_hr'].'</a>
								<div class="opcije">
									<a href="'.$table.'_unos.php?action=update&id='.$r['id'].'" class="edit" title="Uredi"><img src="images/icon-edit.png" alt="Uredi" /></a> 
									<a href="javascript:;" class="delete" title="Izbriši" onclick="if(confirm(\'Dali ste sigurni da želite obrisati kategoriju '.$r['title_hr'].'?\n\r\n\rOprez, brisanjem ove kategorije obrisati će te i sve podkategorije i proizvode koje ova kategorija sadrži!\')) sjx(\'del_category\','.$r['id'].', \''.$table.'\'); return false;"><img src="images/icon-delete.png" alt="Izbriši" /></a>
								</div>
								<div class="clearfix"></div>
							</div>';
					}
					
					if($show['date']){
						if(date("d.m.Y") >= date("d.m.Y", strtotime($r['created']))){
							echo '<div class="datum">'.date("d.m.Y.", strtotime($r['created'])).'</div>';
						}else{
							echo '<div class="datum"><strong>'.date("d.m.Y.", strtotime($r['created'])).'</strong></div>';
						}
					}
					
					if($show['image']){
						echo '<div class="slika"><a href="'.$table.'_unos.php?action=update&id='.$r['id'].'"><img src="'._SITE_URL.'lib/plugins/thumb.php?src='._SITE_URL.$slika.'&w=40&h=40&zc=1" alt="" /></a></div>';
					}
												
					echo '<div class="clearfix"></div></li>';
				}
				
				display_children($table, $r['id'], $level+1, $show, $order);
			}
			
			if($level > 0)
			{
			echo '</ul>';
			}
			
		}else{
			if($level == 0){
				echo '<li class="no-sort"><div class="no_entry">Trenutno nemate unesenu niti jednu kategoriju.</div></li>';
			}			
		}			
	}
	display_children($table, 0, 0, $show, $order);
	?>
</ul>
		
<?php include('include/php/footer.php'); ?>
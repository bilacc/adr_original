<?php 
	include('include/php/header.php');
	
	$table = 'grijanje';
	
	$show['title'] = true;
	$show['date'] = false;
	$show['image'] = false;
	$show['front_page'] = false;
	$show['status'] = false;
	$show['category'] = false;
	$show['cat_filter'] = false;
	$show['pagination'] = true;
	
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
		$order = ' orderby DESC';
	}
	
	if(isset($_GET['cat_id']) && $_GET['cat_id'] > 0)
	{
		$category = ' WHERE categories_id = '.$_GET['cat_id'];
	}else{
		$category = '';
	}
	
	
	if($show['pagination']){
		$on_page = $_SESSION['on-page'];
		
		if(isset($_GET['page']) && $_GET['page'] > 1)
		{
			$start = ($_GET['page'] - 1) * $on_page;
			$stranica = $_GET['page'];
		}else{
			$start = 0;
			$stranica = 1;
		}
		
		$data = Db::query('SELECT * FROM '.$table.$category.' ORDER BY'.$order.' LIMIT '.$start.','.$on_page);
		
		$broj_unosa = Db::query_one('SELECT COUNT(id) FROM '.$table);
		$broj_stranica = (ceil($broj_unosa/$on_page));
	}else{
		$data = Db::query('SELECT * FROM '.$table.$category.' ORDER BY'.$order);
	}
	
	if( isset($_GET['status']) && $_GET['status'] == 'success' )
	{
		echo '<div class="success">Uspješno ste spremili podatke.</div>';
	}
	elseif( isset($_GET['status']) && $_GET['status'] == 'delete_ok')
	{
		echo '<div class="success">Uspješno obrisali podatke.</div>';
	}
		
	$cat = new Admin_ManageCategories($data['id'],'categories');
?>
	<h1>Pregled grijanja <br/><a href="<?php echo $table; ?>_unos.php" class="gumb">Dodaj novo</a></h1>
	
	<?php if($show['cat_filter'] || $show['pagination']){ ?>
	<a href="javascript:;" class="filter-toggle">Postavke pregleda</a>
	<div class="filters">
		<?php if($show['cat_filter']){ ?>
		<select onchange="window.location.href='<?php echo _SITE_URL.'admin/'.$table.'_pregled.php';?>?page=1&cat_id='+this.options[this.selectedIndex].value">
			<option value="0">Sortiraj po kategoriji</option>
			<?php echo $cat->display_tree_select(16,0,$_GET['cat_id']); ?>
		</select> 
		<?php } ?>
		
		<?php if($show['pagination']){ ?>
		<label for="on-page">Redova:</label>
		<input type="text" class="short" name="on-page" id="on-page" value="<?php echo $_SESSION['on-page']; ?>" onchange="sjx('on_page', $(this).val(), '<?php echo $table; ?>_pregled.php');return false;" />
		<span class="broj-unosa"><?php echo $broj_unosa; ?> unos<?php echo ($broj_unosa > 1)? 'a':''; ?></span>
		
		<?php 
		if($broj_stranica > 1)
		{
		$to_remove = 'page='.$_GET['page'];
		$nastavak = str_replace($to_remove, '', $_SERVER['argv'][0]);
		?>
			<ul class="pagination">
				<?php
				if($stranica > 1)
				{
					echo '<li><a href="'.$table.'_pregled.php?page='.($stranica - 1).$nastavak.'">&laquo;</a></li>';
				}
				if($broj_stranica < 5)
				{
					for($i = 1; $i <= $broj_stranica; $i++)
					{
						echo '<li><a href="'.$table.'_pregled.php?page='.$i.$nastavak.'" ';
							if($i == $stranica) echo 'class="pslc"';
						echo '>'.$i.'</a></li>';
					}
				}else{
					if($stranica == 1)
					{
						echo '<li><a href="'.$table.'_pregled.php?page=1'.$nastavak.'" class="pslc">1</a></li>';
						echo '<li><a href="'.$table.'_pregled.php?page=2'.$nastavak.'">2</a></li><li>...</li>';
						echo '<li><a href="'.$table.'_pregled.php?page='.$broj_stranica.$nastavak.'">'.$broj_stranica.'</a></li>';
					}elseif($stranica == $broj_stranica){
						echo '<li><a href="'.$table.'_pregled.php?page=1'.$nastavak.'">1</a></li><li>...</li>';
						echo '<li><a href="'.$table.'_pregled.php?page='.($broj_stranica - 1).$nastavak.'">'.($broj_stranica - 1).'</a></li>';
						echo '<li><a href="'.$table.'_pregled.php?page='.$broj_stranica.$nastavak.'" class="pslc">'.$broj_stranica.'</a></li>';
					}else{
						echo '<li><a href="'.$table.'_pregled.php?page=1'.$nastavak.'">1</a></li><li>...</li>';
						echo '<li><a href="'.$table.'_pregled.php?page='.$stranica.$nastavak.'" class="pslc">'.$stranica.'</a></li>';
						echo '<li>...</li><li><a href="'.$table.'_pregled.php?page='.$broj_stranica.$nastavak.'">'.$broj_stranica.'</a></li>';
					}
				}
				if($stranica < $broj_stranica)
				{
					echo '<li><a href="'.$table.'_pregled.php?page='.($stranica + 1).$nastavak.'">&raquo;</a></li>';
				}
				?>			
			</ul>
		<?php }
		} ?>
	</div>
	<?php } ?>

	<div class="table-wrapper">
		<input type="hidden" id="table" value="<?php echo $table; ?>" />
		<table class="list">
			<thead>
				<tr>
					<?php if($show['title']){ ?><th class="naslov"><a href="<?php echo $table; ?>_pregled.php?order=<?php echo($_GET['order'] == 'title1')? 'title2':'title1'; ?>">Naslov</a></th><?php } ?>
					<?php if($show['category']){ ?><th>Kategorija</th><?php } ?>
					<?php if($show['front_page']){ ?><th>Izdvojeno</th><?php } ?>
					<?php if($show['date']){ ?><th><a href="<?php echo $table; ?>_pregled.php?order=<?php echo($_GET['order'] == 'date1')? 'date2':'date1'; ?>">Datum</a></th><?php } ?>
					<?php if($show['image']){ ?><th class="slika">Slika</th><?php } ?>
				</tr>
			</thead>
			
			<tbody>
				<?php
			if($data){
				foreach($data as $k => $v)
				{
				$slika = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "'.$table.'" AND table_id = '.$v['id']);
				$slika = ($slika)? 'upload_data/site_photos/th_'.$slika : 'admin/images/default.jpg';
				
				$kat = Db::query_one('SELECT title_hr FROM categories WHERE id = '.$v['categories_id']);
				
				if(!$kat)
				{
					$kat = '(nije dodijeljena)';
				}
				
				switch($v['status'])
				{
					case 'da':
						$status = 'Aktivno';
						break;
					case 'ne':
						$status = '<span class="red">Neaktivno</span>';
						break;
					case 'zakazano':
						$status = '<strong>Zakazano</strong>';
						break;
				}
				
				?>
					<tr id="row_<?php echo $v['id']; ?>">
						<td class="naslov">
							<a href="<?php echo $table; ?>_unos.php?action=update&id=<?php echo $v['id']; ?>"><?php echo $v['title_hr']; ?></a>
							<div class="opcije">
								<a href="<?php echo $table; ?>_unos.php?action=update&id=<?php echo $v['id']; ?>" class="edit" title="Uredi"><img src="images/icon-edit.png" alt="Uredi" /></a> 
								<a href="javascript:;" class="delete" title="Izbriši" onclick="if(confirm('Jeste li sigurni da želite obrisati zapis?')){ sjx('del_entry','<?php echo $table; ?>',<?php echo $v['id']; ?>) }"><img src="images/icon-delete.png" alt="Izbriši" /></a>
							</div>
						</td> 
					</tr>
				<?php
				}
			}else{
			?>
				<tr class="no-sort"><td colspan="6" class="no_entry">Trenutno nemate unesenih podataka u bazi.</td></tr>
			<?php } ?>						
			</tbody>
		</table>
	</div>
		
<?php include('include/php/footer.php'); ?>
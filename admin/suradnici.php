<?php 
	require_once('../lib/functions.php');
			
	$crud = new Admin_Crud;
	$crud->table = 'single';
	$crud->title_row_name = 'title_hr';
	$crud->return_url = 'suradnici.php';
	
	$show['title'] = true; 				//naslov
	$show['text'] = true; 				//tekst
	$show['front_page'] = false; 		//izdvajanje na naslovnicu ili gdje se već poveže na frontu
	$show['status'] = false; 			//mogućnost postavljanja statusa (aktivno, neaktivno, zakazano)
	$show['date'] = false; 				//datum
	$show['expires'] = false; 			//mogućnost postavljanja datuma do kojeg će se neka stranica, članak prikazivat na stranici
	$show['single_category'] = false; 	//mogućnost pridodavanja stranice ili članka u jednu kategoriju
	$show['multiple_category'] = false; //mogućnost pridodavanja stranice ili članka u više kategorija (imploda array u string i sprema u multi_categories)
	$show['video'] = false; 				//polje za upis linka od videa
	$show['video_title'] = false;		//naslov videa
	$show['gmap'] = false; 				//google map
	$show['featured_img'] = true; 		//izdvojena slika (bivši static image, ali se drugačije sprema)		
	$crud->files_num = 0; 			// broj fajlova koje unosimo, za neograničeno upiši: null
	$crud->files_titles = false; 		// dali fajlovi trebaju naslov	
	$crud->img_num = null; 				// broj slika koje unosimo, za neograničeno upiši: null
	$crud->img_sizes = array(400,300); 	// veličine slika koje unosimo, ako ima samo 2 broja, slika se neće resizati prilikom uplouda
	$crud->img_titles = false; 			// dali slike trebaju naslov
	

	$cn = lang_data($crud->table, 'title\_');
	$column_name = $cn['column_name'];
	$lang_label = $cn['lang_label'];
		
	
	$id = 9;
	
	$crud->id = $id;
	$data_all = $crud->get_data(); // dohvaća podatke
	
	$data = $data_all['data'];
	$imgs = $data_all['imgs'];
	
	for($i = 0; $i < sizeof($column_name); $i++)
	{
		$files[strtolower($lang_label[$i])] = $data_all['files'][strtolower($lang_label[$i])];
	}
	
	if( isset($_POST) && count($_POST) > 0 ) // ako je POST onda spremamo podatke, unosimo ili updateamo
	{
		if( isset($_POST['e_id']) && (int)$_POST['e_id'] > 0 )
		{
			$crud->id = (int)$_POST['e_id'];
			$crud->action = 'update';
		}
		
		$crud->save_data($_POST);
	}
	
	$cat = new Admin_ManageCategories($data['id'],'categories', $crud->table);
	
	$_SESSION['images'] = null;
	$_SESSION['images']['cnt'] = 1;
	
	for($i = 0; $i < sizeof($column_name); $i++)
	{
		$_SESSION['file-'.strtolower($lang_label[$i])] = null;
		$_SESSION['file-'.strtolower($lang_label[$i])]['cnt'] = 1;
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
	
	<form name="<?php echo $crud->table; ?>" id="<?php echo $crud->table; ?>" class="unos" action="" enctype="multipart/form-data" method="post">
		<input type="hidden" name="e_id" id="e_id" value="<?php echo $crud->id; ?>"/>		
		
		<div class="box-75">
			
		<?php
			if($show['title'])
			{
		?>
			<div class="tabs-title">
				<?php
					if(sizeof($column_name) != 1){
					for($i = 0; $i < sizeof($column_name); $i++){
				?>
						<a href="javascript:;" id="tab_title_<?php echo strtolower($lang_label[$i]);?>" <?php echo ($i == 0)? 'class="slc"':''; ?>>Naslov <span>(<?php echo $lang_label[$i];?>)</span></a>
				<?php
					}
					}
				?>
			</div>
			<div class="tabs-title-content">
				<?php
					if(sizeof($column_name) == 1){
				?>
					<div class="editor-title slc" id="tab_title_hr_content">
						<input type="text" placeholder="Naslov" name="title_hr" id="title_hr" value="<?php echo $data['title_hr']; ?>"/>
					</div>
				<?php }else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
				?>				
				<div class="editor-title <?php echo ($i == 0)? 'slc':''; ?>" id="tab_title_<?php echo strtolower($lang_label[$i]);?>_content">	
					<input type="text" class="" name="title_<?php echo strtolower($lang_label[$i]);?>" id="title_<?php echo strtolower($lang_label[$i]);?>" value="<?php echo isset($data['title_'.strtolower($lang_label[$i])])? $data['title_'.strtolower($lang_label[$i])] : '' ; ?>"/>
				</div>
				<?php
					}
				  }
				?>
			</div>
		<?php
			}
			else
			{
		?>
			<input type="hidden" name="title_hr" id="title_hr" value="<?php echo $data['title_hr']; ?>"/>
		<?php 
			} 
		?>
				
			
			
			<?php
			if($show['text'])
			{
			?>
				<div class="tabs">
				<?php
				if(sizeof($column_name) == 1){
				?>		
					<a href="javascript:;" id="tab_hr" class="slc">Tekst</a>
					<a href="javascript:;" id="tab2_hr">Kratki tekst na naslovnoj</a>
				<?php
				}else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
					?>
					<a href="javascript:;" id="tab_<?php echo strtolower($lang_label[$i]);?>" <?php echo ($i == 0)? 'class="slc"':''; ?>>Tekst <span>(<?php echo $lang_label[$i];?>)</span></a>
					<a href="javascript:;" id="tab2_<?php echo strtolower($lang_label[$i]);?>" <?php echo ($i == 0)? 'class="slc"':''; ?>>Kratki tekst na naslovnoj <span>(<?php echo $lang_label[$i];?>)</span></a>
				<?php
					}
				}
				?>
				</div>
					
				<div class="tabs-content">
				<?php
				if(sizeof($column_name) == 1){
				?>		
					<div class="editor slc" id="tab_hr_content">
						<textarea class="ckeditor" name="text_hr"><?php echo isset($data['text_hr'])? $data['text_hr'] : '' ; ?></textarea>
					</div>
					<div class="editor" id="tab2_hr_content">
						<textarea class="ckeditor" name="text2_hr"><?php echo isset($data['text2_hr'])? $data['text2_hr'] : '' ; ?></textarea>
					</div>
				<?php
				}else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
					?>
					<div class="editor <?php echo ($i == 0)? 'slc':''; ?>" id="tab_<?php echo strtolower($lang_label[$i]);?>_content">
						<textarea class="ckeditor" name="text_<?php echo strtolower($lang_label[$i]);?>"><?php echo isset($data['text_'.strtolower($lang_label[$i])])? $data['text_'.strtolower($lang_label[$i])] : '' ; ?></textarea>
					</div>
				<?php
					}
				}
				?>
				</div>
			<?php
			}
			?>
			
			<?php
			if($crud->img_num > 0 || $crud->img_num === null)
			{
			?>
			<div class="box-100 light">
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
				<h3>Slike <a href="javascript:;" class="gumb-upload" onclick="sjx('open_upload','slike-upload'); return false;">Unesi slike</a></h3>
						
				<div id="images-holder" class="image-sort">
					<?php
					if( $data && count($imgs) > 0 )
					{
						foreach($imgs as $k => $v)
						{
						?>
							<div class="unos-slika" id="img_holder_<?php echo $v['id']; ?>">
								<img src="<?php echo _SITE_URL;?>lib/plugins/thumb.php?src=<?php echo _SITE_URL; ?>upload_data/site_photos/th_<?php echo $v['photo_name']; ?>&w=250&h=188&zc=1" alt="" />
								<a href="javascript:;" onclick="if(confirm('Slika će se trajno izbrisati! Jeste li sigurni da želite obrisati sliku?')){sjx('del_img',<?php echo $v['id']; ?>); return false;}" class="del_img"><img src="images/icon-delete-round.png" alt="Briši" /></a>
								<?php
								if( $crud->img_titles )
								{
									if(sizeof($column_name) == 1){
									?>		
										<input class="no-sort" type="text" name="img_title_hr_<?php echo $v['id']; ?>" value="<?php echo $v['title_hr']; ?>" placeholder="opis slike..."/>
									<?php
									}else{
										for($i = 0; $i < sizeof($column_name); $i++)
										{
										?>
										<input class="no-sort" type="text" name="img_title_<?php echo strtolower($lang_label[$i]); ?>_<?php echo $v['id']; ?>" value="<?php echo $v['title_'.strtolower($lang_label[$i])]; ?>" placeholder="[<?php echo $lang_label[$i]; ?>] opis slike..."/>
									<?php
										}
									}
								}
								?>
							</div>	
						<?php
						}
					}else{
					?>
						<div class="no_entry">Trenutno nemate unesenu niti jednu sliku.</div>
					<?php } ?>
				</div>
			</div>
			<?php
			}
			?>
			
			<?php
			if($crud->files_num > 0 || $crud->files_num === null)
			{
				if(sizeof($column_name) == 1){
				?>		
					<div class="box-100 light">
						<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
						<h3>Dokumenti <a href="javascript:;" class="gumb-upload" onclick="sjx('open_upload','dokumenti-upload-hr'); return false;">Unesi dokumente</a></h3>
						<input type="hidden" name="file_table" class="file_table" value="site_files_hr"/>
						
						<div id="file-hr-holder" class="file-sort">
							<?php
							if( $data && count($files['hr']) > 0 )
							{
								foreach($files['hr'] as $k => $v)
								{
								?>
									<div class="unos-dokument" id="file_holder_hr_<?php echo $v['id']; ?>">
										<a href="../upload_data/site_files/<?php echo $v['file_name']; ?>" target="_blank"><img src="images/doc.png" alt="" /></a>
										<a href="javascript:;" onclick="if(confirm('Dokument će se trajno izbrisati! Jeste li sigurni da želite obrisati dokument?')){sjx('del_file',<?php echo $v['id']; ?>,'hr');return false;}" class="del_img"><img src="images/icon-delete-round.png" alt="Briši" /></a>
										<input class="no-sort" type="text" name="file_title_hr_<?php echo $v['id']; ?>" value="<?php echo ($v['title'] != '')? $v['title']:$v['file_name']; ?>" placeholder="naslov dokumenta..."/>
									</div>	
								<?php
								}
							}else{
							?>
								<div class="no_entry">Trenutno nemate unesen niti jedan dokument.</div>
							<?php
							}
							?>
						</div>
					</div>
				<?php
				}else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
					?>
					<div class="box-100 light file-sort">
						<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
						<h3>Dokumenti <span>(<?php echo $lang_label[$i]; ?>)</span><a href="javascript:;" onclick="sjx('open_upload','dokumenti-upload-<?php echo strtolower($lang_label[$i]); ?>'); return false;" class="gumb-upload">Unesi dokumente</a></h3>
						<input type="hidden" name="file_table" class="file_table" value="site_files_<?php echo strtolower($lang_label[$i]); ?>"/>
						
						<div id="file-<?php echo strtolower($lang_label[$i]); ?>-holder" class="file-sort">
							<?php
							if( $data && count($files[strtolower($lang_label[$i])]) > 0 )
							{
								foreach($files[strtolower($lang_label[$i])] as $k => $v)
								{
								?>
									<div class="unos-dokument" id="file_holder_<?php echo strtolower($lang_label[$i]); ?>_<?php echo $v['id']; ?>">
										<a href="../upload_data/site_files/<?php echo $v['file_name']; ?>" target="_blank"><img src="images/doc.png" alt="" /></a>
										<a href="javascript:;" onclick="if(confirm('Dokument će se trajno izbrisati! Jeste li sigurni da želite obrisati dokument?')){sjx('del_file',<?php echo $v['id']; ?>, '<?php echo strtolower($lang_label[$i]); ?>');return false;}" class="del_img">
											<img src="images/icon-delete-round.png" alt="Briši" />
										</a>
										<input class="no-sort" type="text" name="file_title_<?php echo strtolower($lang_label[$i]); ?>_<?php echo $v['id']; ?>" value="<?php echo ($v['title'] != '')? $v['title']:$v['file_name']; ?>" placeholder="naslov dokumenta..."/>
									</div>	
								<?php
								}
							}else{
							?>
								<div class="no_entry">Trenutno nemate unesen niti jedan dokument.</div>
							<?php
							}
							?>
						</div>
					</div>
					<?php
					}
				}
			}
			?>
					
			<?php
			if($show['gmap']){
			?>
			<div class="box-100 light">
				<h3>Lokacija</h3>
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
				<script>
					$(document).ready(function() {
						start_gmap();
					});
				</script>
				<div id="map1" style="width:100%; height:350px;"></div>
				<input id="gmap_lat_1" value="<?php echo ($data['gmap_lat_1'] != '')? $data['gmap_lat_1']:'45.79649398143752'; ?>" type="hidden" name="gmap_lat_1"/>
				<input id="gmap_lon_1" value="<?php echo ($data['gmap_lon_1'] != '')? $data['gmap_lon_1']:'15.982704162597656'; ?>" type="hidden" name="gmap_lon_1"/>
			</div>
			<?php
			}
			?>
			
		</div>
		<?php
		if($show['status'] || $show['date']){
		?>
		<div class="box-25 light">
			<h3>Status</h3>
			<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
			<?php
			if($show['status']){
			?>
			<select name="status" id="status">
				<option value="da" <?php echo ($data['status'] == 'da')? 'selected="selected"':'';?>>Aktivno</option>
				<option value="ne" <?php echo ($data['status'] == 'ne')? 'selected="selected"':'';?>>Neaktivno</option>
				<?php if(time() < strtotime($data['created'])){ ?>
					<option value="zakazano" selected="selected">Zakazano</option>
				<?php } ?>
			</select>
			<?php
			}
			?>			
			
			<?php
			if($show['date']){
			?>
			<h4><?php echo (time() >= strtotime($data['created']))? 'Objavljeno':'Zakazano'; ?>:</h4>
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
			if($show['expires']){
			?>
			<h4>Traje do:</h4>
			
			<div class="datum">
				<select name="expires_dan" id="">
					<option value="0" <?php echo (date("d.m.Y", strtotime($data['expires'])) == '1.1.1970')? 'selected="selected"':'';?>>-</option>
					<?php
					for($i = 1; $i <= 31; $i++){
					?>
					<option value="<?php echo $i;?>" <?php echo (date("d", strtotime($data['expires'])) == $i && date("Y", strtotime($data['expires'])) != 1970)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php } ?>
				</select>
				
				<select name="expires_mjesec" id="">
					<option value="0" <?php echo (date("d.m.Y", strtotime($data['expires'])) == '1.1.1970')? 'selected="selected"':'';?>>-</option>
					<?php
					for($i = 1; $i <= 12; $i++){
					?>
					<option value="<?php echo $i;?>" <?php echo (date("m", strtotime($data['expires'])) == $i && date("Y", strtotime($data['expires'])) != 1970)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php } ?>
				</select>
				
				<select name="expires_godina" id="">
					<option value="0" <?php echo (date("d.m.Y", strtotime($data['expires'])) == '1.1.1970')? 'selected="selected"':'';?>>-</option>
					<?php
					for($i = 1990; $i <= 2050; $i++){
					?>
					<option value="<?php echo $i;?>" <?php echo (date("Y", strtotime($data['expires'])) == $i && date("Y", strtotime($data['expires'])) != 1970)? 'selected="selected"':'';?>><?php echo $i;?></option>
					<?php } ?>
				</select>
			</div>
			<?php
			}
			}
			?>
			
		</div>
		<?php } ?>
		
		<?php
		if($show['front_page'] || $show['single_category'] || $show['multiple_category']){
		?>
		<div class="box-25 last light">
			<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
			<h3>Karakteristike</h3>
			
			<?php
			if($show['front_page']){
			?>
			<h4>Izdvojeno na naslovnicu</h4>
			
			<select name="front_page" id="front_page">
				<option value="ne" <?php echo ($data['front_page'] == 'ne')? 'selected="selected"':'';?>>Ne</option>
				<option value="da" <?php echo ($data['front_page'] == 'da')? 'selected="selected"':'';?>>Da</option>
			</select>
			<?php
			}
			?>
			
			
			<?php
			if($show['single_category']){
			?>
			<h4>Kategorija:</h4>
			
			<select name="categories_id" id="categories_id">
				<option value="">-- nije u kategoriji --</option>
				<?php echo $cat->display_tree_select(0,0,$data['categories_id'],3); ?>
			</select> 
			<?php
			}
			?>			
			
			
			<?php
			if($show['multiple_category']){
			$kategorije = Db::query('SELECT title_hr, id FROM categories ORDER BY orderby ASC');
			$checked = explode(",", $data['multi_categories']);
			?>
				<h4>Kategorija:</h4>
				
				<input type="checkbox" name="multi_categories[]" id="cat-0" value="0" <?php echo (in_array(0, $checked))? 'checked="checked"':''; ?> onchange="if(this.checked){$('.multi_categories').prop('checked', false);}">
				<label for="cat-0">Nije u kategoriji</label>
				<div class="clearfix"></div>
				<?php
				foreach($kategorije as $kat){
				?>
					<input type="checkbox" class="multi_categories" name="multi_categories[]" id="cat-<?php echo $kat['id']; ?>" value="<?php echo $kat['id']; ?>" <?php echo (in_array($kat['id'], $checked))? 'checked="checked"':''; ?> onchange="if(this.checked){$('#cat-0').prop('checked', false);}">
					<label for="cat-<?php echo $kat['id']; ?>"><?php echo $kat['title_hr']; ?></label>
					<div class="clearfix"></div>	
				<?php 
				} 
			}
			?>
		</div>
		<?php } ?>
		
		<?php
		if($show['featured_img']){
		$featured_img = Db::query_one('SELECT photo_name FROM site_photos WHERE id = '.$data['image']);
		?>
		<div class="box-25 light">
			<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
			<h3>Istaknuta slika</h3>
			
				
			<?php
			if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
			?>
				<a href="javascript:;" onclick="sjx('open_img_selection', '<?php echo $crud->table; ?>', <?php echo $id; ?>);return false;" class="set_featured_img">Postavi istaknutu sliku</a>
			<?php }else{ ?>
				<div class="no_entry" style="font-size:14px;">Stranicu prvo morate spremiti kako bi mogli postaviti istaknutu sliku.</div>
			<?php } ?>
		
			<div id="featured_holder" class="featured-holder">
				<?php
				if($featured_img){
				?>
					<a href="javascript:;" onclick="$('#image').val('0');$('#featured_holder').empty();" class="del_img"><img src="images/icon-delete-round.png" alt="Briši" /></a>
					<img class="featured_img" src="../upload_data/site_photos/th_<?php echo $featured_img; ?>" alt="" />
				<?php } ?>
			</div>
			<input type="hidden" name="image" id="image" value="<?php echo $data['image']; ?>" />
		</div>
		<?php
		}
		?>
		
		<?php
		if($show['video']){
		?>
		<div class="box-25 last light">
			<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
			<h3>Youtube video</h3>
			
			<?php
			if($show['video_title']){
				if(sizeof($column_name) == 1)
				{
				?>
					<h4>Naslov videa</h4>
					<input type="text" name="video_title_hr" id="video_title_hr" value="<?php echo $data['video_title_hr']; ?>">
				<?php
				}else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
					?>
					<h4>Naslov videa <span>(<?php echo $lang_label[$i];?>)</span></h4>
					<input type="text" name="video_title_<?php echo strtolower($lang_label[$i]);?>" id="video_title_<?php echo strtolower($lang_label[$i]);?>" value="<?php echo isset($data['video_title_'.strtolower($lang_label[$i])])? $data['video_title_'.strtolower($lang_label[$i])] : '' ; ?>" />
					<?php
					}
				}
			}
			?>
			<h4>URL</h4>
			<input type="text" name="video_url" id="video_url" value="<?php echo $data['video_url']; ?>">
				
			<?php
			if($data['video_url'] != ''){
			?>
				<iframe width="94%" height="315" src="<?php echo generiraj_youtube_embed_link($data['video_url']); ?>?wmode=transparent" frameborder="0" allowfullscreen></iframe>
			<?php
			}
			?>
			
		</div>
		<?php
		}
		?>
	
		<div class="clearfix"></div>
				
		<div class="save">
			<div class="submit-wrapper">
				<input type="submit" name="spremi3" class="spremi_s" value="Spremi" />
			</div>
		</div>
	</form>
	
	<!-- KRAJ FORME, DALJE SU BOXOVI ZA UPLOAD SLIKA -->
	
	<div class="upload-box slike-upload" style="display:none;">
		<script>
			Dropzone.options.imageUpload = {
				paramName: "images",
				maxFiles: <?php echo ($crud->img_num === null)? 'null':($crud->img_num - count($imgs)); ?>,
				init: function(){
					this.on("complete", function(file){
						if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
							$(".save-uploads").show();
						}
					});
				}
			};
		</script>
		<div class="upload-container">
			<form action="include/php/upload.php" class="dropzone" id="image-upload">
				<div class="fallback">
					Vaš preglednik je zastario. Molimo vas ažurirajte ga kako bi nesmetano mogli prenositi slike i dokumente.
				</div>
			</form>
			<div class="set-image save-uploads" style="display:none;">
				<?php
				if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
				?>
					<a href="javascript:;" onclick="$(this).hide();$('.loader').show();sjx('save_uploads', '<?php echo $crud->table; ?>', <?php echo $crud->id; ?>,  '<?php echo implode("-", $crud->img_sizes); ?>'); return false;">Spremi prenesene dokumente</a>
					<div class="loader" style="display:none;">Pohrana u tijeku...</div>
				<?php }else{ ?>
					<a href="javascript:;" onclick="sjx('preview_uploads', 'images'); return false;">U redu</a>
				<?php } ?>
			</div>
		</div>
		<a href="javascript:;" onclick="sjx('close_upload','slike-upload'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
	</div>
		
		
	<?php
	if(sizeof($column_name) == 1)
	{
	?>
	<div class="upload-box dokumenti-upload-hr" style="display:none;">
		<script>
			Dropzone.options.fileHrUpload = {
				paramName: "file-hr",
				maxFiles: <?php echo ($crud->files_num === null)? 'null':($crud->files_num - count($files['hr'])); ?>,
				init: function(){
					this.on("complete", function(file){
						if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
							$(".save-uploads").show();
						}
					});
				}
			};
		</script>
		<div class="upload-container">
			<form action="include/php/upload.php" class="dropzone" id="file-hr-upload">
				<div class="fallback">
					Vaš preglednik je zastario. Molimo vas ažurirajte ga kako bi nesmetano mogli prenositi slike i dokumente.
				</div>
			</form>
			<div class="set-image save-uploads">
				<?php
				if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
				?>
					<a href="javascript:;" onclick="$(this).hide();$('.loader').show();sjx('save_uploads', '<?php echo $crud->table; ?>', <?php echo $crud->id; ?>,  '<?php echo implode("-", $crud->img_sizes); ?>'); return false;">Spremi prenesene dokumente</a>
					<div class="loader" style="display:none;">Pohrana u tijeku...</div>
				<?php }else{ ?>
					<a href="javascript:;" onclick="sjx('preview_uploads', 'file-hr'); return false;">U redu</a>
				<?php } ?>
			</div>
		</div>
		<a href="javascript:;" onclick="sjx('close_upload','dokumenti-upload-hr'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
	</div>
	<?php
	}else{
		for($i = 0; $i < sizeof($column_name); $i++)
		{
		?>
		<div class="upload-box dokumenti-upload-<?php echo strtolower($lang_label[$i]);?>" style="display:none;">
			<script>
				Dropzone.options.file<?php echo ucfirst(strtolower($lang_label[$i]));?>Upload = {
					paramName: "file-<?php echo strtolower($lang_label[$i]);?>",
					maxFiles: <?php echo ($crud->files_num === null)? 'null':($crud->files_num - count($files[strtolower($lang_label[$i])])); ?>,
					init: function(){
						this.on("complete", function(file){
							if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
								$(".save-uploads").show();
							}
						});
					}
				};
			</script>
			<div class="upload-container">
				<form action="include/php/upload.php" class="dropzone" id="file-<?php echo strtolower($lang_label[$i]);?>-upload">
					<div class="fallback">
						Vaš preglednik je zastario. Molimo vas ažurirajte ga kako bi nesmetano mogli prenositi slike i dokumente.
					</div>
				</form>
				<div class="set-image save-uploads">
				<?php
				if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
				?>
					<a href="javascript:;" onclick="$(this).hide();$('.loader').show();sjx('save_uploads', '<?php echo $crud->table; ?>', <?php echo $crud->id; ?>,  '<?php echo implode("-", $crud->img_sizes); ?>'); return false;">Spremi prenesene dokumente</a>
					<div class="loader" style="display:none;">Pohrana u tijeku...</div>
				<?php }else{ ?>
					<a href="javascript:;" onclick="sjx('preview_uploads', 'file-<?php echo strtolower($lang_label[$i]);?>'); return false;">U redu</a>
				<?php } ?>
			</div>
			</div>
			<a href="javascript:;" onclick="sjx('close_upload','dokumenti-upload-<?php echo strtolower($lang_label[$i]);?>'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
		</div>
	<?php 
		}
	} ?>
	
<?php include('include/php/footer.php'); ?>
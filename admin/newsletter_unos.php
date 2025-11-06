<?php 
	require_once('../lib/functions.php');
			
	$crud = new Admin_Crud;
	$crud->table = 'newsletter';
	$crud->title_row_name = 'title_hr';
	
	$show['title'] = true; 				//naslov
	
	$crud->img_sizes = array(141,141,400,300);
	
	$cn = lang_data($crud->table, 'title\_');
	$column_name = $cn['column_name'];
	$lang_label = $cn['lang_label'];
		
	if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ) // ako je GET znači da trebamo dohvatiti podatke za id koji je naveden
	{
		$id = (int)$_GET['id'];
		
		$crud->id = $id;
		$data_all = $crud->get_data(); // dohvaća podatke
		
		$data = $data_all['data'];
		
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
			
	for($i = 1; $i <= 5; $i++)
	{
		$_SESSION['newsletter_'.$i] = null;
		$_SESSION['newsletter_'.$i]['cnt'] = 1;
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

	<?php
	if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST )
	{
	?>
		<h1>Uređivanje newsletter-a <br/><a href="<?php echo $crud->table; ?>_unos.php" class="gumb">Dodaj novi</a></h1>
	<?php
	}else{
	?>
		<h1>Unos novog newsletter-a</h1>
	<?php } ?>
	
	<form name="<?php echo $crud->table; ?>" id="<?php echo $crud->table; ?>" class="unos" action="" enctype="multipart/form-data" method="post">
		<input type="hidden" name="e_id" id="e_id" value="<?php if( isset($_GET['id']) ){ echo $_GET['id']; } ?>"/>		
		
		<div class="box-75">
			
			<?php
			if($show['title'])
			{
				if(sizeof($column_name) == 1)
				{
				?>
					<label for="title_hr">Naslov</label>
					<input type="text" name="title_hr" id="title_hr" value="<?php echo $data['title_hr']; ?>"/>
				<?php
				}else{
					for($i = 0; $i < sizeof($column_name); $i++)
					{
					?>
					<label for="title_<?php echo strtolower($lang_label[$i]);?>">Naslov <span>(<?php echo $lang_label[$i];?>)</span></label>
					<input type="text" class="" name="title_<?php echo strtolower($lang_label[$i]);?>" id="title_<?php echo strtolower($lang_label[$i]);?>" value="<?php echo isset($data['title_'.strtolower($lang_label[$i])])? $data['title_'.strtolower($lang_label[$i])] : '' ; ?>"/>
					<?php
					}
				}
			}else{
			?>
				<input type="hidden" name="title_hr" id="title_hr" value="<?php echo $data['title_hr']; ?>"/>
			<?php } ?>
			
			<?php
			for($i = 1;$i <= 5;$i++)
			{
			?>			
			<div class="box-100 light">
				<h3>Kartica <?php echo $i; ?></h3>
				<a class="toggle" href="javascript:;">Otvori / Zatvori</a>
				
				<div class="box-75">
					<h4>Naslov</h4>
					<input type="text" name="title<?php echo $i; ?>_hr" id="title<?php echo $i; ?>_hr" value="<?php echo $data['title'.$i.'_hr']; ?>"/>
					
					<h4>Tekst</h4>
					<textarea class="ckeditor" name="text<?php echo $i; ?>_hr" id="text<?php echo $i; ?>_hr"><?php echo $data['text'.$i.'_hr']; ?></textarea>
					
					<h4>Link</h4>
					<input type="text" name="link<?php echo $i; ?>" id="link<?php echo $i; ?>" value="<?php echo $data['link'.$i]; ?>"/>
				</div>
				
				<div class="box-25">
					<h4>Slika</h4>
					<a href="javascript:;" class="gumb-upload" onclick="sjx('open_upload','slike-<?php echo $i; ?>-upload'); return false;" style="margin-top:8px;">Unesi sliku</a>
					
					<div id="newsletter_<?php echo $i; ?>_holder" class="featured-holder" style="margin-top:35px;">
						<?php
						if($data['image'.$i] != ""){
						?>
							<a href="javascript:;" onclick="if(confirm('Slika će se trajno izbrisati! Jeste li sigurni da želite obrisati sliku?')){ $('#image<?php echo $i; ?>').val('');$('#newsletter_<?php echo $i; ?>_holder').empty();sjx('del_newsletter_img','image<?php echo $i; ?>', <?php echo $id; ?>);return false;}" class="del_img"><img src="images/icon-delete-round.png" alt="Briši" /></a>
							<img class="featured_img" src="../upload_data/newsletter_photos/<?php echo $data['image'.$i]; ?>" alt="<?php echo $data['image'.$i]; ?>" />
						<?php } ?>
					</div>
					<input type="hidden" name="image<?php echo $i; ?>" id="image<?php echo $i; ?>" value="<?php echo $data['image'.$i]; ?>"/>
				</div>
			</div>		
			<?php } ?>
		</div>
		
		<div class="clearfix"></div>
				
		<div class="save">
			<div class="submit-wrapper">
				<input type="submit" name="spremi3" class="spremi_s" value="Spremi" />
			</div>
			<div class="submit-wrapper">
				<input type="submit" name="spremi2" class="spremi_pr" value="Spremi i pregledaj sve" />
			</div>
		</div>
	</form>
	
	<!-- KRAJ FORME, DALJE SU BOXOVI ZA UPLOAD SLIKA -->
	
	<?php
	for($i = 1;$i <= 5;$i++)
	{
	?>
	<div class="upload-box slike-<?php echo $i; ?>-upload" style="display:none;">
		<script>
			Dropzone.options.imageUpload<?php echo $i; ?> = {
				paramName: "newsletter_<?php echo $i; ?>",
				maxFiles: 1,
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
			<form action="include/php/upload.php" class="dropzone" id="image-upload-<?php echo $i; ?>">
			</form>
			<div class="set-image save-uploads" style="display:none;">
				<?php
				if( isset($_GET['action']) && isset($_GET['id']) && ! $_POST ){
				?>
					<a href="javascript:;" onclick="$(this).hide();$('.loader').show();sjx('save_uploads', '<?php echo $crud->table; ?>', <?php echo $crud->id; ?>,  '<?php echo implode("-", $crud->img_sizes); ?>'); return false;">Spremi prenesene dokumente</a>
					<div class="loader" style="display:none;">Pohrana u tijeku...</div>
				<?php }else{ ?>
					<a href="javascript:;" onclick="sjx('preview_uploads', 'newsletter_<?php echo $i; ?>'); return false;">U redu</a>
				<?php } ?>
			</div>
		</div>
		<a href="javascript:;" onclick="sjx('close_upload','slike-<?php echo $i; ?>-upload'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
	</div>
	<?php } ?>
	
<?php include('include/php/footer.php'); ?>
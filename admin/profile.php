<?php 
	require_once('../lib/functions.php');
			
	$crud = new Admin_Crud;
	$crud->table = 'admin_users';
	$crud->title_row_name = 'username';
	$crud->return_url = 'profile.php';
	
	$show['featured_img'] = true; 
	
	$crud->img_num = 1;
	$crud->img_sizes = array(400,300); 
	$crud->img_titles = false; 
			
	$id = (int)$_SESSION['admin']['id'];
		
	$crud->id = $id;
	$data_all = $crud->get_data(); // dohvaća podatke
		
	$data = $data_all['data'];
	$imgs = $data_all['imgs'];
	
	
	if( isset($_POST) && count($_POST) > 0 ) // ako je POST onda spremamo podatke, unosimo ili updateamo
	{
		if( isset($_POST['e_id']) && (int)$_POST['e_id'] > 0 )
		{
			$crud->id = (int)$_POST['e_id'];
			$crud->action = 'update';
		}
		
		$crud->save_data($_POST);
	}
		
	$_SESSION['images'] = null;
	$_SESSION['images']['cnt'] = 1;
		
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

	<h1>Uređivanje profila</h1>
	
	<form name="<?php echo $crud->table; ?>" id="<?php echo $crud->table; ?>" class="unos" action="" enctype="multipart/form-data" method="post">
		<input type="hidden" name="e_id" id="e_id" value="<?php echo $id; ?>"/>		
		
		<div class="box-75">
			
			<label for="name">Ime</label>
			<input type="text" name="name" id="name" value="<?php echo $data['name']; ?>"/>
			
			<label for="surname">Prezime</label>
			<input type="text" name="surname" id="surname" value="<?php echo $data['surname']; ?>"/>
			
			<label for="email">E-mail</label>
			<input type="text" name="email" id="email" value="<?php echo $data['email']; ?>"/>
			
			<label for="username">Korisničko ime</label>
			<input type="text" name="username" id="username" value="<?php echo $data['username']; ?>"/>
			
			<label for="password">Lozinka</label>
			<input type="password" name="password" id="password" value="<?php echo $data['password']; ?>"/>
			
		</div>
					
		<?php
		if($show['featured_img']){
		$featured_img = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "'.$crud->table.'" AND table_id = '.$id.' ORDER BY id DESC LIMIT 1');
		?>
		<div class="box-25 light">
			<h3>Slika profila</h3>
			
			<a href="javascript:;" onclick="sjx('open_upload','slike-upload'); return false;" class="set_featured_img">Postavi sliku profila</a>
		
			<div id="featured_holder" class="featured-holder">
				<?php
				if($featured_img){
				?>
					<a href="javascript:;" onclick="if(confirm('Slika će se trajno izbrisati! Jeste li sigurni da želite obrisati sliku?')){sjx('del_avatar_img','<?php echo $crud->table; ?>',<?php echo $id; ?>); $('#featured_holder').empty(); return false;}" class="del_img"><img src="images/icon-delete-round.png" alt="Briši" /></a>
					<img class="featured_img" src="../upload_data/site_photos/th_<?php echo $featured_img; ?>" alt="" />
				<?php } ?>
			</div>
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
			<form action="include/php/upload.php" class="dropzone" id="image-upload">
			</form>
			<div class="set-image save-uploads" style="display:none;">
					<a href="javascript:;" onclick="$(this).hide();$('.loader').show();sjx('save_uploads', '<?php echo $crud->table; ?>', <?php echo $crud->id; ?>,  '<?php echo implode("-", $crud->img_sizes); ?>'); return false;">Spremi prenesene dokumente</a>
					<div class="loader" style="display:none;">Pohrana u tijeku...</div>
			</div>
		</div>
		<a href="javascript:;" onclick="sjx('close_upload','slike-upload'); return false;" class="del_img"><img src="images/icon-delete-round.png" alt="Zatvori" /></a>
	</div>
		
		
	
<?php include('include/php/footer.php'); ?>
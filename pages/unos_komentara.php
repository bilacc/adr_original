<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
<?php 
$insert = false;
$title = _KOMENTARI;
$bg = '<div class="top"><img class="top-img" src="images/kontakt.jpg" /><div class="center"></div></div>';
$c1='';$c2='';$c3='';$c4='';
if(isset($_POST['posalji_upit'])){
	$insert = 'ok';
	if($_POST['ime_comm']==''){
    	$msg .= _IME_ERROR.'<br>';
    	$insert = false;
    	$c1 = 'txt_error';
    }
    if(!$_POST['gdpr']){
    	$msg .= _ERROR_GDPR.'<br>';
    	$insert = false;
    	$c2 = 'txt_error';
    }
    if(!valid_email($_POST['email_comm']) ){
		$msg .= _ERROR_MAIL_NOT_VALID.'<br>';
    	$insert = false;$c3 = 'txt_error';
	}
    if(!$_POST['message_comm']){
    	$msg .= _EMAIL_MSG_KOMENTAR.'<br>';
    	$insert = false;$c4 = 'txt_error';
    }
    if($insert=='ok'){
    	$sql = 'INSERT INTO komentari SET 
					autor_hr  = "'.$_POST['ime_comm'].'",
					email = "'.$_POST['email_comm'].'",
					text_hr = "'.$_POST['message_comm'].'",
					anoniman = "'.$_POST['no-name'].'",
					created = "'.date('Y-m-d H:i:s').'"';
		$q = Db::query($sql);
		$id = Db::insert_id();
		$update_order = '
				UPDATE komentari SET 
					orderby  = "'.$id.'" WHERE id='.$id.';
				';
		$q2 = Db::query($update_order);
		if($q2){
		header('Location: '._SITE_URL._LNG.'/'._URL_KOMENTAR_ZAHVALA);
		exit;	
		}
		
    }
}
?>
<div class="bc row xs">
    <div class="center"><a href=""><?php echo _HOME;?></a><span> | </span><span><?php echo _UNOS_KOMENTARA;?></span></div>
</div>
<div class="row content txt xs">
    <div class="center">
        <h3 style="margin:0"><?php echo _VAS_KOMENTAR?></h3>         
    </div>
</div>

<div class="row content light">
    <div class="center txt">
        <div class="kontakt-forma">
        	<div class="" id="comment_error"></div>
        	<?php 
        	if(isset($_POST['posalji_upit'])){
        		if($insert!='ok'){
	                	echo '<div class="error kontakt-error">'.$msg.'</div>';
	                }
	            }
	        ?>
                        <form id="f_forma" name="comment-forma" method="post" action="">
                            <div class="form-row">
                                <label for="ime_comm"> <?php echo _IME;?></label>
                                <input name="ime_comm" type="text" class="<?php echo $c1?>" id="ime_comm" value="<?php echo $_POST['ime_comm']?>"/>
                            </div>
                            <div class="form-row">
                                <label for="email_comm">E-mail</label>
                                <input name="email_comm" type="text" class="<?php echo $c3?>" id="email_comm" value="<?php echo $_POST['email_comm']?>"/>
                            </div>

                            <div class="form-row ">
                                <label for="message_comm"><?php echo _VAS_KOMENTAR?></label>
                                <textarea rows="6" name="message_comm" id="message_comm" class="<?php echo $c4?>"><?php echo $_POST['message_comm']?></textarea>
                            </div>
                            <div class="form-row">
                                	<p><?php echo _SLAZEM_SE_KOMENTAR; ?></p><p> <?php echo _NASA_PRAVILA?> <a href="<?php echo _SITE_URL._PRAVILA_PDF_URL?>" target="_blank"><strong><?php echo _OVDJE?></strong></a> <br><br><br></p>
                                	
                                </div>
                                <div class="form-row chck">
                                    <input type="checkbox" name="no-name" id="no-name" value="0" <?php echo (in_array(0, $checked))? 'checked="checked"':''; ?> onchange="if(this.checked){$(this).val('1');}else{$(this).val('0');}">
                                    <label for="no-name"> <span></span><?php echo _ZELIM_OSTAT_ANONIMAN?></label>
                                </div>       
                                <div class="clearfix"></div>
                                <div class="form-row chck <?php echo $c2?>">
                                <div class="gdpr-row">
                                	<input id="gdpr" <?php echo ($_POST['gdpr']=='ok')? 'checked="checked"':''; ?> type="checkbox" value="ok" name="gdpr">
                                	<label for="gdpr"><span>
                                	</span><?php echo _SLAZEM_SE_S_PRAVILIMA_PRIVATNOSTI; ?></label>
                                </div>

                                </div>   
                            <div class="form-row ">
                           <div class="button_align">
                    			<input type="hidden" value="ok" id="posalji_upit" class="" name="posalji_upit" placeholder=""> 
                    			<button class="g-recaptcha btn btn-blue aright" data-sitekey="<?php echo _GOOGLE_SITE_KEY?>" name="posalji_upit" data-callback="onSubmit"><?php echo _POSALJITE?></button>
                			</div>
                            </div>
                            
                        </form>
        </div>
	</div>
</div>
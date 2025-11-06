<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
<?php 
$insert = false;
$title = 'Newsletter';
$bg = '<div class="top"><img class="top-img" src="images/kontakt.jpg" /><div class="center"></div></div>';
$c1='';$c2='';$c3='';$c4='';
if(isset($_POST['posalji_upit'])){
	$insert = 'ok';
	if($_POST['ime']==''){
    	$msg .= _IME_ERROR.'<br>';
    	$insert = false;
    	$c1 = 'txt_error';
    }
    if(!$_POST['gdpr']){
    	$msg .= _ERROR_GDPR.'<br>';
    	$insert = false;
    	$c2 = 'txt_error';
    }
    if(!valid_email($_POST['email']) ){
		$msg .= _ERROR_MAIL_NOT_VALID.'<br>';
    	$insert = false;$c3 = 'txt_error';
	}else{
        $is_email = Db::query_one('SELECT id FROM newsletter_email WHERE email = "'.Db::clean($_POST['email']).'" LIMIT 1');
        if($is_email){
            $msg .= _ERROR_MAIL_POSTOJI.'<br>';
            $insert = false;$c3 = 'txt_error';
        }
    }
   
    if($insert=='ok'){
        Db::query();
    	$sql = 'INSERT INTO newsletter_email SET ime = "'.$_POST['ime'].'", email = "'.Db::clean($_POST['email']).'",created = "'.date('Y-m-d H:i:s').'"';
		$q = Db::query($sql);
		if($q){
		header('Location: '._SITE_URL._LNG.'/'._URL_NEWSLETTER_ZAHVALA);
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
        <h3 style="margin:0">Newsletter</h3>         
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
                   
                    <div class="forma">   
                     
                        <form id="f_forma" name="forma" method="post" action="">
                            <div class="form-row">
                                <label for="ime"> <?php echo _IME;?></label>
                                <input name="ime" type="text" class="<?php echo $c1?>" id="ime" value="<?php echo $_POST['ime']?>"/>
                            </div>
                            <div class="form-row">
                                <label for="email">E-mail</label>
                                <input name="email" type="text" class="<?php echo $c3?>" id="email" value="<?php echo $_POST['email']?>"/>
                            </div>
                      <div class="form-row">
                                    <p><?php echo _SLAZEM_SE_NEWSLETTER; ?><br><br></p>
                                </div>
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
</div>
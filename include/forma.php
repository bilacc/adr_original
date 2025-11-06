<?php

####################################################################################################################################
#
#	 sendEmail
#
####################################################################################################################################
function sendEmail($SEND_SETTINGS){
	$greska = false;
	if(is_array($SEND_SETTINGS['provjera'])){
		foreach($SEND_SETTINGS['provjera'] AS $key=>$value){
			# provjerim dali se radi o običnom inputu ili o emailu
			if(strstr($value, "email")){
				if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
			 '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $_POST[$value])){
				$greska = true;$email[$value] = "txt_error";$email[$value.'_slika']="<img src=\"".$SEND_SETTINGS['error_slika']."\" class=\"".$SEND_SETTINGS['error_slika_class']."\" alt=\"".$SEND_SETTINGS['error_slika_alt']."\"/>";
				}#end IF
			}else{
				if(empty($_POST[$value])){
					$greska = true;
					$email[$value] = "txt_error";
					$email[$value.'_slika']="<img src=\"".$SEND_SETTINGS['error_slika']."\" class=\"".$SEND_SETTINGS['error_slika_class']."\" alt=\"".$SEND_SETTINGS['error_slika_alt']."\"/>";
				}#end IF
			}#end ELSE
		}#end FOREACH
	}#end IF

	if($greska){
		$email['action'] = false;
		return $email;
	}else{
		$headers= "MIME-Version: 1.0\n";
		$headers.= "Content-type: text/html; charset=utf-8\n";
		$headers.= "From: ".$_POST['ime_i_prezime']." <".$SEND_SETTINGS['from_email'].">\n";
		$headers.= "X-Sender: <".$SEND_SETTINGS['domena_email'].">\n";
		$headers.= "X-Mailer: Updater <http://www.".$SEND_SETTINGS['domena'].">\n"; 
		$headers.= "Return-Path: <".$SEND_SETTINGS['from_email'].">\n";
		$poruka.= emailTemplate($SEND_SETTINGS);
		
		if(is_array($SEND_SETTINGS['to_email'])){
			foreach($SEND_SETTINGS['to_email'] AS $key=>$value){
				mail($SEND_SETTINGS['domena']." <".$value.">", $SEND_SETTINGS['subject'], $poruka, $headers);
				//mail($SEND_SETTINGS['domena']." <dddario1@yahoo.com>", $SEND_SETTINGS['subject'], $poruka, $headers);
			}#end FOREACH
		}elseif(!empty($SEND_SETTINGS['to_email'])){
			mail($SEND_SETTINGS['domena']." <".$SEND_SETTINGS['to_email'].">", $SEND_SETTINGS['subject'], $poruka, $headers);
			//mail($SEND_SETTINGS['domena']." <dddario1@yahoo.com>", $SEND_SETTINGS['subject'], $poruka, $headers);
		}#end ELSEIF

		$email['action'] = true;
		return $email;
	}#end ELSE
	
}# kraj

####################################################################################################################################





####################################################################################################################################
#
#	 emailTemplate
#
####################################################################################################################################
function emailTemplate($SEND_SETTINGS){
	
	$style_td='padding:6px 10px;';
	$style_text=' font-family:Open Sans,Calibri,sans-serif;font-size:13px;line-height:18px;color:#565656; ';
	$style_naslov= 'font-family:Open Sans,Calibri,sans-serif;font-size:16px;text-transform: uppercase;color:#888;font-weight: 300;margin: 10px 0 0;padding:0 10px;';
	$style_p='font-family:Open Sans,Calibri,sans-serif;font-size:14px;line-height:23px;color:#666;padding:3px 10px;margin: 0;';

	$html.='<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body style="margin: 0; padding: 0;" bgcolor="#FFFFFF">
		<div style="padding:30px 0;width:100%;margin:0 auto;background:#ffffff;">
			<div style="width:100%;max-width:900px;margin:0 auto;">
				<p style="'.$style_p.'">'.$SEND_SETTINGS['uvod_poruka'].'</p><br>
				<h3 style="'.$style_naslov.'">'.$SEND_SETTINGS['naslov_iznad_sadrzaja'].'</h3>
				<table style="width:100%;background:#ffffff;padding:15px 0;">';
				if(is_array($SEND_SETTINGS['popis'])){
					$i=1;
					foreach($SEND_SETTINGS['popis'] AS $key=>$value){
						if($i%2){
							$bg_color = '#F7F7F7';
						}else{
							$bg_color = '#fff';
						}
						$html.='<tr bgcolor="'.$bg_color.'" style="background:'.$bg_color.';">
							<td bgcolor="'.$bg_color.'" style="min-height:30px;background:'.$bg_color.';width: 30%;font-weight:600;text-transform: uppercase; '.$style_text.$style_td.'">'.$key.':</td>
							<td bgcolor="'.$bg_color.'" style="min-height:30px;background:'.$bg_color.';width: 70%; '.$style_text.$style_td.'">'.$_POST[$value].'</td>
					  	</tr>';
						$i++;
					}#end FOREACH
				}#end IF

				$html.='<tr bgcolor="'.$bg_color.'" style="background:'.$bg_color.';">
							<td bgcolor="'.$bg_color.'" style="min-height:30px;background:'.$bg_color.';width: 30%;font-weight:600;text-transform: uppercase; '.$style_text.$style_td.'">GDPR:</td>
							<td bgcolor="'.$bg_color.'" style="min-height:30px;background:'.$bg_color.';width: 70%; '.$style_text.$style_td.'">Korisnik se složio s pravilima i stariji je od 16 godina!</td>
					  	</tr>
					  	</table>
	 		</div>
	 	</div>
	</body>
	</html>';

	return $html;
}# kraj

####################################################################################################################################
?>
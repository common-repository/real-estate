<?php
function rep_current_page(){
	$pageURL = 'http';
	if($_SERVER["HTTPS"]=="on"){$pageURL .= "s";}
	$pageURL .= "://";
	if($_SERVER["SERVER_PORT"]!="80"){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}else{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function rep_contact_form(){
	global $post,$rep_private,$rep_agent_email;
	$agentemail = $rep_agent_email;$name = '';$phone = '';$url = '';$page = '';$info = '';$lang = '';
	if(isset($_POST['rep_cf_name']))$name = ($_POST['rep_cf_name']);
	if(isset($_POST['rep_cf_email']))$email = ($_POST['rep_cf_email']);
	if(isset($_POST['rep_cf_phone']))$phone = ($_POST['rep_cf_phone']);
	if(isset($_POST['rep_cf_url']))$url = ($_POST['rep_cf_url']);//FIXME: put this in $_SESSION...
	$page = rep_current_page();
	if(isset($_POST['rep_cf_info']))$info = ($_POST['rep_cf_info']);
	$error = '';
	if(trim($phone)!=''&&!ereg("^([0-9 \(\)-\/\+\.]{6,15})$",trim($phone)))$error .= 'please include a valid phone number. ';
	if(($email==''||!stristr($email,'@'))&& $phone=='')$error .= 'please include contact info. ';
	$formtext = '';
	if($_SERVER['REQUEST_METHOD'] == "POST"&&$error==''){
		$to = $agentemail;
		$subject = 'Online Web Form';
//		$rep_private = get_post_meta($post->ID, "rep_private", true);
		$body = "
Name: $name
Email: $email
Phone: $phone
Referer: ".urldecode($url)."
Page: $page
Details: $info

";//.nl2br($rep_private)
		$headers = 'From: '. $email . "\r\n" .
		    'Reply-To: '. $email . "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $body, $headers);

		$formtext .= '<p><span class="red">Your information was received. Thank you! You will receive an answer from an agent soon. </span></p>';
	} 
	elseif($_SERVER['REQUEST_METHOD'] == "POST"&&$error!=''){
		$formtext .= '<p style="color:red;"><b>Go back (using the browser button) and '.$error.'</b></p>';
	}
	if(!isset($_POST['submit'])) {
		$formtext .= '
		<form id="rep_cf" action="'.$page.'" method="post"><p>
		<br /><b><span class="underline">Contact us:</span></b><br /><br />
		Name : <input type="text" name="rep_cf_name" id="rep_cf_name" size="25" value="'.$name.'" /><br />
		E-mail: <input type="text" name="rep_cf_email" id="rep_cf_email" size="25" value="'.$email.'" /><br />
		Phone: <input type="text" name="rep_cf_phone" id="rep_cf_phone" size="25" value="'.$phone.'" /><br />
		<input type="hidden" name="rep_cf_url" id="rep_cf_url" size="10" value="';
		if($url)$formtext .= $url; else $formtext .= urlencode($_SERVER['HTTP_REFERER']);
		$formtext .= '" />
		<br />
		<textarea cols="40" rows="10" name="rep_cf_info">'.$info.'</textarea><br />
		<br />
		<input type="submit" id="button1" name="submit" value="Submit" /></p>
		</form>';
	}
	return $formtext;
}
?>

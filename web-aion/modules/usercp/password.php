<?php
/**
 * AionCMS
 * https://aioncms.com
 * 
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2012-2019 Lautaro Angelico, All Rights Reserved
 */
?>
<div class="page-header-block changepwd"></div>
<br /><br />

<h3>Change Password</h3>

<br />

<?php
# Change Password Process
if(isset($_POST['pwd_submit']) && check($_POST['pwd_submit'])) {
	try {
		
		if(!check($_POST['pwd_old'], $_POST['pwd_new'], $_POST['pwd_new_confirm'])) throw new Exception('Please complete all fields.');
		if($_POST['pwd_old'] == $_POST['pwd_new']) throw new Exception('Please choose a different new password.');
		if($_POST['pwd_new'] != $_POST['pwd_new_confirm']) throw new Exception('New passwords did not match, please try again.');
		
		
		$Account = new Account();
		$Account->setId($_SESSION['userid']);
		$accountData = $Account->getAccountData();
		
		if(!is_array($accountData)) throw new Exception('Could not load your account\'s information.');
		if(!$Account->validatePassword($_POST['pwd_old'])) throw new Exception('Your current password is not correct.');
		if(check($accountData['hash'], $accountData['confirmed'])) {
			if(Validator::UnsignedNumber($accountData['hash'])) {
				if(time() > ($accountData['hash']+3600)) {
					# expired password change
					# do nothing
				} else {
					throw new Exception('You have an active password change, please check your email inbox.');
				}
			}
		}
		
		$verificationCode = time();
		$verificationLink = __PAGE_URL__ . 'verification/password/key/' . $verificationCode . '/';
		
		# send verification email
		try {
			
			$email = new Email();
			$email->setTemplate('CHANGE_PWD');
			$email->addVariable('{USERNAME}', $accountData['name']);
			$email->addVariable('{VERIFICATION_LINK}', $verificationLink);
			$email->addAddress($accountData['email']);
			$email->send();
			
		} catch(Exception $ex) {
			throw new Exception('We could not send you the verification email, please contact support.');
		}
		
		# update account data
		$setPasswordChange = $Account->setPasswordChange($_POST['pwd_new'], $verificationCode);
		if(!$setPasswordChange) throw new Exception('There was a problem changing your password, please contact support.');
		
		message('Almost done! We have sent a verification email to <strong>'.$accountData['email'].'</strong>. Follow the instructions given in the email to change your password.', 'success');
		logSystem::add('password change request');
		
		# destroy current session (logout user)
		$_SESSION = array();
		session_destroy();
		
		# redirect home in 10 seconds
		echo '<meta http-equiv="refresh" content="10; url='.__BASE_URL__.'" />';
		
	} catch(Exception $ex) {
		message($ex->getMessage(), 'error');
		logSystem::add('password change error', 5);
	}
}
?>

<form action="<?php module_url(); ?>usercp/password/" method="post">
<table class="login-form">
	<tr>
		<td>Current Password:</td>
		<td><input type="password" name="pwd_old" autofocus/></td>
	</tr>
	<tr>
		<td>New Password:</td>
		<td><input type="password" name="pwd_new" /></td>
	</tr>
	<tr>
		<td>Confirm New Password:</td>
		<td><input type="password" name="pwd_new_confirm" /></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div style="width: 300px;font-size: 12px;">
				After submitting this form we will send you a verification link to <strong><?php echo $_SESSION['email']; ?></strong>. <br /><br />
			</div>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><button type="submit" name="pwd_submit" value="ok">Change Password</button></td>
	</tr>
</table>
</form>
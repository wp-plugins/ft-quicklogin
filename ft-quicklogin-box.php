<?php
/*------------------------------------------------
*  QuickLogin Add Box
*-----------------------------------------------*/
function fql_add_box() {
	$options = get_option('fquicklogin'); ?>

	<!-- Start QuickLogin Box -->
	<div id="fql-box">
		<a href="#" class="fql-close" rel="fql-close"><?php _e('Close Box', 'ft-quicklogin'); ?></a>

		<?php if( $options[box_logo] !== "" && $options[box_logo] !== "http://" ) : ?>
		<img class="fql-logo" src="<?php echo $options[box_logo]; ?>" alt="<?php bloginfo('name'); ?>" />
		<?php endif; ?>

		<?php fql_box_login(); ?>

		<?php if ( get_option( 'users_can_register' ) )
			fql_box_register(); ?>

		<?php fql_box_lostpassword(); ?>

		<?php fql_box_message(); ?>
	</div>
	<!-- End QuickLogin Box -->
<?php }


/*------------------------------------------------
*  QuickLogin Login Box
*-----------------------------------------------*/
function fql_box_login() { ?>
	<div id="fql-box-login">
		<?php if ( is_user_logged_in() ) : ?>
			<p class="message"><?php _e('You are already logged in!', 'ft-quicklogin'); ?></p>
		<?php else : ?>

		<form name="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post">
			<input type="text" name="log" value="<?php _e('Username', 'ft-quicklogin'); ?>" size="20" tabindex="1000" />

			<input type="password" name="pwd" value="<?php _e('Password', 'ft-quicklogin'); ?>" size="20" tabindex="1001" />

			<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" value="forever" tabindex="1002" /> <?php _e('Remember Me', 'ft-quicklogin'); ?></label></p>

			<p class="submit">
				<input type="submit" name="wp-submit" value="<?php _e('Sign In', 'ft-quicklogin'); ?>" tabindex="1003" />
				<input type="hidden" name="redirect_to" value="<?php echo fql_get_current_url() . '#fql-box-message-login'; ?>" />
				<input type="hidden" name="testcookie" value="1" />
			</p>

			<div class="clear"></div>
		</form>
		<?php endif; ?>

		<div class="fql-box-links">
			<?php if ( get_option( 'users_can_register' ) ) : ?><a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('Register', 'ft-quicklogin'); ?></a> | <?php endif; ?><a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>"><?php _e('Lost your password?', 'ft-quicklogin'); ?></a>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Register Box
*-----------------------------------------------*/
function fql_box_register() { ?>
	<div id="fql-box-register">
		<form name="registerform" action="<?php echo site_url('/wp-login.php?action=register'); ?>" method="post">
			<input type="text" name="user_login" value="<?php _e('Username', 'ft-quicklogin'); ?>" size="20" tabindex="1010" />

			<input type="text" name="user_email" value="<?php _e('E-mail', 'ft-quicklogin'); ?>" size="25" tabindex="1011" />

			<p class="reg_passmail"><?php _e('A password will be e-mailed to you.', 'ft-quicklogin'); ?></p>

			<p class="submit">
				<input type="submit" name="wp-submit" value="<?php _e('Register', 'ft-quicklogin'); ?>" tabindex="1012" />
				<input type="hidden" name="redirect_to" value="<?php echo fql_get_current_url() . '#fql-box-message-register'; ?>" />
			</p>

			<div class="clear"></div>
		</form>

		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>"><?php _e('Sign in', 'ft-quicklogin'); ?></a> | <a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>"><?php _e('Lost your password?', 'ft-quicklogin'); ?></a>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Lost Password Box
*-----------------------------------------------*/
function fql_box_lostpassword() { ?>
	<div id="fql-box-lost-password">
		<form name="lostpasswordform" action="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>" method="post">
			<p class="lost_passmail"><?php _e('Please enter your username or email address.', 'ft-quicklogin'); ?><br /><?php _e('You will receive a link to reset the password via email.', 'ft-quicklogin'); ?></p>

			<input type="text" name="user_login" value="<?php _e('Username or E-mail', 'ft-quicklogin'); ?>" size="20" tabindex="1020" />

			<p class="submit">
				<input type="submit" name="wp-submit" value="<?php _e('Get New Password', 'ft-quicklogin'); ?>" tabindex="1021" />
				<input type="hidden" name="redirect_to" value="<?php echo fql_get_current_url() . '#fql-box-message-lostpassword'; ?>" />
			</p>

			<div class="clear"></div>
		</form>

		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>">Sign in</a><?php if ( get_option( 'users_can_register' ) ) : ?> | <a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('Register', 'ft-quicklogin'); ?></a><?php endif; ?>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Message Box
*-----------------------------------------------*/
function fql_box_message() { ?>
	<div id="fql-box-message">
		<p class="message"></p>

		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>"><?php _e('Sign in', 'ft-quicklogin'); ?></a><?php if ( get_option( 'users_can_register' ) ) : ?> | <a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('Register', 'ft-quicklogin'); ?></a><?php endif; ?> | <a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>"><?php _e('Lost your password?', 'ft-quicklogin'); ?></a>
		</div>
	</div>
<?php } ?>
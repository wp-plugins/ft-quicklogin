<?php
/*------------------------------------------------
*  QuickLogin Add Box
*-----------------------------------------------*/
function fql_add_box() {
	$options = get_option('fquicklogin'); ?>

	<!-- Start QuickLogin Box -->
	<div id="fql-box">
		<a href="#" class="fql-close" rel="fql-close">Close Box</a>

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
			<p class="message">You are already logged in!</p>
		<?php else : ?>

		<form name="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post">
			<input type="text" name="log" value="Username" size="20" tabindex="1000" />

			<input type="password" name="pwd" value="Password" size="20" tabindex="1001" />

			<p class="forgetmenot"><label for="rememberme"><input name="rememberme" type="checkbox" value="forever" tabindex="1002" /> Remember Me</label></p>

			<p class="submit">
				<input type="submit" name="wp-submit" value="Sign In" tabindex="1003" />
				<input type="hidden" name="redirect_to" value="<?php echo add_query_arg( 'fql-message', 'login', fql_get_current_url() ); ?>" />
				<input type="hidden" name="testcookie" value="1" />
			</p>

			<div class="clear"></div>
		</form>
		<?php endif; ?>

		<div class="fql-box-links">
			<?php if ( get_option( 'users_can_register' ) ) : ?><a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register</a> | <?php endif; ?><a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>">Lost your password?</a>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Register Box
*-----------------------------------------------*/
function fql_box_register() { ?>
	<div id="fql-box-register">
		<form name="registerform" action="<?php echo site_url('/wp-login.php?action=register'); ?>" method="post">
			<input type="text" name="user_login" value="Username" size="20" tabindex="1010" />

			<input type="text" name="user_email" value="E-mail" size="25" tabindex="1011" />

			<p class="reg_passmail">A password will be e-mailed to you.</p>

			<p class="submit">
				<input type="submit" name="wp-submit" value="Register" tabindex="1012" />
				<input type="hidden" name="redirect_to" value="<?php echo add_query_arg( 'fql-message', 'register', fql_get_current_url() ); ?>" />
			</p>

			<div class="clear"></div>
		</form>

		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>">Sign in</a> | <a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>">Lost your password?</a>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Lost Password Box
*-----------------------------------------------*/
function fql_box_lostpassword() { ?>
	<div id="fql-box-lost-password">
		<form name="lostpasswordform" action="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>" method="post">
			<p class="lost_passmail">Please enter your username or email address.<br />You will receive a link to reset the password via email.</p>

			<input type="text" name="user_login" value="Username or E-mail" size="20" tabindex="1020" />

			<p class="submit">
				<input type="submit" name="wp-submit" value="Get New Password" tabindex="1021" />
				<input type="hidden" name="redirect_to" value="<?php echo add_query_arg( 'fql-message', 'lostpassword', fql_get_current_url() ); ?>" />
			</p>

			<div class="clear"></div>
		</form>

		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>">Sign in</a><?php if ( get_option( 'users_can_register' ) ) : ?> | <a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register</a><?php endif; ?>
		</div>
	</div>
<?php }


/*------------------------------------------------
*  QuickLogin Message Box
*-----------------------------------------------*/
function fql_box_message() {
	if( isset($_GET['fql-message']) && ($_GET['fql-message'] == "login") ) {
		global $current_user;
		get_currentuserinfo();

		$message = "Welcome back <strong>" . $current_user->display_name . "</strong>!";
	} elseif( isset($_GET['fql-message']) && ($_GET['fql-message'] == "logout") )
		$message = "You are now logged out.";
	elseif( isset($_GET['fql-message']) && ($_GET['fql-message'] == "register") )
		$message = "Thanks for register.";
	elseif( isset($_GET['fql-message']) && ($_GET['fql-message'] == "lostpassword") )
		$message = "<strong>Mail Sent!</strong><br />Please check your inbox. If you can't find it check the Spam too.";
	else
		$message = ""; ?>

	<div id="fql-box-message">
		<p class="message"><?php echo $message; ?></p>

		<?php if( isset($_GET['fql-message']) && ($_GET['fql-message'] != "login") ) : ?>
		<div class="fql-box-links">
			<a rel="fql-box-login" href="<?php echo site_url('/wp-login.php'); ?>">Sign in</a><?php if ( get_option( 'users_can_register' ) ) : ?> | <a rel="fql-box-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register</a><?php endif; ?> | <a rel="fql-box-lost-password" href="<?php echo site_url('/wp-login.php?action=lostpassword'); ?>">Lost your password?</a>
		</div>
		<?php endif; ?>
	</div>
<?php } ?>
<?php
/*------------------------------------------------
*  QuickLogin Add Top Bar
*-----------------------------------------------*/
function fql_add_bar() {
	$options = get_option('fquicklogin');

	//Get the content width of the bar
	if( $options[bar_width_type] == "fixed" )
		$content_width = $options[bar_width_size] . "px";
	elseif( $options[bar_width_type] == "fluid" )
		$content_width = "100%";
	else
		$content_width = "990px";

	//Get the number of news
	$posts_number = $options[bar_news_count] != '' ? $options[bar_news_count] : 5;

	//Get the news
	if( $options[bar_news] == "popular" ) {
		$posts_prefix = "Popular article";
		$args = array( 'orderby' => 'comment_count', 'posts_per_page' => $posts_number );
	} elseif( $options[bar_news] == "random" ) {
		$posts_prefix = "Random article";
		$args = array( 'orderby' => 'rand', 'posts_per_page' => $posts_number );
	} elseif( $options[bar_news] == "sticky" ) {
		$posts_prefix = "Featured article";

		$sticky = get_option( 'sticky_posts' );
		rsort( $sticky );
		$sticky = array_slice( $sticky, 0, $posts_number );

		$args = array( 'post__in' => $sticky, 'ignore_sticky_posts' => 1 );
	} else {
		$posts_prefix = "Latest article";
		$args = array( 'posts_per_page' => $posts_number );
	}

	$posts_query = new WP_Query($args); ?>

	<!-- Start QuickLogin Bar -->
	<div id="fql-bar">
		<div id="fql-content" style="width:<?php echo $content_width; ?>">
			<ul id="fql-menu">
				<?php if ( is_user_logged_in() ) : ?>
					<li><a href="<?php echo wp_logout_url(add_query_arg( 'fql-message', 'logout', fql_get_current_url() )); ?>">Log out</a></li>
				<?php else : ?>
					<?php if ( get_option( 'users_can_register' ) ) : ?>
						<li><a rel="fql-register" href="<?php echo site_url('/wp-login.php?action=register'); ?>">Register</a></li>
					<?php endif; ?>
					<li><a rel="fql-login" href="<?php echo site_url('/wp-login.php'); ?>">Sign in</a></li>
				<?php endif; ?>
			</ul>
			<ul id="fql-news">
				<?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
					<li>
						<span><?php echo $posts_prefix; ?>:</span>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
				<?php endwhile; wp_reset_query(); ?>
			</ul>
		</div>
	</div>
	<!-- End QuickLogin Bar -->
<?php }


/*------------------------------------------------
*  QuickLogin Add Custom Colors
*-----------------------------------------------*/
function fql_bar_custom_colors() {
	global $fql_default_settings;
	$options = get_option('fquicklogin');
	
	if( ($fql_default_settings[bar_color_primary] != $options[bar_color_primary]) || ($fql_default_settings[bar_color_secondary] != $options[bar_color_secondary]) )
		echo '
<!-- Start QuickLogin Bar Colors -->
<style type="text/css">
	#fql-bar ul#fql-menu, #fql-bar ul#fql-menu li a, #fql-bar ul#fql-news li, #fql-bar ul#fql-news li a { color: ' . $options[bar_color_primary] . ' }
	#fql-bar ul#fql-menu li a:hover, #fql-bar ul#fql-news li a:hover, #fql-bar ul#fql-news li span, #fql-bar ul#fql-news li span a { color: ' . $options[bar_color_secondary] . ' }
</style>
<!-- End QuickLogin Bar Colors -->' . "\n";
} ?>
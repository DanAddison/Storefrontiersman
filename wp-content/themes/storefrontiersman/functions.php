<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

//  my google fonts:
add_action( 'wp_enqueue_scripts', 'da_google_fonts' );
function da_google_fonts() {
	wp_enqueue_style( 'da-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:300,400" rel="stylesheet"', false ); 
}

// add font awesome brands (some fa is already in the theme eg user, home, cart icons, but not brands):
add_action( 'wp_enqueue_scripts', 'da_font_awesome_enqueue' );
function da_font_awesome_enqueue() {
	wp_enqueue_style( 'font-awesome-5-all', '//use.fontawesome.com/releases/v5.3.1/css/all.css' );
}

// fancybox
add_action( 'wp_enqueue_scripts', 'da_lightbox' );
function da_lightbox() {
	if ( is_page_template( 'template-project.php' ) ) {
	wp_enqueue_script( 'da-lightbox', get_stylesheet_directory_uri() . '/assets/js/jquery.fancybox.js', array(jquery), null, true );
	}
}


function da_move_nav_below_header_image() 
{
remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
remove_action( 'storefront_header', 'storefront_header_cart', 60 );
add_action( 'storefront_before_content', 'storefront_primary_navigation_wrapper', 5);
add_action( 'storefront_before_content', 'storefront_primary_navigation', 5 );
add_action( 'storefront_before_content', 'storefront_header_cart', 5 );
add_action( 'storefront_before_content', 'storefront_primary_navigation_wrapper_close', 5 );
}
add_action( 'init', 'da_move_nav_below_header_image' );

// add hero image to all pages:
/*
add_action ( 'storefront_before_content', 'storefront_page_header' );

function storefront_page_header() {
	if( get_field('hero_image') ) : ?>

	<div class="hero">

		<div class="hero__image" style="background-image: url(<?php the_field('hero_image'); ?>)">
	
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>	
			</header>
				
		</div>

	</div>
		
	<?php endif;
}
*/

// initialise extra new widget areas:
function da_widgets_init() {
	
	register_sidebar( array(
		'id'            => 'above_footer_widget',
		'name'          => __( 'Above Footer', 'text_domain' ),
		'description'   => __( 'Widgets added here will display above all other footer widgets', 'text_domain' ),
		'before_widget' => '<div class="instagram-widget">',
		'after_widget'  => '</div>',
		) );	
}
add_action( 'widgets_init', 'da_widgets_init' );
	
 
// remove 'sort by average rating' from the dropdown on shop pages
add_filter ( 'woocommerce_catalog_orderby', 'da_catalog_orderby', 20);
function da_catalog_orderby( $orderby ){
unset ($orderby['rating']);
unset ($orderby['popularity']);
$orderby['date'] = __('Sort by date: newest to oldest', 'woocommerce');
return $orderby;
}

   
// remove stuff:
add_action( 'init', 'da_remove_storefront_actions' );
function da_remove_storefront_actions() {

	// remove search from header:
	remove_action( 'storefront_header', 'storefront_product_search',	40 );

	// remove secondary menu that usually lives in the header:
	remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );

	// remove some sorting dropdowns
	// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );

	// remove post meta (author card, category, leave a comment link) from posts:
	remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
	remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );

}

// Remove breadcrumbs on non-shop pages
add_action( 'wp', 'wheelbarrow_remove_breadcrumbs' );
function wheelbarrow_remove_breadcrumbs() {
	if( ! is_product() ) {
      remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
    }
}

// remove the sidebars on all pages and archives, except those stated:
// add_action( 'wp', 'da_remove_sidebar_shop_page' );
// function da_remove_sidebar_shop_page() {
// 	if ( ! is_page ( array( 'bio', 'contact' ) )) {		
// 		remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
// 		add_filter( 'body_class', 'da_remove_sidebar_class_body', 10 );
// 	}
// }
// 
// function da_remove_sidebar_class_body( $wp_classes ) {
// 	$wp_classes[] = 'page-template-template-fullwidth-php';
// 	return $wp_classes;
// }

		
// change wording of featured products section from 'we recommend':
// add_filter( 'storefront_featured_products_args', 'da_featured_products' );
// function da_featured_products( $args ){
	// 	$args = array(
		// 		'limit' => 3,
		// 		'columns' => 3,
		// 		'orderby' => 'name',
		// 		'title'	=> __( 'Featured Products', 'storefront' ),
		// 	);
		// 	return $args;
		// }
				
// add horizontal list of product categories on shop archives
function da_archive_categories_list() {
	$args = array(
		'separator' => ' &sol;&sol; ',
		'style' => 'list',
		'taxonomy' => 'product_cat',
		'title_li' => '',
	);
	
	echo '<div class="menu--categories">
	<p>Categories: </p>
	<ul >';
	wp_list_categories( $args );
	echo '</ul></div>';
}
add_action( 'woocommerce_before_shop_loop', 'da_archive_categories_list', 7 );

// remove add to cart button on product archives
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

// remove phone field in checkout:
add_filter( 'woocommerce_checkout_fields', 'da_checkout_fields', 20 );
function da_checkout_fields( $fields ){
	unset( $fields['billing']['billing_phone']);
	return $fields;
}

// Add 'how did you hear about us' feedback to checkout:
add_filter( 'woocommerce_checkout_fields', 'da_hear_about_us', 30 );
function da_hear_about_us ( $fields ){
	$fields['order']['hear_about_us'] = array(
		'type' => 'select',
		'label' => 'How did you hear about us?',
		'options' => array(
			'default' => '--select an option--',
			'wom' => 'Word of mouth',
			'google' => 'Google',
			'social' => 'Social media',
			'print' => 'Print'
		)
		);
	return $fields;
}

// add home icon to handheld footer bar:
add_filter( 'storefront_handheld_footer_bar_links', 'da_add_home_link' );
function da_add_home_link( $links ) {
	$new_links = array(
		'home' => array(
			'priority' => 10,
			'callback' => 'da_home_link',
		),
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

function da_home_link() {
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home' ) . '</a>';
}

// register social menu by filtering the storefront_register_nav_menus and adding our new menu to that array:
add_filter( 'storefront_register_nav_menus', 'da_storefront_register_social_menu');
function da_storefront_register_social_menu($menus){
	$menus['social'] =  __( 'Social Menu', 'storefront' );
	return $menus;
}

// remove footer credits and add my own credits within a new footer that also includes social menu:
add_action( 'init', 'da_custom_footer', 10 );

function da_custom_footer () {
    remove_action( 'storefront_footer', 'storefront_credit', 20 );
		add_action( 'storefront_footer', 'da_sub_footer', 20 );
} 

function da_sub_footer() {
	?>
	<div class="sub-footer">

		<div class="site-legal">
			<p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo( 'name' ) ?></p>
		</div><!-- .site-legal -->

			<nav class="social-navigation" role="navigation" aria-label="<?php esc_html_e( 'Social Navigation', 'storefront' ); ?>">
				<?php
					wp_nav_menu(
						array(
							'theme_location'	=> 'social',
							'fallback_cb'		=> '',
						)
					);
				?>
			</nav><!-- social-navigation -->

		<div class="site-credit">
			<p class="credit">Website by <a href="https://www.danaddisoncreative.com/">Dan Addison</a></p>
		</div><!-- .site-credit -->

	</div><!-- sub-footer -->
	<?php
}

/**
 * Remove customizer sections (commented-out bits allow hiding from non-admin users only)
 */
function da_remove_css_section( $wp_customize ) {
	// $user = wp_get_current_user();

	// if ( ! $user->has_cap( 'manage_options' ) ) {
		$wp_customize->remove_section( 'storefront_typography' );
		$wp_customize->remove_section( 'storefront_footer' );
		$wp_customize->remove_section( 'storefront_buttons' );
		$wp_customize->remove_section( 'storefront_layout' );
		$wp_customize->remove_section( 'storefront_more' );
		$wp_customize->remove_section( 'header_image' );
		$wp_customize->remove_section( 'background_image' );
		$wp_customize->remove_section( 'static_front_page' );
		$wp_customize->remove_section( 'custom_css' );
		$wp_customize->remove_panel( 'widgets' );
	// }
}
add_action( 'customize_register', 'da_remove_css_section', 15 );

// Allow only some core Gutenberg blocks
function wheelbarrow_allowed_block_types() {

	return array(
		'core/heading',
		'core/paragraph',
		'core/list',
		'core/file',
		'core/quote',
		'core/image',
		'core/cover',
		'core/media-text',
		'core/separator',
		'core/shortcode',
		'core-embed/twitter',
		'core-embed/instagram',
		'core-embed/youtube',
		'core-embed/vimeo',

		// allow some blocks from WooCommerce Blocks plugin
		'woocommerce/handpicked-products',
		'woocommerce/featured-product',
		'woocommerce/product-category',
		'woocommerce/products-by-attribute',
	);
}

add_filter( 'allowed_block_types', 'wheelbarrow_allowed_block_types', 10, 2 );

// disable all custom colours in the editor
function nebuloshift_gutenberg_disable_custom_colors() {
	add_theme_support( 'editor-color-palette' );
	add_theme_support( 'disable-custom-colors' );
}
add_action( 'after_setup_theme', 'nebuloshift_gutenberg_disable_custom_colors' );
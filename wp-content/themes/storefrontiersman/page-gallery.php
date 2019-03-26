<?php
/**
 * The template for the main gallery page
 *
 * @package storefront
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'storefront_page_before' ); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php
					/**
					 * Functions hooked in to storefront_page add_action
					 *
					 * @hooked storefront_page_header          - 10
					 * @hooked storefront_page_content         - 20
					 */
					do_action( 'storefront_page' ); ?>

					<?php	if( have_rows('gallery_images') ): ?>

					<div class="gallery">

						<?php	while ( have_rows('gallery_images') ) : the_row(); 
							$image = get_sub_field('image');
							$size = 'medium_large';
							$title = get_sub_field('page_title');
							$link = get_sub_field('page_url');
						?>

						<div class="gallery-item">
							<img src="<?php echo wp_get_attachment_image_url( $image, $size )?>" alt="<?php echo $image['alt']; ?>" class="gallery-item__image">
							<a href="<?php echo $link; ?>" class="gallery-item__overlay">
								<div class="gallery-item__title"><?php echo $title; ?></div>
							</a>
						</div>

						<?php endwhile; ?>

					</div><!-- gallery -->

					<?php	endif;?>

				</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();

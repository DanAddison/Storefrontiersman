<?php
/**
 * The template for the publications page
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

				<?php

				if( have_rows('publication') ):

					while ( have_rows('publication') ) : the_row(); ?>
							
						<div class="publication">
						<?php			
						$imageArray = get_sub_field('image'); // Array returned by Advanced Custom Fields
						$imageAlt = esc_attr($imageArray['alt']); // Grab, from the array, the 'alt'
						$imageSizeURL = esc_url($imageArray['sizes']['medium_large']); //grab from the array, the 'sizes', and from it, the 'large'
						?>
						
						<div class="publication__image alignleft">
						<img src="<?php echo $imageSizeURL;?>" alt="<?php echo $imageAlt; ?>">
						</div><!-- .about-image - main portrait image -->
						
						<div class="publication__details alignright">
						
							<h2 class="publication__title"><?php the_sub_field('title'); ?></h2>
							<p class="publication__author"><?php the_sub_field('author'); ?></p>
							<p class="publication__published"><?php the_sub_field('published'); ?></p>
							<p class="publication__description"><?php the_sub_field('description'); ?></p>
							<?php if( get_sub_field('link') ): ?>
							<a class="publication__link"href="href:<?php the_field('link'); ?>">Link</a>
							<?php endif; ?>
						
						</div><!-- .publication__details -->
						
						</div><!-- .publication -->
							
					<?php endwhile;
					else :
					// no rows found		
				endif; ?>

			</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();

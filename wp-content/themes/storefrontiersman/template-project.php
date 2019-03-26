<?php
/**
 * 
 * Template Name: Project
 * 
 * 
 * The template for displaying all project pages (children of Portfolio page).
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php do_action( 'storefront_page_before' ); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<a href="<?php $parentLink = get_permalink($post->post_parent); echo $parentLink; ?>" title="Go back to the Portfolio page" class="back">&#8592; Back</a>

				<header>
					<?php the_title( '<h1 class="project-title">', '</h1>' ); ?>
				</header>


					<?php
						$fields = get_field_objects();
						
						if( $fields ) : ?>

						<div class="project-meta">

							<p class="project-date"><?php the_field('project_date'); ?></p>
							<p class="project-materials"><?php the_field('project_materials'); ?></p>
							
							<?php if(get_field('project_collaborators')) : ?>
								<p><?php the_field('project_collaborators'); ?></p>
							<?php endif; ?>

						</div><!-- .project-meta info -->

						<div class="project-images">

							<?php			
							$imageArray = get_field('project_featured_image'); // Array returned by Advanced Custom Fields
							$imageAlt = esc_attr($imageArray['alt']); // Grab, from the array, the 'alt'
							$imageMediumLargeURL = esc_url($imageArray['sizes']['large']); //grab from the array, the 'sizes', and from it, the 'medium_large'
							?>
							
							<div class="project-featured-image">
								<img src="<?php echo $imageMediumLargeURL;?>" alt="<?php echo $imageAlt; ?>">
							</div><!-- .project-image - main feature image -->

						</div><!-- .project-images container -->

						<div class="project-description">
							<p><?php the_content(); ?></p>
						</div>

						<div class="project-thumbnails-gallery">

							<?php	while ( have_rows('project_thumbnail_gallery') ) : the_row(); 
								$image = get_sub_field('thumbnail_images');
								$thumbnail = 'thumbnail';
								$large = 'large';
								$video = get_sub_field('video_links');
							?>

							<?php if( $video ) : ?>
							<a href="<?php echo ( $video )?>" data-fancybox="group">
								<img src="<?php echo wp_get_attachment_image_url( $image, $thumbnail )?>" alt="<?php echo $image['alt']; ?>" class="thumbnail-image">
							</a>
							<?php else : ?>
							<a href="<?php echo wp_get_attachment_image_url( $image, $large )?>" alt="<?php echo $image['alt']; ?>" data-fancybox="group">
								<img src="<?php echo wp_get_attachment_image_url( $image, $thumbnail )?>" alt="<?php echo $image['alt']; ?>" class="thumbnail-image">
							</a>
							<?php endif; ?>

							<?php endwhile; ?>

						</div><!-- .project-thumbnails-gallery -->

					<?php endif; ?>

				</article><!-- #post-## -->

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();

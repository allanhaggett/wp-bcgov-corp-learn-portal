<?php
/**
 * The template for displaying all pages of the Course content type. This is primarily
 * a copy of Twenty_Twenty_One's single.php but with added stuff in there and a lot of
 * theme-specific stuff deleted.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header alignfull"><header class="entry-header alignfull" style="background: #FFF; padding: 2em 2em 3em 2em;">
		<div class="alignwide">

	<div><a href="/portal/course/">All Courses</a></div>

	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</div>

</header>

<div class="entry-content">

	<div><?php the_terms( $post->ID, 'learning_partner', 'Learning Partner: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'course_category', 'Categories: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'delivery_method', 'Delivery Methods: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'role', 'Roles: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'program', 'Programs: ', ', ', ' ' ); ?></div>

	<?php the_content() ?>

	<div>
	<a style="background: #3a9bd9; color: #F2F2F2; display: block; font-size: 2rem; padding: .25em .5em; text-align: center" 
		href="<?= $post->course_link ?>" 
		target="_blank" 
		rel="noopener">
			Register Here
	</a>
	</div>


</div><!-- .entry-content -->

<footer class="entry-footer default-max-width">

	<?php //the_meta() ?>
	
</footer><!-- .entry-footer -->


</article><!-- #post-<?php the_ID(); ?> -->

	<?php


endwhile; // End of the loop.

get_footer();

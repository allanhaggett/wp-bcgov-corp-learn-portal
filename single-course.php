<?php
/**
 * The template for displaying all pages of the Course content type. This is primarily
 * a copy of Twenty_Twenty_One's single.php but with added stuff in there.
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

	//get_template_part( 'template-parts/content/content-single' );
	?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header alignwide">

	<div>Course</div>

	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	<?php twenty_twenty_one_post_thumbnail(); ?>

</header>

<div class="entry-content">

	<div><?php the_terms( $post->ID, 'course_catagory', 'Categories: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'delivery_method', 'Delivery Methods: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'role', 'Roles: ', ', ', ' ' ); ?></div>
	<div><?php the_terms( $post->ID, 'program', 'Programs: ', ', ', ' ' ); ?></div>

	<?php
	the_content();

	wp_link_pages(
		array(
			'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
			'after'    => '</nav>',
			/* translators: %: page number. */
			'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
		)
	);
	?>
</div><!-- .entry-content -->

<footer class="entry-footer default-max-width">

	<?php the_meta() ?>
	
	<?php twenty_twenty_one_entry_meta_footer(); ?>
</footer><!-- .entry-footer -->

<?php if ( ! is_singular( 'attachment' ) ) : ?>
	<?php get_template_part( 'template-parts/post/author-bio' ); ?>
<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->

	<?php

	if ( is_attachment() ) {
		// Parent post navigation.
		the_post_navigation(
			array(
				/* translators: %s: parent post link. */
				'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentytwentyone' ), '%title' ),
			)
		);
	}

	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

	// Previous/next post navigation.
	$twentytwentyone_next = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' );
	$twentytwentyone_prev = is_rtl() ? twenty_twenty_one_get_icon_svg( 'ui', 'arrow_right' ) : twenty_twenty_one_get_icon_svg( 'ui', 'arrow_left' );

	$twentytwentyone_next_label     = esc_html__( 'Next post', 'twentytwentyone' );
	$twentytwentyone_previous_label = esc_html__( 'Previous post', 'twentytwentyone' );

	the_post_navigation(
		array(
			'next_text' => '<p class="meta-nav">' . $twentytwentyone_next_label . $twentytwentyone_next . '</p><p class="post-title">%title</p>',
			'prev_text' => '<p class="meta-nav">' . $twentytwentyone_prev . $twentytwentyone_previous_label . '</p><p class="post-title">%title</p>',
		)
	);
endwhile; // End of the loop.

get_footer();

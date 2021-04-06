<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();

$description = get_the_archive_description();
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$parent = get_term($term->parent, get_query_var('taxonomy') ); // get parent term
?>

<?php if ( have_posts() ) : ?>

	<header class="page-header alignwide">
	<div>
		<a href="/portal/course_category/<?php echo $parent->slug ?>">
			<?php echo $parent->name ?>
		</a>
	</div>
	<h1><?php echo $term->name ?></h1>
		<?php //the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header><!-- .page-header -->
	<div class="alignwide">
<?php 
// Get a list of all sub-categories and output them as simple links
$catlist = get_categories(
						array(
							'taxonomy' => 'course_category',
							'child_of' => $term->term_id,
							'orderby' => 'id',
							'order' => 'DESC',
							'hide_empty' => '0'
						));

foreach($catlist as $childcat) {
	echo '<a href="/portal/course_category/'. $childcat->slug . '">' . $childcat->name . '</a> | ';
}

//print_r($catlist);
?>
</div>
<div class="alignwide">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article style="background: #F2F2F2; border-radius: 3px; color: #333; margin: 1em 0; padding: 1em;">
		<h3 style="margin-bottom: .5em;"><a style="color: #333;" href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
		<?php the_content() ?>
		<div style="margin: 1em 0;">
		<a style="background: #3a9bd9; color: #F2F2F2; display: block; font-size: 2rem; padding: .25em .5em; text-align: center" 
			href="<?= $post->course_link ?>" 
			target="_blank" 
			rel="noopener">
				Register Here
		</a>
		</div>
		</article>
	<?php endwhile; ?>
</div>
<div style="clear: both">
	<?php twenty_twenty_one_the_posts_navigation(); ?>
</div>
<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>

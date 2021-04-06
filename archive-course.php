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
?>


	<header class="page-header alignwide">
	
		<h1>Courses</h1>
		<p>Courses are combined from our numerous <a href="#">Learning Partners</a></p>
	</header><!-- .page-header -->
	<div class="alignwide">
	<ul>
	<?php 

 wp_list_categories( array(
	 	'taxonomy' => 'course_category',
        'orderby' => 'name',
		'title_li' => ''
    ) );
//print_r($catlist);
?>
</ul>
</div>


<?php get_footer(); ?>

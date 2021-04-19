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
<hr style="margin: 60px 0">
	<?php
$post_type = 'course'; //your post type name here
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$post_args=array(
    'post_type'                => $post_type,
    'post_status'              => 'publish',
    'posts_per_page'           => 300,
    'paged'                    => $paged, 
    'ignore_sticky_posts'      => 0,
    'child_of'                 => 0,
    'parent'                   => 0,
    'orderby'                  => 'name', 
    'order'                    => 'ASC',
    'hide_empty'               => 0,
    'hierarchical'             => 1,
    'exclude'                  => '',
    'include'                  => '',
    'number'                   => '',
    'pad_counts'               => false, 
);
$post_my_query = null;
$post_my_query = new WP_Query($post_args);

if( $post_my_query->have_posts() ) :
?>
    <ul>
        <?php
            while ($post_my_query->have_posts()) : $post_my_query->the_post(); 
            ?>
            <li>
              <a href="<?php echo get_permalink(); ?>">
              <?php the_Title(); ?>
              </a>
           </li>
            <?php
           endwhile;
       ?>
    </ul>
<?php
else :      
    echo '<p>No Courses Found!</p>';   
endif;
wp_reset_query($post_my_query);
the_posts_navigation(); 
?>
</div>


<?php get_footer(); ?>

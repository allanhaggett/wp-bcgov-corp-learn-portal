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
    'pad_counts'               => true, 
);
$post_my_query = null;
$post_my_query = new WP_Query($post_args);
$lastletter = '';

?>

    <div class="alignwide">

	<div class="">
    <?php
    //  wp_list_categories( array(
	//  	'taxonomy' => 'course_category',
    //     'orderby' => 'name',
	// 	'title_li' => '',
    //     'depth' => 1
    // ) );
    $categories = get_terms( 
        'course_category', 
        array('parent' => 0)
     );
    ?>
    <div style="background: #FFF; border-radius: 3px; margin: 1em 0; padding: 1em;">
    <div>
        <?php echo $post_my_query->post_count; ?> 
        courses in 3 top-level categories from 10 
        <a href="/portal/corporate-learning-partners/">Learning Partners</a>
    </div>

    <?php 
    foreach( $categories as $category ):
        if($category->name == "Ministry") continue;
        $category_link = sprintf( 
            '<a href="%1$s" alt="%2$s" class="coursecat">%3$s</a>',
            esc_url( get_category_link( $category->term_id ) ),
            esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
            esc_html( $category->name )
        );
        echo '' . sprintf( esc_html__( '%s', 'textdomain' ), $category_link ) . ' ';
     
    endforeach ?>
    </div>
    
    <div id="courselist">
    <div class="searchbox">
    <input class="search form-control mb-3" placeholder="Search">
    <!-- <div><span id="coursecount"></span> courses</div> -->

    <?php if( $post_my_query->have_posts() ) : ?>

   </div>
    <style>
    .alphabet a {
        background: #F1F1F1;
        border-radius: 3px;
        display: inline-block;
        font-size: .6em;
        margin: 0 .15em;
        padding: .25em .5em;
        text-decoration: none;
    }
    </style>
    <div class="alphabet">
        <a href="#A">A</a>
        <a href="#B">B</a>
        <a href="#C">C</a>
        <a href="#D">D</a>
        <a href="#E">E</a>
        <a href="#F">F</a>
        <a href="#G">G</a>
        <a href="#H">H</a>
        <a href="#I">I</a>
        <a href="#J">J</a>
        <a href="#K">K</a>
        <a href="#L">L</a>
        <a href="#M">M</a>
        <a href="#N">N</a>
        <a href="#O">O</a>
        <a href="#P">P</a>
        <a href="#Q">Q</a>
        <a href="#R">R</a>
        <a href="#S">S</a>
        <a href="#T">T</a>
        <a href="#U">U</a>
        <a href="#V">V</a>
        <a href="#W">W</a>
        <a href="#X">X</a>
        <a href="#Y">Y</a>
        <a href="#Z">Z</a>
    </div> <!-- /.alphabet -->
    
 
    
    
    <div class="list">
        <?php
            while ($post_my_query->have_posts()) : $post_my_query->the_post(); 

            $title = get_the_title();
            $firstletter = substr($title, 0, 1);
            $secondletter = substr($title, 0, 2);
            
                
            if($firstletter != '{' && $firstletter != '(') {            
                if($firstletter != $lastletter) {
                    echo '<h2 id="' . $firstletter . '">' . $firstletter . '</h2>';
                }
            }
            
            ?>
            
            <div class="course">
                <div style="background: #28537d; height: 6px; width: 100%;"></div> 
                <div class="coursename">
                <a  href="<?php echo get_permalink(); ?>">
                    <?= the_title(); ?>
                </a>
                <!-- <a href="#course-<?= $post->ID ?>" class="showdeets">#</a> -->
                </div>
                <div class="details" id="course-<?= $post->ID ?>">
                    <div class="learningpartner">
                        <?php the_terms( $post->ID, 'learning_partner', 'Offered by: ', ', ', ' ' ); ?>
                    </div>
                    <div class="coursedesc">
                        <?php the_content(); ?>
                    </div>
                    <div class="coursecats">
                        <?php the_terms( $post->ID, 'course_category', 'Categories: ', ', ', ' ' ); ?>
                    </div>
                    <div class="coursekeys" style="display:none">
                        <?php the_terms( $post->ID, 'keywords', 'Keywords: ', ', ', ' ' ); ?>
                    </div>
                    <div class="courseregister">
                    <a style="background: #28537d; color: #F2F2F2; font-size: 1.2rem; padding: .5em 1em; text-align: center; text-decoration: none;" 
                        href="<?= $post->course_link ?>" 
                        target="_blank" 
                        rel="noopener">
                            Register Here +
                    </a>
                    </div>
                </div>
           </div> <!-- /.course -->
            <?php
            
            $lastletter = $firstletter;

           endwhile;
       ?>
    </div>
    </div>
<?php
else :      
    echo '<p>No Courses Found!</p>';   
endif;
wp_reset_query($post_my_query);
//the_posts_navigation(); 
?>
</div>
</div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

var courseoptions = {
    valueNames: [ 'coursename', 'coursedesc', 'coursecats', 'coursekeys' ]
};
var courses = new List('courselist', courseoptions);
document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
courses.on('searchComplete', function(){
    //console.log(upcomingClasses.update().matchingItems.length);
    //console.log(courses.update().matchingItems.length);
    document.getElementById('coursecount').innerHTML = courses.update().matchingItems.length;
});

</script>
<?php get_footer(); ?>

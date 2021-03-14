<?php
/*
Plugin Name: BC Gov Corporate Learning Portal
Plugin URI: https://github.com/allanhaggett/wp-bcgov-corp-learn-portal
Description: A gateway to everything that BC Gov has to offer for learning opportunities.
Author: Allan Haggett <allan.haggett@gov.bc.ca>
Version: 1
Author URI: https://learning.gww.gov.bc.ca
*/

/**
 * This plugin enables a custom content type, and several custom taxonomies to along 
 * with it. We'll create a "course" type and associate taxonomies such as Roles, 
 * Programs, and Delivery Methods. We'll set Courses as a 'page' type, so that we can 
 * also leverage parent/child relationships if we want to.
 * We will also enable custom fields to capture information such as "how to register" 
 * on a item-by-item basis, and create custom meta boxes to better manage the UI
 * for admin folx.
 * We provide page templates for the type, both single view and main archives.
 * 
 * There is also system-specific synchronization methods, starting with the 
 * PSA Learning System (ELM).
 * - Make private all courses within the defined "Source System" taxonomy. 
 * - Read a specific feed of courses from a source system
 * - Loop through each one:
 *     - Does the course already exist here? 
 *         - If yes, does anything need updating?
 *             - Update and publish
 *         - If no, simply publish
 *     - If no, create and publish
 * - **Note again that if the course exists in the system, but not the feed,
 *   then we retain the record of there once having been a course from that 
 *   source, but it is kept as "private" so it's removed from public view here. 
 * 
 */

/**
 * Start by defining the course content type, then start tacking on our taxonomies
 */
function my_custom_post_course() {
    $labels = array(
        'name'               => _x( 'Courses', 'post type general name' ),
        'singular_name'      => _x( 'Course', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'course' ),
        'add_new_item'       => __( 'Add New Course' ),
        'edit_item'          => __( 'Edit Course' ),
        'new_item'           => __( 'New Course' ),
        'all_items'          => __( 'All Courses' ),
        'view_item'          => __( 'View Course' ),
        'search_items'       => __( 'Search Courses' ),
        'not_found'          => __( 'No courses found' ),
        'not_found_in_trash' => __( 'No courses found in the Trash' ), 
        'parent_item_colon'  => ’,
        'menu_name'          => 'Courses'
    );
    $args = array(
        'labels'              => $labels,
        'description'         => 'Holds courses and course specific data',
        'public'              => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'capability_type'     => 'page',
        'has_archive'         => true,
        'query_var'           => true,
        'can_export'          => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        
    );
    register_post_type( 'course', $args ); 
}
add_action( 'init', 'my_custom_post_course' );


/**
 * Start applying various taxonomies; start with the methods, 
 * then init them all in one place
 */

/**
 * Source System. Courses can synchronize from multiple different source systems; 
 * e.g. PSA Learning System We use this taxonomy to keep things fresh between them, 
 * so we can update/add/remove courses within each system separately.
 */
function my_taxonomies_source_system() {
    $labels = array(
        'name'              => _x( 'Source Systems', 'taxonomy general name' ),
        'singular_name'     => _x( 'Source Systems', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Source Systems' ),
        'all_items'         => __( 'All Source Systems' ),
        'parent_item'       => __( 'Parent Source System' ),
        'parent_item_colon' => __( 'Parent Source System:' ),
        'edit_item'         => __( 'Edit Source System' ), 
        'update_item'       => __( 'Update Source System' ),
        'add_new_item'      => __( 'Add New Source System' ),
        'new_item_name'     => __( 'New Source System' ),
        'menu_name'         => __( 'Source Systems' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'source_system', 'course', $args );
}

/**
 * Course Categories
 */
function my_taxonomies_course_category() {
    $labels = array(
        'name'              => _x( 'Course Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Course Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Course Categories' ),
        'all_items'         => __( 'All Course Categories' ),
        'parent_item'       => __( 'Parent Course Category' ),
        'parent_item_colon' => __( 'Parent Course Category:' ),
        'edit_item'         => __( 'Edit Course Category' ), 
        'update_item'       => __( 'Update Course Category' ),
        'add_new_item'      => __( 'Add New Course Category' ),
        'new_item_name'     => __( 'New Course Category' ),
        'menu_name'         => __( 'Course Categories' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
    );
    register_taxonomy( 'course_category', 'course', $args );
}

/**
 * Delivery Methods
 */
function my_taxonomies_course_delivery_method() {
    $labels = array(
        'name'              => _x( 'Delivery Methods', 'taxonomy general name' ),
        'singular_name'     => _x( 'Delivery Method', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Delivery Methods' ),
        'all_items'         => __( 'All Delivery Methods' ),
        'parent_item'       => __( 'Parent Delivery Method' ),
        'parent_item_colon' => __( 'Parent Delivery Method:' ),
        'edit_item'         => __( 'Edit Delivery Method' ), 
        'update_item'       => __( 'Update Delivery Method' ),
        'add_new_item'      => __( 'Add New Delivery Method' ),
        'new_item_name'     => __( 'New Delivery Method' ),
        'menu_name'         => __( 'Delivery Methods' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_in_rest' => true,
    );
    register_taxonomy( 'delivery_method', 'course', $args );
}

/** 
 * Course best suited to a role
 */
function my_taxonomies_course_role() {
    $labels = array(
        'name'              => _x( 'Roles', 'taxonomy general name' ),
        'singular_name'     => _x( 'Role', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Roles' ),
        'all_items'         => __( 'All Roles' ),
        'parent_item'       => __( 'Parent Role' ),
        'parent_item_colon' => __( 'Parent Role:' ),
        'edit_item'         => __( 'Edit Role' ), 
        'update_item'       => __( 'Update Role' ),
        'add_new_item'      => __( 'Add New Role' ),
        'new_item_name'     => __( 'New Role' ),
        'menu_name'         => __( 'Roles' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'role', 'course', $args );
}

/** 
 * Course is a part of a larger program or initiative
 */
function my_taxonomies_course_program() {
    $labels = array(
        'name'              => _x( 'Programs', 'taxonomy general name' ),
        'singular_name'     => _x( 'Program', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Programs' ),
        'all_items'         => __( 'All Programs' ),
        'parent_item'       => __( 'Parent Program' ),
        'parent_item_colon' => __( 'Parent Program:' ),
        'edit_item'         => __( 'Edit Program' ), 
        'update_item'       => __( 'Update Program' ),
        'add_new_item'      => __( 'Add New Program' ),
        'new_item_name'     => __( 'New Program' ),
        'menu_name'         => __( 'Programs' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'show_in_rest' => true,
    );
    register_taxonomy( 'program', 'course', $args );
}

/** 
 * Now let's initiate all of those awesome taxonomies!
 */
add_action( 'init', 'my_taxonomies_source_system', 0 );
add_action( 'init', 'my_taxonomies_course_category', 0 );
add_action( 'init', 'my_taxonomies_course_delivery_method', 0 );
add_action( 'init', 'my_taxonomies_course_role', 0 );
add_action( 'init', 'my_taxonomies_course_program', 0 );

/**
 * Now let's make sure that we're using our own customized template
 * so that courses can show the meta data in a customizable fashion.
 *  
 * #TODO extend this to include archive.php for main index page
 * and also taxonomy pages
 * 
 */
function load_course_template( $template ) {
    global $post;

    if ( 'course' === $post->post_type && locate_template( array( 'single-course.php' ) ) !== $template ) {
        /*
         * This is a 'course' page
         * AND a 'single course template' is not found on
         * theme or child theme directories, so load it
         * from our plugin directory.
         */
        return plugin_dir_path( __FILE__ ) . 'single-course.php';
    }

    return $template;
}

add_filter( 'single_template', 'load_course_template' );


function course_menu() {
	add_submenu_page(
		'edit.php?post_type=course',
		__( 'ELM Sync', 'elm-sync' ),
		__( 'ELM Sync', 'elm-sync' ),
		'elm-sync',
		'elm-sync',
		'course_elm_sync'
	);
}
add_action( 'admin_menu', 'course_menu' );

/**
 * Synchronize with the public feed for the PSA Learning System (ELM)
 */
function course_elm_sync() {

	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    echo '<h1>Why hello there.</h1>';
    echo '<h2>If you are seeing this, you have just updated all courses to be "Private" ';
    echo 'and thus not publicly visible.</h2>';
    echo '<p>Please just bear with Allan as we implement this feature.</p>';
    /**
     * First let's make every page private so that if the course is no longer in the catalog, 
     * that it gets removed from the listing here. Note that we're just making these courses
     * private, and NOT deleting them. We're going to loop through the source catalog after 
     * this, and if the post already exists and nothing has changed, then we just make it 
     * published again and move on.
     * 
     * The term_id for the "PSA Learning System" category in the "Source System" taxonomy
     * is 14; you may need to change this value if it changes as we move betwixt platforms.
     */
    $all_posts = get_posts(array(
        'post_type' => 'course',
        'numberposts' => -1,
        'tax_query' => array(
            array(
            'taxonomy' => 'source_system',
            'field' => 'term_id',
            'terms' => 14)
        ))
    );
    foreach ($all_posts as $single_post){
        $single_post->post_status = 'private';
        wp_update_post( $single_post );
    }
    /**
     * Now that all those courses are private, let's grab the public listing of courses from 
     * the PSA Learning System and loop through those, updating existing ones as required 
     * and publishing new ones.
     */
    $feed = file_get_contents('https://learn.bcpublicservice.gov.bc.ca/learningcentre/courses/feed.json');
    $courses = json_decode($feed);
    echo '<div>' . count($courses->items) . ' Courses.</div>';
    foreach($courses->items as $course) {

        if(!empty($course->title)) {
            $new_course = array(
                'post_title' => $course->title,
                'post_type' => 'course',
                'post_status' => 'publish', 
                'post_content' => $course->summary,
                'post_excerpt' => substr($course->summary, 0, 100)
            );
            $existing = post_exists($course->title);
            if($existing) {
                echo 'ID: ' . $existing . ' ' . $course->title . ' ALREADY EXISTS<br>';
                //#TODO check all relevent values to see if any of them have been updated
                // and update the page if necessary
                // $single_post->post_status = 'private';
                // wp_update_post( $single_post );
            } else {
                $post_id = wp_insert_post( $new_course );
                wp_set_object_terms( $post_id, 'PSA Learning System', 'source_system', true);
                $cats = explode(',', $course->tags);
                foreach($cats as $cat) {
                    wp_set_object_terms( $post_id, $cat, 'course_category', true);
                }
                echo $post_id . ' - ' . $course->title . ' Created<br>';
            }

            
        }
    }
}
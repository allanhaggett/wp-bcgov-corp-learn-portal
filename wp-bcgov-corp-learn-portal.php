<?php
/*
Plugin Name: BC Gov Corporate Learning Portal
Plugin URI: https://github.com/allanhaggett/wp-bcgov-corp-learn-portal
Description: A gateway to everything that BC Gov has to offer for learning opportunities.
Author: Allan Haggett <allan.haggett@gov.bc.ca>
Version: 1
Author URI: https://learningcentre.gww.gov.bc.ca
*/

/**
 * This plugin sticks to simply enabling a custom content type, and
 * several custom taxonomies to along with it. We'll create a "course" type,
 * and associate taxonomies such as Roles, Programs, and 
 * Delivery Methods. We'll set Courses a 'page' type, so that we can also leverage
 * parent/child relationships if we want to.
 * We will also enable custom fields to capture information such as "how to register" 
 * on a item-by-item basis, and create custom meta boxes to better manage the UI
 * for admin folx.
 * 
 * Much of this code is copypasta from:
 * https://www.smashingmagazine.com/2012/11/complete-guide-custom-post-types/
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
 * Start applying various taxonomies; start with the methods, then init them all in one place
 */

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
add_action( 'init', 'my_taxonomies_course_delivery_method', 0 );
add_action( 'init', 'my_taxonomies_course_role', 0 );
add_action( 'init', 'my_taxonomies_course_program', 0 );


/**
 * Now let's make sure that we're using our own customized template
 * so that courses can show the meta data in a customizable fashion.
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
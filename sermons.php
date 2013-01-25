<?php
/*
Plugin Name: Sermon Manager for WordPress
Plugin URI: http://www.wpforchurch.com/products/sermon-manager-for-wordpress/
Description: Add audio and video sermons, manage speakers, series, and more. Visit <a href="http://wpforchurch.com" target="_blank">Wordpress for Church</a> for tutorials and support.
Version: 1.5.6
Author: Jack Lamb
Author URI: http://www.wpforchurch.com/
License: GPL2
*/

// Security check to see if someone is accessing this file directly
if(preg_match("#^sermons.php#", basename($_SERVER['PHP_SELF']))) exit();

// Translations
function wpfc_sermon_translations() {
	load_plugin_textdomain( 'sermon-manager', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'wpfc_sermon_translations' );

// Add Images for Custom Taxonomies
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomy-images/taxonomy-images.php';

// Add Options Page
require_once plugin_dir_path( __FILE__ ) . '/includes/options.php';

// Add Entry Views Tracking
require_once plugin_dir_path( __FILE__ ) . '/includes/entry-views.php';

// Define the plugin URL
define('WPFC_SERMONS', plugins_url() . '/sermon-manager-for-wordpress');

// Create sermon Custom Post Type
add_action('init', 'create_wpfc_sermon_types');
function create_wpfc_sermon_types() 
{
  $plugin = WPFC_SERMONS;
  $labels = array(
    'name' => __( 'Sermons', 'sermon-manager'),
    'singular_name' => __( 'Sermon', 'sermon-manager'),
    'add_new' => __( 'Add New', 'sermon-manager'),
    'add_new_item' => __('Add New Sermon', 'sermon-manager'),
    'edit_item' => __('Edit Sermon', 'sermon-manager'),
    'new_item' => __('New Sermon', 'sermon-manager'),
    'view_item' => __('View Sermon', 'sermon-manager'),
    'search_items' => __('Search Sermons', 'sermon-manager'),
    'not_found' =>  __('No sermons found', 'sermon-manager'),
    'not_found_in_trash' => __('No sermons found in Trash', 'sermon-manager'), 
    'parent_item_colon' => '',
    'menu_name' => __( 'Sermons', 'sermon-manager'),
  );

    $sermon_settings = get_option('wpfc_options');
	$archive_slug = $sermon_settings['archive_slug'];
	if(empty($archive_slug)):
		$archive_slug = 'sermons';
	endif;

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'menu_icon' => plugins_url('/img/book-open-bookmark.png', __FILE__),
	'capability_type' => 'post',
    'has_archive' => true, 
    'rewrite' => array( 'slug' => $archive_slug ),
    'hierarchical' => false,
    'supports' => array( 'title', 'comments', 'thumbnail', 'entry-views' )
  ); 
  register_post_type('wpfc_sermon',$args);
}

//create new taxonomies: preachers, sermon series & topics
add_action( 'init', 'create_wpfc_sermon_taxonomies', 0 );
function create_wpfc_sermon_taxonomies()
{

//Preachers
$labels = array(	
	'name' => __( 'Preachers', 'sermon-manager'),
	'singular_name' => __( 'Preacher', 'sermon-manager' ),
	'menu_name' => __( 'Preachers', 'sermon-manager' ),
	'search_items' => __( 'Search preachers', 'sermon-manager' ), 
	'popular_items' => __( 'Most frequent preachers', 'sermon-manager' ), 
	'all_items' => __( 'All preachers', 'sermon-manager' ),
	'edit_item' => __( 'Edit preachers', 'sermon-manager' ),
	'update_item' => __( 'Update preachers', 'sermon-manager' ), 
	'add_new_item' => __( 'Add new preacher', 'sermon-manager' ),
	'new_item_name' => __( 'New preacher name', 'sermon-manager' ), 
	'separate_items_with_commas' => __( 'Separate multiple preachers with commas', 'sermon-manager' ),
	'add_or_remove_items' => __( 'Add or remove preachers', 'sermon-manager' ),
	'choose_from_most_used' => __( 'Choose from most frequent preachers', 'sermon-manager' ),
	'parent_item' => null,
    'parent_item_colon' => null,
);

register_taxonomy('wpfc_preacher','wpfc_sermon', array(
	'hierarchical' => false, 
	'labels' => $labels, 
	'show_ui' => true,
	'query_var' => true,
    'rewrite' => array ( 'slug' => 'preacher' ),
));

//Sermon Series
$labels = array(	
	'name' => __( 'Sermon Series', 'sermon-manager'),
	'graphic' => '',
	'singular_name' => __( 'Sermon Series', 'sermon-manager'),
	'menu_name' => __( 'Sermon Series', 'sermon-manager' ),
	'search_items' => __( 'Search sermon series', 'sermon-manager' ), 
	'popular_items' => __( 'Most frequent sermon series', 'sermon-manager' ), 
	'all_items' => __( 'All sermon series', 'sermon-manager' ),
	'edit_item' => __( 'Edit sermon series', 'sermon-manager' ),
	'update_item' => __( 'Update sermon series', 'sermon-manager' ), 
	'add_new_item' => __( 'Add new sermon series', 'sermon-manager' ),
	'new_item_name' => __( 'New sermon series name', 'sermon-manager' ), 
	'separate_items_with_commas' => __( 'Separate sermon series with commas', 'sermon-manager' ),
	'add_or_remove_items' => __( 'Add or remove sermon series', 'sermon-manager' ),
	'choose_from_most_used' => __( 'Choose from most used sermon series', 'sermon-manager' ),
	'parent_item' => null,
    'parent_item_colon' => null,
);

register_taxonomy('wpfc_sermon_series','wpfc_sermon', array(
	'hierarchical' => false, 
	'labels' => $labels, 
	'show_ui' => true,
	'query_var' => true,
    'rewrite' => array ( 'slug' => 'sermon-series' ),
));

//Sermon Topics
$labels = array(	
	'name' => __( 'Sermon Topics', 'sermon-manager'),
	'singular_name' => __( 'Sermon Topics', 'sermon-manager'),
	'menu_name' => __( 'Sermon Topics', 'sermon-manager' ),
	'search_items' => __( 'Search sermon topics', 'sermon-manager' ), 
	'popular_items' => __( 'Most popular sermon topics', 'sermon-manager' ), 
	'all_items' => __( 'All sermon topics', 'sermon-manager' ),
	'edit_item' => __( 'Edit sermon topic', 'sermon-manager' ),
	'update_item' => __( 'Update sermon topic', 'sermon-manager' ), 
	'add_new_item' => __( 'Add new sermon topic', 'sermon-manager' ),
	'new_item_name' => __( 'New sermon topic', 'sermon-manager' ), 
	'separate_items_with_commas' => __( 'Separate sermon topics with commas', 'sermon-manager' ),
	'add_or_remove_items' => __( 'Add or remove sermon topics', 'sermon-manager' ),
	'choose_from_most_used' => __( 'Choose from most used sermon topics', 'sermon-manager' ),
	'parent_item' => null,
    'parent_item_colon' => null,
);

register_taxonomy('wpfc_sermon_topics','wpfc_sermon', array(
	'hierarchical' => false, 
	'labels' => $labels, 
	'show_ui' => true,
	'query_var' => true,
    'rewrite' => array ( 'slug' => 'topics' ),
));

//Books of the Bible
$labels = array(	
	'name' => __( 'Book of the Bible', 'sermon-manager'),
	'singular_name' => __( 'Book of the Bible', 'sermon-manager'),
	'menu_name' => __( 'Book of the Bible', 'sermon-manager' ),
	'search_items' => __( 'Search books of the Bible', 'sermon-manager' ), 
	'popular_items' => __( 'Most popular books of the Bible', 'sermon-manager' ), 
	'all_items' => __( 'All books of the Bible', 'sermon-manager' ),
	'edit_item' => __( 'Edit book of the Bible', 'sermon-manager' ),
	'update_item' => __( 'Update book of the Bible', 'sermon-manager' ), 
	'add_new_item' => __( 'Add new books of the Bible', 'sermon-manager' ),
	'new_item_name' => __( 'New book of the Bible', 'sermon-manager' ), 
	'separate_items_with_commas' => __( 'Separate books of the Bible with commas', 'sermon-manager' ),
	'add_or_remove_items' => __( 'Add or remove books of the Bible', 'sermon-manager' ),
	'choose_from_most_used' => __( 'Choose from most used books of the Bible', 'sermon-manager' ),
	'parent_item' => null,
    'parent_item_colon' => null,
);

register_taxonomy('wpfc_bible_book','wpfc_sermon', array(
	'hierarchical' => false, 
	'labels' => $labels, 
	'show_ui' => true,
	'query_var' => true,
    'rewrite' => array ( 'slug' => 'book' ),
));
}

//add filter to insure the text Sermon, or sermon, is displayed when user updates a sermon
add_filter('post_updated_messages', 'wpfc_sermon_updated_messages');
function wpfc_sermon_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['wpfc_sermon'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Sermon updated. <a href="%s">View sermon</a>', 'sermon-manager'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'sermon-manager'),
    3 => __('Custom field deleted.', 'sermon-manager'),
    4 => __('Sermon updated.', 'sermon-manager'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Sermon restored to revision from %s', 'sermon-manager'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Sermon published. <a href="%s">View sermon</a>', 'sermon-manager'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Sermon saved.', 'sermon-manager'),
    8 => sprintf( __('Sermon submitted. <a target="_blank" href="%s">Preview sermon</a>', 'sermon-manager'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Sermon scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview sermon</a>', 'sermon-manager'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i', 'sermon-manager' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Sermon draft updated. <a target="_blank" href="%s">Preview sermon</a>', 'sermon-manager'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

// TO DO: Add more help information
//display contextual help for Sermons
add_action( 'contextual_help', 'add_wpfc_sermon_help_text', 10, 3 );

function add_wpfc_sermon_help_text($contextual_help, $screen_id, $screen) { 
  //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('wpfc_sermon' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a sermon:', 'sermon-manager') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify a sermon series if appropriate. This will help your site visitors while browsing sermons.', 'sermon-manager') . '</li>' .
      '<li>' . __('Specify the correct preacher of the sermon.', 'sermon-manager') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the sermon to be published in the future:', 'sermon-manager') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish meta box, click on the Edit link next to Publish.', 'sermon-manager') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', 'sermon-manager') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more help:', 'sermon-manager') . '</strong></p>' .
      '<p>' . __('<a href="http://wpforchurch.com/" target="_blank">Wordpress for Church</a>', 'sermon-manager') . '</p>' ;
  } elseif ( 'edit-sermon' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying on the sermons page.', 'sermon-manager') . '</p>' ;
  }
  return $contextual_help;
}

// Add filter for custom search: includes bible_passage, sermon_description in WordPress search
function wpfc_sermon_search_query( $query ) {
	if ( !is_admin() && $query->is_search ) {
		$query->set('meta_query', array(
			array(
				'key' => 'bible_passage',
				'value' => $query->query_vars['s'],
				'compare' => 'LIKE'
			),
			array(
				'key' => 'sermon_description',
				'value' => $query->query_vars['s'],
				'compare' => 'LIKE'
			)
		)); 
        //$query->set('post_type', 'wpfc_sermon'); 
	};
}
//add_filter( 'pre_get_posts', 'wpfc_sermon_search_query');


// Initialize the metabox class.
add_action( 'init', 'initialize_wpfc_sermon_meta_boxes', 9999 );

function initialize_wpfc_sermon_meta_boxes() {
	require_once plugin_dir_path( __FILE__ ) . '/includes/meta-box/init.php';	
}

// Meta Box
add_filter( 'wpfc_meta_boxes', 'wpfc_sermon_metaboxes' );


// Define the metabox and field configurations.
function wpfc_sermon_metaboxes( array $meta_boxes ) {

	// Service Types
	$service_types = array(
					array( 'name' => 'Adult Bible Class', 'value' => 'Adult Bible Class', ),
					array( 'name' => 'Sunday AM', 'value' => 'Sunday AM', ),
					array( 'name' => 'Sunday PM', 'value' => 'Sunday PM', ),
					array( 'name' => 'Midweek Service', 'value' => 'Midweek Service', ),
					array( 'name' => 'Special Service', 'value' => 'Special Service', ),
					array( 'name' => 'Radio Broadcast', 'value' => 'Radio Broadcast', ),);	
	$service_types = apply_filters('service_types', $service_types);
	
	$meta_boxes[] = array(
		'id'         => 'wpfc_sermon_details',
		'title'      => __('Sermon Details', 'sermon-manager'),
		'pages'      => array( 'wpfc_sermon', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __('Date', 'sermon-manager'),
				'desc' => __('Enter the date the sermon was given. <strong>NOTE: Each sermon must have a date!</strong>', 'sermon-manager'),
				'id'   => 'sermon_date',
				'type' => 'text_date_timestamp',
			),
			array(
				'name'    => __('Service Type', 'sermon-manager'),
				'desc'    => __('Select the type of service.', 'sermon-manager'),
				'id'      => 'service_type',
				'type'    => 'select',
				'options' => $service_types
			),
			array(
				'name' => __('Main Bible Passage', 'sermon-manager'),
				'desc' => __('Enter the Bible passage with the full book names,e.g. "John 3:16-18".', 'sermon-manager'),
				'id'   => 'bible_passage',
				'type' => 'text',
			),
			array(
				'name' => __('Description', 'sermon-manager'),
				'desc' => __('Type a brief description about this sermon, an outline, or a full manuscript', 'sermon-manager'),
				'id'   => 'sermon_description',
				'type' => 'wysiwyg',
				'options' => array(	'textarea_rows' => 7, 'media_buttons' => false,),
			),
		),
	);

	$meta_boxes[] = array(
		'id'         => 'wpfc_sermon_files',
		'title'      => __('Sermon Files', 'sermon-manager'),
		'pages'      => array( 'wpfc_sermon', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __('Location of MP3', 'sermon-manager'),
				'desc' => __('Upload an audio file or enter an URL.', 'sermon-manager'),
				'id'   => 'sermon_audio',
				'type' => 'file',
			),
			array(
				'name' => __('Video Embed Code', 'sermon-manager'),
				'desc' => __('Paste your embed code for Vimeo, Youtube, or other service here', 'sermon-manager'),
				'id'   => 'sermon_video',
				'type' => 'textarea',
			),
			array(
				'name' => __('Sermon Notes', 'sermon-manager'),
				'desc' => __('Upload a pdf file or enter an URL.', 'sermon-manager'),
				'id'   => 'sermon_notes',
				'type' => 'file',
			),
		),
	);
	
	return $meta_boxes;
}

// Plugin Meta Links.
function wpfc_sermon_manager_plugin_row_meta( $links, $file ) {
	static $plugin_name = '';

	if ( empty( $plugin_name ) ) {
		$plugin_name = plugin_basename( __FILE__ );
	}

	if ( $plugin_name != $file ) {
		return $links;
	}

	$link = wpfc_sermon_manager_settings_page_link( __( 'Settings', 'sermon-manager' ) );
	if ( ! empty( $link ) ) {
		$links[] = $link;
	}

	$links[] = '<a href="http://www.wpforchurch.com/support/" target="_blank">' . __( 'Support', 'sermon-manager' ) . '</a>';

	return $links;
}
add_filter( 'plugin_row_meta', 'wpfc_sermon_manager_plugin_row_meta', 10, 2 );


// Settings Page Link.
function wpfc_sermon_manager_settings_page_link( $link_text = '' ) {
	if ( empty( $link_text ) ) {
		$link_text = __( 'Manage Settings', 'sermon-manager' );
	}

	$link = '';
	if ( current_user_can( 'manage_options' ) ) {
		$link = '<a href="' . admin_url( 'edit.php?post_type=wpfc_sermon&page=sermon-manager-for-wordpress/includes/options.php' ) . '">' . esc_html( $link_text ) . '</a>';
	}

	return $link;
}
//create custom columns when listing sermon details in the Admin
add_action("manage_posts_custom_column", "wpfc_sermon_columns");
add_filter("manage_edit-wpfc_sermon_columns", "wpfc_sermon_edit_columns");

function wpfc_sermon_edit_columns($columns) {
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => __('Sermon Title', 'sermon-manager'),
		"preacher" => __('Preacher', 'sermon-manager'),
		"series" => __('Sermon Series', 'sermon-manager'),
		"topics" => __('Topics', 'sermon-manager'),
		"views" => __('Views', 'sermon-manager'),
	);
	return $columns;
}

function wpfc_sermon_columns($column){
	global $post;
	
	switch ($column){
		case "preacher":
			echo get_the_term_list($post->ID, 'wpfc_preacher', '', ', ','');
			break;
		case "series":
			echo get_the_term_list($post->ID, 'wpfc_sermon_series', '', ', ','');
			break;
		case "topics":
			echo get_the_term_list($post->ID, 'wpfc_sermon_topics', '', ', ','');
			break;
		case "views":
			echo wpfc_entry_views_get( array( 'post_id' => $post->ID ) );
			break;			
	}
}


/* 
 * Shortcodes 
 */
require_once plugin_dir_path( __FILE__ ) . '/includes/shortcodes.php';


/* 
 * Template selection 
 */
 
// Check plugin options to decide what to do
$sermonoptions = get_option('wpfc_options');
if ( isset($sermonoptions['template']) == '1' ) { 
	add_filter('template_include', 'sermon_template_include');
	add_filter('template_include', 'preacher_template_include');
	add_filter('template_include', 'series_template_include');
}

// Include template for displaying sermons by Preacher
function sermon_template_include($template) {
		if(get_query_var('post_type') == 'wpfc_sermon') {
			if ( is_archive() || is_search() ) :
				if(file_exists(get_stylesheet_directory() . '/archive-wpfc_sermon.php'))
					return get_stylesheet_directory() . '/archive-wpfc_sermon.php';
				return plugin_dir_path( __FILE__ ) . '/views/archive-wpfc_sermon.php';
			else :
				if(file_exists(get_stylesheet_directory() . '/single-wpfc_sermon.php'))
					return get_stylesheet_directory() . '/single-wpfc_sermon.php';
				return plugin_dir_path( __FILE__ ) . '/views/single-wpfc_sermon.php';
			endif;
		}
		return $template;
}

// Include template for displaying sermons by Preacher
function preacher_template_include($template) {
		if(get_query_var('taxonomy') == 'wpfc_preacher') {
			if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php')) 
				return get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php'; 
			return plugin_dir_path(__FILE__) . '/views/taxonomy-wpfc_preacher.php';	
		}
		return $template;
}

// Include template for displaying sermon series
function series_template_include($template) {
		if(get_query_var('taxonomy') == 'wpfc_sermon_series') {
			if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php'))
				return get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php';
			return plugin_dir_path(__FILE__) . '/views/taxonomy-wpfc_sermon_series.php';
		}
		return $template;
}

// Add scripts only to single sermon pages
add_action('wp_enqueue_scripts', 'add_wpfc_js');
function add_wpfc_js() {

	// Register them all!
	wp_register_script( 'sermon-ajax', plugins_url('/js/ajax.js', __FILE__), array('jquery'), '1.5', false ); 
	wp_register_script('mediaelementjs-scripts', plugins_url('/js/mediaelement/mediaelement-and-player.min.js', __FILE__), array('jquery'), '2.7.0', false);
	wp_register_style('mediaelementjs-styles', plugins_url('/js/mediaelement/mediaelementplayer.css', __FILE__));
	wp_register_style('sermon-styles', plugins_url('/css/sermon.css', __FILE__));
	wp_register_script('bibly-script', 'http://code.bib.ly/bibly.min.js', false, null );
	wp_register_style('bibly-style', 'http://code.bib.ly/bibly.min.css', false, null );
	
	// Load them as needed
	if ('wpfc_sermon' == get_post_type() ) {
		wp_enqueue_script('mediaelementjs-scripts');
		wp_enqueue_style('mediaelementjs-styles');
	}
	$sermonoptions = get_option('wpfc_options');
	if (is_single() && 'wpfc_sermon' == get_post_type() && !isset($sermonoptions['bibly']) == '1') { 
		wp_enqueue_script('bibly-script');
		wp_enqueue_style('bibly-style');
		
		// get options for JS
		$Bibleversion = $sermonoptions['bibly_version'];
		wp_localize_script( 'bibly-script', 'bibly', array( // pass WP data into JS from this point on
			'linkVersion' 				=> $Bibleversion,
			'enablePopups' 				=> true,
			'popupVersion'				=> $Bibleversion,
		));
	}
	if ( !isset($sermonoptions['css']) == '1') { 
		wp_enqueue_style('sermon-styles');
	}
	
	// Add ajax for pagination if shortcode is present in the content
	global $wp_query;
	global $post;
	if($post) {
	if (  false !== strpos($post->post_content, '[sermons') ) {	
		wp_enqueue_script('sermon-ajax');
		}
	}	
}


// Add the number of sermons to the Right Now on the Dashboard
add_action('right_now_content_table_end', 'wpfc_right_now');
function wpfc_right_now() {
    $num_posts = wp_count_posts('wpfc_sermon');
    $num = number_format_i18n($num_posts->publish);
    $text = _n('Sermon', 'Sermons', intval($num_posts->publish));
    if ( current_user_can('edit_posts') ) {
        $num = "<a href='edit.php?post_type=wpfc_sermon'>$num</a>";
        $text = "<a href='edit.php?post_type=wpfc_sermon'>$text</a>";
    }
    echo '<td class="first b b-sermon">' . $num . '</td>';
    echo '<td class="t sermons">' . $text . '</td>';
    echo '</tr>';
}

/**
 * Images for Series and Speakers
 */
function wpfc_sermon_images() {
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'sermon_small', 75, 75, true ); 
		add_image_size( 'sermon_medium', 300, 200, true ); 
		add_image_size( 'sermon_wide', 940, 350, true ); 
	}
}
add_action("admin_init", "wpfc_sermon_images");

/**
 * Recent Sermons Widget
 */
class WP4C_Recent_Sermons extends WP_Widget {

	function WP4C_Recent_Sermons() {
		$widget_ops = array('classname' => 'widget_recent_sermons', 'description' => __( 'The most recent sermons on your site', 'sermon-manager') );
		parent::__construct('recent-sermons', __('Recent Sermons', 'sermon-manager'), $widget_ops);
		$this->alt_option_name = 'widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_sermons', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Sermons', 'sermon-manager') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array(
				'post_type' => 'wpfc_sermon', 
				'meta_key' => 'sermon_date',
                'meta_value' => date("m/d/Y"),
                'meta_compare' => '>=',
                'orderby' => 'meta_value',
                'order' => 'DESC',
				'posts_per_page' => $number, 
				'no_found_rows' => true, 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul>
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
		<?php global $post; ?>
		<li>
		<a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a><br/>
		<span class="meta">
			<?php 
			$terms = get_the_terms( $post->ID, 'wpfc_preacher' );
									
			if ( $terms && ! is_wp_error( $terms ) ) : 

				$preacher_links = array();

				foreach ( $terms as $term ) {
					$preacher_links[] = $term->name;
				}
									
				$preacher = join( ", ", $preacher_links );
			?>

			<?php echo $preacher; ?>, 

			<?php endif; 
			wpfc_sermon_date('l, F j, Y'); 
			?>
		</span>
		</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_sermons', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_sermons', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of sermons to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("WP4C_Recent_Sermons");') );

// Custom taxonomy terms dropdown function
function wpfc_get_term_dropdown($taxonomy) {
	$terms = get_terms($taxonomy);
	foreach ($terms as $term) {
		$term_slug = $term->slug;
		$current_preacher = get_query_var('wpfc_preacher');
		$current_series = get_query_var('wpfc_sermon_series');
		$current_topic = get_query_var('wpfc_sermon_topics');
		$current_book = get_query_var('wpfc_bible_book');
		if($term_slug == $current_preacher || $term_slug == $current_series || $term_slug == $current_topic || $term_slug == $current_book) {
			echo '<option value="'.$term->slug.'" selected>'.$term->name.'</option>';
		} else {
			echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
		}
	}
}

// Make all queries for sermons order by the sermon date
function wpfc_sermon_order_query( $query ) {
	if ( isset($query->query_vars['post_type']) != 'nav_menu_item' ) :
	if( is_post_type_archive('wpfc_sermon') || is_tax( 'wpfc_preacher' ) || is_tax( 'wpfc_sermon_topics' ) || is_tax( 'wpfc_sermon_series' ) || is_tax( 'wpfc_bible_book' ) ) {
		$query->set('meta_key', 'sermon_date');
		$query->set('meta_value', date("m/d/Y"));
		$query->set('meta_compare', '>=');
		$query->set('orderby', 'meta_value');
		$query->set('order', 'DESC');
	}
	endif;
}
add_action('pre_get_posts', 'wpfc_sermon_order_query', 9999);

// render archive entry
function render_wpfc_sermon_archive() {
	global $post; ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<h2 class="sermon-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sermon-manager' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2> 
		<div class="wpfc_sermon_image">
			<?php render_sermon_image('thumbnail'); ?>
		</div>
		<div class="wpfc_sermon_meta cf">
			<p>	
				<?php 
					wpfc_sermon_date('l, F j, Y', '<span class="sermon_date">', '</span> '); wpfc_sermon_meta('service_type', ' <span class="service_type">(', ')</span> ');
			?></p><p><?php
					wpfc_sermon_meta('bible_passage', '<span class="bible_passage">Bible Text: ', '</span> | ');
					echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ' ', '</span>');
					echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">Series: ', ' ', '</span></p>' ); 
				?>
			</p>
		</div>
	</div>		<?php
}

// render sermon sorting
function render_wpfc_sorting() { ?>
<div id="wpfc_sermon_sorting">
	<span class="sortPreacher">
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_preacher" id="wpfc_preacher" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Preacher', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_preacher'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>
	</span>
	<span class="sortSeries">
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_sermon_series" id="wpfc_sermon_series" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Series', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_sermon_series'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>
	</span>
	<span class="sortTopics">
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_sermon_topics" id="wpfc_sermon_topics" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Topic', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_sermon_topics'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>	
	</span>
	<span class="sortBooks">
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_bible_book" id="wpfc_bible_book" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Book', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_bible_book'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>	
	</span>
</div>
<?php
}

// echo any sermon meta
function wpfc_sermon_meta( $args, $before = '', $after = '' ) {
	global $post;
	$data = get_post_meta($post->ID, $args, 'true');
	if ($data != '')
		echo $before .$data. $after;
}

// return any sermon meta
function get_wpfc_sermon_meta( $args ) {
	global $post;
	$data = get_post_meta($post->ID, $args, 'true');
	if ($data != '')
		return $data;
	return null;
}

// render sermon description
function wpfc_sermon_description( $before = '', $after = '' ) {
	global $post;
	$data = get_post_meta($post->ID, 'sermon_description', 'true');
	if ($data != '')
		echo $before .wpautop($data). $after;
}

// render any sermon date
function wpfc_sermon_date( $args, $before = '', $after = '' ) {
	global $post;
	$ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
	$date = date($args, $ugly_date);
		echo $before .$date. $after;
}

// Change published date to sermon date on frontend display
function wpfc_sermon_date_filter() {
	global $post;
	$ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
	$date = date(get_option('date_format'), $ugly_date);
		return $date;
}
if ( 'wpfc_sermon' == get_post_type() ) {
	add_filter('get_the_date', 'wpfc_sermon_date_filter');
}

// Change the_author to the preacher on frontend display
function wpfc_sermon_author_filter() {
	global $post;
	$preacher = the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); 
		return $preacher;
}
//add_filter('the_author', 'wpfc_sermon_author_filter');

// render sermon image - loops through featured image, series image, speaker image, none
function render_sermon_image($size) {
	//$size = any defined image size in WordPress
		if( has_post_thumbnail() ) :
			the_post_thumbnail($size);
		elseif ( apply_filters( 'sermon-images-list-the-terms', '', array( 'taxonomy'     => 'wpfc_sermon_series', ) )) :
			// get series image
			print apply_filters( 'sermon-images-list-the-terms', '', array(
				'image_size'   => $size,
				'taxonomy'     => 'wpfc_sermon_series',
				'after' => '',
				'after_image' => '', 
				'before' => '', 
				'before_image' => ''
			) );
		elseif ( !has_post_thumbnail() && !apply_filters( 'sermon-images-list-the-terms', '', array( 'taxonomy'     => 'wpfc_sermon_series',	) ) ) :
			// get speaker image
			print apply_filters( 'sermon-images-list-the-terms', '', array(
				'image_size'   => $size,
				'taxonomy'     => 'wpfc_preacher',
				'after' => '',
				'after_image' => '', 
				'before' => '', 
				'before_image' => ''
			) );
		endif;
}

// render files section
function wpfc_sermon_files() {
	if ( get_wpfc_sermon_meta('sermon_video') ) { 
		echo '<div id="wpfc_sermon-video" class="cf">';
			echo do_shortcode( get_wpfc_sermon_meta('sermon_video')); 
		echo '</div>';								
	} elseif ( !get_wpfc_sermon_meta('sermon_video') && get_wpfc_sermon_meta('sermon_audio') ) {
		echo '<div id="wpfc_sermon-audio" class="cf">';?>
			<script>
				jQuery.noConflict();
				jQuery(document).ready(function(){
					jQuery('audio').mediaelementplayer();	
				});
			</script> <?php
			echo '<audio controls="controls">';
				echo '<source src="' . get_wpfc_sermon_meta('sermon_audio') . '"  type="audio/mp3" />';
			echo '</audio>';
		echo '</div>';
	} 
	if ( get_wpfc_sermon_meta('sermon_notes') ) {
		echo '<div id="wpfc_sermon-notes" class="cf">';
			echo '<a href="' . get_wpfc_sermon_meta('sermon_notes') . '" class="sermon-notes">Notes</a>';
		echo '</div>';
	}
}

// render additional files
function wpfc_sermon_attachments() {
	global $post;
	$args = array(
		'post_type' => 'attachment',
		'numberposts' => -1,
		'post_status' => null,
		'post_parent' => $post->ID,
		'exclude' => get_post_thumbnail_id()
	);
	$attachments = get_posts($args);
	if ($attachments) {
		echo '<div id="wpfc-attachments" class="cf">';
		echo '<p><strong>Download Files:</strong>';
		foreach ($attachments as $attachment) {
			echo '<br/><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">';
			echo $attachment->post_title;
		}
		echo '</a>';
		echo '</p>';
		echo '</div>';
	}
}

// render single sermon entry
function render_wpfc_sermon_single() { 
	global $post; ?>
	<div id="wpfc_sermon_wrap" class="cf">
		<div id="wpfc_sermon_image">
			<?php render_sermon_image('sermon_small'); ?>
		</div>
		<div class="wpfc_sermon_meta cf">
			<p>	
				<?php 
					wpfc_sermon_date('l, F j, Y', '<span class="sermon_date">', '</span> '); wpfc_sermon_meta('service_type', ' <span class="service_type">(', ')</span> ');
			?></p><p><?php
					wpfc_sermon_meta('bible_passage', '<span class="bible_passage">Bible Text: ', '</span> | ');
					echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ', ', '</span>');
					echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">Series: ', ', ', '</span></p>' ); 
				?>
			</p>
		</div>
	</div>
	<div id="wpfc_sermon" class="cf">		  
				
		<?php wpfc_sermon_files(); ?>
		
		<?php wpfc_sermon_description(); ?>
		
		<?php wpfc_sermon_attachments(); ?>

		<?php echo the_terms( $post->ID, 'wpfc_sermon_topics', '<p class="sermon_topics">Topics: ', ', ', '</p>' ); ?>		
	</div>
<?php
}

// render single sermon entry
function render_wpfc_sermon_excerpt() { 
	global $post;?>
	<div id="wpfc_sermon_wrap" class="cf">
		<div id="wpfc_sermon_image">
			<?php render_sermon_image('sermon_small'); ?>
		</div>
		<div class="wpfc_sermon_meta cf">
			<p>	
				<?php 
					wpfc_sermon_date('l, F j, Y', '<span class="sermon_date">', '</span> '); wpfc_sermon_meta('service_type', ' <span class="service_type">(', ')</span> ');
			?></p><p><?php
					wpfc_sermon_meta('bible_passage', '<span class="bible_passage">Bible Text: ', '</span> | ');
					echo the_terms( $post->ID, 'wpfc_preacher',  '<span class="preacher_name">', ', ', '</span>');
					echo the_terms( $post->ID, 'wpfc_sermon_series', '<p><span class="sermon_series">Series: ', ', ', '</span></p>' ); 
				?>
			</p>
		</div>
		<?php	$sermonoptions = get_option('wpfc_options'); if ( isset($sermonoptions['archive_player']) == '1') { ?>
			<div id="wpfc_sermon" class="cf">		  	
				<?php wpfc_sermon_files(); ?>
			</div>
		<?php } ?>
	</div>
	<?php 
}

// Add sermon content
add_filter('the_content', 'add_wpfc_sermon_content');

function add_wpfc_sermon_content($content) {
	if ( 'wpfc_sermon' == get_post_type() ){
		if ( is_archive() ) {
			$new_content = render_wpfc_sermon_excerpt();
		} else ( is_singular() && is_main_query() ) {
			$new_content = render_wpfc_sermon_single();
		}
		$content = $new_content;	
	}	
	return $content;
}
	
/**
 * Podcast Settings
 */

// Create custom RSS feed for sermon podcasting
function wpfc_sermon_podcast_feed() {
	load_template(plugin_dir_path( __FILE__ ) . 'includes/podcast-feed.php');
}
add_action('do_feed_podcast', 'wpfc_sermon_podcast_feed', 10, 1);


// Custom rewrite for podcast feed
function wpfc_sermon_podcast_feed_rewrite($wp_rewrite) {
	$feed_rules = array(
		'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
		'(.+).xml' => 'index.php?feed='. $wp_rewrite->preg_index(1)
	);
	$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'wpfc_sermon_podcast_feed_rewrite');


// Get the filesize of a remote file, used for Podcast data
function wpfc_get_filesize( $url, $timeout = 10 ) {
	// Create a curl connection
	$getsize = curl_init();

	// Set the url we're requesting
	curl_setopt($getsize, CURLOPT_URL, $url);

	// Set a valid user agent
	curl_setopt($getsize, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");

	// Don't output any response directly to the browser
	curl_setopt($getsize, CURLOPT_RETURNTRANSFER, true);

	// Don't return the header (we'll use curl_getinfo();
	curl_setopt($getsize, CURLOPT_HEADER, false);

	// Don't download the body content
	curl_setopt($getsize, CURLOPT_NOBODY, true);

	// Follow location headers
	curl_setopt($getsize, CURLOPT_FOLLOWLOCATION, true);

	// Set the timeout (in seconds)
	curl_setopt($getsize, CURLOPT_TIMEOUT, $timeout);

	// Run the curl functions to process the request
	$getsize_store = curl_exec($getsize);
	$getsize_error = curl_error($getsize);
	$getsize_info = curl_getinfo($getsize);

	// Close the connection
	curl_close($getsize); // Print the file size in bytes

	return $getsize_info['download_content_length'];
}

//Returns duration of .mp3 file
function wpfc_mp3_duration($mp3_url) {
	require_once plugin_dir_path( __FILE__ ) . '/includes/getid3/getid3.php'; 
	$filename = tempnam('/tmp','getid3');
	if (file_put_contents($filename, file_get_contents($mp3_url, false, null, 0, 300000))) {
		  $getID3 = new getID3;
		  $ThisFileInfo = $getID3->analyze($filename);
		  unlink($filename);
	}

	$bitratez=$ThisFileInfo[audio][bitrate]; // get the bitrate from the audio file

	$headers = get_headers($mp3_url, 1); // Get the headers from the remote file
				if ((!array_key_exists("Content-Length", $headers))) { return false; } // Get the content length from the remote file
				$filesize= round($headers["Content-Length"]/1000); // Make the failesize into kilobytes & round it

	$contentLengthKBITS=$filesize*8; // make kbytes into kbits
	$bitrate=$bitratez/1000; //convert bits/sec to kbit/sec
	$seconds=$contentLengthKBITS/$bitrate; // Calculate seconds in song

	$playtime_mins = floor($seconds/60); // get the minutes of the playtime string
	$playtime_secs = $seconds % 60; // get the seconds for the playtime string
	if(strlen($playtime_secs)=='1'){$zero='0';} // if the string is a multiple of 10, we need to add a 0 for visual reasons
	$playtime_secs = $zero.$playtime_secs; // add the zero if nessecary
	$playtime_string=$playtime_mins.':'.$playtime_secs; // create the playtime string

		return $playtime_string;
}
?>
<?php
/*
Plugin Name: Sermon Manager for WordPress
Plugin URI: http://wpforchurch.com
Description: Add audio and video sermons, manage speakers, series, and more. Visit <a href="http://wpforchurch.com" target="_blank">Wordpress for Church</a> for tutorials and support.
Version: 1.3.2
Author: Jack Lamb
Author URI: http://wpforchurch.com/
License: GPL2
*/

// Security check to see if someone is accessing this file directly
if(preg_match("#^sermons.php#", basename($_SERVER['PHP_SELF']))) exit();

// Translations
function wpfc_sermon_translations() {
	load_plugin_textdomain( 'sermon-manager', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'wpfc_sermon_translations' );

//Add Options Page
require_once plugin_dir_path( __FILE__ ) . '/options.php';

// Define the plugin URL
define('WPFC_SERMONS', plugins_url() . '/sermon-manager-for-wordpress');

//Create sermon Custom Post Type
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
    'menu_name' => 'Sermons',
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
    'menu_icon' => $plugin . '/img/book-open-bookmark.png',
	'capability_type' => 'post',
    'has_archive' => 'sermons', 
    'rewrite' => array('slug' => $archive_slug),
    'hierarchical' => false,
    'menu_position' => 25,
    'supports' => array('title','comments', 'thumbnail')
  ); 
  register_post_type('wpfc_sermon',$args);
  //flush_rewrite_rules();
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
add_filter('posts_where', 'customSearchWhere');
add_filter('posts_join', 'customSearchJoin');
add_filter('posts_request', 'request_filter');
add_filter('posts_groupby', 'customSearchGroup');

function request_filter($content)
{
  // var_dump($content);
  return $content;
}

function customSearchWhere($content)
{
  global $wpdb;

  if (is_search())
  {
  	$search = get_search_query();	
    $content .= " or ({$wpdb->prefix}postmeta.meta_key = 'bible_passage' and {$wpdb->prefix}postmeta.meta_value LIKE '%{$search}%') ";
    $content .= " or ({$wpdb->prefix}postmeta.meta_key = 'sermon_description' and {$wpdb->prefix}postmeta.meta_value LIKE '%{$search}%') ";
  }
  
  return $content;
}

function customSearchJoin($content)
{
  global $wpdb;

  if (is_search())
  {
    $content .= " left join {$wpdb->prefix}postmeta on {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.id ";
  }
  return $content;
}

function customSearchGroup($content)
{
  global $wpdb;
  if (is_search())
  {
    $content .= " {$wpdb->prefix}posts.id ";
  }
  return $content;
}
// ==================================== End of custom search ===============

//enqueue needed js and styles on sermon edit screen
add_action('admin_enqueue_scripts', 'wpfc_admin_script_post');

function wpfc_admin_script_post() {
global $post_type;
	    if( 'wpfc_sermon' != $post_type )
	        return;
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('jquery-ui-datepicker', plugins_url('/js/jquery-ui-1.8.14.datepicker.min.js', __FILE__) );
		wp_enqueue_style('jquery-ui', plugins_url('/css/jquery.ui.datepicker.css', __FILE__) );  
		//backwards compatible wysiwyg editor for pre-3.3
		if(function_exists(wp_editor)) :
		else :
		wp_tiny_mce( TRUE, Array( "editor_selector" => "wysiwyg" ) );
		endif;
}

//Create custom fields and write panels for the Sermon post type
add_action("admin_init", "admin_init");

function admin_init() {
	add_meta_box('wpfc_sermon_details', __( 'Sermon Details', 'sermon-manager'), 'wpfc_sermon_details', 'wpfc_sermon', 'normal', 'high');
	add_meta_box('wpfc_sermon_files', __( 'Sermon Files', 'sermon-manager'), 'wpfc_sermon_files', 'wpfc_sermon', 'normal', 'high');
}
//top meta box - sermon details
function wpfc_sermon_details() {
	global $post;
	$custom = get_post_custom($post->ID);
	$bible_passage = $custom["bible_passage"] [0];
	$sermon_description = $custom["sermon_description"] [0];
    $sermon_date = $custom["sermon_date"] [0];
    $service_type = $custom["service_type"] [0];
	?>
	
<?php 
// Use nonce for verification  
wp_nonce_field( plugin_basename( __FILE__ ), 'sermons_nounce' );
?>
	<p><label><?php _e( 'Date:', 'sermon-manager'); ?></label>
	<script>jQuery(document).ready(function(){jQuery( "input[name='sermon_date']" ).datepicker({ dateFormat: 'mm/dd/yy', numberOfMonths: 1 }); jQuery( "#ui-datepicker-div" ).hide();});</script>
	<?php 
	$dateMeta = get_post_meta($post->ID, 'sermon_date', true);
    if (get_post_meta($post->ID, 'sermon_date', true)) {
	$displayDate = date('F j, Y', $dateMeta);
	} else { $displayDate = '';
	}
	?>
	<input type="text" name="sermon_date" id="sermon_date" value="<?php echo $displayDate ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<label><?php _e('Service Type:', 'sermon-manager'); ?></label> 
		<select id="service_type" name="service_type">
			<option value="Adult Bible Class"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Adult Bible Class") : ?> selected="true"<?php endif; ?>>Adult Bible Class</option>
			<option value="Sunday AM"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Sunday AM") : ?> selected="true"<?php endif; ?>>Sunday AM</option>
			<option value="Sunday PM"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Sunday PM") : ?> selected="true"<?php endif; ?>>Sunday PM</option>
			<option value="Midweek Service"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Midweek Service'") : ?> selected="true"<?php endif; ?>>Midweek Service</option>
			<option value="Special Service"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Special Service'") : ?> selected="true"<?php endif; ?>>Special Service</option>
			<option value="Radio Broadcast"<?php if ((get_post_meta($post->ID, 'service_type', true)) == "Radio Broadcast") : ?> selected="true"<?php endif; ?>>Radio Broadcast</option>
		</select>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <label><?php _e('Main Bible Passage:', 'sermon-manager'); ?></label> 
	<input type="text" size="40" name="bible_passage" value="<?php echo $bible_passage; ?>" /></p>
	<?php if(function_exists(wp_editor)) {
		$settings = array(
			'wpautop' => false,
			'media_buttons' => false,
		); 
		wp_editor($sermon_description, 'sermon_description', $settings ); }
	else { ?>
                </p><p><label><?php _e('Sermon Description:', 'sermon-manager'); ?></label></p>
		<textarea cols="100" rows="10" id="sermon_description" name="sermon_description" class="wysiwyg"><?php echo $sermon_description; ?></textarea>
    <?php } ?>
	<?php
}
//next meta box - sermon files
function wpfc_sermon_files() {
	global $post;
	$custom = get_post_custom($post->ID);
	$sermon_audio = $custom["sermon_audio"] [0];
	$sermon_video = $custom["sermon_video"] [0];
	?>
	<p><label><?php _e('Location of MP3 file:', 'sermon-manager'); ?> <br />
	<input type="text" size="100" name="sermon_audio" value="<?php echo $sermon_audio; ?>" />  <a class="thickbox menu-top menu-top-first menu-top-last button" href="media-upload.php?post_id=<?php the_ID(); ?>&TB_iframe=1&width=640&height=324">Upload A New One</a></strong></label></p>
	<p><label><?php _e('Paste your video embed code:', 'sermon-manager'); ?></label><br />
	<textarea cols="70" rows="5" name="sermon_video"><?php echo $sermon_video; ?></textarea></p>
	<p><?php _e('If you would like to add pdf, doc, ppt, or other file types upload them here. They\'ll be listed at the bottom of the sermon page.', 'sermon-manager'); ?><br/></p>
    <p><a class="thickbox menu-top menu-top-first menu-top-last button" href="media-upload.php?post_id=<?php the_ID(); ?>&TB_iframe=1&width=640&height=324"><?php _e('Upload Additional Files', 'sermon-manager'); ?></a></strong></label></p>
	<div id="wpfc-attachments">
    <?php
        $args = array(
          'post_type' => 'attachment',
          'numberposts' => -1,
          'post_status' => null,
          'post_parent' => $post->ID,
          );
        $attachments = get_posts($args);
        if ($attachments) {
		  echo '<p>'. __('Currently Attached Files:', 'sermon-manager').' <ul>';
          foreach ($attachments as $attachment) {
            echo '<li>&nbsp;&nbsp;<a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">';
            echo $attachment->post_title;
            echo '</a></li>';
          }
		  echo '</ul></p>';
        }
    ?>
    </div>
	<?php
}
//make sure that we save all of our details!
add_action('save_post', 'save_details');

function save_details(){
  global $post;
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
	return $post_id;
  }

  if( defined('DOING_AJAX') && DOING_AJAX ) { //Prevents the metaboxes from being overwritten while quick editing.
	return $post_id;
  }

  if( ereg('/\edit\.php', $_SERVER['REQUEST_URI']) ) { //Detects if the save action is coming from a quick edit/batch edit.
	return $post_id;
  }
  // added nonce check
  wp_verify_nonce( $_POST['sermons_nounce'], plugin_basename( __FILE__ ) );
  // save all meta data
  update_post_meta($post->ID, "bible_passage", $_POST["bible_passage"]);
  update_post_meta($post->ID, "sermon_description", $_POST["sermon_description"]);
  update_post_meta($post->ID, "sermon_audio", $_POST["sermon_audio"]);
  update_post_meta($post->ID, "sermon_video", $_POST["sermon_video"]);
  update_post_meta($post->ID, "sermon_date", strtotime($_POST["sermon_date"]));
  update_post_meta($post->ID, "service_type", $_POST["service_type"]);
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
			echo getPostViews($post->ID);
			break;			
	}
}

/* 
 * Shortcodes 
 * USAGE: http://www.wpforchurch.com/882/sermon-shortcode/
 */
require_once plugin_dir_path( __FILE__ ) . '/includes/shortcodes.php';

/* 
 * Template selection 
 */

// Include template for displaying sermons by Preacher
add_filter('template_include', 'sermon_template_include');
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
add_filter('template_include', 'preacher_template_include');
function preacher_template_include($template) {
		if(get_query_var('taxonomy') == 'wpfc_preacher') {
			if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php')) 
				return get_stylesheet_directory() . '/taxonomy-wpfc_preacher.php'; 
			return plugin_dir_path(__FILE__) . '/views/taxonomy-wpfc_preacher.php';	
		}
		return $template;
}

// Include template for displaying sermon series
add_filter('template_include', 'series_template_include');
function series_template_include($template) {
		if(get_query_var('taxonomy') == 'wpfc_sermon_series') {
			if(file_exists(get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php'))
				return get_stylesheet_directory() . '/taxonomy-wpfc_sermon_series.php';
			return plugin_dir_path(__FILE__) . '/views/taxonomy-wpfc_sermon_series.php';
		}
		return $template;
}
/*
 * Theme developers can add support for sermon manager to their theme with 
 * add_theme_support( 'sermon-manager' );
 * in functions.php. For now, this will disable the loading of the jwplayer javascript
 */
 
// Add scripts only to single sermon pages
add_action('wp_head', 'add_wpfc_js');
function add_wpfc_js() {
	// Call options array
		$sermonoptions = get_option('wpfc_options');
		$Bibleversion = $sermonoptions['bibly_version'];
	if (is_single() && 'wpfc_sermon' == get_post_type() ) {
		if ( ! current_theme_supports( 'sermon-manager' ) ) :
			echo '<script type="text/javascript" src="'.WPFC_SERMONS . '/js/jwplayer.js"></script>';	
			//wp_enqueue_script('jwplayer.js', plugins_url('/js/jwplayer.js', __FILE__));
		endif;
	}
	if (is_single() && 'wpfc_sermon' == get_post_type() && !$sermonoptions['bibly'] == '1') { 
		?>
		<script src="http://code.bib.ly/bibly.min.js"></script>
		<link href="http://code.bib.ly/bibly.min.css" rel="stylesheet" />
		<script>
			// Bible version for all links. Leave blank to let user choose.
			bibly.linkVersion = '<?php echo $Bibleversion; ?>'; 
			// Turn off popups
			bibly.enablePopups = true;
			// ESV, NET, KJV, or LEB are the currently supported popups.
			bibly.popupVersion = '<?php echo $Bibleversion; ?>';
		</script>
	<?php
	}
	// Add ajax for pagination if shortcode is present in the content
	global $wp_query;
	global $post;
	if($post) {
	if (  false !== strpos($post->post_content, '[sermons') ) {	
		wp_enqueue_script ('jquery');
		wp_enqueue_script( 'ajax.js', plugins_url('/js/ajax.js', __FILE__) ); 
		echo '<script type="text/javascript" src="'.WPFC_SERMONS . '/js/jwplayer.js"></script>';	
		}
	}	
}

// Add CSS to entire site. Looks for sermon.css in the main template directory first.
add_action('wp_head', 'add_wpfc_css');
function add_wpfc_css() {
	if(file_exists(get_stylesheet_directory() . '/sermon.css'))
		echo '<link rel="stylesheet" href="'.get_stylesheet_directory() . '/sermon.css'.'" type="text/css" >';
	echo '<link rel="stylesheet" href="'.WPFC_SERMONS . '/css/sermon.css'.'" type="text/css" >';
}

// Track post views - Added from http://wpsnipp.com/index.php/functions-php/track-post-views-without-a-plugin-using-post-meta/
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
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
		add_image_size( 'wpfc_preacher', 300, 9999 ); 
		add_image_size( 'wpfc_preacher_small', 50, 50, true ); 
		add_image_size( 'wpfc_series', 940, 9999 ); 
		add_image_size( 'wpfc_series_small', 50, 50, true ); 
	}
}
add_action("admin_init", "wpfc_sermon_images");

//include the main class file
require_once plugin_dir_path( __FILE__ ) . 'includes/taxonomy-images.php';

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
		<?php $ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');	$displayDate = date('F j, Y', $ugly_date);?>
		<span class="meta"><?php echo $displayDate; ?></span>
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
		if($term_slug == $current_preacher || $term_slug == $current_series || $term_slug == $current_topic) {
			echo '<option value="'.$term->slug.'" selected>'.$term->name.'</option>';
		} else {
			echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
		}
	}
}

// render archive entry
function render_wpfc_sermon_archive() {
	// Order sermons by date with the latest sermon first.
	global $wp_query;
	global $post;
	$args = array_merge( $wp_query->query, array( 
		'meta_key' => 'sermon_date',
        'meta_value' => date("m/d/Y"),
        'meta_compare' => '>=',
        'orderby' => 'meta_value',
        'order' => 'DESC',
    ) );
	query_posts( $args );
	while ( have_posts() ) : the_post(); //Here's the archive output ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php $ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
			$displayDate = date('l, F j, Y', $ugly_date);?>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sermon-manager' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2> 
		<?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(75,75), array("class" => "alignleft post_thumbnail")); } ?>
		<div class="wpfc_date"><?php echo $displayDate; ?></div>
		<div id="wpfc_sermon">		  
			<div class="wpfc_sermon-meta">
			<?php 
				if (get_post_meta($post->ID, 'bible_passage', true)) {
					echo get_post_meta($post->ID, 'bible_passage', true); ?> |								
				<?php } 
					echo the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); 
						echo the_terms( $post->ID, 'wpfc_sermon_series', __('<br/>Series: ', 'sermon-manager') , ', ', '' ); 
				?>
			</div>
		</div>
	</div>		
	<?php endwhile; // End the loop. Whew. 
}

// render sermon sorting
function render_wpfc_sorting() { ?>
<div id="wpfc_sermon_sorting">
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_preacher" id="wpfc_preacher" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Preacher', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_preacher'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>
	<form action="<?php bloginfo('url'); ?>" method="get">
		<select name="wpfc_sermon_series" id="wpfc_sermon_series" onchange="return this.form.submit()">
			<option value=""><?php _e('Sort by Series', 'sermon-manager'); ?></option>
			<?php wpfc_get_term_dropdown('wpfc_sermon_series'); ?>
		</select>
	<noscript><div><input type="submit" value="Submit" /></div></noscript>
	</form>
</div>
<?php
}

// render single sermon entry
function render_wpfc_sermon_single() {
	global $post;
	while ( have_posts() ) : the_post(); ?>
	<div id="sermon-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php $ugly_date = get_post_meta($post->ID, 'sermon_date', 'true');
				$displayDate = date('l, F j, Y', $ugly_date);?>
			<div class="wpfc_date"><?php echo $displayDate; ?> (<?php echo get_post_meta($post->ID, 'service_type', true); ?>)</div>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

			<div class="entry-content">
				<div id="wpfc_sermon">		  
					<p>	<?php   
							if (get_post_meta($post->ID, 'bible_passage', true)) {
								echo get_post_meta($post->ID, 'bible_passage', true); ?> |								
						<?php } 
							echo the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); 
							echo the_terms( $post->ID, 'wpfc_sermon_series', '<br />Series: ', ', ', '' ); 
							echo setPostViews(the_ID());
						?>
					</p>
					<?php if (get_post_meta($post->ID, 'sermon_video', true)) { ?>
								<div class="wpfc_sermon-video"><?php echo get_post_meta($post->ID, 'sermon_video', true); ?></div>								
							<?php } else { ?>
								<div id="wpfc_sermon-audio">
									<div id='mediaspace'>You must have Javascript enabled to listen</div>
									<script type='text/javascript'>
									jwplayer('mediaspace').setup({
									'flashplayer': '<?php echo ''.WPFC_SERMONS . '/js/player.swf'?>',
									'file': '<?php echo get_post_meta($post->ID, 'sermon_audio', true); ?>',
									'controlbar': 'bottom',
									'width': '350',
									'height': '24'
									});
									</script>
								</div>
							<?php } ?>
							<p><?php echo get_post_meta($post->ID, 'sermon_description', true); ?></p>
							<div id="wpfc-attachments">
								<?php
									$args = array(
										'post_type' => 'attachment',
										'numberposts' => -1,
										'post_status' => null,
										'post_parent' => $post->ID,
										'exclude'     => get_post_thumbnail_id()
									);
									$attachments = get_posts($args);
									if ($attachments) {
										echo '<p><strong>Additional Files:</strong>';
										foreach ($attachments as $attachment) {
										echo '<br/><a target="_blank" href="'.wp_get_attachment_url($attachment->ID).'">';
										echo $attachment->post_title;
										echo '</a>';
									}
									echo '</p>';
									}
								?>
							</div>
					
				</div>


			</div><!-- .entry-content -->

			<div class="entry-utility">
					<span class="tag-links">
						<?php echo the_terms( $post->ID, 'wpfc_sermon_topics', '<br />Topics: ', ', ', '<span class="meta-sep"> | </span>' ); ?>
					</span>
				<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>
				<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-utility -->
		</div><!-- #post-## -->

		<?php comments_template( '', true ); ?>


<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; 
}
?>
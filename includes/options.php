<?php
/*
 * Sermon Manager Plugin Options
 */

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'wpfc_add_defaults');
add_action('admin_init', 'wpfc_init' );
add_action('admin_menu', 'wpfc_add_options_page');
add_filter('plugin_action_links', 'wpfc_plugin_action_links', 10, 2 );

// Define default option settings
function wpfc_add_defaults() {
	$tmp = get_option('wpfc_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('wpfc_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"bibly" => "0",
						"bibly_version" => "KJV",
						"archive_slug" => "sermons",
						"archive_title" => "Sermons"
		);
		update_option('wpfc_options', $arr);
	}
}

// Init plugin options to white list our options
function wpfc_init(){
	register_setting( 'wpfc_plugin_options', 'wpfc_options', 'wpfc_validate_options' );
}

// Add menu page
function wpfc_add_options_page() {
	$page = add_submenu_page('edit.php?post_type=wpfc_sermon', 'Sermon Manager Settings', 'Settings', 'manage_options', __FILE__, 'wpfc_sermon_options_render_form');
	add_action( 'admin_print_styles-' . $page, 'wpfc_sermon_admin_styles' );
}

// Add scripts
function wpfc_sermon_admin_styles() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}

// Render the Plugin options form
function wpfc_sermon_options_render_form() {
	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>
	<div class="wrap">
		
		<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#upload_cover_image').click(function() {
				uploadID = jQuery(this).prev('input');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				uploadID.val(imgurl); /*assign the value to the input*/
				tb_remove();
			};
		});
		</script>
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Sermon Manager Options', 'sermon-manager'); ?></h2>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved', 'sermon-manager' ); ?></strong></p></div>
		<?php endif; ?>
		
		<div class="metabox-holder has-right-sidebar">

			<div class="inner-sidebar">

				<div class="postbox">
				<h3><span><?php _e('Need Help?', 'sermon-manager'); ?></span></h3>
				<div class="inside">
				<p><?php _e('If you need help, please visit <a href="http://www.wpforchurch.com/" target="_blank">WP for Church</a>', 'sermon-manager'); ?></p>
				</div>
				</div>

			</div> <!-- .inner-sidebar -->

		<div id="post-body">
		<div id="post-body-content">
			<form method="post" action="options.php">
			<?php settings_fields('wpfc_plugin_options'); ?>
			<?php $options = get_option('wpfc_options'); ?>

			<div class="postbox">
				<h3><span><?php _e('General Settings', 'sermon-manager'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Archive Page Title', 'sermon-manager'); ?></th>
					<td>
						<input type="text" size="65" name="wpfc_options[archive_title]" value="<?php echo $options['archive_title']; ?>" />
					</td>
				</tr>
				<!-- Slug -->
				<tr valign="top">
					<th scope="row"><?php _e('Archive Page Slug', 'sermon-manager'); ?></th>
					<td>
						<input type="text" size="65" name="wpfc_options[archive_slug]" value="<?php echo $options['archive_slug']; ?>" /> <span style="color:#666666;margin-left:2px;"><?php _e('Go to Settings &rarr; Permalinks and re-save after changing this!', 'sermon-manager'); ?></span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Enable Template Files', 'sermon-manager'); ?></th>
					<td>
						<!-- Bibly -->
						<label><input name="wpfc_options[template]" type="checkbox" value="1" <?php if (isset($options['template'])) { checked('1', $options['template']); } ?> /> <?php _e('Enable template files found in the /views folder', 'sermon-manager'); ?></label><br />
					</td>
				</tr>
				</table>
			</div> <!-- .inside -->
			</div>

			<div class="postbox">
				<h3><span><?php _e('Verse Settings', 'sermon-manager'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
				<!-- Enable Bib.ly -->
				<tr valign="top">
					<th scope="row"><?php _e('Verse Popups', 'sermon-manager'); ?></th>
					<td>
						<!-- Bibly -->
						<label><input name="wpfc_options[bibly]" type="checkbox" value="1" <?php if (isset($options['bibly'])) { checked('1', $options['bibly']); } ?> /> <?php _e('Disable Bib.ly verse popups', 'sermon-manager'); ?></label><br />
					</td>
				</tr>
				<!-- Select Bible Version -->
				<tr>
					<th scope="row"><?php _e('Select Bible Version for Verse Popups', 'sermon-manager'); ?></th>
					<td>
						<select name='wpfc_options[bibly_version]'> <!-- ESV, NET, KJV, or LEB are the currently supported popups. -->
							<option value='KJV' <?php selected('KJV', $options['bibly_version']); ?>>KJV</option>
							<option value='ESV' <?php selected('ESV', $options['bibly_version']); ?>>ESV</option>
							<option value='NET' <?php selected('NET', $options['bibly_version']); ?>>NET</option>
							<option value='LEB' <?php selected('LEB', $options['bibly_version']); ?>>LEB</option>
						</select>
						<span style="color:#666666;margin-left:2px;"><?php _e('ESV, NET, KJV, or LEB are the currently supported popups for <a href="http://bib.ly">bib.ly</a>.', 'sermon-manager'); ?></span>
					</td>
				</tr>
				</table>
			</div> <!-- .inside -->
			</div>

			<div class="postbox">
				<h3><span><?php _e('Podcast Settings', 'sermon-manager'); ?></span></h3>
			<div class="inside">
				<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Title', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[title]" type="text" size="65"  name="wpfc_options[title]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('name'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['title'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Description', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[description]" type="text" size="65" name="wpfc_options[description]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('description'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['description'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Website Link', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[website_link]" type="text" size="65" name="wpfc_options[website_link]" placeholder="<?php _e( 'e.g. ' . home_url(), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['website_link'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Language', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[language]" type="text" size="65" name="wpfc_options[language]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('language'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['language'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Copyright', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[copyright]" type="text" size="65" name="wpfc_options[copyright]" placeholder="<?php _e( 'e.g. Copyright &copy; ' . get_bloginfo('name'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['copyright'] ); ?>" />
					</td>
					<td class="info">
						<p><em><?php _e( 'Tip: Use "' . htmlspecialchars('&copy;') . '" to generate a copyright symbol.', 'sermon-manager' ); ?></em></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Webmaster Name', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[webmaster_name]" type="text" size="65" name="wpfc_options[webmaster_name]" placeholder="<?php _e( 'e.g. Your Name', 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['webmaster_name'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Webmaster Email', 'sermon-manager' ); ?></th>
					<td class="option" colspan="2">
						<input id="wpfc_options[webmaster_email]" type="text" size="65" name="wpfc_options[webmaster_email]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('admin_email'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['webmaster_email'] ); ?>" />
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Author', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_author]" type="text" size="65" name="wpfc_options[itunes_author]" placeholder="<?php _e( 'e.g. Primary Speaker or Church Name', 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_author'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'This will display at the "Artist" in the iTunes Store.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Subtitle', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_subtitle]" type="text" size="65" name="wpfc_options[itunes_subtitle]" placeholder="<?php _e( 'e.g. Preaching and teaching audio from ' . get_bloginfo('name'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_subtitle'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'Your subtitle should briefly tell the listener what they can expect to hear.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Summary', 'sermon-manager' ); ?></th>
					<td class="option">
						<textarea id="wpfc_options[itunes_summary]" class="large-text" cols="65" rows="5" name="wpfc_options[itunes_summary]" placeholder="<?php _e( 'e.g. Weekly teaching audio brought to you by ' . get_bloginfo('name') . ' in City, State.', 'sermon-manager' ); ?>"><?php echo esc_textarea( $options['itunes_summary'] ); ?></textarea>
					</td>
					<td class="info">
						<p><?php _e( 'Keep your Podcast Summary short, sweet and informative. Be sure to include a brief statement about your mission and in what region your audio content originates.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Owner Name', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_owner_name]" type="text" size="65" name="wpfc_options[itunes_owner_name]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('name'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_owner_name'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'This should typically be the name of your Church.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Owner Email', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_owner_email]" type="text" size="65" name="wpfc_options[itunes_owner_email]" placeholder="<?php _e( 'e.g. ' . get_bloginfo('admin_email'), 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_owner_email'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'Use an email address that you don\'t mind being made public. If someone wants to contact you regarding your Podcast this is the address they will use.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
								
				<tr class="top">
					<th scope="row"><?php _e( 'Cover Image', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_cover_image]" size="45" type="text" name="wpfc_options[itunes_cover_image]" value="<?php esc_attr_e( $options['itunes_cover_image'] ); ?>" />
						<input id="upload_cover_image" type="button" class="button" value="Upload Image" />
<?php if($options['itunes_cover_image']): ?>
						<br />
						<img src="<?php esc_attr_e( $options['itunes_cover_image'] ); ?>" width="300px" height="300px" class="preview" />
<?php endif; ?>
					</td>
					<td class="info">
						<p><?php _e( 'This JPG will serve as the Podcast artwork in the iTunes Store. The image should be 1400px by 1400px', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Top Category', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_top_category]" size="65" type="text" name="wpfc_options[itunes_top_category]" placeholder="<?php _e( 'e.g. Religion & Spirituality', 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_top_category'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'Choose the appropriate top-level category for your Podcast listing in iTunes.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><?php _e( 'Sub Category', 'sermon-manager' ); ?></th>
					<td class="option">
						<input id="wpfc_options[itunes_sub_category]" size="65" type="text" name="wpfc_options[itunes_sub_category]" placeholder="<?php _e( 'e.g. Christianity', 'sermon-manager' ); ?>" value="<?php esc_attr_e( $options['itunes_sub_category'] ); ?>" />
					</td>
					<td class="info">
						<p><?php _e( 'Choose the appropriate sub category for your Podcast listing in iTunes.', 'sermon-manager' ); ?></p>
					</td>
				</tr>

				</table>
			</div> <!-- .inside -->
			</div>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'sermon-manager') ?>" />
			</p>
			</form>
			
			<div class="postbox">
				<h3><span><?php _e('Submit to iTunes', 'sermon-manager'); ?></span></h3>
			<div class="inside">
			
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e( 'Podcast Feed URL', 'sermon-manager' ); ?></th>
					<td class="option">
						<input type="text" class="regular-text" readonly="readonly" value="<?php echo home_url(); ?>/feed/podcast" />
					</td>
					<td class="info">
						<p><?php _e( 'Use the ', 'sermon-manager' ); ?><a href="http://www.feedvalidator.org/check.cgi?url=<?php echo home_url(); ?>/feed/podcast" target="_blank"><?php _e( 'Feed Validator', 'sermon-manager' ); ?></a><?php _e( ' to diagnose and fix any problems before submitting your Podcast to iTunes.', 'sermon-manager' ); ?></p>
					</td>
				</tr>
			</table>
			
			<br />
			<p><?php _e( 'Once your Podcast Settings are complete and your Sermons are ready, it\'s time to ', 'sermon-manager' ); ?><a href="https://phobos.apple.com/WebObjects/MZFinance.woa/wa/publishPodcast" target="_blank"><?php _e( 'Submit Your Podcast', 'sermon-manager' ); ?></a><?php _e( ' to the iTunes Store!', 'sermon-manager' ); ?></p>
			
			<p><?php _e( 'Alternatively, if you want to track your Podcast subscribers, simply pass the Podcast Feed URL above through ', 'sermon-manager' ); ?><a href="http://feedburner.google.com/" target="_blank"><?php _e( 'FeedBurner', 'sermon-manager' ); ?></a><?php _e( '. FeedBurner will then give you a new URL to submit to iTunes instead.', 'sermon-manager' ); ?></p>
			
			<p><?php _e( 'Please read the ', 'sermon-manager' ); ?><a href="http://www.apple.com/itunes/podcasts/creatorfaq.html" target="_blank"><?php _e( 'iTunes FAQ for Podcast Makers', 'sermon-manager' ); ?></a><?php _e( ' for more information.', 'sermon-manager' ); ?></p>
			</div> <!-- .inside -->
			</div>

			
		</div> <!-- #post-body-content -->
		</div> <!-- #post-body -->

		</div> <!-- .metabox-holder -->
	</div> <!-- .wrap -->
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wpfc_validate_options($input) {
	 // strip html from textboxes
	$input['archive_slug'] =  wp_filter_nohtml_kses($input['archive_slug']); // Sanitize textbox input (strip html tags, and escape characters)
	$input['archive_title'] =  wp_filter_nohtml_kses($input['archive_title']); // Sanitize textbox input (strip html tags, and escape characters)
	return $input;
}

// Display a Settings link on the main Plugins page
function wpfc_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$wpfc_links = '<a href="'.get_admin_url().'options-general.php?page=sermon-manager-for-wordpress/options.php">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $wpfc_links );
	}

	return $links;
}
?>
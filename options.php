<?php
/*
http://www.presscoders.com/plugins/plugin-options-starter-kit/
Description: Starter kit to help create Plugin options pages. Contains all the commonly used form options.
Version: 0.2
*/

/*  Copyright 2009 David Gwyer (email : d.v.gwyer@presscoders.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
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
	add_submenu_page('edit.php?post_type=wpfc_sermon', 'Sermon Manager Settings', 'Settings', 'manage_options', __FILE__, 'wpfc_render_form');
}

// Render the Plugin options form
function wpfc_render_form() {
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Sermon Manager Options', 'sermon-manager'); ?></h2>

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
				<h3><span><?php _e('Archive Page Settings', 'sermon-manager'); ?></span></h3>
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
						<input type="text" size="65" name="wpfc_options[archive_slug]" value="<?php echo $options['archive_slug']; ?>" /> <span style="color:#666666;margin-left:2px;"><?php _e('Go to Settings => Permalinks and re-save after changing this!', 'sermon-manager'); ?></span>
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
						<span style="color:#666666;margin-left:2px;"><?php _e('ESV, NET, KJV, or LEB are the currently supported popups.', 'sermon-manager'); ?></span>
					</td>
				</tr>
				</table>
			</div> <!-- .inside -->
			</div>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'sermon-manager') ?>" />
			</p>
			</form>
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
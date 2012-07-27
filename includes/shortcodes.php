<?php
/** 
 * Sermons Shortcode 
 *
 * Requires a plugin for pagination to work: http://wordpress.org/extend/plugins/wp-pagenavi/
 *
 * Modified from: Display Posts Shortcode 1.7
 * http://www.billerickson.net/shortcode-to-display-posts/
 * Description: Display a listing of posts using the [display-posts] shortcode
 * @author Bill Erickson <bill@billerickson.net>
 * @copyright Copyright (c) 2011, Bill Erickson
 * @link http://www.billerickson.net/shortcode-to-display-posts/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Create the shortcode
add_shortcode('sermons', 'wpfc_display_sermons_shortcode');
function wpfc_display_sermons_shortcode($atts) {

	// Pull in shortcode attributes and set defaults
	extract( shortcode_atts( array(
		'id' => false,
		'posts_per_page' => '10',
		'order' => 'DESC',
		'hide_nav' => false,
		'taxonomy' => false,
		'tax_term' => false,
		'tax_operator' => 'IN'
	), $atts ) );
	// begin - code from : http://wordpress.org/support/topic/wp-pagenavi-with-custom-query-and-paged-variable?replies=2
		global $paged;
		if( get_query_var( 'paged' ) )
			$my_page = get_query_var( 'paged' );
		else {
		if( get_query_var( 'page' ) )
			$my_page = get_query_var( 'page' );
		else
			$my_page = 1;
		set_query_var( 'paged', $my_page );
		$paged = $my_page;
		}
	// - end
	$args = array(
		'post_type' => 'wpfc_sermon',
		'posts_per_page' => $posts_per_page,
		'order' => $order,
		'meta_key' => 'sermon_date',
        'meta_value' => date("m/d/Y"),
        'meta_compare' => '>=',
        'orderby' => 'meta_value',
		'paged' => $my_page,
	);
	
	// If Post IDs
	if( $id ) {
		$posts_in = explode( ',', $id );
		$args['post__in'] = $posts_in;
	}
	
	// If taxonomy attributes, create a taxonomy query
	if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
	
		// Term string to array
		$tax_term = explode( ', ', $tax_term );
		
		// Validate operator
		if( !in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) )
			$tax_operator = 'IN';
					
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $tax_term,
					'operator' => $tax_operator
				)
			)
		);
		$args = array_merge( $args, $tax_args );
	}
	
	$listing = new WP_Query( $args, $atts ) ;
	// Now that you've run the query, finish populating the object
	ob_start(); ?>
	<div id="wpfc_sermon">	
	<div id="wpfc_loading">
	<?php
	if ( !$listing->have_posts() )
		return;
	while ( $listing->have_posts() ): $listing->the_post(); global $post; ?>
		<div id="sermonImage">
			<?php //render_wpfc_sermon_image(); ?>
		</div>
		<div id="sermonContent">
			<div class="wpfc_date"><?php wpfc_sermon_date('l, F j, Y'); ?></div>
			<h2 class="sermon-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sermon-manager' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2> 
			<?php render_wpfc_sermon_excerpt(); ?>
		</div>
	<?php
	endwhile; //end loop
	if(function_exists(wp_pagenavi)) : ?>
		<div id="sermon-navigation"> 
			<?php wp_pagenavi( array( 'query' => $listing ) ); ?>
		</div>
	<?php
	endif;
	wp_reset_query();
	?>
	</div>
	</div>
	<?php
	$buffer = ob_get_clean();
	return $buffer;
}


add_shortcode('sermon_sort_fields', 'wpfc_sermons_sorting_shortcode');
function wpfc_sermons_sorting_shortcode($atts) {
	render_wpfc_sorting();
}
?>
<?php
/** 
 * Sermons Shortcodes 
 *
 * Requires a plugin for pagination to work: http://wordpress.org/extend/plugins/wp-pagenavi/
 *
 */
 
// List all series or speakers in a simple unordered list
add_shortcode('list-sermons', 'wpfc_list_sermons_shortcode');
function wpfc_list_sermons_shortcode( $atts = array () ){
	extract( shortcode_atts( array(
		'tax' => 'wpfc_sermon_series', // options: wpfc_sermon_series, wpfc_preacher, wpfc_sermon_topics, wpfc_bible_book
		'order' => 'ASC', // options: DESC
		'orderby' => 'name', // options: id, count, name, slug, term_group, none
	), $atts ) );
	
	$terms = get_terms($tax);
 $count = count($terms);
 if ( $count > 0 ){
     $list = '<ul id="list-sermons">';
     foreach ( $terms as $term ) {
       $list .= '<li><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '" title="' . $term->name . '">' . $term->name . '</a></li>';
     }
     $list .= '</ul>';
	 return $list;
 }
}
 
// Display all series or speakers in a grid of images
add_shortcode('sermon-images', 'wpfc_display_images_shortcode');
function wpfc_display_images_shortcode( $atts = array () ) {
	extract( shortcode_atts( array(
		'tax' => 'wpfc_sermon_series', // options: wpfc_sermon_series, wpfc_preacher, wpfc_sermon_topics
		'order' => 'ASC', // options: DESC
		'orderby' => 'name', // options: id, count, name, slug, term_group, none
		'size' => 'sermon_medium' // options: any size registered with add_image_size
	), $atts ) );
		
		$terms = apply_filters( 'sermon-images-get-terms', '', array('taxonomy' => $tax, 'order' => $order, 'orderby' => 'name' ) );
		if ( ! empty( $terms ) ) { 
			print '<ul id="wpfc_images_grid">'; foreach( (array) $terms as $term ) { 
				print '<li class="wpfc_grid_image">';
				print '<a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '">' . wp_get_attachment_image( $term->image_id, $size ) . '</a>';
				print '<h3 class="wpfc_grid_title"><a href="' . esc_url( get_term_link( $term, $term->taxonomy ) ) . '">' . $term->name . '</a></h3>';
				print '</li>'; 
			} print '</ul>'; }
}

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
		'image_size' => 'sermon_small',
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
	<div id="wpfc_sermon_wrap">
		<h3 class="sermon-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'sermon-manager' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h3> 
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
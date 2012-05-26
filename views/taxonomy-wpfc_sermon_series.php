<?php
/**
 * The template for displaying Sermon Series pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); 
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );  
$termid = $term->term_id;
?>

		<div id="container">
			<div id="content" role="main">

				<h1 class="page-title"><?php
					printf( __( 'Sermon Series: %s', 'twentyten' ), '<span>' . $term->name . '</span>' ); 
				?></h1>
				<?php
					/* Series Image */
						//img src (this retrieves the image size selected at "insert into post" on the series edit screen)
						//$series_image = get_tax_meta($termid,'wpfc_series_image',true);
						//echo '<img src="'.$series_image['src'].'">';
						//get image id (this will return any attachment size you desire) - doesn't work yet! :-)
						$saved_data = get_tax_meta($termid,'wpfc_series_image',true);
						$attachment_id = $saved_data['id'];
						//wp_get_attachment_image( $attachment_id, 'wpfc_series', '', '' ); 
						$image_attributes = wp_get_attachment_image_src( $attachment_id, 'wpfc_series' ); // returns an array
				?> 
				<?php if ($saved_data) { ?>
					<img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>">
				<?php }
					
					/* Series Description */
					if ('' != $term->description ) {  
					echo "<p>$term->description</p>\n";  
					}  					
				?>
				<?php render_wpfc_sermon_archive(); ?>
				

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

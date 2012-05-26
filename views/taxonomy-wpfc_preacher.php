<?php
/**
 * The template for displaying Speaker pages.
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
					printf( __( 'Sermons by: %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></h1>
				<?php
					/* Preacher Image */
						$saved_data = get_tax_meta($termid,'wpfc_preacher_image',true);
						$attachment_id = $saved_data['id'];
						$image_attributes = wp_get_attachment_image_src( $attachment_id, 'medium' ); // returns an array
				?> 
				<?php if ($saved_data) { ?>
			    <img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>">
				<?php }
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';
				?>
				<?php render_wpfc_sermon_archive(); ?>
				
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

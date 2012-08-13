<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

	<div id="container">
		<div id="content" role="main">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				<h1 class="entry-title"><?php the_title(); ?></h1>		
			
				<?php render_wpfc_sermon_single(); ?>

				<div class="entry-utility">
					<?php edit_post_link( __( 'Edit', 'sermon-manager' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-utility -->

			</div><!-- #post-## -->

		<div id="nav-below" class="navigation">
			<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'sermon-manager' ) . '</span> %title' ); ?></div>
			<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'sermon-manager' ) . '</span>' ); ?></div>
		</div><!-- #nav-below -->

		<?php comments_template( '', true ); ?>
		</div><!-- #content -->
	</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>

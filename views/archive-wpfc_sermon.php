<?php
/**
 * The template for displaying Sermon Archive pages.
 * To see this visit the http://yourdomain.com/sermons if you have permalinks enabled 
 * or http://yourdomain.com/?post_type=wpfc_sermon if not.
 * 
 * Recent Changes:
 * 1.2.2 - made translation ready, add code for featured image
 */

get_header(); ?>


		<div id="container">
			<div id="content" role="main">

				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if ( $wp_query->max_num_pages > 1 ) : ?>
					<div id="nav-above" class="navigation">
						<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
					</div><!-- #nav-above -->
				<?php endif; ?>

				<?php /* If there are no posts to display, such as an empty archive page */ ?>
				<?php if ( ! have_posts() ) : ?>
					<div id="post-0" class="post error404 not-found">
						<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
						<div class="entry-content">
							<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</div><!-- #post-0 -->
				<?php endif; ?>

				<?php
					/* Start the Loop.
					 *
					 * In Twenty Ten we use the same loop in multiple contexts.
					 * It is broken into three main parts: when we're displaying
					 * posts that are in the gallery category, when we're displaying
					 * posts in the asides category, and finally all other posts.
					 *
					 * Additionally, we sometimes check for whether we are on an
					 * archive page, a search page, etc., allowing for small differences
					 * in the loop on each template without actually duplicating
					 * the rest of the loop that is shared.
					 *
					 * Without further ado, the loop:
					 */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

							<div class="entry-meta">
								<span class="meta-prep meta-prep-author">Preached on </span> <?php wpfc_sermon_date('l, F j, Y'); ?><span class="meta-sep"> by </span> <?php echo the_terms( $post->ID, 'wpfc_preacher', '', ', ', ' ' ); ?>
   							</div><!-- .entry-meta -->

							<div class="entry-content">
								<?php if ( render_sermon_image('thumbnail') ) :	?>
										<div class="gallery-thumb">
											<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php render_sermon_image('thumbnail'); ?></a>
										</div><!-- .gallery-thumb -->
								<?php endif; ?>
										<?php render_wpfc_sermon_excerpt(); ?>
							</div><!-- .entry-content -->

							<div class="entry-utility">
								<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>
								<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
							</div><!-- .entry-utility -->
						</div><!-- #post-## -->

				<?php endwhile; // End the loop. Whew. ?>

				<?php /* Display navigation to next/previous pages when applicable */ ?>
				<?php if (  $wp_query->max_num_pages > 1 ) : ?>
								<div id="nav-below" class="navigation">
									<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
									<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
								</div><!-- #nav-below -->
				<?php endif; ?>

				</div><!-- #content -->
			</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
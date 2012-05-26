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
			<?php
			$sermon_settings = get_option('wpfc_options');
			$archive_title = $sermon_settings['archive_title'];
			if(empty($archive_title)):
				$archive_title = 'Sermons';
			endif; 
			?>
			<h1 class="page-title"><?php echo $archive_title; ?></h1>
			<?php render_wpfc_sorting(); ?>
			<?php render_wpfc_sermon_archive(); ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
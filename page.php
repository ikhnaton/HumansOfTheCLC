<?php get_header(); ?>
<?php
	$pages = get_clc_nav_pages();
?>

<?php if ($post->post_title == "Home") { ?>
	<div class="clc-main-wrap">
		<?php echo display_images_from_media_library(); ?>
	</div>
	<div class="clc-overlay">

	</div>
	<div class="clc-home-content clc-main-fade-in">
		<div class="clc-text">
			<h1>
				Humans of the CLC
			</h1>

			<img class="clc-main-logo" src="<?php echo get_template_directory_uri(); ?>/img/miracosta_white.png" />
		</div>
		<nav class="clc-home-nav">
			<ul>
				<?php
					foreach ($pages as $page)
					{
				?>
					<li><a href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title; ?></a></li>
				<?php
					}
				?>
			</ul>
		</nav>
	</div>
<?php } else { ?>
	<main role="main" class="clc-main-style">
		<!-- section -->
		<section>

			<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

				<?php comments_template( '', true ); // Remove if you don't want comments ?>

				<br class="clear">

				<?php edit_post_link(); ?>

			</article>
			<!-- /article -->

		<?php endwhile; ?>

		<?php else: ?>

			<!-- article -->
			<article>

				<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

			</article>
			<!-- /article -->

		<?php endif; ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
<?php } ?>

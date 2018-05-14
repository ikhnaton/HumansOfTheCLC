<?php get_header(); ?>
<?php
	$clcPages = get_clc_nav_pages();

function noPostsExist($txt)
{
?>
	<section class="clc-story">
		<h2>
			Check back soon, we are currently working on stories for this topic.
		</h2>
	</section>
<?php
}
?>

<?php if ($post->post_title == "Home") { ?>
	<div class="clc-main-wrap">
		<?php echo display_images_from_media_library(); ?>
	</div>
	<div class="clc-overlay">

	</div>
	<div class="clc-home-content clc-main-fade-in">
		<div class="clc-text">
			<h1>Humans of the CLC</h1>

			<a href="http://www.miracosta.edu/instruction/continuingeducation/index.html" target="_blank"><img class="clc-main-logo" src="<?php echo get_template_directory_uri(); ?>/img/miracosta_white.png" onmouseover="hoverLogo(this, '<?php echo get_template_directory_uri(); ?>/img/miracosta_color_white_txt.png')" onmouseout="hoverLogo(this, '<?php echo get_template_directory_uri(); ?>/img/miracosta_white.png')"/></a>
		</div>
		<nav class="clc-home-nav">
			<ul>
				<?php
					foreach ($clcPages as $clcPage)
					{
				?>
					<li><a href="<?php echo get_permalink($clcPage->ID); ?>"><?php echo $clcPage->post_title; ?></a></li>
				<?php
					}
				?>
			</ul>
		</nav>
	</div>
<?php } else { ?>
<main role="main" class="clc-main-style"><div>
		<!-- section -->
		<section>
			<nav class="clc-main-nav">
				<header>
					Humans of the CLC
				</header>
				<ul>
					<li><a href="<?php echo get_home_url(); ?>">Home</a></li>
					<?php
					  foreach ($clcPages as $clcPage)
					  {
					?>
					<li  <?php if (get_the_ID() == $clcPage->ID) echo "class='active'"; ?> ><a href="<?php echo get_permalink($clcPage->ID); ?>"><?php echo $clcPage->post_title; ?></a></li>
					<?php
			  		  }
					?>
				</ul>
			</nav>
	</section>
<?php if ($post->post_title == "Stories") { ?>
<section class="clc-stories">
	<style>
		html, body, .wrapper
		{
			height: 100%;
		}
	</style>
<?php
			$clcPages = get_clc_stories_page();
			$story_page = get_page_by_title( 'Story' )->ID;
				foreach ($clcPages as $clcPage)
				{
					$target_slug = get_post_meta( $clcPage->ID, "clc_category_key", true);
					$div_click = "";
					if ($target_slug !== "")
					{
						$div_click = "onclick=\"viewStory('" . get_permalink($story_page) . "?slug=" . urlencode($target_slug) . "')\"";
					}
					$args = array( 'numberposts' => 1, 'post_type'=> 'page', 'include' => $clcPage->ID, 'post_status' => 'published' );

					$myposts = get_posts($args);

					foreach($myposts as $mypost) {
						setup_postdata($mypost);
?>
	<div class="clc-stories-story<?php if ($div_click !== "") echo " active"; ?>" <?php echo $div_click; ?>>
<?php
						$content = get_the_content();
						$pos = strpos($content, "</a>");
						if ($pos === false)
						{
							$pos = strpos($content, "</embed>");
							if ($pos === false)
							{
								$pos = strpos($content, "</iframe>");
								if ($pos !== false)
								{
									$pos = $pos + 9;
								}
							}
							else
							{
								$pos = $pos + 8;
							}
						}
						else
						{
							$pos = $pos + 4;
						}

						$newstr = substr_replace($content, "<h2 class=\"clc-story-title\">" . $clcPage->post_title . "</h2>", $pos, 0);
						echo $newstr;
						echo "<p class=\"link-text\">Click to meet our students or learn more.</p>";
?>
	</div>
<?php
					}

				}
?>

</section>
<?php } elseif ($post->post_title == "Story") {
	$categoryId = get_cat_ID(get_query_var("slug"));

	if ($categoryId === 0)
	{
		noPostsExist(get_query_var("slug"));
	}
	else
	{
		$query = array(
			'numberposts' => -1,
			'category' => $categoryId,
			'orderby' => 'date',
			'order' => 'DESC',
			'post_type' => 'post',
			'suppress_filters' => true
		);

		$story_posts = get_posts( $query );
		$story_posts = randomize_array($story_posts, 1000);

		if ((count($story_posts) > 0) && ($story_posts[0]->ID != ""))
		{
?>
	<section class="clc-story">

		<?php
		 	setup_postdata($story_posts[0]);
			$content = get_the_content();

			$greeting = strpos($content, "<greeting/>");
			if ($greeting !== false)
			{
				$names = explode(" ", $story_posts[0]->post_title);
				$content = substr_replace($content, "<h2 class=\"clc-story-greeting\">Meet " . $names[0] . "</h2>", $greeting, 11);
			}
		 ?>
			<div class="clc-story-main-content">
				<?php echo $content;?>
			</div>
			<div class="clc-story-bottom-content">
		<?php
			for ($i = 1; $i < count($story_posts); $i++)
			{
				setup_postdata($story_posts[$i]);
				$content = get_the_content();

				//remove <a>
				$keepLooping = true;
				while ($keepLooping)
				{
					$tagLocStart = strpos($content, "<a ");
					if ($tagLocStart === false)
					{
						$keepLooping = false;
					}
					else
					{
						$tagLocEnd = strpos($content, ">", $tagLocStart) + 1;

						$content = substr_replace($content,"",$tagLocStart, $tagLocEnd - $tagLocStart);

						$tagLocStart = strpos($content, "</a>");
						$content = substr_replace($content,"",$tagLocStart, 4);
					}
				}

				$div_click = "onclick=\"viewStory('" . get_permalink($story_posts[$i]) . "')\"";
		?>
				<div class="clc-story-bottom-stories" <?php echo $div_click; ?>>
					<?php echo $content;?>
				</div>
		<?php
			}
		?>
			</div>
	</section>
<?php
		}
		else
		{
			noPostsExist(get_query_var("slug"));
		}
	}
 } else {
?>
		<!-- section -->
		<section>

			<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

				<?php // comments_template( '', true ); // Remove if you don't want comments ?>

				<br class="clear">

				<?php //edit_post_link(); ?>

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
<?php } ?>
		</div></main>

<?php //get_sidebar(); ?>

<?php //get_footer(); ?>
<?php } ?>

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
<?php
// 	$categoryId = get_cat_ID(get_query_var("slug"));

// 	if ($categoryId === 0)
// 	{
// 		noPostsExist(get_query_var("slug"));
// 	}
// 	else
// 	{
// 		$query = array(
// 			'numberposts' => -1,
// 			'category' => $categoryId,
// 			'orderby' => 'date',
// 			'order' => 'DESC',
// 			'post_type' => 'post',
// 			'suppress_filters' => true
// 		);

// 		$story_posts = get_posts( $query );
// 		$story_posts = randomize_array($story_posts, 1000);

// 		if (count($story_posts) > 0)
// 		{
?>
	<section class="clc-story">

		<?php
			global $post;
		 	setup_postdata($post);
			$content = get_the_content();
			$current_post_id = $post->ID;

			$greeting = strpos($content, "<greeting/>");
			if ($greeting !== false)
			{
				$names = explode(" ", $post->post_title);
				$content = substr_replace($content, "<h2 class=\"clc-story-greeting\">Meet " . $names[0] . "</h2>", $greeting, 11);
			}
		 ?>
			<div class="clc-story-main-content">
				<?php echo $content;?>
			</div>
			<div class="clc-story-bottom-content">
		<?php
			$cats = get_the_category();
			$stories = array();
			foreach($cats as $c)
			{
   				$cat = get_category( $c );
				if ($cat->name != "story")
				{
					$categoryId = get_cat_ID($cat->name);
					$query = array(
						'numberposts' => -1,
						'category' => $categoryId,
						'orderby' => 'date',
						'order' => 'DESC',
						'post_type' => 'post',
						'suppress_filters' => true
					);
					$stories = array_merge($stories, get_posts( $query ));
				}
			}
			$stories = randomize_array($stories, 1000);
			foreach ($stories as $story)
			{
				if ($story->ID != $current_post_id)
				{
					setup_postdata($story);
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

					$div_click = "onclick=\"viewStory('" . get_permalink($story) . "')\"";
		?>
				<div class="clc-story-bottom-stories" <?php echo $div_click; ?>>
					<?php echo $content;?><br/>
				</div>
		<?php
				}
			}
		?>
			</div>
	</section>
<?php
// 		}
// 		else
// 		{
// 			noPostsExist(get_query_var("slug"));
// 		}
// 	}
?>
		</div></main>

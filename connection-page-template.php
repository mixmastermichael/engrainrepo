<?php get_header('interior'); 
	/**
 * Template Name: Numbers Page Sightmap
 *
 *
 */	

?>




             <?php wp_reset_postdata(); ?>


<div id="topheading"> </div>


			<div id="content">

			  <div id="inner-content" class="wrap clearfix">

				<div id="main" class="eightcol first clearfix" role="main">
<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<section class="entry-content clearfix" itemprop="articleBody">
									<?php the_content(); ?>
							</section> <!-- end article section -->

								<footer class="article-footer">
									<?php the_tags('<span class="tags">' . __('Tags:', 'bonestheme') . '</span> ', ', ', ''); ?>

								</footer> <!-- end article footer -->

								<?php comments_template(); ?>

							</article> <!-- end article -->

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry clearfix">
										<header class="article-header">
											<h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e("This is the error message in the page.php template.", "bonestheme"); ?></p>
										</footer>
									</article>

							<?php endif; ?>

					  </div> <!-- end #main -->
		
				<!-- end #inner-content -->

			</div> <!-- end #content -->
            
 <?php
// this will execute the function and display the 2 lists in a html echo
echo list_sightmap_data_retreive();

// this is grabing the url and api-key to digest the data from the api
function list_sightmap_data_retreive() {
	$url    = 'https://api.sightmap.com/v1/assets/1273/multifamily/units?per-page=100API';
	$params = array( 'headers' => array( 'API-Key' => '7d64ca3869544c469c3e7a586921ba37' ) );

	// get the data using wp_remote_get
	$api_request = wp_remote_get( $url, $params );

	// check if something went wrong
	if ( is_wp_error( $api_request ) ) {
		return 'Something is wrong the API connection';
	}

	// decode the json data from the API ... wordpress has a great remote retrive function 
	$remote_body = json_decode( wp_remote_retrieve_body( $api_request ) );

	// check if data is not empty
	if ( empty( $remote_body->data ) ) {
		return 'No data found';
	}

	// build the lists
	$areas_with_1    = array();
	$areas_without_1 = array();

	foreach ( $remote_body->data as $key => $place ) {
		if ( $place->area == 1 ) {
			$areas_with_1[] = $place;
		} else {
			$areas_without_1[] = $place;
		}
	}

	/**
	 * These are the mapped keys that we can put in below... 
	 *
	 * You can use the following fields:
	 * $place->id => string '239653' (length=6)
	 * $place->asset_id => string '1273' (length=4)
	 * $place->floor_id' => string '4358' (length=4)
	 * $place->floor_plan_id' => string '13369' (length=5)
	 * $place->map_id' => string '239653' (length=6)
	 * $place->unit_number' => string '04102' (length=5)
	 * $place->area' => int 1
	 * $place->created_at' => string '2017-08-11T15:07:15+00:00' (length=25)
	 * $place->updated_at' => string '2017-08-11T15:07:15+00:00' (length=25)
	 * $place->view_image_url' => null
	 */
    //output buffering here
	ob_start();
    echo '<div class="list one">';
	echo '<h1>The first list with area value 1:</h1>';
	echo '<ul>';
	foreach ( $areas_with_1 as $place ) {
		echo '<li>';
		echo '<strong>Area (SqFt):</strong> ' . $place->area;
        echo ' <strong>Unit number:</strong> ' . $place->unit_number. $place->map_unit_number;
        echo ' <strong>Updated at</strong> ' . $place->updated_at. $place->map_updated_at;
		echo '</li>';
	}
	echo '</ul>';
    echo '</div>';

	echo '<div class="list two">';
	echo '<h1>The second list with area value greater than 1:</h1>';
	echo '<ul>';
	foreach ( $areas_without_1 as $place ) {
		echo '<li>';
		echo '<strong>Area (SqFt):</strong> ' . $place->area;
        echo ' <strong>Unit number:</strong> ' . $place->unit_number. $place->map_unit_number;
          echo ' <strong>Updated at</strong> ' . $place->updated_at. $place->map_updated_at;
		echo '</li>';
	}
	echo '</ul>';
    echo '</div>';


	return ob_get_clean();
}
?>

<style>
@import url('https://fonts.googleapis.com/css?family=Lato&display=swap');


b, strong, .strong {
    font-weight: bold;
    margin-left: 20px;
}


.list {
    float: left;
    margin: 2%;
    width: 46%;
    font-family: 'Lato', sans-serif;
    background-color: #3d3d3d;
    border-radius: 10px;
}

.list ul {
    list-style: none;
}

.list ul li {
    border-bottom: solid 2px;
    padding: 20px;
}

.list h1 {
	text-align: center;
	font-size: 20px;
	color: #ffffff;
    font-weight: bold;
}


.one {
	color: #20ce00;

}

.two {
	color: #ff6ec7;
}

// ~ mobilestyles //
 @media (max-width: 1023px) and (min-width: 320px){
	.list {
    float: left !important;
    clear: both !important;
    width: 100% !important;
    margin: 2% 0 2% 0 !important;
	}
}

</style>
             <?php wp_reset_postdata(); ?>



<?php get_footer(); ?>
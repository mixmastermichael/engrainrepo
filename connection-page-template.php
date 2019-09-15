<?php get_header();
	/**
 * Template Name: Numbers Page Sightmap
 *
 *
 */
?>
<style>
/*
in a this exmaple we are using an embeded stylesheet, in a real time application this would be linked out externally
*/
@import url('https://fonts.googleapis.com/css?family=Lato&display=swap');


b, strong, .strong {
    font-weight: bold;
    margin-left: 20px;
    color: yellow;
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

.divider {
    margin: 10px 0 10px 0;
}

/* mobile styles */
@media only screen and (max-width: 1023px) {
  .list {
    margin: 0 !important;
    width: 100% !important;
}

}

</style>

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

	// build the lists... these will be parsed with a foreach loop below
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
	 * These are the mapped keys that we can put in below...  these were found using postman and looking up the key values available with the api url and key
	 *
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

    //output buffering here with the exchos start 
	ob_start();
    echo '<div class="list one">';
	echo '<h1>The first list with area value of 1:</h1>';
	echo '<ul>';
	foreach ( $areas_with_1 as $place ) {
		echo '<li>';
        echo '<div class="divider">';
        echo ' <strong>Unit number:</strong> ' . $place->unit_number. $place->map_unit_number;
        echo '</div>';
        echo '<div class="divider">';
		echo '<strong>Area (SqFt):</strong> ' . $place->area;
        echo '</div>';
        echo '<div class="divider">';
        echo ' <strong>Updated at:</strong> ' . $place->updated_at. $place->map_updated_at;
        echo '</div>';
		echo '</li>';
	}
	echo '</ul>';
    echo '</div>';

	echo '<div class="list two">';
	echo '<h1>The second list with area value greater than 1:</h1>';
	echo '<ul>';
	foreach ( $areas_without_1 as $place ) {
		echo '<li>';
		echo '<div class="divider">';
        echo ' <strong>Unit number:</strong> ' . $place->unit_number. $place->map_unit_number;
        echo '</div>';
        echo '<div class="divider">';
		echo '<strong>Area (SqFt):</strong> ' . $place->area;
        echo '</div>';
        echo '<div class="divider">';
        echo ' <strong>Updated at:</strong> ' . $place->updated_at. $place->map_updated_at;
        echo '</div>';
		echo '</li>';
	}
	echo '</ul>';
    echo '</div>';

	// Gets the current buffer contents and delete current output buffer.

	return ob_get_clean();
}
?>

<?php get_footer(); ?>
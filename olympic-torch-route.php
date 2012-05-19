<?php
/*
Plugin Name: Olympic Torch Route
Plugin URI: http://philipjohn.co.uk/category/plugins/olympic-torch-route
Description: Easily place a map showing the olympic torch route into a post or page
Version: 0.1
Author: Philip John
Author URI: http://philipjohn.co.uk
License: GPL2

    Copyright (C) 2012 Philip John Ltd

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/*
 * Localise the plugin
 */
load_plugin_textdomain('olympic-torch-route');


/**
 * Stop the file being called directly
 */
if (!function_exists('add_action')){
	echo "Oh. Hello. I think you might be in the wrong place. There's nothing for you here.";
	exit;
}

/**
 * Register and enqueue the Leaflet script and styles
 */
function pj_otr_leafletjs(){
	if (!is_admin()){ //don't load these in admin
		// Register
		wp_register_script('leafletjs', 'http://code.leafletjs.com/leaflet-0.3.1/leaflet.js', 'jquery');
		wp_register_style('leafletjs', 'http://code.leafletjs.com/leaflet-0.3.1/leaflet.css');
		wp_register_style('leafletjsie', 'http://code.leafletjs.com/leaflet-0.3.1/leaflet.ie.css');
		
		// Enqueue
		wp_enqueue_script('jquery');
		wp_enqueue_script('leafletjs');
		wp_enqueue_style( 'leafletjs');
		$GLOBALS['wp_styles']->add_data( 'leafletjsie', 'conditional', 'lte IE 8' ); // add condition to IE-only style
		wp_enqueue_style( 'leafletjsie');
	}
}
add_action('wp_enqueue_scripts', 'pj_otr_leafletjs');

/**
 * The shortcode
 */
function pj_otr_shortcode($atts){
	return '<div id="map" style="height: 400px"></div>
	<script type="text/javascript">
		var map = new L.Map(\'map\');
		var cloudmade = new L.TileLayer(\'http://{s}.tile.cloudmade.com/44244adfc7ed4010a5899aae77532841/997/256/{z}/{x}/{y}.png\', {
		    attribution: \'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>\',
		    maxZoom: 18
		});
		
		// centre the map on Walsall to start with
		var walsall = new L.LatLng(52.598306, -2.018);
		map.setView(walsall, 12).addLayer(cloudmade);
		
		$.getJSON("'.plugins_url('visits.json', __FILE__).'visits.json", function(data) { 
			//When GeoJSON is loaded 
			var geojsonLayer = new L.GeoJSON(data); //New GeoJSON layer 
			map.addLayer(geojsonLayer); //Add layer to map 
		});
		
	</script>
	<div class="clear"></div>';
}
add_shortcode('olympic-torch-route', 'pj_otr_shortcode');

?>
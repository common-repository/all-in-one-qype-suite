<?php
/*
Plugin Name: All in on Qype Suite - Qype City Widget
Description: Adds a sidebar widget to display Qype City Widget
Author: Christoph Bütter
Version: 0.1
Author URI: 

*/

class QypeCityWidget extends QypeWidget {
	function QypeCityWidget() {
		if (function_exists('register_sidebar_widget')) { 
			register_sidebar_widget("Qype City Widget", array(&$this,"qype_widget_display"));
			register_widget_control("Qype City Widget", array(&$this,"qype_widget_control"));
		}	
	}	
	
	/**
	* Contains the widget logic
	*/
	function qype_widget_display($args) {
		$options = (array) get_option('qype-city-widget');
                $options['language'] = $this->get_language();
		$options['tld'] = $this->get_tld();
		$locator = QypeLocator::find_all_by_term($options['city']);
		$options['city_id'] = $locator->id;
		include ($this->getDisplayTemplate('city-widget/widget-display.php'));	}
	
	
	/**
	* Contains the widget control html
	*/
	function qype_widget_control() {
		$options = $newoptions = get_option('qype-city-widget');
		if ( $_POST["qype-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['qype-city-widget-title']));
			$newoptions['city'] = strip_tags(stripslashes($_POST['qype-city-widget-city']));
			$newoptions['rating'] = $_POST['qype-city-widget-rating'];
			$newoptions['count'] = (int) $_POST['qype-city-widget-count'];
		}		
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('qype-city-widget', $options);
		}		
		include(dirname(__FILE__).'/widget-options.php');
	}
}
?>

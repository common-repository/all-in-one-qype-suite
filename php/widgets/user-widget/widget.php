<?php
/*
Plugin Name: All in on Qype Suite - Qype User Widget
Description: Adds a sidebar widget to display Qype review and 
Author: Christoph Bütter
Version: 0.1
Author URI: 

*/

class QypeUserWidget extends QypeWidget {
	function QypeUserWidget() {
		if (function_exists('register_sidebar_widget')) { 
			register_sidebar_widget("Qype User Widget",array(&$this,"qype_widget_display"));
			register_widget_control("Qype User Widget",array(&$this,"qype_widget_control"));
		}	
	}	
	
	/**
	* Contains the widget logic
	*/
	function qype_widget_display($args) {
		$options = (array) get_option('qype-user-widget');
		$options['language'] = $this->get_language();
		$options['tld'] = $this->get_tld();	
		include ($this->getDisplayTemplate('user-widget/widget-display.php'));
	}
		
	/**
	* Contains the widget control html
	*/
	function qype_widget_control() {
		$options = $newoptions = get_option('qype-user-widget');
		if ( $_POST["qype-submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['qype-user-widget-title']));
			$newoptions['username'] = strip_tags(stripslashes($_POST['qype-user-widget-username']));
			$newoptions['rating'] = $_POST['qype-user-widget-rating'];
			$newoptions['count'] = (int) $_POST['qype-user-widget-count'];
		}		
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('qype-user-widget', $options);
		}		
		include(dirname(__FILE__).'/widget-options.php');
	}	
}
?>

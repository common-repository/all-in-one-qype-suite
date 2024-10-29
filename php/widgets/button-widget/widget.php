<?php
/*
Plugin Name: All in on Qype Suite - Qype User Button Widget
Description: Adds a sidebar widget to display Qype review and 
Author: Christoph Bütter
Version: 0.1
Author URI: 

*/

class QypeButtonWidget extends QypeWidget {
	function QypeButtonWidget() {
		if (function_exists('register_sidebar_widget')) { 
			register_sidebar_widget("Qype Button Widget",array(&$this,"qype_widget_display"));
			register_widget_control("Qype Button Widget",array(&$this,"qype_widget_control"));
		}	
	}
		
	/**
	* Contains the widget logic
	*/
	function qype_widget_display($args) {
		$options = (array) get_option('qype-button-widget');
		$options['language'] = $this->get_language();
		$options['tld'] = $this->get_tld();		
		$user = QypeUser::find_by_login($options['username']);
		$options['userid'] = $user->get_id();
		include ($this->getDisplayTemplate('button-widget/widget-display.php'));
	}	
	
	/**
	* Contains the widget control html
	*/
	function qype_widget_control() {		
		$options = $newoptions = get_option('qype-button-widget');
		if ( $_POST["qype-submit"] ) {
			$newoptions['username'] = strip_tags(stripslashes($_POST['qype-button-widget-username']));
			$newoptions['size'] = $_POST['qype-button-widget-size'];
		}		
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('qype-button-widget', $options);
		}		
		include(dirname(__FILE__).'/widget-options.php');
	}
}
?>

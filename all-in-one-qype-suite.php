<?php
/*
Plugin Name: All in One Qype Suite
Plugin URI: http://wordpress.org/extend/plugins/all-in-one-qype-suite/
Description: All in one Qype Suite "Qypifys" your Blog! Add beautiful tooltip-links to Qype Places, auto-geotag your post & pimp the sidebar with 3 different Qype Widgets
Author: Christoph Büttner
Version: 0.6
Generated At: www.wp-fun.co.uk;
*/

/*  Copyright 2009  Christoph Büttner (email : dev@jump-around.eu)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function qype_language_setting($locale) {
   if( !defined( 'QYPE_LANGUAGE') ) return $locale;
   return ( QYPE_LANGUAGE == 'de' ) ? 'de_DE' : 'en_US';   
}
add_filter('locale', 'qype_language_setting', 100, 1);

if (!class_exists('WpQypeSuite')) {
	require_once dirname(__FILE__).'/php/lib/qype_api.php';
	
	class WpQypeSuite {
		
		/**
		* @var string   The name the options are saved under in the database
		*/
		var $adminOptionsName = "qype-suite";
		
		/**
		* @var string   The options used by the plugin
		*/
		var $adminOptions = array();
		
		/**
		* @var string   The name of the database table used by the plugin
		*/
		static $db_table_name = '';
		
		/**
		* PHP 4 Compatible Constructor
		*/
		function WpQypeSuite() { $this->__construct(); }
		
		/**
		* PHP 5 Constructor
		*/
		function __construct() {
			global $wpdb;
			add_action('admin_menu', array(&$this,"add_admin_pages"));
			
			register_activation_hook(__FILE__,array(&$this,"install_on_activation"));
			add_action('plugins_loaded', array(&$this,"register_widgets" ) );
			
			add_action('init', array(&$this,"add_scripts"));
			add_action('init', array(&$this,"add_css"));
			
			add_filter('media_buttons_context',       array(&$this, 'media_buttons_context'));
			add_action('media_upload_qype-search',    array(&$this, 'media_upload_content'));
			add_action('media_upload_qype-favorites', array(&$this, 'media_upload_content_favorites'));
			
			add_action('save_post', array(&$this, 'save_geo_data'), 99);
			
			$this->adminOptions = $this->getAdminOptions();
			WpQypeSuite::$db_table_name = $wpdb->prefix . "qype_suite";
			
			add_shortcode('qype', array( &$this , 'qype_shortcode_handler' ) );
			
			$qypeStyleFile = WP_PLUGIN_URL.'/all-in-one-qype-suite/css/style.css';	    
			wp_register_style('qypeStyle', $qypeStyleFile);
			
			$qypeStyleFile = WP_PLUGIN_URL.'/all-in-one-qype-suite/css/style-new.css';
			wp_register_style('qypeNewStyle', $qypeStyleFile);
			//*****************************************************************************************
			// These lines allow the plugin to be translated into different languages
			// You will need to create the appropriate language files
			// this assumes your language files will be in the format: all-in-one-qype-suite-locationcode.mo
			// This also assumes your text domain will be: all-in-one-qype-suite
			// For more info: http://codex.wordpress.org/Translating_WordPress
			//*****************************************************************************************
			$locale = get_locale();
			$mofile = dirname(__FILE__)."/languages/".$locale.".mo";
			load_textdomain("qype-suite", $mofile);
			
		}
		
		/**
		* Creates or updates the database table, and adds a database table version number to the WordPress options.
		*/
		function install_on_activation() {
			global $wpdb;
			$plugin_db_version = "0.4";
			$installed_ver = get_option( "qype-suite_db_version" );
			//only run installation if not installed or if previous version installed
			if ($installed_ver === false || $installed_ver != $plugin_db_version) {
				
				//*****************************************************************************************
				// Create the sql - You will need to edit this to include the columns you need
				// Using the dbdelta function to allow the table to be updated if this is an update.
				// Read the limitations of the dbdelta function here: http://codex.wordpress.org/Creating_Tables_with_Plugins
				// remember to update the version number every time you want to make a change.
				//*****************************************************************************************
				$sql = "CREATE TABLE ".WpQypeSuite::$db_table_name." (
				id int NOT NULL,
				typ VARCHAR(20),
				object TEXT,
				created_at timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)
				);";
				require_once(ABSPATH . "wp-admin/upgrade-functions.php");
				dbDelta($sql);
				//add a database version number for future upgrade purposes
				update_option("qype-suite_db_version", $plugin_db_version);
			}
		}
		
		function register_widgets() {
			require_once( dirname(__FILE__)."/php/widgets/user-widget/widget.php");
			$GLOBALS['QypeUserWidget'] = & new QypeUserWidget();
			require_once( dirname(__FILE__)."/php/widgets/city-widget/widget.php");
			$GLOBALS['QypeCityWidget'] = & new QypeCityWidget();
			require_once( dirname(__FILE__)."/php/widgets/button-widget/widget.php");
			$GLOBALS['QypeButtonWidget'] = & new QypeButtonWidget();
			
		}
		
		/**
		* Retrieves the options from the database.
		* @return array
		*/
		function getAdminOptions() {
			$adminOptions = array("username" => "", "style" => "classic");
			$savedOptions = get_option($this->adminOptionsName);
			
			if(!empty($savedOptions)) {
				foreach ($savedOptions as $key => $option) {
					$adminOptions[$key] = $option;
				}
			}
			if( !empty( $_POST['qype-suite'] ) ) $adminOptions = shortcode_atts( $adminOptions, $_POST['qype-suite'] );
			
			update_option($this->adminOptionsName, $adminOptions);
			return $adminOptions;
		}
		
		/**
		* Saves the admin options to the database.
		*/
		function saveAdminOptions() {
			update_option($this->adminOptionsName, $this->adminOptions);
		}
		
		function add_admin_pages() {
			add_submenu_page('options-general.php', "All in one Qype Suite", "All in one Qype Suite", 10, "all-in-one-qype-suite", array(&$this,"output_sub_admin_page"));
		}
		
		/**
		* Outputs the HTML for the admin sub page.
		*/
		function output_sub_admin_page() {
			$options = $this->adminOptions;
			include(dirname(__FILE__).'/php/admin/options.php');
		}
		
		/**
		* qype_shortcode_handler - produces and returns the content to replace the shortcode tag
		*
		* @param array $atts  An array of attributes passed from the shortcode
		* @param string $content   If the shortcode wraps round some html, this will be passed.
		*/
		function qype_shortcode_handler($atts , $content = null) {
			$attributes = shortcode_atts(array( 'id' => 1, 'language' => 'de' ), $atts);
			//get place data
			$place = Tooltip::find_place($attributes['id']);			
			if( !$place->valid() ) return __('place not found', 'qype-suite');
			// render baloon with image, link, raiting address, map??			
			return Tooltip::dynamic($place, $content);
		}
		
		
		/**
		* Tells WordPress to load the scripts
		*/
		function add_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('cluetip', WP_PLUGIN_URL.'/all-in-one-qype-suite/js/jquery.cluetip.js', array('jquery'));
			wp_enqueue_script('qtip', WP_PLUGIN_URL.'/all-in-one-qype-suite/js/qtip.js', array('cluetip'));
		}
		
		/**
		* Adds a link to the stylesheet to the header
		*/
		function add_css() {
			$style = ($this->adminOptions['style'] != 'classic' ) ? 'qypeNewStyle' : 'qypeStyle';
			wp_enqueue_style( $style );
		}
		
		//*****************************************************************************************
		
		function media_buttons_context($context) {
			global $post_ID, $temp_ID;
			$dir = dirname(__FILE__);
			
			$image_btn = WP_PLUGIN_URL.'/all-in-one-qype-suite/imgs/icon.gif';
			$image_title = __('All in one Qype Suite - Place Chooser', 'qype-suite');
			
			$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
			
			$media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";
			$out = ' <a href="'.$media_upload_iframe_src.'&tab=qype-search&TB_iframe=true&height=300&width=640" class="thickbox" title="'.$image_title.'"><img src="'.$image_btn.'" alt="'.$image_title.'" /></a>';
			return $context.$out;
		}
		
		function media_upload_content_favorites () { $this->media_upload_content('qype-favorites'); }
		
		function media_upload_content($tab = 'qype-search') {
			add_filter('media_upload_tabs', array(&$this, 'media_upload_tabs'));
			wp_enqueue_style( 'media');
			add_action('qype_media_upload_header', 'media_upload_header');
			if( $tab == 'qype-favorites' ) {
				wp_iframe(array(&$this, 'media_favorites_tab'));
			}
			else {
				wp_iframe(array(&$this, 'media_search_tab'));
			}
		}
		
		function media_upload_tabs($tabs) {
			return array(
				'qype-search' =>    __('Search', 'qype-suite'),
				'qype-favorites' => __('Favorites', 'qype-suite')
				);
		}
		
		function media_search_tab() {
			do_action('qype_media_upload_header');
			if( !empty($_REQUEST['search_qype_query']) || !empty($_REQUEST['search_qype_locator']) ) {
				$term = $_REQUEST['search_qype_query'];
				$city = $_REQUEST['search_qype_locator'];
				$page = ( !empty($_REQUEST['search_qype_page'])) ? $_REQUEST['search_qype_page'] : 1;
				$places = QypePlace::find_all_by_term_and_city( $term, $city, 20, $page );
			}
			include(dirname(__FILE__).'/php/tabs/search-tab.php');
		}
		
		function media_favorites_tab() {
			do_action('qype_media_upload_header'); 	
			$places = Tooltip::find_places();
			include(dirname(__FILE__).'/php/tabs/favorites-tab.php');
		}
		
		function save_geo_data($post_id) {
			$post_id = (int) $post_id;
			if ( !$post =& get_post( $post_id ) )
				return false;
			preg_match( '/\[qype id="([0-9]+)"\]/s', $post->post_content, $erg);
			if( !empty( $erg[1]) ) {
				$place = Tooltip::find_place($erg[1]);
				update_post_meta($post_id, '_geo_location', $place->point);
				update_post_meta($post_id, '_wp_geo_longitude', $place->lng());
				update_post_meta($post_id, '_wp_geo_latitude', $place->lat());	
			}
		}				
	}
	
	class QypeWidget {		
		function getDisplayTemplate($file) {
			$path = file_exists(TEMPLATEPATH . '/'.$file) ? TEMPLATEPATH : dirname(__FILE__);
			return $path.'/php/widgets/'.$file;
		}
		
		function get_language() {
			list( $language, $country ) = explode( '_', get_locale() );
			return $language;
		}
		
		function get_tld() {
			$lng = $this->get_language();
			switch( $lng ) {
			case 'en':
				return 'co.uk';
			default:
				return $lng;
			}
		}
	}
	
	class Tooltip {
		static $places = array();
				
		static function find_place( $id, $use_caching = true ) {
			global $wpdb;
			$db_table_name = WpQypeSuite::$db_table_name;
			if( !empty( Tooltip::$places[$id] ) ) return Tooltip::$places[$id];
			
			if( $use_caching ) {
				$place_row = $wpdb->get_row("SELECT object FROM ".$db_table_name." WHERE id = ".$id." AND typ = 'place' AND created_at > SUBTIME(NOW(),'48:0:0')");
				if( $place_row->object ) {
					Tooltip::$places[$id] = unserialize( $place_row->object );
					return Tooltip::$places[$id];
				}
			}
			Tooltip::$places[$id] = QypePlace::find_by_id( $id);
			$obj = addslashes( serialize(Tooltip::$places[$id]) );
			$query = "INSERT INTO ".$db_table_name." SET id = ".$id.", typ = 'place', object ='".$obj."' ON DUPLICATE KEY UPDATE object ='".$obj."'";
			if( $use_caching ) $wpdb->query( $wpdb->prepare( $query ) );
			return Tooltip::$places[$id];
		}
		
		static function find_places( $size = 50 ) {
			global $wpdb;
			$places = array();
			$rows = $wpdb->get_results("SELECT object FROM ".WpQypeSuite::$db_table_name." WHERE typ = 'place' ORDER BY created_at DESC LIMIT ".$size);
			foreach( $rows as $row) {  
				$places[] = unserialize( $row->object );
			}
			return $places;
		}
		
		static function dynamic( $place, $title = '', $link = '' ) {
			// set  content to place title if content empty
			if( empty( $title ) ) $title = $place->title;
			if( empty( $link ) )  $link  = $place->link;
			if( get_locale() == 'de_DE' ) $link = str_replace( 'qype.co.uk', 'qype.com', $link );
			
			$content =
			'<a title="<div class='.__('en', 'qype-suite').'/></div>" class="qtip" rel="span.place'.$place->id.'" href="'.$link.'">'.$title.'</a>
			<span class="place'.$place->id.'" style="display:none"><a href="'.$place->link.'"><img src="'.$place->image_medium.'" align="right" border="0" /></a>
			<strong><a href="'.$place->link.'">'.$place->title.'</a></strong><br />'.
			$place->street.' '.$place->housenumber.'<br />'.
			$place->postcode.' '.$place->city.'<br/><br/>'.
			$place->phone.'<br />
			<a href="'.$place->url.'">'.substr($place->url, 50).'</a><br /><br />
			<strong style="float:left">'.__('Qype Rating', 'qype-suite').':</strong><span class="rates rating'.$place->average_rating.'">&nbsp;</span> &nbsp;'.$place->review_count().' '.__('reviews', 'qype-suite').'
			</span>';
			return $content;
		}

		static function simple( $place, $title = '', $link = '', $include_css = false ) {
			// set  content to place title if content empty
			$content = '';
			if( empty( $title ) ) $title = $place->title;
			if( empty( $link ) )  $link  = $place->link;			
			if( get_locale() == 'de_DE' ) $link = str_replace( 'qype.co.uk', 'qype.com', $link );			
			
			if( $include_css ) {
			  $content = '<style type="text/css">'.file_get_contents( dirname(__FILE__).'/php/standalone/style.css').'</style>';
			}
			$content .= '<a class="qt" href="'.$link.'">'.$title.'<span>'.
                           '<div class="h"><img src="http://assets0.qype.com/images/logos/qype_logo_de.png" height="30"></div>'.
                           '<div class="t"><img src="'.$place->image_medium.'" align="right" border="0" /><strong>'.$place->title.'</strong><br />'.$place->street.' '.$place->housenumber.'<br />'.$place->postcode.' '.$place->city.'<br/><br/>'.$place->phone.'<br /><br />'.
                           '<strong>'.__('Qype Rating', 'qype-suite').':</strong>&nbsp;<img src="http://assets2.qype.com/images/rating_small_'.$place->average_rating.'.png">&nbsp;'.$place->review_count().' '.__('reviews', 'qype-suite').'</div>'.
                           '</span></a>';
			return $content;			   
		}

	}		
}

//instantiate the class
if (class_exists('WpQypeSuite')) {
	$WpQypeSuite = new WpQypeSuite();
}

?>
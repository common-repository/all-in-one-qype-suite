<?php 
#define('QYPE_TRACKING_ID', 'UA-7927378-1');

$language = ( !empty($_REQUEST['language']) ) ? $_REQUEST['language'] : 'de';
define( 'QYPE_LANGUAGE', $language);

require_once('../../../../../wp-load.php');

if( empty( $_REQUEST['search_qype_place_id'] ) ) {
	if( !empty($_REQUEST['search_qype_query']) || !empty($_REQUEST['search_qype_locator']) ) {
		$term = $_REQUEST['search_qype_query'];
		$city = $_REQUEST['search_qype_locator'];
		$page = ( !empty($_REQUEST['search_qype_page'])) ? $_REQUEST['search_qype_page'] : 1;
		$places = QypePlace::find_all_by_term_and_city( $term, $city, 20, $page );
	}
	$page_to_load = 'search';
}
else {
	$place    = Tooltip::find_place($_REQUEST['search_qype_place_id']);
	$title    = ( !empty($_REQUEST['search_qype_title']))    ? $_REQUEST['search_qype_title']    : $place->title;
	$style    = ( !empty($_REQUEST['search_qype_style']))    ? $_REQUEST['search_qype_style']    : '';
	$page_to_load = 'result';
}	

$path = pathinfo( $_SERVER['PHP_SELF'] );
$url_path = ( $path['dirname'] != '/') ? $path['dirname'] : '';

$l = (QYPE_LANGUAGE == 'de' ) ? '<a href="?language=en">'.__('English', 'qype-suite').'</a>' : '<a href="?language=de">'.__('German', 'qype-suite').'</a>';
$lang_switch = '<div style="float:right">'.$l.'</div>';
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de-DE">
  <title><?php _e('Link to a Qype Place from your website', 'qype-suite'); ?></title>
  <head profile="http://gmpg.org/xfn/11">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
     <?php echo file_get_contents( '../../../../../wp-admin/css/media.css' ); ?>
     <?php echo file_get_contents( '../../../../../wp-admin/css/global.css' ); ?>
     <?php echo file_get_contents( '../../../../../wp-admin/wp-admin.css' ); ?>     
     <?php echo file_get_contents( '../../../../../wp-admin/css/colors-fresh.css' ); ?>
    </style> 
    <link rel='stylesheet' href='<?php echo $url_path; ?>/style.css' type='text/css' media='all' />
  </head>
  <body> 
    <?php  require_once dirname(__FILE__).'/'.$page_to_load.'.php'; ?>
    <br><br>
    <div style="font-size: 10px; padding: 20px;" align="center">
      Link2Qype is part of the <a href="http://wordpress.org/extend/plugins/all-in-one-qype-suite/">All-in-one-Qype-Suite</a> Wordpress Plugin<br />
      <em>fully powered by <a href="http://www.qype.com" target="_blank">Qype API</a></em>   
    </div>   
<?php   if( defined( 'QYPE_TRACKING_ID')) { ?>
    <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
    try {
    var pageTracker = _gat._getTracker("<?php echo QYPE_TRACKING_ID; ?>");
    pageTracker._trackPageview();
    } catch(err) {}</script>
<?php } ?>    
     
  </body>
</html>

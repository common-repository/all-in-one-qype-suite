<?php  
  $param = '?search_qype_locator='.$city.'&search_qype_query='.$term.'&search_qype_page=';	
  $pagination ='<a href="'.$url_path.$param.($page+1).'">'.__('next page','qype-suite').' &gt;&gt;</a>';
  if( $page > 1) { 
    $pagination ='<a href="'.$url_path.$param.($page-1).'">&lt;&lt; '.__('previous page','qype-suite').'</a> &bull; '.$pagination; 
  } 
  $pagination = __('Page:', 'qype-suite').' '.$page.' '.$pagination;
?>

<div style="padding: 5px; margin: 0px auto; width: 700px;">
<?php echo $lang_switch; ?>
<form method="get" action="<?php echo $url_path; ?>" class="media-upload-form type-form validate">
  <input type="hidden" name="search_qype_page" value="1" />
  <input type="hidden" name="language" value="<?php echo QYPE_LANGUAGE; ?>" />
  
  <h3 class="media-title"><?php _e('Link to a Qype Place from your website', 'qype-suite'); ?></h3>
  
  <?php _e('First, search for the place on Qype. Second, mouseover placelink for a preview and click to get the code for your website', 'qype-suite'); ?><br /><br />
  <div class="SearchBox" align="center">
    <table cellspacing="5" align="center">
     <tr>
      <td>
        <label><?php _e('What?', 'qype-suite'); ?></label>
        <input id="search_qype_query" name="search_qype_query"   type="text" value="<?php echo $term; ?>" />
      </td>
      <td>
        <label><?php _e('Where?', 'qype-suite'); ?></label>
        <input id="search_qype_locator" name="search_qype_locator" type="text" value="<?php echo $city; ?>" />
      </td>
      <td valign="bottom"><br>
       <input type="submit" value="<?php _e('start search', 'qype-suite'); ?>" class="button">
      </td>
     </tr>
    </table>   
  </div>
  <br />
  
  <?php if(!empty($places)) echo $pagination; ?>
  <hr />
  <table>
  <?php foreach( (array) $places AS $place ) {
	  $link = $url_path.'?language='.QYPE_LANGUAGE.'&search_qype_place_id='.$place->id;
	  echo '<tr><td>'.Tooltip::simple($place, '', $link, false).' - '.$place->street.' '.$place->housenumber.', '.$place->postcode.' '.$place->city.'</td></tr>';
  }
  ?>	    
  </table>
</form>
    <?php if( empty($places) ) {   	    
        if( !empty($city) || !empty($term) ) { 
          echo "<em>".__('Sorry, nothing found', 'qype-suite')."</em><hr />";  
	}
	else {
     ?>
     
      <div align="left">
            <b><?php _e('Examples', 'qype-suite'); ?>:</b><br />
           <?php _e('Do you know', 'qype-suite'); ?> <style type="text/css">a.qt {position:relative;text-decoration:none;}a.qt span {display:none;top:10px;left:100px;width:270px;border:1px solid #dddddd;background:white;}a.qt .h {width:264px;background:white;padding:3px;border-bottom: 4px solid #FF0033;}a.qt .t {padding:3px;font: 10pt Arial;color:black;background-color:#DAF8FF;text-decoration:none;}a.qt:hover span {position:absolute;display:block;z-index:99;}</style><a class="qt" href="http://www.qype.com/place/21391-Burg-Nuernberg-Kaiserburg--Nuernberg">Burg Nürnberg (Kaiserburg)<span><div class="h"><img src="http://assets0.qype.com/images/logos/qype_logo_de.png" height="30"></div><div class="t"><img src="http://assets3.qype.com/uploads/photos/0062/7495/SN151399_thumb.JPG" align="right" border="0" /><strong>Burg Nürnberg (Kaiserburg)</strong><br />Auf der Burg 13<br />90403 Nürnberg<br/><br/>0911 24 46 590<br /><br /><strong>Qype Bewertung:</strong> <img src="http://assets2.qype.com/images/rating_small_5.png"> 15 Beiträge</div></span></a> <?php _e('or my Favorite', 'qype-suite'); ?> 
          <style type="text/css">a.qt {position:relative;text-decoration:none;}a.qt span {display:none;top:10px;left:100px;width:270px;border:1px solid #dddddd;background:white;}a.qt .h {width:264px;background:white;padding:3px;border-bottom: 4px solid #FF0033;}a.qt .t {padding:3px;font: 10pt Arial;color:black;background-color:#DAF8FF;text-decoration:none;}a.qt:hover span {position:absolute;display:block;z-index:99;}</style><a class="qt" href="http://www.qype.com/place/8133-Zeit-Raum-Nuernberg">Zeit & Raum<span><div class="h"><img src="http://assets0.qype.com/images/logos/qype_logo_de.png" height="30"></div><div class="t"><img src="http://assets2.qype.com/uploads/photos/0028/6585/IMGP0315_thumb.JPG" align="right" border="0" /><strong>Zeit & Raum</strong><br />Wespennest 2<br />90403 Nürnberg<br/><br/>0911 227406<br /><br /><strong>Qype Bewertung:</strong> <img src="http://assets2.qype.com/images/rating_small_4.png"> 28 Beiträge</div></span></a>?
      </div>
      
      <?php 
        }		
      } else {
	echo "<hr />".$pagination;
      } ?>
</div>

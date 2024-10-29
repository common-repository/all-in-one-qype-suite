<div style="margin: 13px;">
<?php 
  $url_path = (ereg('media-upload.php', $_SERVER['REQUEST_URI']) ? 'media-upload.php' : 'upload.php'); 
  $param = '?tab='.$_REQUEST['tab'].'&post_id='.$_REQUEST['post_id'].'&search_qype_locator='.$city.'&search_qype_query='.$term.'&search_qype_page=';	
  $pagination ='<a href="'.$url_path.$param.($page+1).'">'.__('next page','qype-suite').' &gt;&gt;</a>';
  if( $page > 1) { 
    $pagination ='<a href="'.$url_path.$param.($page-1).'">&lt;&lt; '.__('previous page','qype-suite').'</a> &bull; '.$pagination; 
  } 
  $pagination = __('Page:', 'qype-suite').' '.$page.' '.$pagination;
?>

<form method="get" action="<?php echo $url_path; ?>" class="media-upload-form type-form validate">
  <input type="hidden" name="tab" value="<?php echo $_REQUEST['tab'];?>" />
  <input type="hidden" name="post_id" value="<?php echo $_REQUEST['post_id'];?>" />
  <input type="hidden" name="search_qype_page" value="1" />
  
  <h3 class="media-title"><?php _e('Search for the place on Qype you like to add', 'qype-suite'); ?></h3>
  
  <div class="SearchBox">
    <table cellspacing="5" align="center">
     <tr>
      <td>
        <label><?php _e('What?', 'qype-suite'); ?></label>
        <input id="search_qype_query" name="search_qype_query" type="text" value="<?php echo $term; ?>" />
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
  <br /> <br />
  
<script type="text/javascript">
//<![CDATA[
	function insert(id, title){
                var html = '[qype id="' + id + '"]';
		if( title ) {
			html += (title + '[/qype]');
		}
		var win = window.dialogArguments || opener || parent || top;
		win.send_to_editor(html);
	}
//]]>
</script>
  
  <?php if(!empty($places)) echo $pagination; ?>
  <hr />
  <table>
  <?php foreach( (array) $places AS $place ) {
	  $link = 'javascript:insert('.$place->id.')';
	  echo '<tr><td>'.Tooltip::dynamic($place, '', $link).' - '.$place->street.' '.$place->housenumber.', '.$place->postcode.' '.$place->city.'</td></tr>';
  }
  ?>	    
  </table>
</form>

<?php if( empty($places) ) {   	    
        if( !empty($city) || !empty($term) ) { 
          echo "<em>".__('Sorry, nothing found', 'qype-suite')."</em><hr />";  
	}
      } else {
	echo "<hr />".$pagination;
      }
?>

<br><br>
<em style="font-size: 9px;">powered by <a href="http://www.qype.com" target="_blank">Qype API</a></em>
</div>

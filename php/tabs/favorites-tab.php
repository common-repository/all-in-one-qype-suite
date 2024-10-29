<div style="margin: 13px;">
  
  <h3 class="media-title"><?php _e('Pick a Qype place from your favorites', 'qype-suite'); ?></h3>
  
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
    
    <hr />
    <table>
    <?php foreach( (array) $places AS $place ) {
  	  $link = 'javascript:insert('.$place->id.')';
  	  echo '<tr><td>'.Tooltip::dynamic($place, '', $link).' - '.$place->street.' '.$place->housenumber.', '.$place->postcode.' '.$place->city.'</td></tr>';
    }
    ?>	    
    </table>
    <em><?php 
      if( empty($places) ) {
  	_e('Sorry, you have no favorite places yet', 'qype-suite');
      }
    ?></em>
  
  <br><br>
  <em style="font-size: 9px;">powered by <a href="http://www.qype.com" target="_blank">Qype API</a></em>
</div>

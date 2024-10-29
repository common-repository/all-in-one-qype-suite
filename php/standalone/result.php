<?php 
  $tooltip = Tooltip::simple($place, $title, '', true); 
?>
<div style="padding: 5px; margin: 0px auto; width: 700px;">
  <?php echo $lang_switch; ?>
  <form method="get" action="<?php echo $url_path; ?>" class="media-upload-form type-form validate">
    <input type="hidden" name="search_qype_place_id" value="<?php echo $place->id; ?>" />
    <input type="hidden" name="language" value="<?php echo QYPE_LANGUAGE; ?>" />
    
    <h3 class="media-title"><?php _e('Link to a Qype Place from your website', 'qype-suite'); ?></h3>
  
    <div class="SearchBox">
      <table cellspacing="5" align="center">
       <tr>
        <td><label><?php _e('Link text', 'qype-suite'); ?>:</label></td>
        <td><input id="search_qype_query" name="search_qype_title" type="text" size="60" value="<?php echo $title; ?>" /></td>
       </tr>
  <!--     <tr>  
        <td><label><?php _e('Style', 'qype-suite'); ?>:</label></td>
        <td><input id="search_qype_language" name="search_qype_language" type="text" value="<?php echo QYPE_LANGUAGE; ?>" /></td>
       </tr>     
       <tr>  
        <td><label><?php _e('Language', 'qype-suite'); ?>:</label></td>
        <td><input id="search_qype_language" name="search_qype_language" type="text" value="<?php echo QYPE_LANGUAGE; ?>" /></td>
       </tr> -->
      <tr><td colspan="2" align="center"><input value="<?php _e('Update', 'qype-suite'); ?>"  class="button" type="submit">&nbsp;  <a href="?language=<?php echo QYPE_LANGUAGE; ?>"><?php _e('New search', 'qype-suite'); ?></a></td></tr> 
      </table>   
    </div>
    <hr />
    <br />
    <b><?php _e('Preview', 'qype-suite'); ?>:</b> &nbsp;&nbsp;  <?php echo $tooltip; ?>
    
    <br /> <br />  
    <?php _e('Copy & paste this HTML code to your website - and you get a beautiful link to the Qype place.', 'qype-suite'); ?><br /><br />
    <textarea readonly="true" cols="80" rows="15"><?php echo str_replace( '  ', '', str_replace( "\n", '', $tooltip ) ); ?></textarea>
    <br />
  </form>
</div>

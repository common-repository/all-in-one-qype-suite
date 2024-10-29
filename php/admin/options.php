<div class="wrap">
 <h2><?php _e('All in One Qype Suite Configuration',  'qype-suite'); ?></h2>

 <form method="post" style="clear:both">
   <input type="hidden" name="action" value="qyp-suite-settings" />

   <p>
     <label for="qype-user-widget-title"><?php _e('Username', 'qype-suite'); ?>:
     <input type="text" name="qype-suite[username]" value="<?php echo htmlspecialchars($options['username']); ?>" />
     </label>
   </p>
   
   <p>
     <label for="qype-user-widget-rating">
     <?php _e('Qype Theme', 'qype-suite'); ?>: 
      <input type="radio" id="qype-suite-style" name="qype-suite[style]" size="4" value="classic" <?php echo ($options['style'] == 'classic' ) ? 'checked="checked"' : ''; ?>/> <?php _e('Classic', 'qype-suite'); ?>
      <input type="radio" id="qype-suite-style" name="qype-suite[style]" size="4" value="new"     <?php echo ($options['style'] != 'classic' ) ? 'checked="checked"' : ''; ?>/> <?php _e('New', 'qype-suite'); ?>
     </label>
   </p>

   <input type="submit" name="submit" value="<?php _e('Save Changes', 'qype-suite'); ?>" />
  </form>
</div>

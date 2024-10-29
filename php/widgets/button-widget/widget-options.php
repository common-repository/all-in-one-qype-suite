<p>
<label for="qype-button-widget-username"><?php _e('Username', 'qype-suite'); ?>:
<input type="text" id="qype-button-widget-username" class="widefat" name="qype-button-widget-username" value="<?php echo htmlspecialchars($options['username']); ?>" />
</label>
</p>

<p>
<label for="qype-button-widget-size">
<?php _e('Size', 'qype-suite'); ?>: 
 <input type="radio" id="qype-button-widget-size" name="qype-button-widget-size" value="large" <?php echo ($options['size'] == 'large') ? 'checked="checked"' : ''; ?>/> <?php _e('Large', 'qype-suite'); ?>
 <input type="radio" id="qype-button-widget-size" name="qype-button-widget-size" value="medium" <?php echo ($options['size'] == 'medium') ? 'checked="checked"' : ''; ?>/> <?php _e('Medium', 'qype-suite'); ?>
 <input type="radio" id="qype-button-widget-size" name="qype-button-widget-size" value="small" <?php echo ($options['size'] == 'small') ? 'checked="checked"' : ''; ?>/> <?php _e('Small', 'qype-suite'); ?>
</label>
</p>

<input type="hidden" id="qype-submit" name="qype-submit" value="1" />
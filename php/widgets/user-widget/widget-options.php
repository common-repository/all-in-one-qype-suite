<p>
<label for="qype-user-widget-title"><?php _e('Title', 'qype-suite'); ?>:
<input type="text" id="qype-user-widget-title" class="widefat" name="qype-user-widget-title" value="<?php echo htmlspecialchars($options['title']); ?>" />
</label>
</p>

<p>
<label for="qype-user-widget-title"><?php _e('Username', 'qype-suite'); ?>:
<input type="text" id="qype-user-widget-username" class="widefat" name="qype-user-widget-username" value="<?php echo htmlspecialchars($options['username']); ?>" />
</label>
</p>

<p>
<label for="qype-user-widget-count"><?php _e('Reviews count', 'qype-suite'); ?>:
<input type="text" id="qype-user-widget-count" class="widefat" name="qype-user-widget-count" size="4" value="<?php echo htmlspecialchars($options['count']); ?>" style="width:25px;text-align:center;" />
</label>
</p>
<p>
<label for="qype-user-widget-rating">
<?php _e('Show ratings', 'qype-suite'); ?>: <input type="checkbox" id="qype-user-widget-rating" name="qype-user-widget-rating" size="4" value="1" <?php echo ($options['rating'] == 1) ? 'checked="checked"' : ''; ?>/>
</label>
</p>

<input type="hidden" id="qype-submit" name="qype-submit" value="1" />
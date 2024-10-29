<p>
<label for="qype-city-widget-title"><?php _e('Title', 'qype-suite'); ?>:
<input type="text" id="qype-city-widget-title" class="widefat" name="qype-city-widget-title" value="<?php echo htmlspecialchars($options['title']); ?>" />
</label>
</p>

<p>
<label for="qype-city-widget-title"><?php _e('City', 'qype-suite'); ?>:
<input type="text" id="qype-city-widget-city" class="widefat" name="qype-city-widget-city" value="<?php echo htmlspecialchars($options['city']); ?>" />
</label>
</p>

<p>
<label for="qype-city-widget-count"><?php _e('Reviews count', 'qype-suite'); ?>:
<input type="text" id="qype-city-widget-count" class="widefat" name="qype-city-widget-count" size="4" value="<?php echo htmlspecialchars($options['count']); ?>" style="width:25px;text-align:center;" />
</label>
</p>
<p>
<label for="qype-city-widget-rating">
<?php _e('Show ratings', 'qype-suite'); ?>: <input type="checkbox" id="qype-city-widget-rating" name="qype-city-widget-rating" size="4" value="1" <?php echo ($options['rating'] == 1) ? 'checked="checked"' : ''; ?>/>
</label>
</p>

<input type="hidden" id="qype-submit" name="qype-submit" value="1" />
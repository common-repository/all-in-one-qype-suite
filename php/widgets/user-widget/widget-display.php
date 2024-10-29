<script type="text/javascript">
var qypetoolConfig = { 
   reviewCount: <?php echo $options['count']; ?>,
   headline: '<?php echo $options['title']; ?>',
   showStars: <?php echo ($options['rating'] == 1) ? 'true' : 'false'; ?>
}
</script><script src="http://www.qype.<?php echo $options['tld']; ?>/qypetool/user_widget/<?php echo $options['username']; ?>.<?php echo $options['language']; ?>.js" type="text/javascript"></script>
<p class="qypeLinkToQype"><?php _e('I am', 'qype-suite'); ?> <a href="http://www.qype.<?php echo $options['tld']; ?>/people/<?php echo $options['username']; ?>"><?php echo $options['username']; ?></a> <?php _e('on', 'qype-suite'); ?> <a href="http://www.qype.<?php echo $options['tld']; ?>">Qype</a></p>
<br />

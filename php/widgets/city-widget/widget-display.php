<script type="text/javascript">
var qypetoolConfig = { 
   reviewCount: <?php echo $options['count']; ?>,
   headline: '<?php echo $options['title']; ?>',
   showStars: <?php echo ($options['rating'] == 1) ? 'true' : 'false'; ?>
}
</script><script src="http://www.qype.<?php echo $options['tld']; ?>/qypetool/city_widget/<?php echo $options['city_id']; ?>.<?php echo $options['language']; ?>.js" type="text/javascript"></script>
<br />

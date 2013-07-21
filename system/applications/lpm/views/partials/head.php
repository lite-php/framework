<head>
	<title><?php echo isset($this->title) ? $this->title : "Todos"; ?></title>
	<?php $this->partial('scripts'); ?>
	<?php $this->partial('stylesheets'); ?>
	<!-- Set the base url for javscript applications -->
	<script type="text/javascript">
		//<![CDATA[
			var application = {
				base		: "<?php echo $this->route(); ?>",
				ajax_base	: "<?php echo $this->route('ajax'); ?>"	
			}
		//]]>
	</script>
</head>
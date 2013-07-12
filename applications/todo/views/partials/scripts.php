<!-- scripts -->
<?php if(isset($this->scripts)): ?>
	<?php foreach ($this->scripts as $script): ?>
		<script type="text/javascript" src="/litephp/static/todo/scripts/<?php echo $script ?>.js"></script>
	<?php endforeach; ?>
<?php endif; ?>
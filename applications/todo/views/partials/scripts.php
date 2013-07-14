<!-- scripts -->
<?php if(isset($this->scripts)): ?>
	<?php foreach ($this->scripts as $script): ?>
		<script type="text/javascript" src="<?php echo $this->link("static/todo/scripts/$script.js"); ?>"></script>
	<?php endforeach; ?>
<?php endif; ?>
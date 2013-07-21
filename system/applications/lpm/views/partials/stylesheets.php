<!-- stylesheets -->
<?php if(isset($this->stylesheets)): ?>
	<?php foreach ($this->stylesheets as $stylesheet): ?>
		<link rel="stylesheet" href="<?php echo $this->link("static/todo/stylesheets/$stylesheet.css"); ?>" />
	<?php endforeach; ?>
<?php endif; ?>
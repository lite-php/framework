<!-- stylesheets -->
<?php if(isset($this->stylesheets)): ?>
	<?php foreach ($this->stylesheets as $stylesheet): ?>
		<link rel="stylesheet" href="/litephp/static/todo/stylesheets/<?php echo $stylesheet ?>.css" />
	<?php endforeach; ?>
<?php endif; ?>
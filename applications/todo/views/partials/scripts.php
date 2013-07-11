<!-- Scripts -->
<?php if(isset($this->scripts)): ?>
	<?php foreach ($$this->scripts as $script): ?>
		<script type="text/javascript" src="<?php echo $script ?>"></script>
	<?php endforeach; ?>
<?php endif; ?>
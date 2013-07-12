<!DOCTYPE html>
<html>
	<!-- partial(header) -->
	<?php $this->partial('head') ?>

	<!-- body -->
	<body>
		<!-- Primary Container -->
		<div class="container">
			<!-- Header Row -->
			<div class="row">
				<?php $this->partial('todo/header'); ?>
			</div>

			<!-- Create Row -->
			<div class="row">
				<?php $this->partial('todo/create'); ?>
			</div>
		</div>
	</body>
</html>
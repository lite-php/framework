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

			<!-- Auto mark as completed -->
			<div class="row">
				<?php $this->partial("todo/markall"); ?>
			</div>

			<!-- Todo list -->
			<div class="row">
				<?php $this->partial("todo/list", $this->todos); ?>
			</div>
		</div>
	</body>
</html>
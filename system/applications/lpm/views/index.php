<?php
/**
 * LightPHP Framework
 * LitePHP is a framework that has been designed to be lite waight, extensible and fast.
 * 
 * @author Robert Pitt <robertpitt1988@gmail.com>
 * @category core
 * @copyright 2013 Robert Pitt
 * @license GPL v3 - GNU Public License v3
 * @version 1.0.0
 *
 * Note: All files within the subfolder "partials" inherit the license outline above.
 */?>
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

			<!-- Notices Row -->
			<div class="row">
				<?php $this->partial('todo/noticies'); ?>
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
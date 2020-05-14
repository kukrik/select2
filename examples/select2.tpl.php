<?php require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div class="instructions">
	<h1 class="instruction_title">The Select2 Control</h1>
	<b>Select2</b> wraps the ListBox control with the select2 jQuery plugin.
	<br/><br/>
	<!--Below is the <a href="<?php /*echo __EXAMPLES__; */ ?>/basic_qform/listbox.php"> same example as the one for QListBox</a>, but uses QSelect2ListBox.-->
</div>

<div>
	<?= _r($this->lstPersons); ?>
	Currently Selected: <?= _r($this->lblMessage); ?>
</div>

<?php $this->RenderEnd(); ?>


<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>

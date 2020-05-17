<?php require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>
<?php $this->RenderBegin(); ?>

<div class="instructions">
    <h1 class="instruction_title">The Select2 Control</h1>
    <b>Select2</b> wraps the ListBox control with the select2 jQuery plugin.
    <br/><br/>
    Below is the <a href="<?php echo QCUBED_EXAMPLES_URL; ?>/basic_qform/listbox.php"> same example as the one for
        ListBox</a>, but uses Select2.
</div>

<div style="display: inline-block; width: 50%; float: left;">
    <h2>Single listbox with Bootrtap theme</h2>
    <?= _r($this->lstSingle); ?>
</div>

<div style="display: inline-block; width: 50%; float: left;">
    <h2>Multiple listbox with Bootrtap theme</h2>
    <?= _r($this->lstMultiple); ?>
</div>

<div style="clear: both; padding-top: 30px; padding-left: 20%">
    Currently Selected: <?= _r($this->lblMessage); ?>
</div>

<div style="clear: both; height: 20px;"></div>

<?php $this->RenderEnd(); ?>
<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>

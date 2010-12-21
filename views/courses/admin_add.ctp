<div class="courses">
<?php echo $this->Form->create('Course');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Course', true)); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>

	<?php echo $this->Html->link(__('Cancel', true), array('action' => 'index', 'admin' => false));?>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
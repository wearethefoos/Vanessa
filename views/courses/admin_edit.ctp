<div class="courses">
<?php echo $this->Form->create('Course');?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), $this->data['Course']['name']); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
	?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
	<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Course.id')), null, sprintf(__('Are you sure you want to delete %s?', true), $this->Form->value('Course.name'))); ?>
	<?php echo $this->Html->link(__('Cancel', true), array('action' => 'index'));?>
</div>
<div class="activities form">
<?php echo $this->Form->create('Activity');?>
	<fieldset>
 		<legend><?php __('Edit Activity'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('course_id');
		echo $this->Form->input('name');
		echo $this->Form->input('teacher');
		echo $this->Form->input('room');
		echo $this->Form->input('description');
		echo $this->Form->input('max_participants');
		echo $this->Form->input('min_participants');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Activity.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Activity.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Activities', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Courses', true), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course', true), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Join Activity Groups', true), array('controller' => 'join_activity_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Join Activity Group', true), array('controller' => 'join_activity_groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="securityLogs form">
<?php echo $this->Form->create('SecurityLog');?>
	<fieldset>
 		<legend><?php __('Edit Security Log'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('username');
		echo $this->Form->input('ip');
		echo $this->Form->input('login_status');
		echo $this->Form->input('log_time');
		echo $this->Form->input('sec_msg');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('SecurityLog.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('SecurityLog.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Security Logs', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
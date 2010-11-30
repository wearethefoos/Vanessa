<div class="securityLogs form">
<?php echo $this->Form->create('SecurityLog');?>
	<fieldset>
 		<legend><?php __('Add Security Log'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Security Logs', true), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(__('List Users', true), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User', true), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
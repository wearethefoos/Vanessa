<div class="students form">
<?php echo $this->Form->create('Student');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Student', true)); ?></legend>
	<?php
		echo $this->Form->input('coll_kaart');
		echo $this->Form->input('ldap_uid');
		echo $this->Form->input('Preference');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Students', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Placements', true)), array('controller' => 'placements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Placement', true)), array('controller' => 'placements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Preferences', true)), array('controller' => 'preferences', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Preference', true)), array('controller' => 'preferences', 'action' => 'add')); ?> </li>
	</ul>
</div>
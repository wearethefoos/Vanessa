<div class="students view">
<h2><?php  __('Student');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $student['Student']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Coll Kaart'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $student['Student']['coll_kaart']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Ldap Uid'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $student['Student']['ldap_uid']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('Student', true)), array('action' => 'edit', $student['Student']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('Student', true)), array('action' => 'delete', $student['Student']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $student['Student']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Students', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Student', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Placements', true)), array('controller' => 'placements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Placement', true)), array('controller' => 'placements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Preferences', true)), array('controller' => 'preferences', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Preference', true)), array('controller' => 'preferences', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Placements', true));?></h3>
	<?php if (!empty($student['Placement'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Solution Id'); ?></th>
		<th><?php __('Activity Id'); ?></th>
		<th><?php __('Student Id'); ?></th>
		<th><?php __('Preference Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($student['Placement'] as $placement):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $placement['solution_id'];?></td>
			<td><?php echo $placement['activity_id'];?></td>
			<td><?php echo $placement['student_id'];?></td>
			<td><?php echo $placement['preference_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'placements', 'action' => 'view', $placement['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'placements', 'action' => 'edit', $placement['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'placements', 'action' => 'delete', $placement['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $placement['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Placement', true)), array('controller' => 'placements', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Preferences', true));?></h3>
	<?php if (!empty($student['Preference'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Description'); ?></th>
		<th><?php __('Unwantedness'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($student['Preference'] as $preference):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $preference['id'];?></td>
			<td><?php echo $preference['description'];?></td>
			<td><?php echo $preference['unwantedness'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'preferences', 'action' => 'view', $preference['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'preferences', 'action' => 'edit', $preference['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'preferences', 'action' => 'delete', $preference['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $preference['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Preference', true)), array('controller' => 'preferences', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

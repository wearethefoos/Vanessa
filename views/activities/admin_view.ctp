<div class="activities view">
<h2><?php  __('Activity');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $activity['Activity']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $activity['Activity']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Max Participants'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $activity['Activity']['max_participants']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Min Participants'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $activity['Activity']['min_participants']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s', true), __('Activity', true)), array('action' => 'edit', $activity['Activity']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s', true), __('Activity', true)), array('action' => 'delete', $activity['Activity']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $activity['Activity']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Activities', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Activity', true)), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Placements', true)), array('controller' => 'placements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Placement', true)), array('controller' => 'placements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Students Preferences', true)), array('controller' => 'students_preferences', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Students Preference', true)), array('controller' => 'students_preferences', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s', true), __('Placements', true));?></h3>
	<?php if (!empty($activity['Placement'])):?>
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
		foreach ($activity['Placement'] as $placement):
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
	<h3><?php printf(__('Related %s', true), __('Students Preferences', true));?></h3>
	<?php if (!empty($activity['StudentsPreference'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Id'); ?></th>
		<th><?php __('Student Id'); ?></th>
		<th><?php __('Preference Id'); ?></th>
		<th><?php __('Activity Id'); ?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($activity['StudentsPreference'] as $studentsPreference):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $studentsPreference['id'];?></td>
			<td><?php echo $studentsPreference['student_id'];?></td>
			<td><?php echo $studentsPreference['preference_id'];?></td>
			<td><?php echo $studentsPreference['activity_id'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View', true), array('controller' => 'students_preferences', 'action' => 'view', $studentsPreference['id'])); ?>
				<?php echo $this->Html->link(__('Edit', true), array('controller' => 'students_preferences', 'action' => 'edit', $studentsPreference['id'])); ?>
				<?php echo $this->Html->link(__('Delete', true), array('controller' => 'students_preferences', 'action' => 'delete', $studentsPreference['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $studentsPreference['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Students Preference', true)), array('controller' => 'students_preferences', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>

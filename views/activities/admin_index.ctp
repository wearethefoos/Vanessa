<div class="activities index">
	<h2><?php __('Activities');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('course_id');?></th>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('teacher');?></th>
			<th><?php echo $this->Paginator->sort('room');?></th>
			<th><?php echo $this->Paginator->sort('description');?></th>
			<th><?php echo $this->Paginator->sort('max_participants');?></th>
			<th><?php echo $this->Paginator->sort('min_participants');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($activities as $activity):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $activity['Activity']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($activity['Course']['name'], array('controller' => 'courses', 'action' => 'view', $activity['Course']['id'])); ?>
		</td>
		<td><?php echo $activity['Activity']['name']; ?>&nbsp;</td>
		<td><?php echo $activity['Activity']['teacher']; ?>&nbsp;</td>
		<td><?php echo $activity['Activity']['room']; ?>&nbsp;</td>
		<td><?php echo $activity['Activity']['description']; ?>&nbsp;</td>
		<td><?php echo $activity['Activity']['max_participants']; ?>&nbsp;</td>
		<td><?php echo $activity['Activity']['min_participants']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $activity['Activity']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $activity['Activity']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $activity['Activity']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $activity['Activity']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Activity', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Courses', true), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course', true), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Join Activity Groups', true), array('controller' => 'join_activity_groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Join Activity Group', true), array('controller' => 'join_activity_groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="solutions index">
	<h2><?php __('Solutions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('course_id');?></th>
			<th><?php echo $this->Paginator->sort('student_id');?></th>
			<th><?php echo $this->Paginator->sort('unwantedness');?></th>
			<th><?php echo $this->Paginator->sort('activity_id');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($solutions as $solution):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $solution['Solution']['id']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($solution['Course']['name'], array('controller' => 'courses', 'action' => 'view', $solution['Course']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($solution['Student']['id'], array('controller' => 'students', 'action' => 'view', $solution['Student']['id'])); ?>
		</td>
		<td><?php echo $solution['Solution']['unwantedness']; ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($solution['Activity']['name'], array('controller' => 'activities', 'action' => 'view', $solution['Activity']['id'])); ?>
		</td>
		<td><?php echo $solution['Solution']['created']; ?>&nbsp;</td>
		<td><?php echo $solution['Solution']['modified']; ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $solution['Solution']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $solution['Solution']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $solution['Solution']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $solution['Solution']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Solution', true), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Courses', true), array('controller' => 'courses', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Course', true), array('controller' => 'courses', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Students', true), array('controller' => 'students', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Student', true), array('controller' => 'students', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Activities', true), array('controller' => 'activities', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Activity', true), array('controller' => 'activities', 'action' => 'add')); ?> </li>
	</ul>
</div>
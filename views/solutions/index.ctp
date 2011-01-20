<?php
   function getTimestamp($date_time) {
      $date_time = explode(" ", $date_time);
      $date = explode("-", $date_time[0]);
      $time = explode(":", $date_time[1]);

      return mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
   }

   function showTimestamp($date_time) {
      return date('d-m-y G:i', getTimestamp($date_time));
   }

?>

<style>
   div.solutions table {table-layout: fixed}
   div.solutions table td {width:20%}
</style>
<div class="solutions">
	<h2><?php __('Solutions');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('Course', 'course.name');?></th>
			<th><?php echo $this->Paginator->sort('score');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th class="actions"><?php __('Delete');?></th>
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
      <td><?php echo $this->Html->link($solution['Solution']['name'], array('action' => 'edit', $solution['Solution']['id'], 'admin' => true)); ?></td>
		<td>
			<?php echo $this->Html->link($solution['Course']['name'], array('controller' => 'courses', 'action' => 'view', $solution['Course']['id'])); ?>
		</td>
		<td><?php echo $solution['Solution']['score']; ?>&nbsp;</td>
		<td><?php echo showTimestamp($solution['Solution']['created']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(
                                    $this->Html->image(
                                                   'delete.png',
                                                   array('title' => __('delete this solution', true),
                                                         'onclick' => 'return confirm("' . __('Are you sure you want to delete this solution?', true) . '")')
                                                 ),
                                    array('action' => 'delete',
                                          $solution['Solution']['id'],
                                          'admin' => true),
                                    array('escape' => false)
                              );
               ?>
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
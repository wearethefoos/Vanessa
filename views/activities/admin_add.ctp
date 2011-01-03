<div class="assignments">
<?php echo $this->Form->create('Activity');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Activity', true)); ?></legend>
	<?php
		echo $this->Form->input('course_id');
		echo $this->Form->input('name', array('between' => '<div class="help"><p>' . __('Use a descriptive title, and please try to avoid duplicate titles!', true) . '</p></div>'));
		echo $this->Form->input('room', array('between' => '<div class="help"><p>' . __('In which room will this be activity be?', true) . '</p></div>'));
		echo $this->Form->input('teacher', array('between' => '<div class="help"><p>' . __('Please provide the name of the teacher for this activity.', true) . '</p></div>'));
		echo $this->Form->input('description', array('between' => '<div class="help"><p>' . __('This field is optional, and can be used for extra info', true) . '</p></div>'));
		echo $this->Form->input('min_participants', array('between' => '<div class="help"><p>' . __('Minimum amount of students for this activity', true) . '</p></div>'));
		echo $this->Form->input('max_participants', array('between' => '<div class="help"><p>' . __('Maximum amount of students for this activity', true) . '</p></div>'));
	?>
	</fieldset>
   <div style="width:400px;position: relative">
<?php
   echo $this->Form->submit(__('Submit', true), array('div' => array('style' => 'width:100px;position:relative;left:0px')));
   echo $this->Form->submit(__('Cancel', true), array('div' => array('style' => 'width:100px;position:relative;right:0px'),'onclick' => 'alert("HOI");return false;'));
?>
   </div>
<?php
   echo $this->Form->end();
?>

	<table>
		<thead>
			<tr>
				<th><?php echo sprintf(__('Activities for %s', true), $courses[$this->data['Activity']['course_id']]); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($activities as $activity_id => $activity) : ?>
			<tr>
				<td><?php echo $this->Html->link($activity['name'], array('action' => 'edit', $activity_id, 'admin' => true)); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
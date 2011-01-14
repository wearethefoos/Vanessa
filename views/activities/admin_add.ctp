<div class="assignments">
<?php echo $this->Form->create('Activity');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Activity', true)); ?></legend>
	<?php
		echo $this->Form->input('course_id');
		echo $this->Form->input('name', array('between' => '<div class="help"><p>' . __('The name must be unique!', true) . '</p></div>'));
		echo $this->Form->input('title', array('between' => '<div class="help"><p>' . __('Use a descriptive title', true) . '</p></div>'));
		echo $this->Form->input('room', array('between' => '<div class="help"><p>' . __('In which room will this be activity be?', true) . '</p></div>'));
		echo $this->Form->input('teacher', array('between' => '<div class="help"><p>' . __('Please provide the name of the teacher for this activity.', true) . '</p></div>'));
		echo $this->Form->input('description', array('between' => '<div class="help"><p>' . __('This field is optional, and can be used for extra info', true) . '</p></div>'));
		echo $this->Form->input('min_participants', array('between' => '<div class="help"><p>' . __('Minimum amount of students for this activity', true) . '</p></div>'));
		echo $this->Form->input('max_participants', array('between' => '<div class="help"><p>' . __('Maximum amount of students for this activity', true) . '</p></div>'));
		echo $this->Form->input('message', array('between' => '<div class="help"><p>' . __('This message will be used as part of the email that will be sent to the student when he/she will be placed in the course', true) . '</p></div>'));
	?>
	</fieldset>
   <div style="width:100%;height:50px;position: relative">
<?php
   echo $this->Form->submit(__('Submit', true), array('div' => array('style' => 'position:absolute;left:0px')));
   echo $this->Form->submit(__('Cancel', true), array('div' => array('style' => 'position:absolute;right:0px'),'onclick' => 'window.location="' . $html->url(array('controller' => 'courses', 'action' => 'view', $this->data['Activity']['course_id'], 'admin' => true)) . '";return false;'));
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
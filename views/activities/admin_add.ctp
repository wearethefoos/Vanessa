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
		echo $this->Form->input('max_participants', array('between' => '<div class="help"><p>' . __('Maximum amount of students for this activity', true) . '</p></div>'));
		echo $this->Form->input('min_participants', array('between' => '<div class="help"><p>' . __('Minimum amount of students for this activity', true) . '</p></div>'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
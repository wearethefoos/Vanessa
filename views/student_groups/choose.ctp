<?php 
	echo $this->Form->create('StudentGroup', array('url' => array('action' => 'choose', $course_id))); 
?>
<div class="course">
	<fieldset>
		<legend><?php __('Choose your group!'); ?></legend>
		<p>There are two ways to subscribe to this course (your choice):
			<ol>
				<li>By yourself</li>
				<li>With a bunch of friends</li>
			</ol>
		</p>
		<p>&nbsp;</p>
<?php
if (!$grouped) :
	echo $this->Form->radio(
		'group_up',
		array(
			0 => __('Yeah, it\'ll be just me, thank you..', true),
			1 => __('Allright, let\'s invite some friends then!', true),
		),
		array(
			'legend' => __('What will it be?', true),
			'value' => 0,
		)
	);

else :

	echo $this->Form->radio(
		'group_up',
		array(
			1 => __('Yeah, I wanna sign up with my friends', true),
			0 => __('No, it\'ll be just me, thank you..', true),
		),
		array(
			'legend' => __('Do you accept the invitation?', true),
			'value' => 1,
		)
	);
	
endif;
	
	echo $this->Form->end(__('Next', true));
?>
	</fieldset>
</div>
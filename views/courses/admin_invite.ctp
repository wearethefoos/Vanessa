<div class="courses">
	<h3 class="pink"><?php __('Invite Students') ?></h3>
	<?php
      $students_not_found = '';
      if (isset($studentsnotfound)) {
         foreach ($studentsnotfound as $student_nummer) {
            $students_not_found = $student_nummer . '
';
         }
      }
		echo $this->Form->create('Course');
		echo $this->Form->input('id');
		echo $this->Form->input('students', array(
         'value' => $students_not_found,
			'type' => 'textarea', 
			'between' => '<div class="help"><p>' . __('You can add multiple students at once by placing each student number on a new line, like so:', true) . '</p><p>12345678<br />23456789<br />34567890</p></div>'
			));
		echo $this->Form->input('password', array(
			'label' => __('Your password', true), 
			'type' => 'password', 
			'required' => true, 
			'between' => '<div class="help"><p>' . __('I need your UvAnetID password to look up the students you add in the UvA directory service.', true) . '</p></div>'
			));
		echo $this->Form->end(__('Invite!', true));
	?>
	<div class="students">
		<h3><?php __('Assigned Students'); ?></h3>
		<?php if (!count($students)) : ?>
			<p><?php __('No assigned students yet...'); ?></p>
		<?php else : ?>
		<table>
			<tbody>
			<?php foreach($students as $student) : ?>
				<tr>
					<th><?php echo $student['coll_kaart']; ?></th>
					<td><?php echo $student['User']['name']; ?></td>
				</tr>
			<?php endforeach; ?>
			<tbody>
		</table>
		<?php endif; ?>
	</div>
</div>

<?php
   $args = array('courses' => $courses);
   if (isset($unpreferenced))
      $args['unpreferenced'] = $unpreferenced;
   if (isset($unassigned))
      $args['unassigned'] = $unassigned;
   echo $this->element('display_courses', $args);
?>
<? if (false) : ?>
<div class="courses">
	<div class="header"></div>
	<?php
      $role = $this->Session->read('Auth.User.role_id');
      if ($role <= TEACHER) : // teachers ?>
		<div class="right">
			<?php echo $this->Html->link(__('New course', true), array(
				'controller' => 'courses',
				'action' => 'add',
				'admin' => true,
				));
			?>
		</div>
	<?php endif; ?>
	<?php foreach ($courses as $course) : ?>
        <?php
            $class = 'course';
            $action = 'view';
            if (isset($unpreferenced[$course['Course']['id']])) {
                $class = 'course waiting'; // awaiting preferencing by student
				$action = 'pick';
            } elseif (isset($unassigned[$course['Course']['id']])) {
                $class = 'course pending'; // awaiting assignment by teacher
			}
        ?>
	<div class="<?php echo $class; ?>" title="<?php echo $course['Course']['description']; ?>">
		<?php echo $this->Html->link($course['Course']['name'], array('controller' => 'courses', 'action' => $action, $course['Course']['id'])); ?> 
		<?php switch ($role) {
				case STUDENT : // student 
				echo $this->Html->link(__('Preferences', true), array(
					'controller' => 'courses',
					'action' => 'pick',
					$course['Course']['id'],
					),
					array('class' => 'pink')
					);
				break;
				case ADMINISTRATOR : // supervisor
				case SUPERVISOR : // supervisor
				case TEACHER : // supervisor
				echo $this->Html->link(__('Invitations', true), array(
					'controller' => 'courses',
					'action' => 'invite',
					$course['Course']['id'],
					'admin' => true,
					),
					array('class' => 'pink')
					);
				break;
	    } ?>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>

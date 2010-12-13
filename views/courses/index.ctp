<div class="courses">
	<div class="header"></div>
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
		<?php switch ($this->Session->read('Auth.User.role_id')) { 
				case 7 : // student 
				echo $this->Html->link(__('Preferences', true), array(
					'controller' => 'courses',
					'action' => 'pick',
					$course['Course']['id'],
					),
					array('class' => 'pink')
					);
				break;
				case 6 : // teacher
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
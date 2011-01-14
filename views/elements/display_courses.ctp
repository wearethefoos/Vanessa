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
       <br><br>
		</div>
	<?php endif; ?>
	<?php foreach ($courses as $course) : ?>
        <?php
         $class = 'course';
			$controller = 'courses';
			$action = 'view';
         $admin = true;
			$extra_action = null;
         if ($role == STUDENT && isset($unpreferenced[$course['Course']['id']])) {
            $class .= ' waiting'; // awaiting preferencing by student
            $controller = 'student_groups';
            $action = 'choose';
            $admin = false;
         } elseif (isset($unassigned[$course['Course']['id']])) {
            $class .= ' pending'; // awaiting assignment by teacher
			} elseif ($role <= TEACHER) { // add an extra link for teachers to invite students
				$extra_action = $this->Html->link(__('Invitations', true),
                                             array(
                                                   'controller' => 'courses',
                                                   'action' => 'invite',
                                                   $course['Course']['id'],
                                                   'admin' => true,
                                                   ),
                                             array(
                                                   'style' => 'margin-left:10px',
                                                   'class' => 'pink',
                                             ));
            $extra_action .= $this->Html->link(__('Roster', true),
                                             array(
                                                   'controller' => 'courses',
                                                   'action' => 'roster',
                                                   $course['Course']['id'],
                                                   'admin' => true,
                                                   ),
                                             array(
                                                   'style' => 'margin-left:10px',
                                                   'class' => 'purple',
                                             ));
			}
        ?>
	<div class="<?php echo $class; ?>" title="<?php echo $course['Course']['description']; ?>">
		<?php echo $this->Html->link($course['Course']['name'], array('controller' => $controller, 'action' => $action, $course['Course']['id'], 'admin' => $admin))?>
		<?php echo $extra_action; ?>
	</div><br />
	<?php endforeach; ?>
</div>
<script>
	$$('.waiting').each(function(w) {
		attention(w);
	});

	function attention(w) {
		w.morph('margin-left:20px', {
			duration: 1.3,
			transition: 'elastic',
			after: function() {
				w.morph('margin-left: 0px', {
					duration: 1.3,
					transition: 'elastic'
				});
				attention(w);
			}
		});
	}
</script>
<?php if ($this->Session->read('Auth.User.role_id') == STUDENT) : ?>
<!-- Dashboard -->
<div class="assignments">
	<div class="header"></div>
	<?php
	    $heading = null; 
	    foreach ($assignments as $assignment) : 
	        if ($assignment['Course']['name'] != $heading) {
	            $heading = $assignment['Course']['name'];
    ?>
    <div class="heading">
		<h4 class="blue"><?php echo $heading; ?></h4>
		<p><?php echo $assignment['Activity']['name']; ?> <?php echo $this->Html->link(__('More..', true), array(
			'controller' => 'placements',
			'action' => 'view',
			$assignment['Activity']['Placement'][0]['solution_id']
			)); ?>
		</p>
	</div>
	<?php             
	        }
	?>
	<div class="course" title="<?php echo $assignment['Activity']['description']; ?>"><?php echo $this->Html->link($assignment['Activity']['description'], array('controller' => 'activities', 'action' => 'view', $assignment['Activity']['id']))?></div>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="courses">
	<div class="header"></div>
	<?php if ($this->Session->read('Auth.User.role_id') <= TEACHER) : // teachers ?>
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
			$controller = 'courses';
			$action = 'view';
			$extra_action = null;
            if (isset($unpreferenced[$course['Course']['id']])) {
                $class = 'course waiting'; // awaiting preferencing by student
				$controller = 'student_groups';
				$action = 'choose';
            } elseif (isset($unassigned[$course['Course']['id']])) {
                $class = 'course pending'; // awaiting assignment by teacher
			} elseif ($this->Session->read('Auth.User.role_id') <= TEACHER) { // add an extra link for teachers to invite students
				$extra_action = $this->Html->link(__('Invitations', true), array(
					'controller' => 'courses',
					'action' => 'invite',
					$course['Course']['id'],
					'admin' => true,
					),
					array(
					'class' => 'pink',
					));
			}
        ?>
	<div class="<?php echo $class; ?>" title="<?php echo $course['Course']['description']; ?>">
		<?php echo $this->Html->link($course['Course']['name'], array('controller' => $controller, 'action' => $action, $course['Course']['id']))?> 
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
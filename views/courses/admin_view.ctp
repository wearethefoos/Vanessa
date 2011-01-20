<div class="courses">
	<h2><?php
      echo $this->Html->link($course['Course']['name'],
                             array(
                                    'controller' => 'courses',
                                    'action' => 'edit',
                                    $course['Course']['id'],
                                    'admin' => true
                     				)
                           );

   ?></h2>
	<div class="right">
		<?php switch ($this->Session->read('Auth.User.role_id')) {
				case STUDENT : // student
				echo $this->Html->link(__('Preferences', true), array(
					'controller' => 'courses',
					'action' => 'pick',
					$course['Course']['id'],
					),
					array('class' => 'pink')
					);
				break;
				case ADMINISTRATOR :
				case SUPERVISOR :
				case TEACHER : // teacher
				echo $this->Html->link(__('Invitations', true),
                                 array(
                                       'controller' => 'courses',
                                       'action' => 'invite',
                                       $course['Course']['id'],
                                       'admin' => true,
                                       ),
                                 array(
                                       'class' => 'pink'
                                      )
                                 );
            echo $this->Html->link(__('Roster', true),
                                 array(
                                       'controller' => 'courses',
                                       'action' => 'roster',
                                       $course['Course']['id'],
                                       'admin' => true,
                                       ),
                                 array(
                                       'style' => 'margin-left:10px',
                                       'class' => 'purple',
                                      )
                                 );

				break;
	    } ?>
	</div>
	<div class="description">
		<?php echo $course['Course']['description']; ?>
		&nbsp;
	</div>
</div>
<?php if (isset($preferences) && count($preferences)) : ?>
<div class="assignments">
	<div id="tree" class="tree center">
		<h3><?php __('Your cherries'); ?></h3>
		<ol>
		<?php foreach ($preferences as $preference) : ?>
			<li class="activity unwantedness-<?php echo $preference['Preference']['unwantedness']; ?>"><?php echo $preference['Activity']['name']?></li>
		<?php endforeach; ?>
		</ol>
	</div>
</div>
<?php else : ?>
<div class="assignments">
	<h3 class="purple"><?php printf(__('Related %s', true), __('Activities', true));?></h3>
	<?php if ($this->Session->read('Auth.User.role_id') <= TEACHER) { // roles teachers and up
		echo $this->Html->link(__('Add activity', true), array(
				'controller' => 'activities',
				'action' => 'add',
				$course['Course']['id'],
				'admin' => true,
			));
	} ?>
	<?php if (!empty($course['Activity'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php __('Name'); ?></th>
		<th><?php __('Teacher'); ?></th>
		<?php if ($this->Session->read('Auth.User.role_id') <= TEACHER) : ?>
		<th class="actions" style="text-align: center"><?php __('Delete');?></th>
		<?php endif; ?>
	</tr>
	<?php
		$i = 0;
		foreach ($course['Activity'] as $activity):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
         <td><?php echo $this->Html->link($activity['name'], array('controller' => 'activities', 'action' => 'edit', $activity['id'], 'admin' => true)); ?></td>
			<td><?php echo $activity['teacher'];?></td>
			<?php if ($this->Session->read('Auth.User.role_id') <= TEACHER) : // roles teachers and up ?>
			<td><?php echo $this->Html->link(
                                    $this->Html->image(
                                                   'delete.png',
                                                   array('title' => __('delete this activity', true),
                                                         'onclick' => 'return confirm("' . sprintf(__('Are you sure you want to delete %s?', true), $activity['name']) . '")')
                                                 ),
                                    array('controller' => 'activities',
                                          'action' => 'delete',
                                          $course['Course']['id'],
                                          $activity['id'],
                                          'admin' => true),
                                    array('escape' => false)
                              );
               ?>
			</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>
</div>
<?php endif; ?>
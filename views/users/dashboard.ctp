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
<?php endif;
$args = array('courses' => $courses);
if (isset($unpreferenced))
   $args['unpreferenced'] = $unpreferenced;
if (isset($unassigned))
   $args['unassigned'] = $unassigned;
echo $this->element('display_courses', $args);

?>

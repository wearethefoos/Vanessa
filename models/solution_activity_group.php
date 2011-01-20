<?php
class SolutionActivityGroup extends AppModel {
	var $name = 'SolutionActivityGroup';
   var $useTable = 'solution_activity_groups';

   var $belongsTo = array(
        'SolutionActivity',
        'Group'
   );
}
?>

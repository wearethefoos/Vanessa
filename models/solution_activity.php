<?php
class SolutionActivity extends AppModel {
	var $name = 'SolutionActivity';

   var $belongsTo = array(
        'Solution',
        'Activity'
   );
   
   var $hasMany = array(
      'SolutionActivityGroup'
   );
}

?>

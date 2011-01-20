<?php
class Solution extends AppModel {
	var $name = 'Solution';
   var $actsAs = array('Containable');

	var $validate = array(
		'course_id' => array(
         'rule' => array('numeric'),
         //'message' => 'Your custom message here',
         //'allowEmpty' => false,
         //'required' => false,
         //'last' => false, // Stop validation after this rule
         //'on' => 'create', // Limit validation to 'create' or 'update' operations
		),
		'score' => array(
         'rule' => array('numeric'),
         //'message' => 'Your custom message here',
         //'allowEmpty' => false,
         //'required' => false,
         //'last' => false, // Stop validation after this rule
         //'on' => 'create', // Limit validation to 'create' or 'update' operations
      ),
      'name' => array(
         'rule' => array('notEmpty')
      )
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array('Course');
	var $hasMany = array('SolutionActivity');

   public function saveSolution($data, $new_record) {
      $result = true;
      if ($new_record) {
         $this->create();
      }
      if ($this->save($data)) {
         $solution_id = $this->id;
         foreach($data['Placement'] as $activity_id => $groups) {
            if ($new_record)
               $this->SolutionActivity->create();
            if ($this->SolutionActivity->save(array('SolutionActivity' => array('solution_id' => $solution_id, 'activity_id' => $activity_id)))) {
               $solution_activity_id = $this->SolutionActivity->id;
               foreach($groups as $group_id) {
                  if ($new_record)
                     $this->SolutionActivity->SolutionActivityGroup->create();
                  $this->SolutionActivity->SolutionActivityGroup->save(array('SolutionActivityGroup' => array('solution_activity_id' => $solution_activity_id, 'group_id' => $group_id)));
               }
            } else {
               $result = false;
               break;
            }
         }
      } else {
         $result = false;
      }
      return $result;
   }
}
?>
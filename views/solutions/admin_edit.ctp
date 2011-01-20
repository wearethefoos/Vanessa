<?php
   echo $this->element('display_solution', array(
      'activities' => $activities,
      'student_groups' => $studentgroups,
      'students' => $students,
      'solution' => $solution,
      'dropdown_groups' => $dropdowndata['groups'],
      'dropdown_activities' => $dropdowndata['activities']));

?>
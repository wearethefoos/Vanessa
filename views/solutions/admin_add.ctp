<?php
   echo $this->element('display_solution', array(
      'activities' => $placements->activities,
      'student_groups' => $placements->student_groups,
      'students' => $placements->students,
      'solution' => $result['placements'],
      'dropdown_groups' => $dropdowndata['groups'],
      'dropdown_activities' => $dropdowndata['activities']));
?>

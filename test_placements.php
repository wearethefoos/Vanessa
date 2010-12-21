<?php
   require 'vendors\place_students.php';
   $test = new PlaceStudents(array(
      'nb_activities' => 50,
      'nb_students' => 550,
      'nb_groups' => 100,
      'nb_preferences'=> 7
   ));
   $test->test();
?>

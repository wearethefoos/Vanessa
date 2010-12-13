<?php
define('UNWANTED_SCORE', 99);
define('MIN_CONSTRAINT_SCORE', 200);
define('MAX_CONSTRAINT_SCORE', 300);
define('TRIALS_BLOCK', 200);

define('ACTIVITY_MIN_MIN', 15);
define('ACTIVITY_MIN_MAX', 18);
define('ACTIVITY_MAX', 5);
define('STUDENT_GROUP_MIN', 2);
define('STUDENT_GROUP_MAX', 8);

define('USE_MIN_CONSTRAINT', true);
define('MAX_TRIALS', 1000);
define('MAX_GENERATIONS', 20);


class PlaceStudents {

   var $students = array();
   var $student_groups = array();
   var $activities = array();

   private function clone_placements($placements) {
      $result = array();
      foreach ($placements as $activity_key => $placement) {
         $result[$activity_key] = array('groups' => array(), 'nb_students' => $placement['nb_students'], 'preference' => $placement['preference']);
         foreach ($placement['groups'] as $student_group_key => $preference)
            $result[$activity_key]['groups'][$student_group_key] = $preference;
      }

      return $result;
   }

   private function shuffle_assoc_array(&$assoc_array) {
      $keys = array_keys($assoc_array);
      shuffle($keys);
      $result = array();
      foreach ($keys as $key) {
         $result[$key] = &$assoc_array[$key];
      }
      return $result;
   }

   private function display_placements(&$placements, $score, $trial, $generation) {
      echo '<div style="float:left;margin-right:10px">';

      echo '<table border="1" style="border:solid">
               <tr>
                  <th>Activity</th>
                  <th colspan="2">Score: ' . $score . '</th>
                  <th colspan="2">Trial: ' . $trial . '</th>
                  <th colspan="2">Generation: ' . $generation . '</th>
               </tr>';
      $nb_students = 0;
      foreach($placements as $activity_key => &$placement) {
         $style = '';
         if ($placement['nb_students'] < $this->activities[$activity_key]['min'])
            $style = 'background-color:#FF3030';
         else if ($placement['nb_students'] > $this->activities[$activity_key]['max'])
            $style = 'background-color:#9400D3';
         echo '<tr>
                  <td style="' . $style . '">' . $this->activities[$activity_key]['name'] . '</td>';
         $nb_students_in_activity = 0;
         foreach ($placement['groups'] as $student_group_key => $preference){
            switch ($preference) {
               case 0: $color = "Green"; break;
               case 1: $color = "GreenYellow"; break;
               case 2: $color = "Yellow"; break;
               case 3: $color = "Orange"; break;
               case 4: $color = "OrangeRed"; break;
               case UNWANTED_SCORE: $color = 'red'; break;
               default: $color = "OrangeRed"; break;
            }
            $style = 'background-color:' . $color;
            if (count($this->student_groups[$student_group_key]['group']) == 1) {
               $nb_students++;
               $nb_students_in_activity++;
               if ($nb_students_in_activity == $this->activities[$activity_key]['min']) {
                  $style .= ';border-right:8px solid blue';
               }
               echo '<td style="' . $style . '">' . $this->students[$this->student_groups[$student_group_key]['group'][0]]['name'] . '</td>';
            } else {
               $style .= ';border:3px solid black';
               echo '<td style="' . $style . '" colspan="' . count($this->student_groups[$student_group_key]['group']) . '">';
               echo '<table style="width:100%"><tr>';
               foreach($this->student_groups[$student_group_key]['group'] as $student_key) {
                  $nb_students++;
                  $nb_students_in_activity++;
                  if ($nb_students_in_activity == $this->activities[$activity_key]['min']) {
                     echo '<td style="border-right:8px solid blue">';
                  } else {
                     echo '<td>';
                  }

                  echo $this->students[$student_key]['name'] . '</td>';
               }
               echo '</tr></table>';
               echo '</td>';
            }
         }
         for ($j = $placement['nb_students']; $j < $this->activities[$activity_key]['max']; $j++) {
            echo '<td style="background-color:grey"></td>';
         }
         echo '</tr>';
      }
      echo '</table>Nb of students: ' . $nb_students . '</div>';

   }

   function display_scores($scores) {
      echo '<div style="float:left;margin-right:10px">';

      echo '<table border="1" style="border:solid">
               <tr>
                  <th>Scores</th>
               </tr>';
      foreach($scores as $score) {
         echo '<tr>
                  <td>' . $score . '</td>
               </tr>';
      }
      echo '</table>';
      echo '</div>';
   }

   static function sort_groups_per_preference(&$g1, &$g2) {
      if ($g1['new_preference'] < $g2['new_preference'])
         return 1;
      else if ($g2['new_preference'] < $g1['new_preference'])
         return -1;
      else if ($g1['old_preference'] > $g2['old_preference'])
         return 1;
      else if ($g2['old_preference'] > $g1['old_preference'])
         return -1;
      else
         return 0;
   }

   private function get_activity_with_most_free_places(&$placements, $max_student = 0) {
      $result = -1;
      $max_free_places = 0;
      $activity_preference = -1;
      foreach ($placements as $activity_key => &$placement) {
         $free_places = $this->activities[$activity_key]['max'] - $placement['nb_students'];
         if ($result == -1 ||
             ($free_places > $max_free_places) ||
             ($free_places == $max_free_places && $placement['preference'] > $activity_preference)) {
            $use_this_activity = true;
            // the activity must be able to accept max_student
            if ($this->activities[$activity_key]['max'] < $max_student) {
               $use_this_activity = false;
            } else {
               // There must be enough students in smaller groups than max_student
               $nb_students_in_smaller_groups_than_max_student = 0;
               foreach ($placement['groups'] as $group_key => $preference) {
                  if (count($this->student_groups[$group_key]['group']) < $max_student)
                     $nb_students_in_smaller_groups_than_max_student += count($this->student_groups[$group_key]['group']);
               }
               if ($nb_students_in_smaller_groups_than_max_student < $max_student) {
                  $use_this_activity = false;
               }
            }
            if ($use_this_activity) {
               $result = $activity_key;
               $max_free_places = $free_places;
               $activity_preference = $placement['preference'];
            }
         }
      }

      if ($result == -1 && $max_student > 0) {
         // There is no activity that has enough space.
         // We must exceed the max student constraint to find one activity.
         $result = $this->get_activity_with_most_free_places($placements);
      }
      return $result;
   }

   private function initialize($nb_activities, $nb_students, $nb_student_groups, $nb_preferences) {
      for ($activity_key = 0; $activity_key < $nb_activities; $activity_key++) {
         $min = rand(ACTIVITY_MIN_MIN, ACTIVITY_MIN_MAX);
         $max = $min + rand(0, ACTIVITY_MAX);

         $this->activities[] = array('name' => 'A' . ($activity_key + 1), 'min' => $min, 'max' => $max);
      }

      for ($student_key = 0; $student_key < $nb_students; $student_key++) {
         $this->students[] = array('name' => 'S' . ($student_key + 1));
      }

      $students_keys = range(0, $nb_students - 1);
      shuffle($students_keys);
      $student_index = 0;
      for ($group_key = 0; $group_key < $nb_student_groups; $group_key++) {
         $group = array();
         $nb_of_students = rand(STUDENT_GROUP_MIN, STUDENT_GROUP_MAX);
         for ($j=0; $j < $nb_of_students; $j++) {
            $group[] = $students_keys[$student_index];
            $student_index++;
         }
         $preferences = array();
         $activity_keys = range(0, $nb_activities - 1);
         shuffle($activity_keys);
         foreach($activity_keys as $activity_key) {
            if (count($group) <= $this->activities[$activity_key]['max'])
               $preferences[] = $activity_key;
            if (count($preferences) == $nb_preferences)
               break;
         }
         $this->student_groups[] = array('name' => 'G' . ($group_key + 1), 'group' => $group, 'preferences' => $preferences);
      }
      for (; $student_index < $nb_students; $student_index++, $group_key++) {
         $activity_keys = range(0, $nb_activities - 1);
         shuffle($activity_keys);
         $this->student_groups[] = array('name' => 'G' . ($group_key + 1), 'group' => array($students_keys[$student_index]), 'preferences' => array_slice($activity_keys, 0, $nb_preferences));
      }

      //Initial Checks
      $sum_min = $sum_max = 0;
      foreach ($this->activities as $activity) {
         $sum_min += $activity['min'];
         $sum_max += $activity['max'];
      }
      if ($sum_min > $nb_students) {
         echo "<h2> Number of students (" . $nb_students . ") should be higher than " . $sum_min . ". All activities cannot be fulfilled</h2>";
      }
      if ($nb_students > $sum_max) {
         echo "<h2> Number of students (" . $nb_students . ") should be smaller than " . $sum_max . " Some activities will necessarily exeed their maximum</h2>";
      }
   }

   private function display_activities() {
      echo '<div style="float:left">';
      echo '<table border="1" style="border:solid">
               <tr>
                  <th>Activity</th>
                  <th>MIN</th>
                  <th>MAX</th>
               </tr>';
      for ($activity_key = 0; $activity_key < count($this->activities); $activity_key++) {
         echo '<tr>
                  <td>' . $this->activities[$activity_key]['name'] . '</td>
                  <td>' . $this->activities[$activity_key]['min'] . '</td>
                  <td>' . $this->activities[$activity_key]['max'] . '</td>
               </tr>';
      }
      echo '</table></div>';
   }

   private function display_preferences($nb_preferences) {
      echo '<div style="float:left;margin-left:10px">';
      echo '<table border="1" style="border:solid">
               <tr>
                  <th>Student</th>';
      for ($i = 0; $i < $nb_preferences; $i++)
         echo '<th>Pref ' . $i . '</th>';
      echo '</tr>';
      foreach($this->student_groups as $student_group) {
         echo '<tr>
                  <td>';
         $first_student = true;
         foreach($student_group['group'] as $student_key) {
            if (!$first_student)
               echo '-';
            $first_student = false;
            echo $this->students[$student_key]['name'];
         }
         echo '</td>';
         foreach($student_group['preferences'] as $activity_key)
            echo '<td>' . $this->activities[$activity_key]['name'] . '</td>';
         echo '</tr>';
         }
      echo '</table></div>';
   }

   private function get_one_solution(&$placements, $min_constraint, $student_group_keys = null, $init_score = 0) {
      if ($student_group_keys == null) {
         $student_group_keys = range(0, count($this->student_groups) - 1);
      }
      shuffle($student_group_keys);
      $score = $init_score;
      for ($i=0; $i<count($student_group_keys);$i++) {                // Do not use foreach since $student_group_keys might be modified in the loop self
         $student_group_key = $student_group_keys[$i];
         $nb_of_students_in_this_group = count($this->student_groups[$student_group_key]['group']);
         $place_found = false;
         $group_score = -1;
         foreach($this->student_groups[$student_group_key]['preferences'] as $preference => $activity_key) {
            $nb_of_students_in_this_activity = $placements[$activity_key]['nb_students'];
            if ($nb_of_students_in_this_activity + $nb_of_students_in_this_group <= $this->activities[$activity_key]['max']) {
               $place_found = true;
               $group_score = $preference;
               break;
            }
         }
         if (!$place_found) {
            // We could not find a 'prefered activity' for this group
            // Choose randomy an activity with enough places.
            $activity_keys = range(0, count($this->activities) - 1);
            shuffle($activity_keys);
            for ($j = 0; $j < count($this->activities); $j++) {
               $activity_key = $activity_keys[$j];
               $nb_of_students_in_this_activity = $placements[$activity_key]['nb_students'];
               if ($nb_of_students_in_this_activity + $nb_of_students_in_this_group <= $this->activities[$activity_key]['max']) {
                  $place_found = true;
                  $group_score = UNWANTED_SCORE;
                  break;
               }
            }
         }

         if (!$place_found) {
            // There is no activity with enough places for this group (count(group) must be > 1):
            // Choose an activity with the most free places
            $activity_key = $this->get_activity_with_most_free_places($placements, $nb_of_students_in_this_group);
            if ($activity_key == -1) {
               echo '<h2>No activity could be found for group: ';
               $first_student = true;
               foreach ($this->student_groups[$student_group_key]['group'] as $student_key) {
                  if (!$first_student)
                     echo '-';
                  else
                     $first_student = false;
                  echo $this->students[$student_key]['name'];
               }
               echo '</h2>';
            } else {
               // We must remove some students in this activity.
               $nb_of_students_to_be_removed = $nb_of_students_in_this_group - ($this->activities[$activity_key]['max'] - $placements[$activity_key]['nb_students']);
               $groups_in_placement = array();
               foreach ($placements[$activity_key]['groups'] as $group_key => $preference) {
                  if (count($this->student_groups[$group_key]['group']) < $nb_of_students_in_this_group) {
                     // Do not remove group of students that are more numerous than the group of students we want to add
                     $groups_in_placement[$group_key] = $preference;
                  }
               }
               arsort($groups_in_placement);    // sort by preference
               $groups_to_be_removed = array();
               $nb_of_students_in_groups_to_be_removed = 0;
               foreach($groups_in_placement as $group_key => $preference) {
                  $nb_of_students = count($this->student_groups[$group_key]['group']);
                  $groups_to_be_removed[$group_key] = array('nb_students' => $nb_of_students, 'preference' => $preference);
                  $nb_of_students_in_groups_to_be_removed += $nb_of_students;
                  if ($nb_of_students_in_groups_to_be_removed >= $nb_of_students_to_be_removed)
                     break;
               }
               foreach($groups_to_be_removed as $group_key => $properties) {
                  unset($placements[$activity_key]['groups'][$group_key]);
                  $placements[$activity_key]['nb_students'] -= $properties['nb_students'];
                  $placements[$activity_key]['preference'] -= ($properties['preference'] * $properties['nb_students']);
                  $student_group_keys[] = $group_key;      // By adding the group at the end of student_group_keys, it will be again placed in a new activity.
               }
               $place_found = true;
               $preferences = array_flip($this->student_groups[$student_group_key]['preferences']);
               $group_score = isset($preferences[$activity_key]) ? $preferences[$activity_key] : UNWANTED_SCORE;
            }
         }

         if ($place_found) {
            $placements[$activity_key]['groups'][$student_group_key] = $group_score;
            $placements[$activity_key]['nb_students'] += $nb_of_students_in_this_group;
            $placements[$activity_key]['preference'] += ($group_score * $nb_of_students_in_this_group);
            $score += ($group_score * $nb_of_students_in_this_group);
         }
      }

      if ($min_constraint) {
         $activities_with_not_enough_students = array();
         $activities_with_more_than_enough_students = array();
         foreach($placements as $activity_key => &$placement) {
            if ($placement['nb_students'] < $this->activities[$activity_key]['min'])
               $activities_with_not_enough_students[$activity_key] = &$placement;
            if ($placement['nb_students'] > $this->activities[$activity_key]['min'])
               $activities_with_more_than_enough_students[$activity_key] = &$placement;
         }
         // Shuffle the 2 arrays
         $activities_with_not_enough_students = $this->shuffle_assoc_array($activities_with_not_enough_students);
         $activities_with_more_than_enough_students = $this->shuffle_assoc_array($activities_with_more_than_enough_students);

         // For each activity with not enough students, find the most interesting student groups in activities
         // where there are more than enough students.
         foreach($activities_with_not_enough_students as $activity_min_key => &$placement_min) {
            $min_nb_of_students_to_be_added = $this->activities[$activity_min_key]['min'] - $placement_min['nb_students'];
            $max_nb_of_students_to_be_added = $this->activities[$activity_min_key]['max'] - $placement_min['nb_students'];;
            $student_groups_to_be_added = array();
            $nb_of_students_retrieved = 0;
            foreach ($activities_with_more_than_enough_students as $activity_more_key => $placement_more) {
               $nb_of_students_retrievable_for_current_activity = $placement_more['nb_students'] - $this->activities[$activity_more_key]['min']; // must be > 0
               $student_groups_to_be_added_in_current_activity = array();
               $nb_of_students_retrieved_for_current_activity = 0;
               foreach($placement_more['groups'] as $student_group_key => $old_preference) {
                  $nb_of_students_in_this_group = count($this->student_groups[$student_group_key]['group']);
                  if ($nb_of_students_in_this_group <= $nb_of_students_retrievable_for_current_activity) {
                     $new_preference = UNWANTED_SCORE;                                     // Get the preference of the student for the activity_min
                     foreach ($this->student_groups[$student_group_key]['preferences'] as $preference => $activity_key) {
                        if ($activity_key == $activity_min_key) {
                           $new_preference = $preference;
                           break;
                        }
                     }
                     $add_group = false;
                     if (($nb_of_students_retrieved_for_current_activity + $nb_of_students_in_this_group) > $nb_of_students_retrievable_for_current_activity) {
                        // Cannot add the group to the array because the activity cannot have less students,
                        // but the group can maybe replace another group student (in the same activity) in the $student_groups_to_be_added array if:
                        //    . by replacing this other group, the activity has still enough students
                        //    . the maximun number of students won't be exceeded
                        //    . the total preference in the array will be improved
                        $found_group_to_be_replaced = false;
                        foreach($student_groups_to_be_added_in_current_activity as $group_to_be_replaced_key => $group_to_be_replaced_properties) {
                           $nb_of_students_in_group_to_be_replaced = count($this->student_groups[$group_to_be_replaced_key]['group']);
                           if ($nb_of_students_retrieved_for_current_activity + $nb_of_students_in_this_group - $nb_of_students_in_group_to_be_replaced <= $nb_of_students_retrievable_for_current_activity &&
                               $nb_of_students_retrieved + $nb_of_students_in_this_group - $nb_of_students_in_group_to_be_replaced <= $max_nb_of_students_to_be_added) {
                              $found_group_to_be_replaced = true;
                              break;
                           }
                        }
                        if ($found_group_to_be_replaced) {
                           if ($new_preference < $group_to_be_replaced_properties['new_preference'] ||
                                ($new_preference == $group_to_be_replaced_properties['new_preference'] &&
                                 $old_preference > $group_to_be_replaced_properties['old_preference'])) {
                              // this group is suited to be replaced by $student_group_key in the $student_groups_to_be_added array
                              $add_group = true;
                              unset($student_groups_to_be_added[$group_to_be_replaced_key]);
                              unset($student_groups_to_be_added_in_current_activity[$group_to_be_replaced_key]);
                              $nb_of_students_retrieved -= $nb_of_students_in_group_to_be_replaced;
                              $nb_of_students_retrieved_for_current_activity -= $nb_of_students_in_group_to_be_replaced;
                           }
                        }
                     } else if ($nb_of_students_retrieved >= $min_nb_of_students_to_be_added) {
                        // Cannot add the group to the $student_groups_to_be_added array because the array has already enough students,
                        // but the group can maybe replace another group student in the $student_groups_to_be_added array if:
                        //    . the maximun number of students won't be exceeded
                        //    . the total preference in the array will be improved
                        $found_group_to_be_replaced = false;
                        foreach($student_groups_to_be_added as $group_to_be_replaced_key => $group_to_be_replaced_properties) {
                           $nb_of_students_in_group_to_be_replaced = count($this->student_groups[$group_to_be_replaced_key]['group']);
                           if ($nb_of_students_retrieved + $nb_of_students_in_this_group - $nb_of_students_in_group_to_be_replaced <= $max_nb_of_students_to_be_added) {
                              $found_group_to_be_replaced = true;
                              break;
                           }
                        }
                        if ($found_group_to_be_replaced) {
                           if ($new_preference < $group_to_be_replaced_properties['new_preference'] ||
                                ($new_preference == $group_to_be_replaced_properties['new_preference'] &&
                                 $old_preference > $group_to_be_replaced_properties['old_preference'])) {
                              $add_group = true;
                              if ($group_to_be_replaced_properties['activity_key'] == $activity_more_key) {
                                 $nb_of_students_retrieved_for_current_activity -= $nb_of_students_in_group_to_be_replaced;
                                 unset($student_groups_to_be_added_in_current_activity[$group_to_be_replaced_key]);
                              }
                              unset($student_groups_to_be_added[$group_to_be_replaced_key]);
                              $nb_of_students_retrieved -= $nb_of_students_in_group_to_be_replaced;
                           }
                        }
                     } else {
                        $add_group = true;
                     }

                     if ($add_group) {
                        $nb_of_students_retrieved += $nb_of_students_in_this_group;
                        $nb_of_students_retrieved_for_current_activity += $nb_of_students_in_this_group;
                        $student_groups_to_be_added[$student_group_key] = array('activity_key' => $activity_more_key, 'new_preference' => $new_preference, 'old_preference' => $old_preference);
                        $student_groups_to_be_added_in_current_activity[$student_group_key] = array('activity_key' => $activity_more_key, 'new_preference' => $new_preference, 'old_preference' => $old_preference);
                        uasort($student_groups_to_be_added, 'sort_groups_per_preference');
                        uasort($student_groups_to_be_added_in_current_activity, 'sort_groups_per_preference');
                     }
                  }
               }
            }

            foreach ($student_groups_to_be_added as $student_group_key => $properties) {
               $nb_of_students_in_this_group = count($this->student_groups[$student_group_key]['group']);
               unset($placements[$properties['activity_key']]['groups'][$student_group_key]);
               $placements[$properties['activity_key']]['nb_students'] -= $nb_of_students_in_this_group;
               $placements[$properties['activity_key']]['preference'] -= ($properties['old_preference'] * $nb_of_students_in_this_group);
               if ($placements[$properties['activity_key']]['nb_students'] <= $this->activities[$properties['activity_key']]['min']) {
                  unset($activities_with_more_than_enough_students[$properties['activity_key']]);
               }
               $placements[$activity_min_key]['groups'][$student_group_key] = $properties['new_preference'];
               $placements[$activity_min_key]['nb_students'] += $nb_of_students_in_this_group;
               $placements[$activity_min_key]['preference'] += ($properties['new_preference'] * $nb_of_students_in_this_group);
               if ($placements[$activity_min_key]['nb_students'] > $this->activities[$activity_min_key]['min'])
                  $activities_with_more_than_enough_students[$activity_min_key] = &$placements[$activity_min_key];
               $score += $nb_of_students_in_this_group * ($properties['new_preference'] - $properties['old_preference']);
            }
         }
      }

      $nb_activities_exceding_maximum_students = 0;
      $nb_activities_with_not_enough_students = 0;
      foreach($placements as $activity_key => &$placement) {
         if ($placement['nb_students'] < $this->activities[$activity_key]['min'])
            $nb_activities_with_not_enough_students++;
         else if ($placement['nb_students'] > $this->activities[$activity_key]['max'])
            $nb_activities_exceding_maximum_students++;
      }
      $result = array(
         'score' => $score,
         'placements' => $placements,
         'end_score' => $score + ($nb_activities_exceding_maximum_students * MAX_CONSTRAINT_SCORE) + ($nb_activities_with_not_enough_students * MIN_CONSTRAINT_SCORE)
         );

   //   $this->display_placements($placements, $score, 1);
      return $result;
   }

   private function mutate_solution(&$solution, &$activity_keys, $min_constraint) {
      $new_placements = $this->clone_placements($solution['placements']);
      $student_group_keys = array();
      $score = $solution['score'];
      foreach ($activity_keys as $activity_key) {
         $student_group_keys = array_merge($student_group_keys, array_keys($new_placements[$activity_key]['groups']));
         $score -= $new_placements[$activity_key]['preference'];
         $new_placements[$activity_key] = array('nb_students' => 0, 'preference' => 0, 'groups' => array());
      }
      return $this->get_one_solution($new_placements, $min_constraint, $student_group_keys, $score);
   }

   static function cmp_solution(&$s1, &$s2) {
      return $s1['end_score'] - $s2['end_score'];
   }

   function find_best_fit($min_constraint = USE_MIN_CONSTRAINT, $max_trials = MAX_TRIALS, $max_generations = MAX_GENERATIONS) {
      $score_history = array();
      $pool = array();
      while((count($pool) < $max_trials) && (count($score_history) < 2 || ($score_history[count($score_history) - 1] < $score_history[count($score_history) - 2]))) {
         for ($trial = 0; $trial < TRIALS_BLOCK; $trial++) {
            $placements = array();
            for ($activity_key=0; $activity_key < count($this->activities); $activity_key++) {
               $placements[$activity_key] = array('nb_students' => 0, 'preference' => 0, 'groups' => array());
            }
            $pool[] = $this->get_one_solution($placements, $min_constraint);
         }
         usort($pool, 'cmp_solution');
         $score_history[] = $pool[0]['end_score'];
      }

      $generation = 1;
      while (($generation < $max_generations) && ($generation < 3 || $score_history[count($score_history) - 1] < $score_history[count($score_history) - 3])) {
         $pool = array_slice($pool, 0, ceil(count($pool) / 3));
         foreach ($pool as $solution) {
            $activity_keys = range(0, count($this->activities) - 1);
            shuffle($activity_keys);
            $activity_keys1 = array_slice($activity_keys, 0, ceil(count($this->activities)/2));
            $activity_keys2 = array_slice($activity_keys, ceil(count($this->activities)/2));
            $pool[] = $this->mutate_solution($solution, $activity_keys1, $min_constraint);
            $pool[] = $this->mutate_solution($solution, $activity_keys2, $min_constraint);
         }
         usort($pool, 'cmp_solution');
         $generation++;
         $score_history[] = $pool[0]['end_score'];
      }
//      $this->display_placements($pool[0]['placements'], $pool[0]['end_score'], count($pool), $generation);
//      $this->display_scores($score_history);
   }

   public function __construct($students, $activities, $student_groups) {
      $this->activities = $activities;
      $this->students = $students;
      $this->student_groups = $student_groups;
   }

//   echo '<div style="width:3000px">';
//   initialize($NB_activities, $NB_students, $NB_student_groups, $NB_preferences);
//   display_activities();
//   display_preferences($NB_preferences);
//   find_best_fit($USE_MIN_CONSTRAINT, $MAX_TRIALS, $MAX_GENERATIONS);
//   echo '</div>';


}
?>

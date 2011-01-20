<?php ?>
<script type="text/javascript">
   String.prototype.trim = function() { return this.replace(/^[\s\n]+|[\s\n]+$/g, ''); };

   function addPreferenceClass(placement_element) {
      if (placement_element.hasClassName('no-student'))
         return;

      if (placement_element.hasClassName('group')) {
         // get first student in this group
         var student = placement_element.down('.student');
         var info = $('information' + student.id);
      } else {
         var container_element = placement_element.down();
         var info = $('information' + container_element.id);
      }
      var preferences = info.select('li');
      var activity = placement_element.up().down().innerHTML.trim();
      for (i=0; i<preferences.length;i++) {
         if (preferences[i].innerHTML.trim() == activity)
            break;
      }
      if (i == preferences.length)
         placement_element.addClassName('pref-no');
      else {
         if (i >= 6)
            i == 6;
         placement_element.addClassName('pref-' + i);
      }
   }

   function setMinMaxStudents(activity_element) {
      var tr_element = activity_element.up();
      var info = $('information' + activity_element.id);
      var min_students = info.down('.min-students').innerHTML;
      var max_students = info.down('.max-students').innerHTML;
      var students = tr_element.select('.student');

      for (i = 0; i < students.length; i++) {
         students[i].removeClassName('min-students');
         if (i == min_students - 1)
            students[i].addClassName('min-students');
      }

      var no_students = tr_element.select('.no-student');
      if (students.length + no_students.length > max_students) {
         var nb_elements_to_be_removed = (max_students < students.length) ? no_students.length : (no_students.length - (max_students - students.length));
         for (var i = 0; i < nb_elements_to_be_removed; i++)
            no_students[i].remove();
      } else if (students.length + no_students.length < max_students) {
         var nb_elements_to_be_added = max_students - students.length - no_students.length;
         for (var i = 0; i < nb_elements_to_be_added; i++) {
            var element = new Element('td', {'class': 'placement no-student'});
            tr_element.insert({bottom: element});
         }
      }
   }

   function addActivityClass(item) {
      var nb_students = item.up().select('.student').length;
      var info = $('information' + item.id);
      var min_students = info.down('.min-students').innerHTML;
      var max_students = info.down('.max-students').innerHTML;
      if (nb_students > max_students)
         item.addClassName('too-many-students');
      else if (nb_students < min_students)
         item.addClassName('not-enough-students');
      setMinMaxStudents(item);
   }

   function showInformation(event) {
      setToMousePosition($('information' + this.readAttribute('id')), event);
      $('information' + this.readAttribute('id')).show();
      this.observe('mouseout', hideInformation);
   }

   function hideInformation(event) {
      $('information' + this.readAttribute('id')).hide();
      this.observe('mouseover', showInformation);
   }

   document.observe('dom:loaded', function() {
      $$('form .information').each(function(information){
         information.hide();

         var parent_info = information.up();
         parent_info.addClassName('withInfo');

         information.writeAttribute('id', 'information' + parent_info.readAttribute('id'));
         $('blank_container').insert({
            before: information
         });

         parent_info.observe('mouseover', showInformation);

      });

      $$('.placement').each(addPreferenceClass);
      $$('.activity').each(addActivityClass);
   });


   function setPreferenceClass(item) {
      all_classes = $w(item.className);
      pref_class = all_classes.detect(function (i) {
         return i.match(/^pref/);
      });
      if (pref_class)
         item.removeClassName(pref_class);
      addPreferenceClass(item);
   }

   function setActivityClass(item) {
      item.removeClassName('not-enough-students');
      item.removeClassName('too-many-students');
      addActivityClass(item);
   }

   function change_student_placement() {
      var student = $('SolutionStudents');
      if (student.selectedIndex == 0) {
         alert('Choose a student!');
         return;
      }
      var activity = $('SolutionActivities');
      if (activity.selectedIndex == 0) {
         alert('Choose an activity!');
         return;
      }

      var group_id = student.getValue();
      var activity_id = activity.getValue();
      var group_td = $('G' + group_id).up();
      var from_tr = group_td.up();
      var activity_td = $('A' + activity_id);
      var to_tr = activity_td.up();
      var free_placements = to_tr.select('td.no-student');
      if (free_placements.length == 0) {
         to_tr.insert({bottom: group_td});
      } else {
         free_placements[0].insert({before: group_td});
      }
      setPreferenceClass(group_td);
      setActivityClass(activity_td);
      setActivityClass(from_tr.down('td'));
   }
</script>
<style>
   table tr td.student {width:100px;text-align:center}
   table tr td.group {border:5px solid black;margin:0;padding:0}
   table tr td.pref-0 {background-color:green;}
   table tr td.pref-1 {background-color:GreenYellow;}
   table tr td.pref-2 {background-color:Yellow;}
   table tr td.pref-3 {background-color:Orange;}
   table tr td.pref-4 {background-color:OrangeRed;}
   table tr td.pref-5 {background-color:OrangeRed;}
   table tr td.pref-6 {background-color:OrangeRed;}
   table tr td.pref-no {background-color:Red;}
   table tr td.no-student {width:100px; background-color:grey;}lo
   table tr td.activity {width:150px;height:30px}
   table tr td.not-enough-students {background-color:#FF3030}
   table tr td.too-many-students {background-color:#9400D3}
   table tr td.min-students {border-right:6px solid blue;border-left:6px solid transparent}
   td.placement {border:0; margin:0; padding:0;}
   td.group td.student {border-bottom: 0;margin:0; padding:0; background-color: transparent;}
   td.group table {width:100%; height:100%;border:0; margin:0; padding:0; background-color: transparent;}
   td div.group-container {width:100%;height:30px;border:1px; margin:0; padding:2px; background-color: transparent;}
</style>';


<?php
   echo $this->Form->create();

   echo '<div style="float:left;margin-right:10px">';

   echo '<table border="1" style="border:solid">
            <tr>
               <th>Activity</th>
               <th colspan="2">Score: ' . $this->data['Solution']['score'] . '</th>
               <th colspan="2">Trial: ' . $this->data['Solution']['trial'] . '</th>
               <th colspan="2">Generation: ' . $this->data['Solution']['generation'] . '</th>
            </tr>';
   $nb_students = 0;
   foreach($solution as $activity_key => &$placement) {
      echo '<tr>
               <td class="activity" id="A' . $activity_key . '">
                  <div class="information">
                     <p>Title:' . $activities[$activity_key]['title'] . '</p>
                     <p>Min students: <span class="min-students">' . $activities[$activity_key]['min_participants'] . '</span></p>
                     <p>Max students: <span class="max-students">' . $activities[$activity_key]['max_participants'] . '</span></p>
                  </div>' . $activities[$activity_key]['name'] . '
               </td>';
      $nb_students_in_activity = 0;
      foreach ($placement['groups'] as $student_group_key => $preference){
         $class = "placement";
         if (count($student_groups[$student_group_key]['group']) == 1) {
            $student = $students[$student_groups[$student_group_key]['group'][0]];
            $nb_students++;
            $nb_students_in_activity++;
            $class .= ' student';
            echo '<td class="' . $class . '">
                     <div id="G' . $student_group_key . '" class="group-container">
                        <div class="information">
                           <p>' . $student['name'] . '</p>
                           <ol>';
            foreach($student_groups[$student_group_key]['preferences'] as $preference) {
               echo           '<li>' . $activities[$preference]['name'] . '</li>';
            }
            echo          '</ol>
                        </div>
                        <input type="hidden" name="data[Placement][' . $activity_key . '][]" value="' . $student_group_key . '"></input>
                        ' . $student['number'] . '
                    </div>
                 </td>';
         } else {
            $class .= ' group';
            echo '<td class="' . $class . '" colspan="' . count($student_groups[$student_group_key]['group']) . '">';
            echo '<div id="G' . $student_group_key . '" class="group-container">
                     <table>
                        <tr>';
            foreach($student_groups[$student_group_key]['group'] as $student_key) {
               $student = $students[$student_key];
               $nb_students++;
               $nb_students_in_activity++;
               $class = 'student';
               echo        '<td id="S' . $student['number'] . '" class="' . $class . '">
                              <div class="information">
                                 <p>' . $student['name'] . '</p>
                                 <ol>';
               foreach($student_groups[$student_group_key]['preferences'] as $preference) {
                  echo '            <li>' . $activities[$preference]['name'] . '</li>';
               }
               echo             '</ol>
                              </div>' . $student['number'] . '
                           </td>';
            }
            echo '      </tr>
                     </table>';
            echo '   <input type="hidden" name="data[Placement][' . $activity_key . '][]" value="' . $student_group_key . '"></input>
                  </div>
                  </td>';
         }
      }
      echo '</tr>';
   }
   echo '</table></div>';


   echo '<div>';
   echo $this->Form->input('Solution.course_id', array('type' => 'hidden'));
   echo $this->Form->input('Solution.score', array('type' => 'hidden'));
   echo $this->Form->input('Solution.trial', array('type' => 'hidden'));
   echo $this->Form->input('Solution.generation', array('type' => 'hidden'));
   echo '<div style="width:700px">';
   echo $this->Form->input('Students', array('options' => $dropdown_groups, 'empty' => 'Choose a (group) student', 'div' => array('style' => 'width:300px;position:relative;left:0')));
   echo $this->Form->input('Activities', array('options' => $dropdown_activities, 'empty' => 'Choose an activity', 'div' => array('style' => 'width:300px;position:relative;right:0')));
   echo $this->Form->submit(__('Change student placement', true), array('onclick' => 'change_student_placement();return false'));
   echo '</div>';
   echo '<div style="width:400px">';
   echo $this->Form->input('Solution.name', array('label' => 'Name for this solution'));
   echo '<div style="width:100%;height:50px;position: relative">';
   echo $this->Form->submit(__('Save this solution', true), array('div' => array('style' => 'position:absolute;left:0px', 'onclick' => 'if (!$(\'SolutionName\').getValue()) {alert(\'You must give a name to this solution\');return false} else return true;')));
   echo $this->Form->submit(__('Cancel', true), array('div' => array('style' => 'position:absolute;right:0px'),'onclick' => 'window.location="' . $html->url(array('action' => 'index', 'admin' => false)) . '";return false;'));
   echo '</div>';
   echo '</div>';
   echo $this->Form->end();

?>

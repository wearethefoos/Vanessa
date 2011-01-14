<?php ?>
<script type="text/javascript">
   String.prototype.trim = function() { return this.replace(/^[\s\n]+|[\s\n]+$/g, ''); };

   var clicked_element;

   function addPreferenceClass(item) {
      if (item.hasClassName('no-student'))
         return;

      var preferences = item.select('li');
      var activity = item.up().down().innerHTML.trim();
      for (i=0; i<preferences.length;i++) {
         if (preferences[i].innerHTML.trim() == activity)
            break;
      }
      if (i == preferences.length)
         item.addClassName('pref-no');
      else {
         if (i >= 6)
            i == 6;
         item.addClassName('pref-' + i);
      }
   }

   function addMinStudentsClass(activity_element) {
      var tr_element = activity_element.up();
      var info = $('information' + activity_element.id);
      var min_students = info.down('.min-students').innerHTML;
      var students = tr_element.select('.student');
      for (i = 0; i < students.length; i++) {
         students[i].removeClassName('min-students');
         if (i == min_students - 1)
            students[i].addClassName('min-students');
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
      addMinStudentsClass(item);
   }

   function clickElement(event) {
      if (!clicked_element)
         clicked_element = this;
      clicked_element.addClassName('clicked');
  }

   document.observe('dom:loaded', function() {
      $$('.placement').each(addPreferenceClass);
      $$('.activity').each(addActivityClass);
      $$('.placement', '.activity').each(function(item) {
         item.observe('click', clickElement);
      });
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
      var student = $('CourseStudents');
      if (student.selectedIndex == 0) {
         alert('Choose a student!');
         return;
      }
      var activity = $('CourseActivities');
      if (activity.selectedIndex == 0) {
         alert('Choose an activity!');
         return;
      }

      var group_id = student.getValue();
      var activity_id = activity.getValue();
      var group_td = $('G' + group_id);
      var from_tr = group_td.up();
      var activity_td = $('A' + activity_id);
      var to_tr = activity_td.up();
      var free_placements = to_tr.select('td.no-student');
      if (free_placements.length == 0) {
         to_tr.insert({bottom: group_td});
      } else {
         free_placements[0].insert({before: group_td});
         var nb_students = group_td.select('.student').length;
         if (nb_students == 0)
            nb_students = 1;
         for (i=0; i < free_placements.length && i < nb_students;i++)
            from_tr.insert({bottom: free_placements[i]});
      }
      setPreferenceClass(group_td);
      setActivityClass(activity_td);
      setActivityClass(from_tr.down('td'));
   }
</script>
<style>
   table tr td.student {width:100px}
   table tr td.group {border:3px solid black;margin:0;padding:0}
   table tr td.pref-0 {background-color:green;}
   table tr td.pref-1 {background-color:GreenYellow;}
   table tr td.pref-2 {background-color:Yellow;}
   table tr td.pref-3 {background-color:Orange;}
   table tr td.pref-4 {background-color:OrangeRed;}
   table tr td.pref-5 {background-color:OrangeRed;}
   table tr td.pref-6 {background-color:OrangeRed;}
   table tr td.pref-no {background-color:Red;}
   table tr td.no-student {width:100px; background-color:grey;}
   table tr td.activity {width:150px;}
   table tr td.not-enough-students {background-color:#FF3030}
   table tr td.too-many-students {background-color:#9400D3}
   table tr td.min-students {border-right:8px solid blue}
   table tr td.clicked {border: 5px solid black}
</style>';

<?php
//   $placements->display_activities();
//   $placements->display_preferences();
   echo $this->Form->create('Course');
   $placements->display_placements($result['placements'], $result['end_score'], $result['nb_solutions'], $result['nb_generations']);

   echo '<div style="position:relative;width:800px">';
   echo $this->Form->input('Students', array('options' => $groups, 'empty' => 'Choose a (group) student', 'div' => array('style' => 'position:relative;left:0')));
   echo $this->Form->input('Activities', array('options' => $activities, 'empty' => 'Choose an activity', 'div' => array('style' => 'position:relative;right:0')));
   echo $this->Form->submit(__('Change student placement', true), array('onclick' => 'change_student_placement();return false'));
   echo '</div>';
   echo $this->Form->end('Submit');

//   $placements->display_scores($result['score_history']);
?>

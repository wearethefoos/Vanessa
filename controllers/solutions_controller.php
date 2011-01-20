<?php
class SolutionsController extends AppController {

	var $name = 'Solutions';

	function index() {
      $this->layout = 'large';
		$this->Solution->recursive = 0;
		$this->set('solutions', $this->paginate());
	}

   private function _get_dropdown_data(&$activities, &$student_groups, &$students) {
      $result_groups = array();
      foreach($student_groups as $group_id => $group) {
         $new_group = array();
         foreach($group['group'] as $student_id) {
            $new_group[] = $students[$student_id]['number'];
         }
         sort($new_group);
         $result_groups[$group_id] = implode('-', $new_group);
      }
      $result_activities = array();
      foreach($activities as $activity_id => $activity) {
         $result_activities[$activity_id] = $activity['name'];
      }
      asort($result_groups);

      return array('groups' => $result_groups, 'activities' => $result_activities);
   }

	function admin_add($course_id = null) {
		$this->layout = 'blank';
      require '..\vendors\place_students.php';
      if (isset($this->data['Solution'])) {
         if (!$this->Solution->saveSolution($this->data, true)) {
            $this->Session->setFlash(__('The solution could not be saved. ', true) . $this->Solution->get_errors(), 'flash/modal', array('class' => 'error'));
         } else {
				$this->Session->setFlash(__('The solution has been saved.', true), 'flash/modal', array('class' => 'success'));
         }
         $this->redirect(array('controller' => 'solutions', 'action' => 'index', 'admin' => false));
      }
      else if ($course_id == null) {
         $placements = new PlaceStudents(null, array(
                                                'nb_activities' => 50,
                                                'nb_students' => 550,
                                                'nb_groups' => 100,
                                                'nb_preferences'=> 7
                           ));
      } else {
         $data = array();

         $data['activities'] = $this->Solution->Course->Activity->getActivityListFromCourse($course_id);
         $data['students'] = $this->Solution->Course->getStudentList($course_id);
         $data['student_groups'] = $this->Solution->Course->Group->getStudentGroupListFromCourse($course_id);

         $placements = new PlaceStudents($data);
      }
      $result = $placements->find_best_fit();

      $this->data = array('Solution' => array(
         'course_id' => $course_id,
         'score' => $result['end_score'],
         'trial' => $result['nb_solutions'],
         'generation' => $result['nb_generations']));

      $dropdowndata = $this->_get_dropdown_data($placements->activities, $placements->student_groups, $placements->students);

      $this->set(compact('result', 'placements', 'dropdowndata'));
	}

	function admin_edit($id = null) {
		$this->layout = 'blank';
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid solution', true), 'flash/modal', array('class' => 'error'));
			$this->redirect(array('action' => 'index', 'admin' => false));
		}
		if (!empty($this->data)) {
         if ($this->Solution->saveSolution($this->data, false)) {
				$this->Session->setFlash(__('The solution has been saved', true), 'flash/modal', array('class' => 'success'));
				$this->redirect(array('action' => 'index', 'admin' => false));
			} else {
				$this->Session->setFlash(__('The solution could not be saved. ', true) . $this->Solution->get_errors(), 'flash/modal', array('class' => 'error'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Solution->find('first', array(
                 'conditions' => array('Solution.id' => $id),
                 'contain' => array('SolutionActivity.SolutionActivityGroup')));
		}
      $solution = array();
      foreach($this->data['SolutionActivity'] as $solution_activity) {
         $groups = array();
         foreach ($solution_activity['SolutionActivityGroup'] as $group) {
            $groups[$group['group_id']] = '';
         }
         $solution[$solution_activity['activity_id']] = array('groups' => $groups);
      }

      $course_id = $this->data['Solution']['course_id'];
      $activities = $this->Solution->Course->Activity->getActivityListFromCourse($course_id);
      $students = $this->Solution->Course->getStudentList($course_id);
      $studentgroups = $this->Solution->Course->Group->getStudentGroupListFromCourse($course_id);
      $dropdowndata = $this->_get_dropdown_data($activities, $studentgroups, $students);

		$this->set(compact('solution', 'studentgroups', 'students', 'activities', 'dropdowndata'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for solution', true), 'flash/modal', array('class' => 'error'));
		} else {
         if ($this->Solution->delete($id)) {
            $this->Session->setFlash(__('Solution deleted', true), 'flash/modal', array('class' => 'success'));
         } else {
            $this->Session->setFlash(__('Solution was not deleted', true), 'flash/modal', array('class' => 'error'));
         }
      }
      $this->redirect(array('action'=>'index', 'admin' => false));
	}
}
?>
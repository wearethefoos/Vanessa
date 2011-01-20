<?php // Dummy tag so that NetBeans understands that this file is a PHP file
?>
<script>
   var sort_order = 'asc';
   var sort_uvanetid_fn = function(a,b) {
      var compA = ($(a).childElements())[1].firstChild.nodeValue;
      var compB = ($(b).childElements())[1].firstChild.nodeValue;
      if (sort_order == 'desc')
         return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
      else
         return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
   };
   var sort_name_fn = function(a,b) {
      var compA = ($(a).childElements())[2].firstChild.nodeValue;
      var compB = ($(b).childElements())[2].firstChild.nodeValue;
      if (sort_order == 'desc')
         return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
      else
         return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
   };

   function sort_students(sort_fn, sort_order_id) {
      var top = $("student_table");
      var sort_order_elt = $(sort_order_id);
      sort_order = sort_order_elt.getValue();
      if (sort_order == 'desc')
         sort_order_elt.value = 'asc';
      else
         sort_order_elt.value = 'desc';

      var students = $$("#student_table > tr");
      students.splice(0, 1);
      students.sort(sort_fn);
      students.each(function(item) {
         top.insert({'bottom':item});
      });
   }

   function show_message(msg, type) {
      $('flashMessage').removeClassName('error');
      $('flashMessage').removeClassName('success');
      $('flashMessage').addClassName(type);
      $('flashMessage').update(msg);
      $('flashMessage').morph('top:0px;', { duration:1.3, transition: 'elastic' });
      setTimeout(
            function() {$('flashMessage').morph('top:-100px;', { duration:1.3, transition: 'easeTo' });},
            10000);
   }

   function send_invitation_emails(course_id) {
      <?php
         echo 'url = "' . $this->Html->url(array('action' => 'send_invitation_emails', 'admin' => true)) . '/";';
      ?>
      ajax = new Ajax.Request(url + course_id,
      {
         onSuccess: function(transport) {
            var json = transport.responseText.evalJSON();
            show_message(json.message, json.type);
         },
         onFailure: function() {
            show_message('Error when sending emails', 'error');
         }
      });
   }

   function send_emails_to_selected_students() {
      var emails = [];
      $$('input.selected').each(function (item){if (item.checked) {emails.push(item.readAttribute('email'))}});
      window.location = "mailto:<?php echo $email ?>?bcc=" + emails.join(';');
   }
   
   function select_all(checked) {
      $$('input.selected').each(function(item){item.checked = checked});
   }

   function delete_selected_students() {
      <?php
         echo 'url = "' . $this->Html->url(array('action' => 'delete_invite', 'admin' => true)) . '/' . $this->data['Course']['id'] . '/";';
      ?>
      var student_ids = [];
      $$('input.selected').each(function (item){if (item.checked) {student_ids.push(item.readAttribute('student_id'))}});
      window.location = url + student_ids.join('_');
   }




</script>
<div class="courses">
	<h3 class="pink"><?php echo __('Invite Students for course ', true) . $this->data['Course']['name'] ?></h3>
	<?php
      $students_not_found = '';
      if (isset($studentsnotfound)) {
         foreach ($studentsnotfound as $student_nummer) {
            $students_not_found .= $student_nummer . chr(13);
         }
      }
		echo $this->Form->create('Course');
		echo $this->Form->input('id');
		echo $this->Form->input('name', array('type' => 'hidden'));
		echo $this->Form->input('students', array(
         'value' => $students_not_found,
			'type' => 'textarea', 
			'between' => '<div class="help"><p>' . __('You can add multiple students at once by placing each student number on a new line, like so:', true) . '</p><p>12345678<br />23456789<br />34567890</p></div>'
			));
		echo $this->Form->input('password', array(
			'label' => __('Your password', true), 
			'type' => 'password', 
			'required' => true, 
			'between' => '<div class="help"><p>' . __('I need your UvAnetID password to look up the students you add in the UvA directory service.', true) . '</p></div>'
			));
 ?>
   <div style="width:100%;height:50px;position: relative">
<?php
   echo $this->Form->submit(__('Invite!', true), array('div' => array('style' => 'position:absolute;left:0px')));
   echo $this->Form->submit(__('Cancel', true), array('div' => array('style' => 'position:absolute;right:0px'),'onclick' => 'window.location="' . $html->url(array('action' => 'index', 'admin' => false)) . '";return false;'));
?>
   </div>
<?php
   echo $this->Form->end();
	?>
	<div class="students">
		<h3><?php __('Assigned Students'); ?></h3>
		<?php if (!count($students)) : ?>
			<p><?php __('No assigned students yet...'); ?></p>
		<?php else :
         echo $this->Form->button(__('Send emails to selected students', true), array(
                           'type' => 'button',
                           'onclick' => 'send_emails_to_selected_students()'
         ));
      ?>
		<table>
			<tbody id="student_table">
            <tr id="student_header">
               <th><?php echo $this->Form->checkbox('all', array('title' => 'select/unselect all', 'onclick' => 'select_all(this.checked)'));?></th>
               <th><a title="sort per UvanetID" href="#" onclick="sort_students(sort_uvanetid_fn, 'sort_order_uvanetid')">UvanetID</a></th>
               <th><a title="sort per name" href="#" onclick="sort_students(sort_name_fn, 'sort_order_name')">Name</a></th>
               <th><?php echo $this->Html->link(
                                    $this->Html->image(
                                                   'delete.png',
                                                   array('title' => __('delete selected students', true),
                                                         'onclick' => 'return confirm("' . __('Are you sure you want to delete the selected students?', true) . '")')
                                                ),
                                    "#",
                                    array('onclick' => 'delete_selected_students();return false',
                                          'escape' => false)
                              );
                  ?></th>
               <input type="hidden" id="sort_order_name" value="asc"/>
               <input type="hidden" id="sort_order_uvanetid" value="asc"/>

			<?php foreach($students as $student) : ?>
				<tr>
               <td><?php echo $this->Form->checkbox('selected', array('class' => 'selected', 'student_id' => $student['id'], 'email' => $student['User']['email'])); ?></td>
					<td><?php echo $student['coll_kaart']; ?></td>
					<td><?php echo $student['User']['name']; ?></td>
               <td><?php echo $this->Html->link(
                                    $this->Html->image(
                                                   'delete.png',
                                                   array('title' => __('delete this student', true),
                                                         'onclick' => 'return confirm("' . __('Are you sure you want to delete this student?', true) . '")')
                                                 ),
                                    array('action' => 'delete_invite',
                                          $this->data['Course']['id'],
                                          $student['id'],
                                          'admin' => true),
                                    array('escape' => false)
                              );
               ?></td>
				</tr>
			<?php endforeach; ?>
			<tbody>
		</table>
		<?php endif; ?>
	</div>
</div>

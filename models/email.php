<?php
class Email extends AppModel {

   var $name = 'Email';
   var $useTable = 'mail_box';
   var $primaryKey = 'msg_id';

   static $vanessa_email_adres = 'b.boutin@uva.nl';
   static $vanessa_coordinator = 'Bruno Boutin';

   function beforeSave()
   {
      if(!empty($this->data['Email']['post_date']))
        unset($this->data['Email']['post_date']);

      return true;
   }

   public function sendCourseInvitation($student, $course_name, $supervisor) {
      $student_email = $student['User']['email'];
      $student_user_name = $student['User']['name'];
      $subject = __('Course invitation', true);

      $message = array();
      $message[] = sprintf(__('You are invited to take part to the course %s.', true), $course_name);
      $message[] = __('Please log in to the Vanessa web site (www.test.uva.nl/vanessa) to choose which activities in this course you would prefer', true);

      $contact = array($supervisor['name'],
                        'email: ' . $supervisor['email']);

      $this->_sendEmail($student_email, array(
                              'source' => 'courses_controller::admin_send_email_invitation',
                              'user_name' => $student_user_name,
                              'subject' => $subject,
                              'message' => $message,
                              'reply_to' => $supervisor['email'],
                              'contact' => $contact)
      );
   }

   private function _sendEmail($email, $data) {
      // Retrieve Email source
      if (isset($data['source']))
         $source = 'Vanessa::' . $data['source'];
      else
         $source = 'Vanessa';

      // Retrieve Email subject
      if (isset($data['subject']))
         $subject = 'Vanessa: ' . $data['subject'];
      else
         $subject = 'Vanessa';

      // Retrieve start message
      $start_message = __('Dear', true) . ' ';
      if (isset($data['user_name']))
         $start_message .= $data['user_name'];
      else
         $start_message .= 'Vanessa ' . __('user', true);

      // Retrieve Email plain message
      $message = $data['message'];
      if (is_array($message)) {
         $plain_message = '';
         foreach($message as $paragraph) {
            $plain_message .= $paragraph . '
';
         }
      } else
         $plain_message = $message;

      // Retrieve Email HTML message
      if (isset($data['html_message']))
         $html_message = $data['html_message'];
      else {
         if (is_array($message)) {
            $html_message = '';
            foreach($message as $paragraph) {
               $html_message .= '<p>' . $paragraph . '</p>';
            }
         } else {
            $html_message = '<p>' . $message . '</p>';
         }
      }

      // Retrieve Email reply-to
      if (isset($data['reply_to']))
         $reply_to = $data['reply_to'];
      else
         $reply_to = Email::$vanessa_email_adres;

      // Retrieve Email contact
      if (isset($data['contact'])) {
         if (is_array($data['contact'])) {
            $contact = $data['contact'];
         } else {
            $contact = array($data['contact']);
         }
      } else
         $contact = array(Email::$vanessa_coordinator, __('Vanessa', true));
      $greeting = __('Thanks', true);

      // Build plain and HTML end message
      $plain_end_message = $greeting . ',

';
      $html_end_message = '<p>' . $greeting . ',</p></p>';
      foreach($contact as $contact_line) {
         $plain_end_message .= '
' . $contact_line;
         $html_end_message .= '<p>' . $contact_line . '</p>';
      }

      $boundary = md5(date('r', time()));
      $msg_hdr = 'From: Vanessa@uva.nl'."\r\n".'Reply-to: '. $reply_to ."\r\nContent-Type: multipart/alternative; boundary=\"$boundary\"";

      // Build Email Message
      $msg_txt = "
--$boundary\r\n".
"Content-type: text/plain; charset=\"utf-8\"\r\n".
"Content-Transfer-Encoding: 7bit\r\n".
"\r\n".
"--$boundary\r\n".
"   Content-type: text/plain; charset=\"utf-8\"\r\n".
"   Content-Transfer-Encoding: 7bit\r\n".
"\r\n".
"  $start_message \r\n".
"\r\n".
" $plain_message \r\n".
"\r\n".
"\r\n".
" $plain_end_message \r\n".
"\r\n".
"--$boundary\r\n".
"Content-type: text/html; charset=\"utf-8\"\r\n".
"Content-Transfer-Encoding: 7bit\r\n".
"\r\n".
"   <p>$start_message</p>$html_message</p></p>$html_end_message</p>\r\n".
"\r\n".
"--$boundary--\r\n";

      if (defined(SEND_ALL_EMAILS_TO_LOGGED_USER) && SEND_ALL_EMAILS_TO_LOGGED_USER) {
         $CakeSession =& new CakeSession(null, false);
         $id = $CakeSession->read('Auth.User.id');
         if ($id) {
            $user_model = new User();
            $user_record = $user_model->findById($id);
            if (isset($user_record['User']['email']) && $user_record['User']['email']) {
               $subject .= '( TO: ' . $email . ')';
               $email = $user_record['User']['email'];
            }
         }
      }
      $this->create(array('msg_src' => $source,
                                 'msg_to' => $email,
                                 'msg_sbj' => $subject,
                                 'msg_txt' => $msg_txt,
                                 'msg_hdr' => $msg_hdr,
                                 'send' => 0
                           ));
      $this->save();
   }

}
?>
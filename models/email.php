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

   function sendAppointment($email, $user_name, $timeslot, $owner, $cancel_appointment = false) {
      App::import('model', 'Location');                           // load location model
      $location_model = new Location();
      $location = $location_model->read(null, 2);                 // get location
      $location = $location['Location'];
      $research_type = '';
      foreach($timeslot['Project']['ProjectType'] as $key => $project_type) {
         if ($key != 0)
            $research_type .= ', ';
         $research_type .= $project_type['name'];
      }

      $msg_sbj = ($cancel_appointment ? __('Annulering van afspraak', true) : __('Registratie bevestiging.', true)) ;

      $message = array(
         __('Onderzoekspanel Psychologie bevestigt hiermee', true) . ' ' . ($cancel_appointment ? __('het annulering van onderstaande afspraak:', true) : __('de registratie voor het volgende project:', true)),
         '',
         __('Project titel', true) . ': '. $timeslot['Project']['prj_name']);
      if (!$cancel_appointment)
         $message[] = __('Datum', true) . ': ' . ($timeslot['Timeslot']['ts_nr'] == 0 ? __('de onderzoeker neemt contact met je op om een afspraak te maken', true) : $timeslot['Timeslot']['ts_date']);
      else if ($timeslot['Timeslot']['ts_nr'] != 0)
         $message[] = __('Datum', true) . ': ' . $timeslot['Timeslot']['ts_date'];
      if (isset($timeslot['Room']['roomnr']))
         $message[] = __('Lokatie', true) . ':' . __('Kamer', true) . ' ' . $timeslot['Room']['roomnr'];
      $message[] = $location['street'] . ' ' . $location['str_nr'] . ',  ' . $location['zip'] . ', ' . $location['city'] . ', ' . $location['country'];
      $message[] = __('Telephone', true) . ': ' .$location['tel'];
      $message[] = __('Type onderzoek', true) . ': '. $research_type;
      $message[] = __('Duur', true) . ': ' . $timeslot['Project']['ses_dur'] . ' ' . __('minuten', true);

      $contact = array($owner['Contact']['full_name'],
                        'email: ' . $owner['Contact']['email']);
      if ($owner['Contact']['telephone'])
         $contact[] = 'tel: ' . $owner['Contact']['telephone'];
      if ($owner['Contact']['mobile'])
         $contact[] = 'mob: ' . $owner['Contact']['mobile'];
         $this->_sendEmail($email, array(
                              'source' => 'timeslots_controller::' . ($cancel_appointment ? 'cancelAppointmentMail' : 'sendConfirmationMail'),
                              'user_name' => $user_name,
                              'subject' => $msg_sbj,
                              'message' => $message,
                              'reply_to' => $owner['Contact']['email'],
                              'contact' => $contact)
                           );

   }

   function sendAccountValidation($email, $user_name, $account_name, $activate_link, $site_link) {
      $msg_sbj = __('Validatie van jouw Onderzoekspanel Psychologie account.', true);

      $plain_msg =
   __('Dank je wel voor je aanmelding voor de onderzoekspanel Psychologie van de Universiteit van Amsterdam.', true) . '

' . __('Om jouw account te activeren volg de volgende link:', true) . '

   '.$activate_link.'

' . __('Om vanaf nu in te kunnen loggen gebruik je:', true) . '

' . __('Naam', true) . ':          '.$account_name.'
' . __('Website', true) . ':       '.$site_link.'

' . __('Bewaar deze gegevens goed want deze worden maar eenmalig verstrekt.', true) . '

' . __('Als je op de website inlogt kom je in je persoonlijke omgeving.
Hier kun je je gegevens controleren en wijzigen.
Ook vind je hier bij "Mijn eigenschappen" een nieuwe vragenlijst.
Als je deze invult kun je ook meedoen aan betaald internetonderzoek en labonderzoek.', true) . '

' . __('Kijk snel op je persoonlijke pagina bij "Beschikbaar onderzoek" of
er al onderzoek beschikbaar is dat je interesse heeft en meld je aan.', true);

   $html_msg = '
   <p>' . __('Dank je wel voor je aanmelding voor de Subjectpool Psychologie van de Universiteit van Amsterdam.', true) . '</p>

   <p><a href="'.$activate_link.'">' . __('Klik hier om jouw account te activeren</a> of volg de volgende link:', true) . '</p>

   <p><a href="'.$activate_link.'">'.$activate_link.'</a></p>

   <p>' . __('Om vanaf nu in te kunnen loggen gebruik je:', true) . '</p>

   <table>
     <tr>
       <th>' . __('Naam', true) . ':</th><td>'.$account_name.'</td>
     </tr>
     <tr>
       <th>' . __('Website', true) . ':</th><td><a href="'.$site_link.'">'.$site_link.'</a></td>
     </tr>
   </table>

   <p>' . __('Bewaar deze gegevens goed want deze worden maar eenmalig verstrekt.', true) . '</p>

   <p>' . __('Als je op de website inlogt kom je in je persoonlijke omgeving.
   Hier kun je je gegevens controleren en wijzigen.<br>
   Ook vind je hier bij "Mijn eigenschappen" een nieuwe vragenlijst.
   Als je deze invult kun je ook meedoen aan betaald internetonderzoek en labonderzoek.', true) . '</p>

   <p>' . __('Kijk snel op je persoonlijke pagina bij "Beschikbaar onderzoek" of
   er al onderzoek beschikbaar is dat je interesse heeft en meld je aan.', true) . '</p>

   <p></p>';

   $this->_sendEmail($email, array(
                     'source' => 'users_controller::register3',
                     'user_name' => $user_name,
                     'subject' => $msg_sbj,
                     'message' => $plain_msg,
                     'html_message' => $html_msg)
                  );
   }

   function sendAppointmentNotification($supervisor_email, $supervisor_user_name, $participant_email, $participant_user_name, $timeslot, $cancel_appointment = false) {
      $is_appointment = ($timeslot['Timeslot']['ts_nr'] == 0 || !$timeslot['Timeslot']['ts_date']);
      $msg_sbj = $cancel_appointment ? __('Annulering notificatie', true) : __('Registratie notificatie', true);
      $message = __('Onderzoekspanel Psychologie wil je graag informeren dat ', true) . $participant_user_name . ' (email: ' . $participant_email . ') ';
      if ($cancel_appointment) {
         $message .= __('heeft de afspraak voor het project', true) . ' ' . $timeslot['Project']['prj_name'];
         if (!$is_appointment) {
            $message .= ' ' . __('op ', true) . $timeslot['Timeslot']['ts_date'];
         }
         $message .=  ' ' . __('geannuleerd', true);
      } else {
         if ($is_appointment) {
            $message .= __('heeft zich ingeschreven voor het project', true) . ' ' .$timeslot['Project']['prj_name'];
            $message .= __(', en verwacht dat je binnenkort contact opneemt om een afspraak te maken', true);
         } else {
            $message .= __('is nu ingeschreven voor het project', true) . ' ' .$timeslot['Project']['prj_name'];
            $message .= ' ' . __('op ', true) . $timeslot['Timeslot']['ts_date'];
         }
      }
      $message .= '.';
      $this->_sendEmail($supervisor_email, array(
                              'source' => 'timeslots_controller::sendConfirmationMail',
                              'user_name' => $supervisor_user_name,
                              'subject' => $msg_sbj,
                              'message' => $message)
                           );
   }

   public function sendCourseInvitation($student, $course_name, $supervisor) {
      $student_email = $student['User']['email'];
      $student_user_name = $student['User']['name'];
      $subject = __('Course invitation', true);

      $message = array();
      $message[] = sprintf(__('You are invited to take part to the course %s.', true), $course_name);
      $message[] = __('Please log in to the Vanessa web site (www.test.uva.nl\vanessa) to choose which activities in this course you would prefer');

      $contact = array($supervisor['User']['name'],
                        'email: ' . $supervisor['User']['email']);

      $this->_sendEmail($student_email, array(
                              'source' => 'courses_controller::admin_send_email_invitation',
                              'user_name' => $student_user_name,
                              'subject' => $subject,
                              'message' => $message,
                              'reply_to' => $supervisor['User']['email'],
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
<?php 
if (($session->read('Auth.User.role_id')==4)) {
    $password_link='https://accountbeheer.student.uva.nl:6601/bin/user/admin/bin/enduser';
} else {
    $password_link='https://id.uva.nl/idm/user/login.jsp';
}
$password_link=$html->link(__('You can change your UvA password here.', true), $password_link, array('class' => 'help-link'));
?>
<div class="users form">
<?php echo $this->Form->create('User', array('url' => array('action' => 'profile')));?>
	<fieldset>
 		<legend><?php printf(__('Edit %s', true), __('Profile', true)); ?></legend>
 		<div><strong><?php __('Username') ?>:</strong> <?php echo $this->data['User']['username']; ?></div>
	<?php
		echo $this->Form->input('id');
		//echo $this->Form->input('passwordhint', array('label' => __('password', true), 'disabled' => 'disabled', 'after' => $password_link, 'value' => __('Password change locked', true)));
		echo $this->Form->input('email');
		echo $this->Form->input('phone_number');
		echo $this->Form->input('name');
		echo $this->Form->input('sex', array('options' => array(1 => __('male', true), 2 => __('female', true)), 'type' => 'radio'));
		echo sprintf(__('Role: %s', true), $this->data['Role']['name']);
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit', true));?>
</div>
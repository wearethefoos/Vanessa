<div class="courses">
<?php echo $this->Form->create('Course');?>
	<fieldset>
 		<legend><?php printf(__('Add %s', true), __('Course', true)); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('deadline', array('dateFormat' => 'DMY', 'minYear' => date('Y'),'maxYear' => date('Y') + 5));
		echo $this->Form->input('amount_of_preferences');

	?>
	</fieldset>

   <div style="width:100%;height:50px;position: relative">
<?php
   echo $this->Form->submit(__('Submit', true), array('div' => array('style' => 'position:absolute;left:0px')));
   echo $this->Form->submit(__('Cancel', true), array('div' => array('style' => 'position:absolute;right:0px'),'onclick' => 'window.location="' . $html->url(array('action' => 'index', 'admin' => false)) . '";return false;'));
?>
   </div>
<?php
   echo $this->Form->end();
?>
</div>
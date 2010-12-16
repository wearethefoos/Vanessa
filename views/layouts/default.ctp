<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Vanessa:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->meta('description', $description);
		echo $this->Html->meta('keywords', $keywords);
		echo $this->Html->meta('author', 'W.R. de Vos');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('vanessa');
		echo $this->Html->script('prototype.s2.min');
		echo $this->Html->script('s2.ui');

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="header">
		<?php if ($this->Session->read('Auth.User')) {
		    echo $this->Html->link(
		        $this->Html->image('logout.png', array('alt' => __('logout', true))),
		        '/logout',
		        array('escape' => false)
		    );
		}
		?>
	</div>
	<div id="container">
		
		<div id="content">
			
        	<div id="content-heading">
            	<?php echo $this->Html->image(
            	    'vanessa-text.png',
            	    array('alt' => 'Vanessa')
            	);?>
        	</div>
			<?php echo $this->Session->flash(); ?>
			
			<div id="content-inner"><?php echo $content_for_layout; ?></div>
			<?php 
				$section['Assignments'] = array('assignments', 'placements', 'students_preferences', 'solutions');
				$section['Courses'] = array('courses', 'activities', 'preferences');
				
				$current_section = Router::getParams(); $current_section = $current_section['controller']; 
			?>
		<div id="menu">
			<ul class="menu">
				<?php $link = $this->Html->url('/dashboard'); $class = ($this->here == $link) ? ' active' : ''; ?>
				<li class="blue<?php echo $class;?>"><?php echo $this->Html->link(__('Dashboard', true), '/dashboard'); ?></li>
				<?php $link = $this->Html->url('/assignments'); $class = (in_array($current_section, $section['Assignments'])) ? ' active' : ''; ?>
				<li class="purple<?php echo $class;?>"><?php echo $this->Html->link(__('Assignments', true), '/assignments'); ?></li>
				<?php $link = $this->Html->url('/courses'); $class = (in_array($current_section, $section['Courses'])) ? ' active' : ''; ?>
				<li class="pink<?php echo $class;?>"><?php echo $this->Html->link(__('Courses', true), '/courses'); ?></li>
		    </ul>
		</div>
		<div id="vanessa" class="vanessa"></div>
        <div id="speech-bubble" class="speech-hi"></div>
        <style>
            #login-button { position: absolute; z-index: 2; top: 68px; left: 278px; }
            #vanessa { position: absolute; z-index: 2; top: 448px; left: 560px; }
            #speech-bubble { position: absolute; z-index: 3; top: 400px; left: 700px; }
        </style>
		</div>
	</div>
</body>
</html>
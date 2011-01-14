<h2><?php echo $course['Course']['name']; ?></h2>
<div class="assignments tree">
<?php if (count($preferences)) : // preferences are already set ?>
	<div id="tree" class="activities">
		<h3><?php __('Your cherries'); ?></h3>
		<ol>
		<?php foreach ($preferences as $preference) : ?>
			<li class="activity unwantedness-<?php echo $preference['Preference']['unwantedness']; ?>"><?php echo $preference['Activity']['name']?></li>
		<?php endforeach; ?>
		</ol>
	</div>
<?php else : 

    echo $this->Html->script('s2.delegate');
    echo $this->Html->script('s2.sortable');
?>
		<div class="header"></div>
		<div id="tree" class="activities">
			<ul class="sortable">
			<?php foreach ($course['Activity'] as $i => $activity) : ?>
				<li id="<?php echo $activity['id'] ?>" class="activity draggable"><?php echo $activity['name']?></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="assignments basket">
		<div id="basket" class="activities">
			<ul class="sortable">
				<?php for ($j=0; $j<$course['Course']['amount_of_preferences']; $j++) : ?>
				<li id="cherry_<?php echo $j ?>" class="cherry sortable droppable"><?php echo $j+1 ?></li>
				<?php endfor; ?>
			</ul>
		</div>
		<button onclick="savePrefs()">Save</button>
	<script type="text/javascript">
		var tree = new CD3.Dnd.Draggable('tree');
		var basket = new CD3.Dnd.Sortable('basket');
//		var tree = new CD3.Dnd.Draggable('tree');

		var savePrefs = function() {
			var assignments = new Array();
			var cherries    = new Array();
			
			var i=0;
			$$('#basket ul li').each(function(c) {
				if (!c.hasClassName('cherry')) {
					cherries[i] = c.readAttribute('id');
				}
				i++;
			});
			if (cherries.length < i) {
				alert('<?php
					__('You need to pick all your '); ?>' + i + ' <?php __('cherries first ;)');
				?>');
			} else {
				var j=0;
				$$('#tree ul li').each(function(a) {
					assignments[j] = a.readAttribute('id');
					j++;
				})
				new Ajax.Updater('basket', '<?php
					echo $this->Html->url(array(
						'controller' => 'preferences',
						'action' => 'add',
						$course['Course']['id'],
					));?>', {
						parameters: { prefs: serialize(cherries), noprefs: serialize(assignments) },
						insertion: 'top'
					});
			}
			return false;
		}
		
	</script>
<?php endif; ?>
		</div>
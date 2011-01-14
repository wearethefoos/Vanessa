document.observe('dom:loaded', function() {
	$$('form .help').each(function(i){
		i.hide();
		
		var helpable = i.next('input, textarea, select');
		helpable.writeAttribute('class', 'withHelp');
		
		var help = i.clone(true);
		help.writeAttribute('id', 'help' + helpable.readAttribute('id'));
		$('container').insert({
			before: help
		});
		
		helpable.observe('click', showHelp);
	});

   $$('form .information').each(function(information){
      information.hide();

      var parent_info = information.up();
		parent_info.addClassName('withInfo');

      if (parent_info.hasClassName('student')) {
         information = information.clone(true);
      }
		information.writeAttribute('id', 'information' + parent_info.readAttribute('id'));
		$('blank_container').insert({
			before: information
		});

		parent_info.observe('mouseover', showInformation);

   });
});

function showInformation(event) {
	setToMousePosition($('information' + this.readAttribute('id')), event);
	$('information' + this.readAttribute('id')).appear();
	this.observe('mouseout', hideInformation);
}

function hideInformation(event) {
	$('information' + this.readAttribute('id')).fade();
	this.observe('mouseover', showInformation);
}


function showHelp(event) {
	setToLeftOfOtherElementsPosition($('help' + this.readAttribute('id')), this);
	$('help' + this.readAttribute('id')).appear();
	this.observe('blur', hideHelp);
}
function hideHelp(event) {
	$('help' + this.readAttribute('id')).fade();
	this.observe('click', showHelp);
}
function setToMousePosition(element, e) {
	element = $(element);
	var pointer = e.pointer();

    // Can sometimes happen if the pointer exited the window during
    // mousedown.
    //if (!this._startPointer) return;
    var delta = {
      x: pointer.x + 20,
      y: pointer.y + 10
    };

    var newPosition = {
      left: delta.x + 'px',
      top:  delta.y + 'px'
    };
	
    element.setStyle(newPosition);
}

function setToLeftOfOtherElementsPosition(element, otherElement) {
	element = $(element);
	otherElement = $(otherElement).makePositioned();
	
	elementsLayout = element.getLayout();
	otherElementsLayout = otherElement.getLayout();
	containerLayout = $('container').getLayout();

    // Can sometimes happen if the pointer exited the window during
    // mousedown.
    //if (!this._startPointer) return;

    var delta = {
      x: containerLayout.get('margin-left') + otherElementsLayout.get('left') - elementsLayout.get('width'),
      y: containerLayout.get('margin-top') + otherElementsLayout.get('top')
    };

    if (Prototype.Browser.IE || Prototype.Browser.Gecko) {
       delta.x += elementsLayout.get('width');
    }

    var newPosition = {
      left: delta.x + 'px',
      top:  delta.y + 'px'
    };
	
    element.setStyle(newPosition);
}
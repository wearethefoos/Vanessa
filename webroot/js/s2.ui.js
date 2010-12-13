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
});

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
      x: pointer.x,
      y: pointer.y
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

    var newPosition = {
      left: delta.x + 'px',
      top:  delta.y + 'px'
    };
	
    element.setStyle(newPosition);
}
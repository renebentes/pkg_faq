if(jQuery && jQuery.noConflict) {
	jQuery.noConflict();
}
function addHit(id) {
	if(!jQuery('[id="collapse' + id + '"]').hasClass('in')) {
		jQuery.post(
			'index.php?option=com_faq&view=faq&task=hit',
			{Itemid : id}
		);
	}
}
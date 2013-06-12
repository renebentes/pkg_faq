if(jQuery && jQuery.noConflict) {
	jQuery.noConflict();
}
function addHit(id) {
	if(!jQuery('[id="collapse' + id + '"]').hasClass('in')) {
		jQuery.ajax({
			url: 'index.php?option=com_faq&task=faq.savehitajax&format=json',
			type: 'POST',
			data: {
				Itemid : id
			},
			dataType: 'json',
			success: function(data) {
			}
		});
	}
}
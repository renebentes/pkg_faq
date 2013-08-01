function hit(id) {
	if(!jQuery('[id="collapse' + id + '"]').hasClass('in')) {
		jQuery.ajax({
			url: 'index.php?option=com_faq&task=faq.savehitajax',
			type: 'POST',
			data: {
				Itemid : id
			},
			dataType: 'json',
			success: function(data) {
				jQuery('[id="hits' + id + '"]').text(data.message);
			}
		});
	}
}

function rating(id, like) {
	if(!jQuery('[id="collapse' + id + '"]').hasClass('in')) {
		jQuery.ajax({
			url: 'index.php?option=com_faq&task=faq.saveratingajax',
			type: 'POST',
			data: {
				Itemid : id,
				like : like
			},
			dataType: 'json',
			success: function(data) {
			}
		});
	}
}
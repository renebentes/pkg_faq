(function($) {
	$(function() {
		// Add hit
		$('a.accordion-toggle').click(function () {
			var id = $(this).attr('href');
			if(!$(id).hasClass('in')) {
				id = id.substr(9);
				$.ajax({
					url: 'index.php?option=com_faq&task=faq.savehitajax',
					type: 'POST',
					data: {
						Itemid : id
					},
					dataType: 'json',
					success: function(data) {
						$('#hits' + id).text(data.message);
					}
				});
			}
		});

		// Rating
		$('a.vote').click(function() {
			var vote = $(this).attr('href');
			var rate = '';
			var id = 0;
			if (vote.search('#up') != -1) {
				rate = vote.substr(1, 2);
				id = vote.substr(3);
			}
			else {
				if (vote.search('#down') != -1) {
					rate = vote.substr(1, 4);
					id = vote.substr(5);
				}
			}
			if (rate != '' && id != 0) {
				$.ajax({
					url: 'index.php?option=com_faq&task=faq.saveratingajax',
					type: 'POST',
					data: {
						Itemid : id,
						rate : rate
					},
					dataType: 'json',
					success: function(data) {
						if (data.status == 1) {
							$('#' + rate + id).text(data.message);
						}
						else {
							alert(data.message);
						}
					}
				});
			}
		});
	});
})(jQuery);
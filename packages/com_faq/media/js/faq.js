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
	});
})(jQuery);

/* ===================================================
 * pinto-rating.js v1.0.0
 * http://
 * ===================================================
 * Copyright 2013 Rene Bentes Pinto, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

 (function($){
    $.fn.extend({
    	rating: function(options) {
    		var defaults = {
    			url: null
    		};

    		var settings = $.extend(defaults, options);

    		return this.each(function() {
        		if (settings.url == null) {
        			return;
        		}

        		var container = $(this);

        		container.click(function() {
        			var id = $(container).children('a').attr('class').split('-')[1];
        			var rate = $(container).children('a').attr('class').split('-')[0];
					$.ajax({
						url: settings.url,
						type: 'POST',
						data: {
							Itemid: id,
							rate: rate
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
        		});
        	});
    	}
    });
})(jQuery);
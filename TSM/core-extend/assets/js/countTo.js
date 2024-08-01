// jQuery CountTo
// http://stackoverflow.com/questions/2540277/jquery-counter-to-count-up-to-a-target-number

(function($) {
	'use strict';
	
    $.fn.countTo = function(options) {
        // merge the default plugin settings with the custom options
        options = $.extend({}, $.fn.countTo.defaults, options || {});

        // how many times to update the value, and how much to increment the value on each update
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;

        return $(this).each(function() {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));

                if (typeof(options.onUpdate) == 'function') {
                    options.onUpdate.call(_this, value);
                }

                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;

                    if (typeof(options.onComplete) == 'function') {
                        options.onComplete.call(_this, value);
                    }
                }
            }
        });
    };

    $.fn.countTo.defaults = {
        from: 0,  // the number the element should start at
        to: 100,  // the number the element should end at
        speed: 1000,  // how long it should take to count between the target numbers
        refreshInterval: 10,  // how often the element should be updated
        decimals: 0,  // the number of decimal places to show
        onUpdate: null,  // callback method for every time the element is updated,
        onComplete: null,  // callback method for when the element finishes updating
    };


	$('.mnky_counter_wrapper').each(function() {
		var data_wrapper = $(this).find('.count_data');
		var waypoints = data_wrapper.vcwaypoint({
			handler: function() {
				
				var count_from = data_wrapper.data('count-from'),
					count_to = data_wrapper.data('count-to'),
					count_speed = data_wrapper.data('count-speed'),
					count_interval = data_wrapper.data('count-interval'),
					count_decimals = data_wrapper.data('count-decimals');	  
			  
				data_wrapper.countTo({
					from: count_from, 
					to: count_to, 
					refreshInterval: count_interval, 
					speed: count_speed,
					decimals: count_decimals
				});
				
				this.destroy()
			},
			offset: '90%',
		});
	});

})(jQuery);
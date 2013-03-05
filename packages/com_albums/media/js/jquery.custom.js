jQuery.noConflict();

(function ($) {
	$(function () {
		// Call the mask.
		if ($.fn.setMask) {
			$('.phone').setMask('phone');
			$('.phone-us').setMask('phone-us');
			$('.cpf').setMask('cpf');
			$('.cnpj').setMask('cnpj');
			$('.date').setMask('date');
			$('.date2').setMask({
				mask: '39-19-9999'
			});
			$('.date-us').setMask('date-us');
			$('.zip').setMask('cep');
			$('.time').setMask('time');
			$('.cc').setMask('cc');
			$('.integer').setMask('integer');
			$('.integer-limit5').setMask({
				mask: '999.99',
				'maxLength': 5,
				type: 'reverse'
			});
			$('.decimal').setMask('decimal');
			$('.decimal-us').setMask('decimal-us');
			$('.signed-decimal').setMask('decimal-us');
			$('.signed-decimal-us').setMask('decimal-us');
		}

		if ($.fn.masonry) {
			var $container = $('#images');
			var gutter = 10;
			var min_width = 133;

			$container.imagesLoaded(function () {
				$container.masonry({
					itemSelector: '.box',
					gutterWidth: gutter,
					isAnimated: true,
					columnWidth: function (containerWidth) {
						var num_of_boxes = (containerWidth / min_width | 0);
						var box_width = (((containerWidth - (num_of_boxes - 1) * gutter) / num_of_boxes) | 0);

						if (containerWidth < min_width) {
							box_width = containerWidth;
						}

						$('.box').width(box_width);

						return box_width;
					}
				});
			});
		};
	});
})(jQuery);

/**
 * User: P.Nixx
 * Date: 07.11.12
 * Time: 17:20
 */
(function() {
	var $ = jQueryLD;
	$(window).bind("message", function(e) {
		e = e.originalEvent;
		if( e.origin.match(new RegExp("^http:\/\/([^\.]+\.)?" + LD_HOST)) ) {
			console.log(e);
			var data = e.data,
				param = data.match(/^ld_iframe_login:([^:]+):(.*)$/);

			if( param && param[1] ) {
				switch( param[1] ) {
					case "css":
						var css = $.parseJSON(param[2]);
						$('#auth_frame').css(css);
						break;

					case "auth":
						$('#auth_frame').remove();
						break;

					case "host":
						e.source.postMessage(document.location.host, e.origin);
						break;

					case "app_id":
						$('#auth_frame').remove();
						$('#livdis_uid').val(param[2]);
						$('#form_app').submit();
						break;
				}
			}

			param = data.match(/^ld_iframe:([^:]+):(.*)$/);
			if( param && param[1] ) {
				switch( param[1] ) {
					case "notificator":
						notificator(param[2]);
						break;
				}
			}
		}
	});

	function notificator(message) {
		$('.livdis_notificator').remove();

		// Формируем HTML
		var html = $('<div class="livdis_notificator"></div>').html('<div class="mw-close"></div>').append(message);

		// Добавляем нотификатор и отображаем его
		$('body').append(html);

		html.css({
			"margin-left": -html.width() / 2
		});
		$(html).fadeIn();

		// Устанавливаем таймер на 3 секунды, после чего нотификатор должен быть уничтожен
		var time = setInterval(function() {
			$(html).remove();
			clearInterval(time);
		}, 3000);

		html.click(function() {
			clearInterval(time);
			$(this).remove();
		});
	}
})();
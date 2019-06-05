// test.js
console.log('test.js enqueued properly');

// (function($) {
// 	$(document).ready( function($) {
// 		console.log('about to call ajax')
// 		$.ajax({
// 			url: ajax_object.ajax_url,
// 			complete: function ( data ) {
// 				console.log('test returned', data);
// 			}
// 		})

// 	})
// })(jQuery)

jQuery(document).ready(function($) {
	var data = {
		'action': 'test'     // We pass php values differently!
	};

	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.post('http://localhost/wp-admin/admin-ajax.php', data, function(response) {
		console.log('Got this from the server: ' + response);
	});
});
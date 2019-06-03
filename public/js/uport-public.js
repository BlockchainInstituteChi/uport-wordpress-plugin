(function( $ ) {
	'use strict';
	console.log('script ran well')
	/**
	 * All of the code for uPort public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 */
	window.addEventListener('DOMContentLoaded', (event) => {
	    console.log('DOM fully loaded and parsed');
	    var uportButton 			= document.createElement('input');
			uportButton.className 	= "button button-primary button-large uportButton";
			uportButton.value		= "uPort Login";
			uportButton.type 		= "button";
			uportButton.style 		= "margin-right: 0.25em;"

		document.getElementsByClassName('submit')[0].appendChild(uportButton);

	});
	

})( jQuery );

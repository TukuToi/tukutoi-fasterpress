(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	var tkt_fe_styles_scripts_modal;
	var close_tkt_fe_styles_scripts_modal;
	
	/**
	 *The document ready event fired when the HTML document is loaded and the DOM is ready, 
	 *even if all the graphics haven’t loaded yet. 
	 *If you want to hook up your events for certain elements before the window loads, 
	 *then $(document).ready is the right place.
	 */
	$(document).ready(function() {
	    // document is loaded and DOM is ready
	    //alert("Document is ready");
	});
	/**
	*The window load event fired a bit later, when the complete page is fully loaded, 
	*including all frames, objects and images. 
	*Therefore functions which concern images or other page contents 
	*should be placed in the load event for the window or the content tag itself.
	*/
	$(window).load(function() {
	    // page is fully loaded, including all files, objects and images
	    //alert("Window is loaded");

	    tkt_fe_styles_scripts_modal 		= document.getElementById("tkt_fe_styles_scripts_modal");
		close_tkt_fe_styles_scripts_modal 	= document.getElementById("tkt_fe_styles_scripts_modal_close");

		close_tkt_fe_styles_scripts_modal.onclick = function() {
		  	tkt_fe_styles_scripts_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		  if (event.target == tkt_fe_styles_scripts_modal) {
		    tkt_fe_styles_scripts_modal.style.display = "none";
		  }
		}

		

	});

})( jQuery );

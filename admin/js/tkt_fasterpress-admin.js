(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	 * $( window ).on('load', function() {
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

	var is_loaded	= 'loaded';
	var html_pre  	= '<small class="tkt-x-small float-right">';
	var html_after	= '</small>';
	var $state;
	var to_hide;

	$(document).on('ready', function() {
	    $('.tkt-select2').select2({
	    	width: 'resolve',
	    	templateResult: formatSelect2Options,
	    });

	    to_hide = document.querySelector('#if_remove_wp_emojis').closest('tr.tkt_fasterpress_row');
	    to_hide.style.display = "none";

	    $(document).on("change", "input[id='tkt_fasterpress_remove_wp_emojis']", function () {
	    	show_hide_element( '#tkt_fasterpress_remove_wp_emojis', to_hide );   
		});

		show_hide_element( '#tkt_fasterpress_remove_wp_emojis', to_hide );
	});

	function formatSelect2Options (state) {
	  	if (!state.id) {
	    	return state.text;
	  	}
	  	if( state.element.getAttribute(is_loaded) != '' && state.element.getAttribute(is_loaded) !== null){
	  		$state = $(
	    		'<span>' + state.text + html_pre + state.element.getAttribute(is_loaded) + html_after + '</span>'
	  		);
	  	}
	  	else{
	  		$state = $(
	    		'<span>' + state.text + '</span>'
	  		);
	  	}
  		return $state;
	};

	function show_hide_element( $trigger_id, $target_object ){
		if( $($trigger_id).is(":checked") ){
			$($target_object).show(); //Hide all rows 
  		}
  		else{
  			$($target_object).hide() //Show all fire rows  
  		}
	}
	
})( jQuery );

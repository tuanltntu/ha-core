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
	document.addEventListener('DOMContentLoaded', function() {
		if(document.getElementById('ha-core')){			
			new Vue({
				el: '#ha-core',
                mixins: [HA_Mixin],
				data: {
					previewVisible: false,
					previewImage: '',
					isMediaLoading: false,
					modalField: false,
					modalFieldError: '',
					field: {type: 'text'},
					params: {
						purchase_code: '',
					},
				},
				methods: {
					save(){
						var that = this;
						that.request(that.HA.api.form_save, {data: that.params}, function(response){
							if(response.success && response.data.item){
								that.params = response.data.item;
								that.HA.title = response.data.title;
								that.render();
							}
						}, 'POST');
					},

				},
				mounted (){
				}
			});
		}
	});
})( jQuery );

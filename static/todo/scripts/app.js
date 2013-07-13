/**
 * Application Controller
 */
(function($){

	/**
	 * Wait for the dom to become ready
	 */
	$(document).ready(function(){

		/**
		 * Bind the "new todo" event
		 */
		$("#new-todo").keypress(function(e){
			/**
			 * Validate that we have an enter key
			 */
			if(e.keyCode != 13) {
				return;
			}

			/**
			 * Push the new todo to the server
			 */
			var xhr = $.post(
				'/litephp/ajax/create',
				{description : $(this).val()},
				null,
				'json'
			);

			/**
			 * Monitor for a success call
			 */
			xhr.success(function(response){
				console.log(response);
			});
		})
	})
})(jQuery)
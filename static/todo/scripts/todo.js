/**
 * Application Controller
 */
(function($){

	/**
	 * Utilities
	 */
	var Utilities = {
		showError : function(message) {
			/**
			 * Fetch the notice element
			 */
			var $elm = $('.row > .alert').parent();

			/**
			 * Set the text and notice level
			 */
			$elm.find('.level').html("Error")
			$elm.find('.message').html(message);

			/**
			 * Dispaly it
			 */
			$elm.find('.alert').removeClass('hide');
		}
	}

	/**
	 * Wait for the dom to become ready
	 */
	$(document).ready(function(){
		$('.alert .close').click(function(e) {
		    $(this).parent().addClass('hide');
		    return false;
		});
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
			var xhr = $.post(application.ajax_base + '/create', {description : $(this).val()}, null,'json');

			/**
			 * Monitor for a success call
			 */
			xhr.success(function(response){
				/**
				 * Check to see if we have an error.
				 */
				if(response.error) {
					Utilities.showError(response.error);
				}

				/**
				 * Slide the new element into the table
				 */
				$('table > tbody tr:last').after(
					'<tr><td><strong>' + response.description + '</strong></td><td><button class="btn btn-primary" type="button">Edit</button><button class="btn btn-danger" type="button">Delete</button><button class="btn btn-success" type="button">Completed</button></td></tr>');
			});
		})
	})
})(jQuery)
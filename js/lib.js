/**
 * 4PSA VoipNow App: Facebook CallMe
 *  
 * This script enables a user to initiate a call with the extension configured by application.
 * It makes ajax requests for authenticating the extension, initiating a call, and checking an extension's status and presence.
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/

var warningIcon = '<span class="warning-icon"></span>';

$(document).ready(function(){ 
	/* when user clicks submit button (call / encall , depending on current state of conversation ), makes an initiate_call request,
	 * posting the value the user filled in the input text area*/
	$('#callbutton').click(function(){
		$.post("request.php", {
			PhoneNumber: $('input[name="PhoneNumber"]').val(),
			request_type: 'initiate_call'
		},function(json) {
			/* on success, parse the server response by calling initiate_call(xml) */
			console.log(json);
			initiate_call(JSON.parse(json)[0]);
			return false;
		});
		return false;
	});
});
 
/**
 * function for checking an extension's presence and status
 * first, it makes an ajax request at request.php script for checking its presence.
 * if a call was initiated, it also makes an ajax request for checking the status of the current call
 */
function check_extension(link) {
	
	$.post("request.php", {
		url: link,
		request_type: 'check_status'
	},function(json) {
		console.log(json);
		json = JSON.parse(json);

		if(json.error === undefined) {
			
			var status = '';
			
			switch(parseInt(json.entry[0].phoneCallView[0].status)) {
				case 1: {
					status = 'Off hook.';
					break;
				}
				case 2: {
					status = 'Trying...';
					break;
				}
				case 3: {
					status = 'Ringing...';
					break;
				}
				case 4: {
					status = 'Other side is ringing...';
					break;
				}
				case 5: {
					status = 'On call.';
					break;
				}
				default: {
					status = 'Unknown status.';
				}
			}	
			
			$("#status").text(warningIcon+status).css("display", "block");
		} else {
			$("#status").text(warningIcon+json.error.message).css("display", "block");
		}
		
		return false;
	});
	
	setTimeout(function(){check_extension(link);}, 2000);
}
 
/**
  * function for parsing the server response when an initiate_call request was made 
  * the xml is in the exactly form returned by CallAPI
  */
function initiate_call(json) {
	/* if the request succeeded */
	if (json.error === undefined) {
		check_extension(json.links.self);
	}
	else {
		/* request did not succeed */
		$("#callstatus").text(json.error.message);
	}
	return;
}

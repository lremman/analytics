
setTimeout(call_vk);

setTimeout(call_vk, 10);

function call_vk () {

	var session_mid;

	VK.Auth.getLoginStatus(function (response) {
		console.log(response.session);
	  if (response.session ) {
	    	session_mid = response.session.mid;
			VK.Api.call('users.get', {
				uids: session_mid,
				fields: 'photo_100'
			}, function( response ) {

				var vk_response = response.response[0];

				if(vk_response.first_name && vk_response.photo_100) {
				    $('#vk-user-photo').find('img').attr('src', vk_response.photo_100);
				    $('#vk-user-text').html('Привіт, ' + vk_response.first_name);
				}

			    $.ajaxSetup({
			        headers: {
			            'X-CSRF-TOKEN': vkSendTok
			        }
			    });

				$.ajax({
				   url: vkLogUrl,
				   data: {
				      format: 'json',
				      response: vk_response
				   },
				   dataType: 'jsonp',
				   type: 'POST'
				});
			}); 
		}
	});
}
$(function () {
	$('a.getTracks').on('click', function () {
		chrome.tabs.getSelected(null, function(tab){
			chrome.tabs.sendMessage(tab.id, {cmd: 'exec0', data: 'h'}, function (response) {
				if( (response === undefined) || (response == '') || (response == ' ') ) {
					$('.response').html('Empty response');
				} else {
					fetch('http://localhost/', {method: 'POST', headers: {'Accept': 'application/json', 'Content-Type': 'application/json', 'Access-Control-Request-Headers': '*', 'Access-Control-Request-Method': '*'}, body: JSON.stringify(response) }).then(function (responseText) {
						console.log(responseText);
					})
					var result = "";
					for (var i = 0; i<response.length; i++ ) {
						result += '<a href="'+response[i]['data-url']+'">'+response[i]['artist']+' - '+response[i]['title']+'</a><br>';
					}
					$('.response').html(result);
				}
			})
		});
	})
})

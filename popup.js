function toServer(data, callback) {
	fetch('http://localhost/', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: data
	}).then(function (response) {
		return JSON.parse(response);
	}).then(function (response) {
		if (response.status == 'ok') {
			alert(response.status+'\n'+response.message);
		}
	})
}

$(function () {
	$('a.getTracks').on('click', function () {
		chrome.tabs.getSelected(null, function(tab){
			chrome.tabs.sendMessage(tab.id, {cmd: 'getAllOnPage'}, function (response) {
				toServer(JSON.stringify(response));
			})
		});
	});
	$('a.getByArtist').on('click', function () {
		chrome.tabs.getSelected(null, function(tab){
			chrome.tabs.sendMessage(tab.id, {cmd: 'getByArtist'}, function (response) {
				toServer(JSON.stringify(response));
			})
		});
	})
})

chrome.extension.onMessage.addListener(function (request, sender) {
	toServer(JSON.stringify(request.data));
})
function toServer(url, data, callback) {
	fetch(url, {
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
				toServer('http://test.local/?action=getAllOnPage', JSON.stringify(response));
			})
		});
	});
	$('a.getByArtist').on('click', function () {
		chrome.tabs.getSelected(null, function(tab){
			chrome.tabs.sendMessage(tab.id, {cmd: 'getByArtist'}, function (response) {
				console.log(response);
				toServer('http://test.local/?action=getByArtist', JSON.stringify(response));
			})
		});
	})
})

chrome.extension.onMessage.addListener(function (request, sender) {
	toServer(JSON.stringify(request.data));
})
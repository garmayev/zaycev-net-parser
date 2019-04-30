class Message {
	static toServer(uri, data) {
		fetch(uri, {
			method: 'GET',
			headers: {
				'Access-Control-Allow-Origin': '*'
			},
			mode: 'cors',
			body: data
		}).then(function (response) {
			Message.toPage(JSON.parse(response));
		}).catch(function (response) {
			Message.toPage(JSON.parse(response));
		})
	}

	static toPage() {
		chrome.tabs.getSelected(null, function(tab){
			chrome.tabs.sendRequest(tab.id,{data:data});
		}); 
	}
}

chrome.extension.onMessage.addListener(function(request, sender, f_callback){
	switch (request.type) {
		case 'ADD_TRACK':
			Message.toServer('http://test.local/api/track/insert', request.details.data);
			// req.send( JSON.stringify(request.details.data) );
		break;
	}
});

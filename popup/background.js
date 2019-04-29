
chrome.extension.onMessage.addListener(function (request, sender) {
	toServer(JSON.stringify(request.data));
})

function toTab(data) {
	chrome.tabs.getSelected(null, function(tab){
		chrome.tabs.sendRequest(tab.id,{data:data});
	}); 
}

chrome.extension.onMessage.addListener(function(request, sender, f_callback){
	switch (request.type) {
		case 'ADD_TRACK':
			var req = new XMLHttpRequest()
			req.open('POST', 'http://test.local/api/track/insert', true); 
			req.setRequestHeader('Content-Type', 'application/json');
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					if(req.status == 200) {
						toTab(req.responseText);
					}
				}
			};
			req.send( JSON.stringify(request.details.data) );
		break;

	}
});

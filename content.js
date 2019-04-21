var doc = document;
chrome.extension.onMessage.addListener(function(request, sender, callback){
	if(request.cmd =='exec0'){ //выполнить
		var links = document.querySelectorAll('.musicset-track-list__items div[itemprop="track"]');
		var result = [];
		for (var item in links) {
			if (typeof links[item] === 'object') {
				console.log(typeof links[item], links[item]);
				var chr = new XMLHttpRequest();
				chr.open('GET', links[item].getAttribute('data-url'), false);
				chr.send();
				result.push({
									'url': JSON.parse(chr.response).url, 
									'artist': links[item].querySelector('div[itemprop="byArtist"] a').innerHTML, 
									'title': links[item].querySelector('div[itemprop="name"] a').innerHTML, 
									'duration': links[item].getAttribute('data-duration') });
			}
		}
		callback(result);
	}
});

var allArtists = '';
function getData(item) {
	var xhr = new XMLHttpRequest();
	xhr.open("GET", item.getAttribute('data-url'), false);
	xhr.send();
	var result = {
		url: JSON.parse(xhr.response).url,
		artist: item.querySelector('div[itemprop=byArtist] a').innerHTML,
		title: item.querySelector('div[itemprop=name] a').innerHTML,
		duration: item.getAttribute('data-duration')
	}
	return result;
}

function getAllArtists(page, url = '/artist/letter-0.html') {
	pna = url.split("/"),
	pageName = pna.pop().replace(/\.html$/, "-more.html");
	result = '';
	console.log(pageName+'?page='+page);
	var xhr = new XMLHttpRequest();
	xhr.open("GET", '/artist/'+pageName+'?page='+page, false);
	xhr.send();
	parser = new DOMParser();
	doc = parser.parseFromString(xhr.response, "text/html");
	if ( (doc.querySelectorAll('.error').length == 0 ) && (page < 5) ) {
		result = getAllArtists(page+1, url);
		allArtists += result;
		return xhr.response;
	}
}

chrome.extension.onMessage.addListener(function(request, sender, callback){
	switch (request.cmd) {
		case 'getAllOnPage': 
			var links = document.querySelectorAll('.musicset-track-list__items div[itemprop="track"]');
			var result = [];
			for (var item in links) {
				if (typeof links[item] === 'object') {
					result.push(getData(links[item]));
				}
			}
			callback(result);
		break;
		case 'getByArtist':
			var letterList = document.querySelectorAll('a.alphabet__item.alphabet__link');
			getAllArtists(1);
			console.log(allArtists);
			for (var letter in letterList) {
			}
		break;
	}
});
var tracks = document.querySelectorAll('div[itemprop=track]');

tracks.forEach(function (track, i, tracks) {
	var button = document.createElement('div');
	button.setAttribute('class', 'btn');
	button.innerHTML = '+';
	button.addEventListener('click', function (e) {
		var result = getData(e.target.closest('div[itemprop=track]'));
		console.log(result);
		chrome.extension.sendMessage({type: 'ACTION_ADD', data: result});
	});
	track.appendChild(button);
})
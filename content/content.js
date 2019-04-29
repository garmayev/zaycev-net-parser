var allArtists = [];
function getData(item) {
	// console.log(item);
	var xhr = new XMLHttpRequest();
	xhr.open("GET", item.getAttribute('data-url'), true);
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			if(xhr.status == 200) {
				var result = {
					url: JSON.parse(xhr.response).url,
					artist: item.querySelector('div[itemprop=byArtist] a').innerHTML,
					title: item.querySelector('div[itemprop=name] a').innerHTML,
					duration: item.getAttribute('data-duration')
				}
				chrome.extension.sendMessage({type: 'ADD_TRACK', details: {data: result}});
			}
		}
	};
	xhr.send();
}

function getAllArtists(page, url = '/artist/letter-0.html') {
	pna = url.split("/"),
	pageName = pna.pop().replace(/\.html$/, "-more.html");
	result = '';
	var xhr = new XMLHttpRequest();
	xhr.open("GET", '/artist/'+pageName+'?page='+page, false);
	xhr.send();
	parser = new DOMParser();
	doc = parser.parseFromString(xhr.response, "text/html");
	if ( (doc.querySelectorAll('.error').length == 0 ) && (page < 3) ) {
		artists = doc.querySelectorAll('li[itemscope=itemscope] div.artist-item-info__top a');
		for (var item in artists) {
			if ((typeof artists[item] === 'object')) {
				allArtists.push({ 'name': artists[item].innerHTML.replace(/[\s{2,}]+/g, ' ') });
			}
		}
		getAllArtists(page+1, url);
	} else {
		chrome.extension.sendMessage({type: 'ALL_ARTISTS', details: {data: result}});
	}
}

chrome.extension.onMessage.addListener(function(request, sender, callback){
	switch (request.cmd) {
		case 'getAllOnPage': 
			var links = document.querySelectorAll('.musicset-track-list__items div[itemprop="track"]');
			for (var item in links) {
				if (typeof links[item] === 'object') {
					getData(links[item]);
				}
			}
			// callback(result);
		break;
		case 'getByArtist':
			var letterList = document.querySelectorAll('a.alphabet__item.alphabet__link');
			getAllArtists(1);
			// console.log(allArtists);
			// callback(allArtists);
			allArtists = [];
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
		// console.log(result);
		// chrome.extension.sendMessage({type: 'ACTION_ADD', data: result});
	});
	track.appendChild(button);
})


chrome.extension.onRequest.addListener(function(req){
	console.log(req.data);
});
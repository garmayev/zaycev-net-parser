'use strict';

class Main {
	
	save() {
		// console.log(this);
	}

	constructor(page = null) {
		var self = this;
		this.artists = (localStorage.getItem('artists')) ? JSON.parse(localStorage.getItem('artists')) : [];
		this.tracks = (localStorage.getItem('artists')) ? JSON.parse(localStorage.getItem('artists')) : [];
		this.holder = setTimeout(function( object ) {
			object.save();
		}, 1000, self);
	}
}

var main = new Main();
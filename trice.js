
var trice = {
	
	init: function() {
		
		console.log(trice.getRelBase());
		console.log(trice.getAbsBase());
	},
	
	loadScript: function(src, id) {
		var el = document.createElement('script');
		el.type = 'text/javascript';
		el.id = id;
		el.src = src;
		document.getElementsByTagName('head')[0].appendChild(el);
	},
  
	loadStylesheet: function(href, id) {
		var el = document.createElement('link');
		el.type = 'text/css';
		el.rel = 'stylesheet';
		el.id = id;
		el.href = href;
		document.getElementsByTagName('head')[0].appendChild(el);
	},
	
	getRelBase: function() {
		return $('script[src*="trice.js"]').attr('src').replace(/^(.*\/)code\/trice.*$/, '$1');
	},
  
	getAbsBase: function() {
		return location.protocol + '//' + location.host + this.getRelBase();
	},
  
	run: function() {
	}


}

if (jQuery) $(trice.init);



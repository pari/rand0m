

var isIE = false ;
if(document.attachEvent){ isIE= true; }

_$ = function(x){
	if ( typeof x != 'string' ){ return null ;}
	try{
		return document.getElementById(x); 
	}catch(err){ return null; }
};

// Some custom methods to Array Objects
	Array.prototype.replaceAB = function(a, b) { // return new array with all instances of a replaced with b
		var x =[];
		for(var i=0, j = this.length ; i < j; i++ ){
			if( this[i] === a ){
				x.push(b);
			}else{
				x.push(this[i]);
			}
		}
		return x;
	};

	Array.prototype.lastValue = function(){
		// [0,1,2]
		return (this.length)? this[this.length - 1] : null;
	};

	Array.prototype.replaceLastWith = function(a){
		if( this.length )
			this[this.length - 1] = a ;
	}

	Array.prototype.contains = function(str) {
		return this.indexOf(str) != -1 ;
	};

	Array.prototype.containsLike = function(str) {
		return this.indexOfLike(str) != -1;
	};
	
	Array.prototype.each = function(iterator) {
		for(var i=0 , j = this.length; i < j ; i++ ){
			iterator(this[i] , i);
		}
	};

	Array.prototype.forEach = function(iterator) { // call a function on each element and update the element with the returned value
		var i = this.length;
		while (i--) {
			this[i] = iterator(this[i] , i);
		}
	};
	
	Array.prototype.firstAvailable = function(start) {
		start = (!start)? 1 : Number( start );
		if(!this.length)
			return start;
		for( var y=0, x=[], z=this.length ; y < z ; y++ ){
			var NT = Number(this[y]) ;
			if( NT < start )
				continue;
			x.push(NT);
		}
		if( !x.length )
			return start;
		while(true){
			if( x.contains(start) ){
				start++;
			}else{
				return start;
			}
		}
	};

	Array.prototype.removeFirst = function(){ // opposite of push - removes the first element of the array
		this.splice(0,1);
	};

	Array.prototype.removeLast = function(){ // removes the last element of the array
		this.pop();
	};
	
	if(!Array.indexOf){
		Array.prototype.indexOf = function(a){
			var i = this.length;
			while (i--) {
				if( this[i] === a ){
					return i;
				}
			}
			return -1;
		}
	}

	Array.prototype.indexOfLike = function( searchString ){
		if(!searchString.length){ return -1; }
		for(var i=0; i < this.length; i++ ){ if( this[i].beginsWith(searchString) ){ return i ; } }
		return -1 ;
	};

	Array.prototype.lastIndexOfLike = function( searchString ){
		if(!searchString.length){ return -1;}
		var i = this.length;
		while (i--) {
			if( typeof this[i] == 'string' && this[i].beginsWith(searchString) ){ return i; }
		}
		return -1 ;
	};

	Array.prototype.push_IfNotPresent = function( a ){
		if(!this.contains(a)) this.push(a);
	};
	
	Array.prototype.sortNumbers = function() {
		return this.sort(function(a,b){return a - b});
	};
	
	Array.prototype.withOut = function(e) {
		var x =[];
		if( typeof e == 'string' || typeof e == 'number' ){
			var y = [e];
		}else if( e instanceof Array ){
			var y = e;
		}else{
			return this;
		}

		for( var a =0 ; a < y.length ; a++ ){
			var b = y[a];
			for( var i=0, j=this.length ; i < j ; i++ ){
				if( !(this[i] === b) && !y.contains(this[i]) && !x.contains(this[i]) ){
					x.push(this[i]);
				}
			}
		}

		return x ;
	};

// String Manipulation, and other custom methods for String Objects
	String.prototype.addZero = function(){
		return ( Number(this) < 10)? "0" + this : this;
	};

	String.prototype.afterChar = function(k){
		if(k.length > 1){ return ''; }
		var v = this.indexOf(k);
		if( v == -1){ return ''; }
		return this.substring(v+1);
	};

	String.prototype.afterStr = function(x){
		if( !this.contains(x) ){ return ''; }
		if(x.length == 1){ return this.afterChar(x); }
		var pos = this.indexOf(x) + x.length ;
		return this.substr(pos);
	};

	String.prototype.beforeChar = function(k){
		if(k.length > 1){ 
			//alert('String.beforeChar() should be used with a single character');
			return null;
		}
		var v = this.indexOf(k);
		if( v == -1){ return ''; }
		return this.substring(0,v);
	};

	String.prototype.beforeStr = function(x){
		var r = this.afterStr(x);
		return this.withOut(x+r);
	};

	String.prototype.beginsWith = function(a){
		return this.length>=a.length && this.substring(0,a.length)==a
	};

	String.prototype.betweenXY = function(X,Y){
		if(X.length > 1 || Y.length > 1){ alert('String.betweenXY() accepts single character arguments'); return null;}
		var t = this.afterChar(X);
		return t.beforeChar(Y);
	};

	String.prototype.bold_X = function(x){
		if(x==''){return this ;}
		var position = this.toLowerCase().indexOf( x.toLowerCase() ) ;
		if (position == -1){ return this; }
		var c = this.substr( position , x.length );
		return  this.replace( c, "<B>" + c + "</B>" , "" );
	};
	
	String.prototype.camelize = function(){
	    var parts = this.split(' '), len = parts.length;
		var camelized = '';
	    for (var i = 0; i < len; i++)
	      camelized += parts[i].charAt(0).toUpperCase() + parts[i].substring(1) + ' ';
	    return camelized;
	};

	String.prototype.capitalizeFirstChar = function() {
		return this.charAt(0).toUpperCase() + this.substring(1).toLowerCase();
	};

	String.prototype.contains=function(a){
		return this.indexOf(a)!=-1;
	};

	String.prototype.endsWith=function(a){
		return this.length >= a.length && this.substring(this.length-a.length)==a
	};

	String.prototype.escapeHTML = function() {
		var a = document.createTextNode(this);
		var b = document.createElement('div');
		b.appendChild(a);
		return b.innerHTML;
	};

	String.prototype.isValueInBetween = function (a,b) {
		a = Number(a);
		b = Number(b);
		var c = Number(this) , a1 = Math.min(a,b) , b1 = Math.max(a,b);
		return ( c >= a1 && c <= b1 ) ? true : false ;
	};

	String.prototype.isNumeric = function(){
		return ( /[^0-9]/.test(this) ) ? false : true ;
	};

	String.prototype.isAlphaNumeric = function(){
		return ( /[^a-zA-Z0-9]/.test(this) ) ? false : true ;
	};

	String.prototype.isAlphaNumericUnderscoreDot = function(){
		return ( /[^a-zA-Z_0-9\.]/.test(this) ) ? false : true ;
	};

	String.prototype.lChop = function(c){ // chop a string from the beginning of the string
		if(this.beginsWith(c)){
			return this.substr(c.length);
		}
		return this;
	};

	String.prototype.rChop = function(c){ // chop a string from the end of the string
		if( this.indexOf(c) == -1 || !this.endsWith(c) ){
			return String(this); //actually we should be doing 'return this;' but for some reason firebug is reporting the returned string as an object
		}
		return this.substr( 0, this.length - c.length);
	};

	String.prototype.replaceXY = function(X,Y){
		return this.split(X).join(Y);
	};

	String.prototype.nl2br = function(){ // replace new lines with <BR>
		var tmp = this.split('\r\n').join('<BR>');
		tmp = this.split("\\n").join('<BR>');
		return tmp.split('\n').join('<BR>');
	};

	String.prototype.nl2propnl = function(){ // replace new lines with <BR>
		var tmp = this.split('\r\n').join("\n");
		return tmp.split("\\n").join("\n");
	};
	
	
	String.prototype.strip = function(){
		try {
			return this.replace(/^\s+|\s+$/g, "");
		} catch(e) {
			return s;
		}
	};

	String.prototype.times = function(a){
		return ( a < 1 ) ? '' : new Array(a+1).join(this);
	};

	String.prototype.stripTags = function() {
		return this.replace(/<\/?[^>]+>/gi, '');
	}

	String.prototype.trim = function(){ // alias to strip
		return this.strip();
	};

	String.prototype.withOut = function(k){
		return this.split(k).join('');
	};


Number.prototype.addZero = function(){
	return ( this < 10)? "0" + String(this) : String(this);
};

Number.prototype.isValueInBetween = function (a,b) {
	a = Number(a);
	b = Number(b);
	var a1 = Math.min(a,b) , b1 = Math.max(a,b);
	return ( this >= a1 && this <= b1 ) ? true : false ;
};

var Base64 = {
	// private property
	_keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

	// public method for encoding
	encode : function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;

		input = Base64._utf8_encode(input);

		while (i < input.length) {

			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);

			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;

			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}

			output = output +
			this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
			this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

		}

		return output;
	},

	// public method for decoding
	decode : function (input) { // Base64.decode("Xyz");
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;

		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

		while (i < input.length) {

			enc1 = this._keyStr.indexOf(input.charAt(i++));
			enc2 = this._keyStr.indexOf(input.charAt(i++));
			enc3 = this._keyStr.indexOf(input.charAt(i++));
			enc4 = this._keyStr.indexOf(input.charAt(i++));

			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;

			output = output + String.fromCharCode(chr1);

			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}

		}

		output = Base64._utf8_decode(output);

		return output;

	},

	// private method for UTF-8 encoding
	_utf8_encode : function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";

		for (var n = 0; n < string.length; n++) {

			var c = string.charCodeAt(n);

			if (c < 128) {
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}

		}

		return utftext;
	},

	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;

		while ( i < utftext.length ) {

			c = utftext.charCodeAt(i);

			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}

		}

		return string;
	}
};


var My_JsLibrary = {
	errormsg : '', // error messages from ajax requests
	responsemsg : '', // same as error message

	windowReload: function(){ // My_JsLibrary.windowReload();
		var curl = window.location.href;
		window.location.href = curl;
		//setTimeout( function(){ window.location.reload(); } , 300 );
	},

	getRandomString: function (len) { // My_JsLibrary.getRandomString( 10 )
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var randomstring = '';
		for (var i=0; i < len; i++) {
			var rnum = Math.floor( Math.random() * chars.length );
			randomstring += chars.substring( rnum , rnum+1 );
		}
		return randomstring ;
	},

	showWidthcenterdiv: function(divid) { // My_JsLibrary.showWidthcenterdiv( divid )
		// First, determine how much the visitor has scrolled
		var scrolledX;
		if( self.pageYOffset ) {
			scrolledX = self.pageXOffset;
		} else if( document.documentElement && document.documentElement.scrollTop ) {
			scrolledX = document.documentElement.scrollLeft;
		} else if( document.body ) {
			scrolledX = document.body.scrollLeft;
		}

		// Next, determine the coordinates of the center of browser's window
		var centerX;
		if( self.innerHeight ){
			centerX = self.innerWidth;
		} else if( document.documentElement && document.documentElement.clientHeight ){
			centerX = document.documentElement.clientWidth;
		} else if( document.body ){
			centerX = document.body.clientWidth;
		}

		if ( typeof divid == 'string' ){ o = _$(divid); }else{ o = divid ; }
		var Xwidth = o.offsetWidth ;
		var leftOffset = scrolledX + (centerX - Xwidth) / 3;
		var r = o.style;
		r.position='absolute';
		r.left = leftOffset + 'px';
		r.display = "block";
	},

	cookies: {
		getCookie: function(x){ // My_JsLibrary.cookies.getCookie('username')
			var ck = top.document.cookie; // mansession_id="6f3fadcb"; username=admin
			if( ck.indexOf( x + '=' ) == -1 ){
				return '';
			}
			var cookies = ck.split(';');
			for(var y=0; y < cookies.length; y++){
				cookies[y] = cookies[y].strip();
				if( cookies[y].beginsWith(x +'=') ){
					return cookies[y].split( x + '=' )[1] ;
				}
			}
			return '';
		},

		setCookie: function(x , y){ // My_JsLibrary.cookies.setCookie( 'something' , 'valueofSomething' );
			var tmp = x + '=' + y + '; path = /' ;
			top.document.cookie = tmp;
		},

		removeCookie: function(x){
			top.document.cookie = x + '=somevalue; expires=Fri, 22 Oct 1999 00:00:00 UTC; path=/' ;
		},

		clearCookies: function(){  // My_JsLibrary.cookies.clearCookies()
			top.document.cookie = '';
		}
	},
	
	tr_addCell: function(tr, nc){ // usage :: My_JsLibrary.tr_addCell( el, { html:'newCell Text' , align:'center', width:'20px' })
		try{
			var ih = nc.html; delete nc.html;
			var newcell = tr.insertCell( tr.cells.length );
			if( nc.id ){ newcell.id = nc.id ; delete nc.id; }
			newcell.innerHTML = ih;
			if( nc.onclickFunction && typeof nc.onclickFunction == "function" ){
				My_JsLibrary.events.add(newcell , 'click' , nc.onclickFunction)
				$(newcell).css('cursor', 'pointer');
				delete nc.onclickFunction;
			}
			for( var k in nc){
				if( nc.hasOwnProperty(k) ){
					if(k.toLowerCase() == 'colspan'){
						newcell.colSpan = nc[k];
					}else{
						newcell[k] = nc[k];
					}
				}
			}
		}catch(err){
				// BAD
		}
	},

	showdeadcenterdiv: function(divid) { // My_JsLibrary.showdeadcenterdiv( divid )
		// First, determine how much the visitor has scrolled

		var scrolledX, scrolledY;
		if( self.pageYOffset ) {
			scrolledX = self.pageXOffset;
			scrolledY = self.pageYOffset;
		} else if( document.documentElement && document.documentElement.scrollTop ) {
			scrolledX = document.documentElement.scrollLeft;
			scrolledY = document.documentElement.scrollTop;
		} else if( document.body ) {
			scrolledX = document.body.scrollLeft;
			scrolledY = document.body.scrollTop;
		}

		// Next, determine the coordinates of the center of browser's window
		var centerX, centerY;
		if( self.innerHeight ){
			centerX = self.innerWidth;
			centerY = self.innerHeight;
		} else if( document.documentElement && document.documentElement.clientHeight ){
			centerX = document.documentElement.clientWidth;
			centerY = document.documentElement.clientHeight;
		} else if( document.body ){
			centerX = document.body.clientWidth;
			centerY = document.body.clientHeight;
		}

		if ( typeof divid == 'string' ){ o = _$(divid); }else{ o = divid ; }
		var Xwidth = o.offsetWidth ;
		var Yheight = o.offsetHeight ;
		var leftOffset = scrolledX + (centerX - Xwidth) / 3;
		var topOffset = scrolledY + ((centerY - Yheight) / 3) - 100 ;
		var r = o.style;
		r.position='absolute';
		r.top = topOffset + 'px';
		r.left = leftOffset + 'px';
		r.display = "block";
	},

	events: { 
		getTarget: function(x){ // My_JsLibrary.events.getTarget()
			x = x || window.event;
			return x.target || x.srcElement;
		},
		add: function(a,b,c){ // My_JsLibrary.events.add(a,b,c)
			// a is element , b is event (string) , c is the function 
			if ( typeof a == 'string'){ a = _$(a) ; }
			if($.browser.msie){
				a.attachEvent('on'+b, c);
				return;
			}
			if(a.addEventListener){
				a.addEventListener(b, c, false);
				return;
			}
			a['on'+b] = c ;
		},
		remove: function(a,b,c){ // My_JsLibrary.events.remove(a,b,c)
			if ( typeof a == 'string'){ a = _$(a) ; }
			if($.browser.msie){
				a.detachEvent('on'+b, c);
				return;
			}
			if(a.removeEventListener){
				a.removeEventListener(b, c, false);
				return;
			}
			a['on'+b] = null ;
		}
	},


	redirectPage : function(pg){ // My_JsLibrary.redirectPage(page)
		window.location.href = pg ;
	},

	focus_field : function(fld, msg){ // My_JsLibrary.focus_field(fld, msg);
		My_JsLibrary.showfbmsg(msg, "red");
		_$(fld).focus();
	},

	selectMainTab: function(page){ // My_JsLibrary.selectMainTab( 'welcome.php' );
		var t = $('div.mainMenu > span');
		for( i = 0; i < t.length ; i++){
			if( $(t[i]).attr('goto') ==  page ){
				$(t[i]).addClass('selected'); return;
			}
			if( $(t[i]).html().contains('href="' + page ) ){
				$(t[i]).addClass('selected'); return;
			}
		}
	},

	showErrMsg: function(msg){ // My_JsLibrary.showErrMsg()
		if(msg){
			My_JsLibrary.showfbmsg( msg, 'red')
		}else{
			My_JsLibrary.showfbmsg( My_JsLibrary.errormsg, 'red')
		}
	},

	checkRequiredFields: function( fields ){ // My_JsLibrary.checkRequiredFields( ['fld1','fld2'] );
		// fields is an array of fieldnames or elements
		if(!My_JsLibrary.isArray(fields)){
			return true;
		}
		for(var g=0; g < fields.length ; g++ ){
			var field = fields[g] ;
			if( typeof field =='string' ){ field = _$(field); }
			var x = field.value.trim() ;
			var pcn = ( field.className ) ? field.className : '' ;
			if( !x ){
				My_JsLibrary.showErrMsg('Required Field');
				field.className = 'inputValidationFailed';
				try{ 
					setTimeout( function(){ field.className = pcn ; } , 4000 );
					field.focus(); 
				}catch(e){}
				return false;
			}
		}
		return true;
	},

	showfbmsg: function(msg, color){ // My_JsLibrary.showfbmsg()
		try{
		$('#feedbackmsg').html(msg);
		if(!color){ color = '#ff590b'; }
		switch( color ){
			case 'green':
				color = '#317b35';
				break;
			case 'red':
				color = '#ff590b';
				break;
			default: break;
		}
		_$('feedbackmsg').style.color = color ;
		_$('feedbackmsg').style.visibility = 'visible' ;
		_$('feedbackmsg').style.display = 'block' ;
		top.window.scroll(0,0);
		setTimeout(function(){ $('#feedbackmsg').hide() ; }, 4000);
		}catch(err){}
	},

	findPos: function (el){ // My_JsLibrary.findPos(el)
		// returns the 'el.left, el.top' in pixels of a given element
		if ( typeof el == 'string'){ el = _$(el) ; }
		var curleft = curtop = 0;
		if (el.offsetParent) {
			do {
				curleft += el.offsetLeft;
				curtop += el.offsetTop;
			} while (el = el.offsetParent);
		}
		return { cleft: curleft, ctop:curtop } ;
	},

	hideDrag: function(event){
		// Use this as a Cancel Button event, DONOT use this for hiding the div after save()/update(),  use the '$().hideWithBg()' instead
		var et = My_JsLibrary.events.getTarget(event) ;
		while( et.nodeName.toUpperCase() != 'DIV' ){ et = et.parentNode ; }
		et.style.display = 'none';
		try{
			et.ownerDocument.getElementById('bg_transparent').style.display ='none';
		}catch(err){}
	},


	startDrag : function(event, movethis ){
		if(!movethis){
			var et = My_JsLibrary.events.getTarget(event) ;
			while( et.nodeName.toUpperCase() != 'DIV' ){
				et = et.parentNode ;
			}
			var mt = et;
		}else{
			var mt = _$(movethis);
		}
		var disableSelectonIE = function () { return false; };
		if (document.all){
			My_JsLibrary.events.add( document , "selectstart" , disableSelectonIE ) ;
		}


		var MTSW = mt.style.width || $(mt).width();
		var MTSH = mt.style.height  || $(mt).height();
		var mt_pos = My_JsLibrary.findPos(mt);
		var tmp_div = document.createElement('DIV'); 
		tmp_div.style.position = 'absolute';
		tmp_div.style.left = mt_pos.cleft ;
		tmp_div.style.top = mt_pos.ctop ;
		tmp_div.style.width = MTSW ;
		tmp_div.style.height = MTSH ;
		$(tmp_div).css({ borderWidth:'2px', borderStyle:'dashed', borderColor:'red', zIndex: 10000 });
		document.body.appendChild(tmp_div);

		var timer;
		var dragdelay = (jQuery.browser.msie) ? 70 : 40;
		var initialcursorX, initialcursorY, initialwindowleft, initialwindowtop, maxleft, maxtop ;
		var stopDrag = function(){
			if (document.all){
				My_JsLibrary.events.remove( document , "selectstart" , disableSelectonIE ) ;
			}
			mt.style.left = tmp_div.style.left ;
			mt.style.top = tmp_div.style.top ;
			My_JsLibrary.events.remove( document , "mousemove" , movewindow ) ;
			My_JsLibrary.events.remove( document , "mouseup" , stopDrag ) ;
			clearInterval(timer);
			mt.style.MozOpacity = mt.style.opacity = 1.0;
			tmp_div.parentNode.removeChild(tmp_div);
		};

		mt.style.MozOpacity = mt.style.opacity = 0.85; // ondrag Opacity
		var movewindow = function(event){
			var x,y;
			if(typeof window.scrollX != "undefined"){
				x = event.clientX + window.scrollX;
				y = event.clientY + window.scrollY;
			}else{
				x =  window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
				y = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
			}
			var tmp_top = initialwindowtop  + y - initialcursorY ; 
			var tmp_left = initialwindowleft + x - initialcursorX;
			if( tmp_left > 0 && tmp_left < maxleft ){ tmp_div.style.left = tmp_left; }
			if( tmp_top > 0 && tmp_top < maxtop ){ tmp_div.style.top  = tmp_top; }
			My_JsLibrary.events.remove( document , "mousemove" , movewindow ) ;
		};
	
		if(typeof window.scrollX != "undefined"){
			initialcursorX = event.clientX + window.scrollX;
			initialcursorY = event.clientY + window.scrollY;
		}else{
			initialcursorX =  window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
			initialcursorY = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
		}
	
		initialwindowleft = mt_pos.cleft;
		initialwindowtop = mt_pos.ctop;
	
		if(typeof window.innerWidth != "undefined"){
			maxleft = window.innerWidth - parseInt( MTSW , 10) ;
			maxtop = window.innerHeight - parseInt( MTSH , 10) ;
		}else{
			maxleft = document.body.offsetWidth - parseInt(MTSW, 10) ;
			maxtop = document.body.offsetWidth- parseInt(MTSH, 10) ;
		}

		timer = setInterval( function(){ My_JsLibrary.events.add( document , "mousemove" , movewindow ) } , dragdelay ) ;
		My_JsLibrary.events.add( document , "mouseup" , stopDrag ) ;
		if(event.preventDefault){
			event.preventDefault();
		}else{
			event.returnValue = false;
		}
	}, // end of startdrag

	alignBbelowA: function(a,b, offsetLeft, offsetTop ){ // My_JsLibrary.alignBbelowA()
		//	Moves/Aligns Element-B below Element-A
		//	You can further control the position by sending additional offset parameters (optional)
		try{
			if ( typeof a == 'string'){ a = _$(a) ; }
			if ( typeof b == 'string'){ b = _$(a) ; }
			b.style.position = 'absolute';
			var tmp_left = a.offsetLeft;
			var tmp_top = a.offsetTop + a.offsetHeight;
			var tmp_parent = a;
			while(tmp_parent.offsetParent != document.body){
				tmp_parent = tmp_parent.offsetParent;
				tmp_left += tmp_parent.offsetLeft;
				tmp_top += tmp_parent.offsetTop;
			}
			b.style.left = tmp_left + ( offsetLeft || 0 );
			b.style.top = tmp_top + (offsetTop || 1);
		}catch(err){
			// alert(err.description);
		}
	},

	alignBontopofA: function(a,b, c){ // My_JsLibrary.alignBontopofA( a, b );
		//	set Element-B's co-ordinates to those of Element-A
		try{
			if ( typeof a == 'string'){ a = _$(a) ; }
			if ( typeof b == 'string'){ b = _$(b) ; }
			var d = -1 * (a.offsetHeight) ; 
			var C = (c) ? c : 0 ;
			My_JsLibrary.alignBbelowA(a,b, C , d );
			//b.zIndex = 9999 ;
		}catch( err ){
			// alert( err.description );
		}
	},

	showbg: function(t){ // My_JsLibrary.showbg( bool )
		// show/hide a transparent background layer while Showing/Hiding Edit Dialogs (to prevent the user from interacting with other elements on the page)
		try{
			if(t){
				var docheight = $.getDocHeight() + 'px';
				if( !document.getElementById('bg_transparent') ){ 
					var d = document.createElement( 'DIV' ) ;
					d.setAttribute('id','bg_transparent');
					d.style.height = docheight ;
					document.body.appendChild(d) ;
				}else{
					var d = document.getElementById('bg_transparent') ;
					d.style.height = docheight ;
					d.style.display = '';
				}
				return;
			}
			if( document.getElementById('bg_transparent') ){ 
				var d = document.getElementById('bg_transparent') ;
				d.style.display = 'none';
			}
		}catch(err){}
	},


	callCB : function(resp, cb){ // My_JsLibrary.callCB(resp, cb) ;
		resp_lc = resp.toLowerCase() ;
		if( resp_lc.contains('response: success') && resp_lc.contains('responsemessage:') ){
			My_JsLibrary.errormsg = My_JsLibrary.responsemsg = resp.afterStr('ResponseMessage:') ;
			cb(true);
		}else{
			if( resp_lc.contains('error #a104') ){
				My_JsLibrary.errormsg = My_JsLibrary.responsemsg = 'This Account is suspended !' ;
			}else{
				if( resp_lc.contains('response: fail') ){
					My_JsLibrary.errormsg = My_JsLibrary.responsemsg = resp.afterStr('ResponseMessage:') ;
				}else{
					My_JsLibrary.errormsg = My_JsLibrary.responsemsg = 'Unknown Error' ;
				}
			}
			cb(false);
		}
	},

	isArray: function(a){
		// My_JsLibrary.isArray(a) ;
		return a instanceof Array || ( a!= null && typeof a=="object" && typeof a.push == "function" && typeof a.concat == "function" ) ;
	},

	getTodaysDate : function(){ // My_JsLibrary.getTodaysDate();
		var todayDate = new Date();
		return todayDate.getFullYear() + '-' + ((todayDate.getMonth())+1).addZero() + '-' + todayDate.getDate().addZero() ;
	}, 

	disableTheseFields : function( flds , disable ){
		// My_JsLibrary.disableTheseFields(flds) ; flds = [ el1, el2 , el_3 ] ;
		if( typeof flds == 'string' ){ flds = [flds]; }
		if( !My_JsLibrary.isArray(flds) ){ return; }
		for (var i=0; i < flds.length ; i++ ) {
			el = flds[i] ;
			if ( typeof el == 'string'){ el = _$(el) ; }
			el.disabled = ( disable ) ? false : true;
		}
	},

	getFieldValue : function(el){ // My_JsLibrary.getFieldValue(el)
		if( !el ){ return ''; }
		if ( typeof el == 'string'){ el = _$(el) ; }
		switch(el.type){
			case 'checkbox':
				return (el.checked) ? 'yes':'no' ;
				break;
			case 'radio':
				return (el.checked) ? el.value : '' ;
				break;
			case 'select-one':
				return el.value ; //.trim()
				break;

			case 'text':
			case 'textarea':
			case 'password':
			default:
				return el.value.trim() ;
				break;
		}
		return '';
	},

	enableTheseFields : function( flds ){ // My_JsLibrary.enableTheseFields( flds );
		My_JsLibrary.disableTheseFields(flds, true);
	},

	resetTheseFields : function( flds ){ 
		// My_JsLibrary.resetTheseFields(flds) ; flds = [ el1, el2 , el_3 ] ; - sets each element to blank value
		if( typeof flds == 'string' ){ flds = [flds]; }

		if( !My_JsLibrary.isArray(flds) ){ return; }
		var reset_el = function(el){
			var tmp_dfalt = $(el).attr('dfalt');
			switch(el.type){
				case 'text':
					el.value = '';
					if( tmp_dfalt )
						el.value = tmp_dfalt;
					break ;
				case 'checkbox':
					el.checked = false;
					if( tmp_dfalt)
						el.checked = ( tmp_dfalt == 'Y' ) ? true:false ;
					break ;
				case 'radio':
					el.checked = false;
					break ;
				case 'select-one':
					el.selectedIndex = -1;
					if(tmp_dfalt) My_JsLibrary.selectbox.selectOption(el, tmp_dfalt);
					break ;
				case 'textarea':
					el.value = '';
					if( tmp_dfalt )
						el.value = tmp_dfalt;
					break ;
				default : break ;
			}
		};
		var el;
		for (var i=0; i < flds.length ; i++ ) {
			el = flds[i] ;
			if ( typeof el == 'string'){ el = _$(el) ; }
			el.disabled = false;
			reset_el( el );
		}
	},

	selectbox:  {
		insert_before: function(el,txt, val, i){ // My_JsLibrary.selectbox.insert_before()
			if(isIE){
				el.add(new Option (txt,val), i );
			}else{
				el.add(new Option (txt,val), el.options[i] );
			}
		},
	
		insertOption_before: function(el,opt, i){ // My_JsLibrary.selectbox.insertOption_before()
			if(isIE){ 
				el.add(opt, i ); 
			}else{
				el.add(opt, el.options[i] );
			} 
		},
	
		append: function(el,txt, val){ // My_JsLibrary.selectbox.append(el,txt, val)
			el.options[el.options.length] = new Option (txt,val);
		},
	
		append_option: function(el,opt){ // My_JsLibrary.selectbox.append_option()
			if(isIE){
				el.add(opt);
			} else{
				el.add(opt,null);
			}
		},
	
		remove_i: function(el, i){ // My_JsLibrary.selectbox.remove_i()
			el.options[i] = null;
		},
	
		clear: function(el){ // My_JsLibrary.selectbox.clear()
			el.options.length = 0;
		},
	
		selectOption: function(el, opt){ // My_JsLibrary.selectbox.selectOption()
			el.selectedIndex = -1;
			for (var x=0; x < el.options.length; x++) { if (el.options[x].value == opt){ el.selectedIndex = x; } }
		}
	}
};


window.onerror = function(err, url, errcode ){ // Log any errors on this page
	if(window.APPDEBUGMODE){
		var alertmsg = 'ErrorCode / LineNumber : ' + errcode  + '\n Error : ' + err + '\n Location: ' + url ;
		alert(alertmsg);
	}
	return true;
};

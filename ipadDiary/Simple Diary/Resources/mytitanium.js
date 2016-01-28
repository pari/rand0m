var My_JsLibrary = {
	getRandomString: function (len) { // My_JsLibrary.getRandomString( 10 )
		var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
		var randomstring = '';
		for (var i=0; i < len; i++) {
			var rnum = Math.floor( Math.random() * chars.length );
			randomstring += chars.substring( rnum , rnum+1 );
		}
		return randomstring ;
	},
	
	weekDayOfDate: function(day, month, year){ // My_JsLibrary.weekDayOfDate( day, month, year)
		var WeekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday' , 'Friday' , 'Saturday'];
		var someDate = new Date();
		someDate.setMonth(Number(month));
		someDate.setDate(Number(day));
		someDate.setFullYear( Number(year) );
		// return someDate.toLocaleString() ;
		return WeekDays[someDate.getDay()];
	},
	
	getOneFieldValueFromQuery: function(query){ // My_JsLibrary.getOneFieldValueFromQuery()
		// query must return one row
		// other wise you will get the last row's value
		var t = '';
		var selmonth_rows = db.execute(query);
		while ( selmonth_rows.isValidRow()){
			t = selmonth_rows.field(0);
			selmonth_rows.next();
		}
		selmonth_rows.close();
		return t;
	},
	
	
	getOneFieldAsArrayFromQuery: function(query){ // My_JsLibrary.getOneFieldAsArrayFromQuery()
		var t = [];
		var selmonth_rows = db.execute(query);
		while ( selmonth_rows.isValidRow()){
			t.push(selmonth_rows.field(0));
			selmonth_rows.next();
		}
		selmonth_rows.close();
		return t;
	},
	
	
	getHalfSizeBlob: function(imgurl , cbFunc ) { // My_JsLibrary.getHalfSizeBlob (imgurl , cbFunc )
		var imageView = Titanium.UI.createImageView({ url: imgurl });
		imageView.visible = false;
		win.add(imageView);
		var afterLoadingImage = function(){
			var blob = imageView.toBlob();
			var reducedImageView = Titanium.UI.createImageView({});
			reducedImageView.image = blob ;
			reducedImageView.width = parseInt(blob.width/2, 10);
			reducedImageView.height = parseInt(blob.height/2, 10);
			imageView.removeEventListener('load', afterLoadingImage );
			win.remove(imageView);
			cbFunc(reducedImageView.toBlob());
		};
		imageView.addEventListener('load', afterLoadingImage );
	},
	
	alert: function(al){ // My_JsLibrary.alert( msg ) ;
		if( typeof al == 'string' || typeof al == 'number' ){
			var a = Titanium.UI.createAlertDialog({title:'You Clicked'});
			a.setMessage(al);
		}else{
			var a = Titanium.UI.createAlertDialog({title: al.title});
			a.setMessage(al.msg);
		}
		a.show();
	}
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
		var c = 0 ; var c1 = 0 ; var c2 = 0;

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

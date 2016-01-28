Titanium.include('include_start.js');
Titanium.include('mytitanium.js');
var textarea_fullHeight = 385;
var textarea_halfHeight = 190;

var win = Titanium.UI.currentWindow;
var selectedPageId = Titanium.UI.currentWindow.PageId ;
var page_text = '';
var HaveToAutoSAVE = false;

if( selectedPageId ){
	var selmonth_rows = db.execute( 'SELECT NOTES FROM ' + Titanium.App.DIARY.TABLENAME + " WHERE PGID='"+ selectedPageId + "' " );
	while ( selmonth_rows.isValidRow()){
		page_text = Base64.decode(selmonth_rows.field(0));
		selmonth_rows.next();
	}
	selmonth_rows.close();
}

var l2 = Titanium.UI.createLabel({ text:'', height: 'auto', width:'auto', color:'#9F7346', font:{fontSize: 20 }, top: 5 , textAlign:'center' });


var ta1 = Titanium.UI.createTextArea({
	value: page_text,
	height: textarea_fullHeight,
	width:290,
	top:10,
	left: 20,
	font:{ fontSize:17, fontFamily:'American Typewriter'},
	color: '#000',
	textAlign:'left',
	appearance: Titanium.UI.KEYBOARD_APPEARANCE_ALERT,	
	keyboardType: Titanium.UI.KEYBOARD_DEFAULT,
	returnKeyType: Titanium.UI.RETURNKEY_DEFAULT,
	suppressReturn : false,
	borderWidth: 0,
	backgroundColor:'transparent'
	//borderRadius:5
});

// ta1.enabled = false;

ta1.addEventListener('change', function(){
	HaveToAutoSAVE = true;
});

ta1.addEventListener('focus', function(){
	ta1.height = textarea_halfHeight ;
});


if( selectedPageId ){
	setInterval(function(){
		if(!HaveToAutoSAVE){ return; }
		var page_text = Base64.encode(ta1.value) ;
		db.execute( "update MYPAGES set NOTES='"+ page_text +"' where PGID='"+ selectedPageId + "' " );
		HaveToAutoSAVE = false;
	}, 4000);
}

win.add(l2);
win.add(ta1);
win.addEventListener('click', function(){ ta1.blur(); ta1.height = textarea_fullHeight ; });

var takePicture = Titanium.UI.createButton({ systemButton:Titanium.UI.iPhone.SystemButton.CAMERA });
win.rightNavButton = takePicture;

takePicture.addEventListener('click', function(){
	
	Titanium.Media.showCamera({
		success:function(event){
			var randomFileName =  My_JsLibrary.getRandomString( 12 ) + '.png' ;
			var filehandler = Titanium.Filesystem.getFile( Titanium.Filesystem.applicationDataDirectory, randomFileName );
			// f.write(event.media);
			
			//My_JsLibrary.getHalfSizeBlob ( event.media , function(resizedImage){
			//	filehandler.write(resizedImage);
				filehandler.write(event.media);
				var tday = new Date();
				var today_dayOfMonth = tday.getDate();
				var today_MonthOfYear = tday.getMonth();
				db.execute( " INSERT INTO " + Titanium.App.DIARY.TABLEPHOTOS + "(PGID, PGDAY,PGMONTH,PHOTONAME) VALUES( '" + selectedPageId + "','" + today_dayOfMonth + "','" + today_MonthOfYear + "', '" + randomFileName + "' )");
			//} );
		},
		cancel:function(){
			
		},
		error:function(error){
			var a = Titanium.UI.createAlertDialog({title:'Camera'});
			if (error.code == Titanium.Media.NO_CAMERA){
				a.setMessage('Device does not have video recording capabilities');
			}else{
				a.setMessage('Unexpected error: ' + error.code);
			}
			a.show();
		},
		//saveToPhotoGallery:true,
		allowImageEditing: false
	});

});

/*
incomplete list keyboard types
	Titanium.UI.KEYBOARD_DEFAULT
	Titanium.UI.KEYBOARD_EMAIL
	Titanium.UI.KEYBOARD_ASCII
	Titanium.UI.KEYBOARD_URL
	Titanium.UI.KEYBOARD_NUMBER_PAD
	Titanium.UI.KEYBOARD_NUMBERS_PUNCTUATION
	Titanium.UI.KEYBOARD_PHONE_PAD

supported returnKeyTypes
	Titanium.UI.RETURNKEY_DEFAULT
	Titanium.UI.RETURNKEY_GO
	Titanium.UI.RETURNKEY_GOOGLE
	Titanium.UI.RETURNKEY_JOIN
	Titanium.UI.RETURNKEY_NEXT
	Titanium.UI.RETURNKEY_ROUTE
	Titanium.UI.RETURNKEY_SEARCH
	Titanium.UI.RETURNKEY_SEND
	Titanium.UI.RETURNKEY_YAHOO
	Titanium.UI.RETURNKEY_DONE
	Titanium.UI.RETURNKEY_EMERGENCY_CALL

*/

//Titanium.UI.createAlertDialog({title:'Camera', message:'Check your Photo Gallery'}).show();
// ta1.blur(); ta1.height = 300 ;
// var imageView = Titanium.UI.createImageView({ height:100, width:100, top:320, left:10, backgroundColor:'#999' });
// win.add(imageView);
// imageView.image = event.media;

// var cropRect = event.cropRect;
// var image = event.media;
// var thumbnail = event.thumbnail;
// var f = Titanium.Filesystem.getFile(Titanium.Filesystem.applicationDataDirectory,'camera_photo.png');
// f.write(image);

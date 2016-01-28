Titanium.include('include_start.js');
Titanium.include('mytitanium.js');

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
	height: 200,
	width:300,
	top:40,
	font:{ fontSize:19, fontFamily:'Marker Felt', fontWeight:'bold'},
	color: '#888',
	textAlign:'left',
	appearance: Titanium.UI.KEYBOARD_APPEARANCE_ALERT,	
	keyboardType: Titanium.UI.KEYBOARD_DEFAULT,
	returnKeyType: Titanium.UI.RETURNKEY_DEFAULT,
	suppressReturn : false,
	borderWidth:2,
	borderColor:'#bbb',
	borderRadius:5
});

ta1.addEventListener('change', function(){
	HaveToAutoSAVE = true;
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
win.addEventListener('click', function(){ ta1.blur(); });

var takePicture = Titanium.UI.createButton({ systemButton:Titanium.UI.iPhone.SystemButton.CAMERA });
win.rightNavButton = takePicture;


takePicture.addEventListener('click', function(){
	
	Titanium.Media.showCamera({
		success:function(event){
			Titanium.UI.createAlertDialog({title:'Camera', message:'Check your Photo Gallery'}).show();
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
		saveToPhotoGallery:true,
		allowImageEditing:true
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

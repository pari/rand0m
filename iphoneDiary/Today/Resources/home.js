// File 001
// This file is loaded into the app.js
// From here to either mydiary.js or to picarchive.js
Titanium.include('include_start.js');
var win = Titanium.UI.currentWindow;
var view = Ti.UI.createView({ backgroundImage:"home.png", width:320, height:420, top:0 });
win.add(view);

var todayButton = Titanium.UI.createButton({ systemButton:Titanium.UI.iPhone.SystemButton.ADD });
win.rightNavButton = todayButton;

todayButton.addEventListener('click', function(e){
	// check if there is atleast one entry in the database for today
	// if there is then set pageId to that and redirect
	// else create a pageId and then redirect to that
	var tday = new Date();
	var today_dayOfMonth = tday.getDate();
	var today_MonthOfYear = tday.getMonth();
	var PGID = 0;

	var selmonth_rows = db.execute( 'SELECT PGID FROM ' + Titanium.App.DIARY.TABLENAME + " WHERE PGMONTH='"+ today_MonthOfYear + "' and PGDAY='"+ today_dayOfMonth +"'" );
	var rowCount = 0;
	while ( selmonth_rows.isValidRow()){
		PGID = selmonth_rows.field(0);
		selmonth_rows.next();
		rowCount++;
	}
	selmonth_rows.close();

	if(rowCount == 1){ 
		// nothing much to do, we already know the PGID
	}

	if(rowCount == 0){
		db.execute( " INSERT INTO MYPAGES(PGDAY,PGMONTH,NOTES) VALUES( '" + today_dayOfMonth + "','" + today_MonthOfYear + "', '' )");
		PGID = db.lastInsertRowId ;
	}

	var pagewin = Titanium.UI.createWindow({
		url: 'page.js',
		title:  'Today' ,
		backgroundColor:'#FFEB8F',
		PageId: PGID,
		barColor:'#AD7A12'
	});
	Titanium.UI.currentTab.open( pagewin , {animated: true});
});



setTimeout(function(){
	
	var icon_archive = Ti.UI.createView({ backgroundImage:"home_icon_diaryarchive.png", width: 110, height: 91, top:570 , left: 200 });
	win.add(icon_archive);
	
	icon_archive.animate({ top: 70, duration:400 });
	
	var pic_archive = Ti.UI.createView({ backgroundImage:"home_icon_picarchive.png", width: 110, height: 91, top:570 , left: 200 });
	win.add(pic_archive);
	
	pic_archive.animate({ top: 180, duration:600 });
		
	var icon_settings = Ti.UI.createView({ backgroundImage:"home_icon_prefs.png", width: 110, height: 91, top:590 , left: 200 });
	win.add(icon_settings);
	
	icon_settings.animate({ top: 290, duration: 800 });

	icon_archive.addEventListener('click', function(e){
		var win_listMonths = Titanium.UI.createWindow({ url: 'mydiary.js', title: '2010' , backgroundColor:'#FFF', barColor:'#AD7A12', backButtonTitle :'Home' });
		Titanium.UI.currentTab.open(win_listMonths,{animated:true});
	});
	
}, 500 );



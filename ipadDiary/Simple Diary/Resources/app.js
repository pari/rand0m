// File 000.000
// This is the first thing that the app launches
// From here to the home page


Titanium.include('include_start.js');


// 
// db.execute( 'delete from MYPAGES' );
// Titanium.include('mytitanium.js');
// db.execute( "INSERT INTO MYPAGES(PGDAY,PGMONTH,NOTES) VALUES(12,5, '"+Base64.encode('June 12th is an awesome day for me because i have started working on titanium again for real and that changed everything. In a few hours the iPhone now seems to be like this magic box.')+"')");
// db.execute( "INSERT INTO MYPAGES(PGDAY,PGMONTH,NOTES) VALUES( 31,4, '"+Base64.encode('Its Prasad birthday today, but that does not change anything. I left him an sms and he left me back a voicemail. Later i came to know that it was long weekend and he went to Niagara. I was busy with kalamandir stuff as usual. Life sucks big time in this country')+"')");


// Delete all photos from database on startup
		// for each photo delete photo
		// var selmonth_rows = db.execute("select PHOTONAME from MYPHOTOS ORDER BY PHOTONAME");
		// while ( selmonth_rows.isValidRow()){
		// 	var filehandler = Titanium.Filesystem.getFile( Titanium.Filesystem.applicationDataDirectory, selmonth_rows.field(0) );
		// 	filehandler.deleteFile();
		// 	selmonth_rows.next();
		// }
		// selmonth_rows.close();
		// //db.execute( 'delete from table MYPHOTOS');
		// db.execute( 'delete from MYPHOTOS');
//



Titanium.UI.setBackgroundColor('#000');
// create base UI tab and root window
var win1 = Titanium.UI.createWindow({ url:'home.js', title:'Home', backgroundColor:'#fff', tabBarHidden: true, barColor:'#AD7A12' });
// create tab group and add tabs
var tabGroup = Titanium.UI.createTabGroup();
var tab1 = Titanium.UI.createTab({ icon:'KS_nav_views.png', title:'Tab 1', window:win1 });
tabGroup.addTab(tab1);  
tabGroup.open();




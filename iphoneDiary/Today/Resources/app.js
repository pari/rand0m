// File 000.000
// This is the first thing that the app launches
// From here to the home page


Titanium.include('include_start.js');
// db.execute( "INSERT INTO MYPAGES(PGDAY,PGMONTH,NOTES) VALUES(12,5, 'Some notes for june 12th ')");
Titanium.UI.setBackgroundColor('#000');

// create base UI tab and root window
var win1 = Titanium.UI.createWindow({ url:'home.js', title:'Home', backgroundColor:'#fff', tabBarHidden: true, barColor:'#AD7A12' });
// create tab group and add tabs
var tabGroup = Titanium.UI.createTabGroup();
var tab1 = Titanium.UI.createTab({ icon:'KS_nav_views.png', title:'Tab 1', window:win1 });
tabGroup.addTab(tab1);  
tabGroup.open();




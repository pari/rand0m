Titanium.include('include_start.js');

var MONTHS = [ 'Jan', 'Feb' , 'Mar' , 'Apr' , 'May' , 'Jun' , 'Jul' , 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] ;
var selectedMonth = Titanium.UI.currentWindow.selectedMonth ;


var data = [];
var selmonth_rows = db.execute( 'SELECT PGDAY, PGID FROM ' + Titanium.App.DIARY.TABLENAME + " WHERE PGMONTH='"+ selectedMonth + "' ORDER BY PGDAY DESC " );
while ( selmonth_rows.isValidRow()){
	data.push( {title: MONTHS[selectedMonth] + ' ' + selmonth_rows.field(0) + ', ' + Titanium.App.DIARY.SELECTEDYEAR , hasChild:true , pageId : selmonth_rows.field(1) });
	selmonth_rows.next();
}
selmonth_rows.close();








var tableview = Titanium.UI.createTableView({ data: data });

tableview.addEventListener('click', function(e){
	var win = Titanium.UI.createWindow({
		url: 'page.js',
		title:  e.rowData.title ,
		backgroundColor:'#FFEB8F',
		barColor:'#111' ,
		PageId: e.rowData.pageId
	});
	Titanium.UI.currentTab.open( win , {animated: true , transition: Titanium.UI.iPhone.AnimationStyle.CURL_DOWN });
	
	//win.animate({view:view2, transition:Ti.UI.iPhone.AnimationStyle.CURL_DOWN});
});

Titanium.UI.currentWindow.add( tableview );

// db.execute( "INSERT INTO MYPAGES(PGDAY,PGMONTH,NOTES) VALUES(12,5, 'Some notes for june 12th ')");
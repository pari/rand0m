Titanium.include('include_start.js');
Titanium.include('mytitanium.js');

var refreshListOfEntries = function(eCase){
	tableview.setData([]);
	var sqlstring = "";
	var tday = new Date();
	var today_dayOfMonth = tday.getMonth();

	switch (eCase) {
		case 0:
			sqlstring = "select PGID, PGDAY, PGMONTH, NOTES from MYPAGES ORDER BY PGID DESC LIMIT 10";
		break;
		case 1:
			sqlstring = "select PGID, PGDAY, PGMONTH, NOTES from MYPAGES WHERE PGMONTH='" + today_dayOfMonth + "' ORDER BY PGID DESC";
		break;
		case 2:
			sqlstring = "select PGID, PGDAY, PGMONTH, NOTES from MYPAGES ORDER BY PGID DESC";
		break;
	}
	
	var selmonth_rows = db.execute( sqlstring );
	var data = [];
	while ( selmonth_rows.isValidRow()){
		var PGID = selmonth_rows.field(0);
		var PGDAY = selmonth_rows.field(1);
		var PGMONTH = selmonth_rows.field(2);
		var NOTES = Base64.decode(selmonth_rows.field(3));
		NOTES = NOTES.split(" ").slice(0,20).join(' ');
		//data.push({ title: NOTES , hasChild:true, pageId: PGID });
		
		// get one picture name (if exists) associated with this PGID
		// var onePictureFileName = My_JsLibrary.getOneFieldValueFromQuery('select PHOTONAME from ' + Titanium.App.DIARY.TABLEPHOTOS + " where PGID = '" + PGID + "' LIMIT 1 " );
		
		var row = Ti.UI.createTableViewRow(); row.selectedBackgroundColor = '#fff'; row.height = 90; row.className = 'datarow';
		row.pageId = PGID ;

		// if(onePictureFileName){
		// 	var photo = Ti.UI.createImageView({ url: Titanium.Filesystem.applicationDataDirectory + '/' + onePictureFileName , top:5, left:10, width:50, height:50 });
		// }else{
			var photo = Ti.UI.createView({ backgroundImage: 'user.png' , top:5, left:10, width:50, height:50 });
		//}
		row.add(photo);
		
		var comment = Ti.UI.createLabel({ color:'#222', font:{fontSize:16,fontWeight:'normal', fontFamily:'Arial'}, left:70,
			top: 4 , height:50, width:250, text: NOTES });
		//comment.rowNum = c;
		row.add(comment);
		
		
		var calMonth = Ti.UI.createLabel( { color:'#8F6143',  font: { fontSize:14, fontWeight:'bold', fontFamily:'Verdana'}, bottom: 10 , right: 250, height:'auto', width:'auto', text: MONTHS_FULL[PGMONTH] } );
		row.add(calMonth);
		
		var calico = Ti.UI.createView({ backgroundImage:'cal.png', bottom:2,
		left:70, width:32, height:32 });
		row.add(calico);
		
		var calDay = Ti.UI.createLabel( { color:'#8F6342',  font: { fontSize:13, fontWeight:'bold', fontFamily:'Verdana'}, left:75, bottom: 10 , height:'auto', width:'auto', text: PGDAY });
		row.add(calDay);
		
		// 
		var calYear = Ti.UI.createLabel( { color:'#BDA078',  font: { fontSize:15, fontWeight:'normal', fontFamily:'Verdana'}, bottom: 10 , left: 105, height:'auto', width:'auto', text: My_JsLibrary.weekDayOfDate( PGDAY, PGMONTH, '2010') } );
		row.add(calYear);
		
		data.push(row);
		selmonth_rows.next();
	}
	
	selmonth_rows.close();
	tableview.setData(data);
};


var tableview = Titanium.UI.createTableView({ data: [ ]});

var ThreeTopOptions = Titanium.UI.createTabbedBar({
	labels:['Recent', 'This Month', '2010'],
	index:0,
	backgroundColor:'#AD7A12',
	style:Titanium.UI.iPhone.SystemButtonStyle.BAR
});

Titanium.UI.currentWindow.setTitleControl(ThreeTopOptions);

ThreeTopOptions.addEventListener('click', function(e){
	refreshListOfEntries(e.index);
});


tableview.addEventListener('click', function(e){
	// var win = Titanium.UI.createWindow({
	// 	url: 'month.js',
	// 	title: MONTHS_FULL[e.index] + ', ' + Titanium.App.DIARY.SELECTEDYEAR,
	// 	backgroundColor:'#FFEB8F',
	// 	barColor:'#AD7A12' ,
	// 	selectedMonth: e.index
	// });
	// 
	// //win.open();
	// Titanium.UI.currentTab.open( win , {animated: true });
	var win = Titanium.UI.createWindow({
		url: 'page.js',
		title:  e.rowData.title ,
		backgroundColor:'#FFEB8F',
		barColor:'#AD7A12' ,
		PageId: e.rowData.pageId,
		backgroundImage : './page.png',
		backButtonTitle :'Back'
	});
	Titanium.UI.currentTab.open( win , {animated: true , transition: Titanium.UI.iPhone.AnimationStyle.CURL_DOWN });
});

Titanium.UI.currentWindow.add( tableview );

refreshListOfEntries(0);

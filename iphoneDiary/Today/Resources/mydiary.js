Titanium.include('include_start.js');

var MONTHS = [ 'Jan', 'Feb' , 'Mar' , 'Apr' , 'May' , 'Jun' , 'Jul' , 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] ;
var data = [];
for( var t=0; t < MONTHS.length; t++ ){
	data.push({ title: MONTHS[t] + ' ' + Titanium.App.DIARY.SELECTEDYEAR , hasChild:true });
}
var tableview = Titanium.UI.createTableView({ data: data });

tableview.addEventListener('click', function(e){

	var win = Titanium.UI.createWindow({
		url: 'month.js',
		title: 'Entries for ' + MONTHS[e.index] ,
		backgroundColor:'#FFEB8F',
		barColor:'#111' ,
		selectedMonth: e.index
	});

	//win.open();
	Titanium.UI.currentTab.open( win , {animated: true });
});

Titanium.UI.currentWindow.add( tableview );

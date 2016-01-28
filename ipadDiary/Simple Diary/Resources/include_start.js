var MONTHS = [ 'Jan', 'Feb' , 'Mar' , 'Apr' , 'May' , 'Jun' , 'Jul' , 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] ;
var MONTHS_FULL = [ 'January', 'February' , 'March' , 'April' , 'May' , 'June' , 'July' , 'August', 'September', 'October', 'November', 'December'] ;

Titanium.App.DIARY = {
	SELECTEDYEAR: '2010',
	SELECTEDMONTH: '',
	DATABASENAME: 'mydiary',
	TABLENAME: 'MYPAGES',
	TABLEPHOTOS: 'MYPHOTOS',
	TABLEPREVIEWS: 'PREVIEWS'
};

var db = Titanium.Database.open(Titanium.App.DIARY.DATABASENAME);
db.execute( 'CREATE TABLE IF NOT EXISTS ' + Titanium.App.DIARY.TABLENAME + ' (PGID INTEGER PRIMARY KEY, PGDAY INTEGER , PGMONTH INTEGER, NOTES TEXT)' );

db.execute( 'CREATE TABLE IF NOT EXISTS ' + Titanium.App.DIARY.TABLEPHOTOS + ' (PHOTOID INTEGER PRIMARY KEY, PGID INTEGER, PGDAY INTEGER , PGMONTH INTEGER, PHOTONAME TEXT)' );

db.execute( 'CREATE TABLE IF NOT EXISTS PREVIEWS (PREVIEWID INTEGER PRIMARY KEY, PHOTOID INTEGER, PREVIEWNAME TEXT)' );



var get_NumberOfPhotos_PGID = function(pgid){
	var srs = db.execute( 'SELECT PHOTOID FROM ' + Titanium.App.DIARY.TABLEPHOTOS + " WHERE PGID='"+ pgid + "'" );
	var rowCount = 0;
	while ( srs.isValidRow()){
		rowCount++;
		srs.next();
	}
	srs.close();
	return rowCount;
};
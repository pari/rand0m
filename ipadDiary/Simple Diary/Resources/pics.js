Titanium.include('include_start.js');
Titanium.include('mytitanium.js');

// get list of all pictures in an array (LIFO)

// create a walled display by looping though these

// add window click event listener
// if the source is the image
// scale it / scale out

var pic_filename = My_JsLibrary.getOneFieldAsArrayFromQuery("select PHOTONAME from MYPHOTOS ORDER BY PGID DESC ");

var noPics = pic_filename.length;

var current_set_x = 0;
var x_current_set = 0 ;

for(var t=0; t < noPics ; t++ ){
	var toppx = (x_current_set * 96) + 5 ;
	leftpx = (current_set_x * 70 ) + (5* (current_set_x+1));

	var photo = Ti.UI.createImageView({ url: Titanium.Filesystem.applicationDataDirectory + '/' + pic_filename[t] , width:70, height: 'auto' , left: leftpx , top: toppx });
	
	Titanium.UI.currentWindow.add( photo );
	
	if(current_set_x == 3){
		current_set_x = 0;
		x_current_set++;
	}else{
		current_set_x++;
	}
}
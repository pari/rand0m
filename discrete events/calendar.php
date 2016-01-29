<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";
	$username = $_SESSION["uname"];
?>
<SCRIPT>
	
	var localajaxinit = function(){
		
		DE_USER_action( 'getJsonEventsForCalendarView' , {
			workid : 'somearg',
			callback : function(a){
				if(a){
					var CALEVENTS = [];
					eval( My_JsLibrary.responsemsg ); // MYCALTASKS
					for( var i = 0 ; i < MYCALTASKS.length ; i++ ){
						var t = {};
						t.title = MYCALTASKS[i]['tdesc'] ;
						t.start = new Date( MYCALTASKS[i]['tY'], MYCALTASKS[i]['tM'] , MYCALTASKS[i]['tD']);
						CALEVENTS.push(t);
					}
					
					$('#calendar').fullCalendar({
						theme: true,
						header: { left: 'prev,next today', center: 'title', right: 'month,agendaWeek,agendaDay' },
						editable: false,
						events: CALEVENTS
					});
				}else{
					My_JsLibrary.showErrMsg();
				}
			}
		});
	}; // End of localajaxinit

</SCRIPT>

<div style='clear:both; margin-top:30px;'>
	<div id='calendar'></div>
</div>

<?php

// SOME Javascript notes
					// $('#calendar').fullCalendar({
					// 	theme: true,
					// 	header: {
					// 		left: 'prev,next today',
					// 		center: 'title',
					// 		right: 'month,agendaWeek,agendaDay'
					// 	},
					// 	editable: true,
					// 	events: [
					// 		{
					// 			title: 'All Day Event',
					// 			start: new Date(y, m, 1)
					// 			// end: new Date(y, m, d-2) // Optional End date
					// 			// start: new Date(y, m, d, 10, 30), // meeting at a particular time (reminders)
					// 			// end: new Date(y, m, d, 14, 0), // End time is optional when you set allDay:false
					// 			// allDay: false
					// 			// url: 'http://google.com/'
					// 		}
					// 		// { // Repeating Events
					// 		// 	id: 999,
					// 		// 	title: 'Repeating Event',
					// 		// 	start: new Date(y, m, d-3, 16, 0),
					// 		// 	allDay: false
					// 		// },
					// 	]
					// });

include "include_footer.php";
?>
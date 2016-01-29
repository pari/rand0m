<?php

	include_once "include_db.php";
	include_once "include_functions.php";
	checkUserSessionandCookie();
	include_once "include_header.php";

	$username = $_SESSION["uname"];
?>
<SCRIPT>
	var EDITNOTEID = '';

	var MYNOTES = 
		<?php
		$result = mysql_query("select noteID , note_text from NOTES where note_user='$username' ORDER BY noteID DESC");
		IF (@mysql_num_rows($result)==0){
			echo "{}";
		}else{
			$notes = array();
			while ( $row = mysql_fetch_assoc($result) ) { // $row['note_text'] , $row['noteID']
				$notes[$row['noteID']] = base64_encode($row['note_text']) ;
			}
			echo json_encode( $notes );
		}
		?>
	;

	var localajaxinit = function(){
		My_JsLibrary.selectMainTab('notes.php');

		var tmp_str = '';
		for( var i in MYNOTES){
			if(!MYNOTES.hasOwnProperty(i)) { continue; }
			var this_note = Base64.decode(MYNOTES[i]).nl2br() ;
			tmp_str += "<div class='NOTE'>" ;
				tmp_str += "<div class='NOTE_text' id='note"+i+"' noteID='" + i + "'>" ;
				tmp_str += this_note;
				tmp_str += "</div>";

				tmp_str += "<div class='NOTE_options'>" ;
				tmp_str += "<span class='blueTextButton' onclick=\"manageNotesJsfunctions.emailNoteForm('"+ i +"')\">Email Note</span>";
				tmp_str += "<span class='blueTextButton' onclick=\"manageNotesJsfunctions.deleteNote('"+ i +"')\" style='margin-left:10px;'>Delete Note</span>";
				tmp_str += "</div>";

				tmp_str += "<div style='clear:both;'></div>";

			tmp_str += "</div>";
		}

		$("#ListOfNotes").html( tmp_str );

		$("#ListOfNotes div.NOTE div.NOTE_text").click(function(){
			EDITNOTEID = $(this).attr('noteID') ;
			var thisnoteText = Base64.decode( MYNOTES[EDITNOTEID] );
			_$('textarea_editnote').value = thisnoteText.nl2propnl() ;
			//My_JsLibrary.showdeadcenterdiv( _$("div_editnote_container") );
			$("#div_editnote_container").showWithBg();
			_$('textarea_editnote').focus();
		});

	}; // End of localajaxinit




	var manageNotesJsfunctions = {
		emailNoteForm : function (NOTEID){
			EDITNOTEID = NOTEID;
			$("#div_emailnote_container").showWithBg();
			_$('text_emailNoteto').value = '';
			_$('text_emailNoteto').focus();
		},

		sendNote : function(){
			var emailto = _$('text_emailNoteto').value ;
			DE_USER_action('EmailNote',
			{	noteId: EDITNOTEID,
				emailTo : emailto,
				callback:function(a){
					if(a){ 
						$("#div_emailnote_container").hideWithBg();
						My_JsLibrary.showfbmsg( 'Note Sent!', 'green');
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
		},

		deleteNote : function( NOTEID ){ // manageNotesJsfunctions.deleteNote(a)
			if(!confirm('Sure ?')){return;}
			DE_USER_action('DeleteNote',
			{	noteId: NOTEID,
				callback:function(a){
					if(a){ 
						$("#note"+NOTEID).parent().remove();
						delete MYNOTES[NOTEID];
						$("#div_editnote_container").hideWithBg();
						My_JsLibrary.showfbmsg( 'Note deleted!', 'green');
					}else{
						My_JsLibrary.showErrMsg();
					}
				}
			});
		},

		updateNote : function(){ // manageNotesJsfunctions.updateNote();

			var newcontent = _$('textarea_editnote').value ;

			if(!newcontent){
				if(!confirm('Updating with blank content would delete this note.\n \n            Proceed ?')){return;}
				manageNotesJsfunctions.deleteNote(EDITNOTEID);
				return;
			}

			DE_USER_action('UpdateNote',
			{	noteId: EDITNOTEID,
				updatedContent: newcontent,
				callback:function(a){ if(a){ 
					$("#note"+EDITNOTEID).html(newcontent.nl2br());
					// TODO : Update Javascript Object
					MYNOTES[EDITNOTEID] = Base64.encode( newcontent );
					$("#div_editnote_container").hideWithBg();
				}else{ My_JsLibrary.showErrMsg() ; }}
			});
		},

		createNewNote_form : function(){
			$('#NewNoteForm').show();
			$('#NewNoteForm_heading').hide();
			_$('textarea_newnote').focus();
		},

		AddNote : function(){
			var newnote = _$('textarea_newnote').value ;
			if( ! My_JsLibrary.checkRequiredFields( ['textarea_newnote'] ) ){
				return;
			}
			DE_USER_action( 'AddNote',
			{
				NewNoteText: newnote,
				callback:function(a){
					if(a){
						window.location.href='notes.php';
					}else{
						My_JsLibrary.showErrMsg() ;
					}
				}
			});
		},

		createNewNote_cancel : function(){
			$('#NewNoteForm').hide();
			$('#NewNoteForm_heading').show();
		}
	};


</SCRIPT>


<div id="div_emailnote_container" style="display:none; width: 440" class="divAboveBg">
	<TABLE width="440" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Email Note</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="438" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="center">
			Send this Note to <input type='text' id='text_emailNoteto' size=35>
			</TD>
		</TR>
		<TR>
			<TD style="padding:10px;" align="center">
				<span class="bluebuttonSmall" onclick="manageNotesJsfunctions.sendNote()">Send Note</span>
			</TD>
		</TR>
	</TABLE>
</div>


<div id="div_editnote_container" style="display:none; width: 740" class="divAboveBg">
	<TABLE width="740" cellpadding=0 cellspacing=2 border=0  class="divHeadingTable">
	<TR><TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">Edit Note</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="19"><img src="/images/close.gif" border=0></TD>
	</TR>
	</TABLE>
	<TABLE width="738" cellpadding="4" cellspacing=0 border=0>
		<TR><TD align="center">
				<textarea id='textarea_editnote' rows=13 cols=75></textarea>
			</TD>
		</TR>
		<TR>
			<TD style="padding:10px;" align="center">
				<span class="bluebuttonSmall" onclick="manageNotesJsfunctions.updateNote()">Update Note</span>
			</TD>
		</TR>
	</TABLE>
</div>


<div class="NotesListing">
	<div style="margin-bottom: 10px;" id='NewNoteForm_heading'>
		<span onclick="manageNotesJsfunctions.createNewNote_form();" class='bluebutton'>New Note</span>
	</div>

	<div id ='NewNoteForm' class='NOTE' style='display:none;'>
		<div class='NOTE_text NoteEditMode'>
			New Note:<BR>
			<textarea id='textarea_newnote' rows=7 cols=60></textarea>
		</div>
		<div style='float:right; padding:10px; margin-bottom:5px;'>
			<span class='bluebuttonSmall' onclick='manageNotesJsfunctions.createNewNote_cancel();'>Cancel</span>
			<span class='bluebuttonSmall' onclick='manageNotesJsfunctions.AddNote();'>Add Note</span>
		</div>
		<div style='clear:both;'></div>
	</div>
</div>

<div class="NotesListing" id="ListOfNotes"></div>

<?php
include "include_footer.php";
?>
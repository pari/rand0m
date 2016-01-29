<?php

include ("../include_variables.php");
include ("include_header.php");
include ("../include_functions.php");
?>
<script>

<?php 
	// PACKAGES 
	returnPackagesJSObject();
?>

var localajaxinit = function(){
	My_JsLibrary.selectMainTab('clients.php');
	
	$("span.pkgnumber").each(function(){
		var thispkg = $(this).html();
		$(this).html( PACKAGES[thispkg].Name );
	});

	var t= '<TR><TD colspan=3 align=center> <FONT SIZE="+1" COLOR="#7B9CB7"><B>Packages</B></FONT></TD></TR>';
	var selbx = _$('nc_pkg') ;
	for( var i in PACKAGES ){
		if( !PACKAGES.hasOwnProperty(i) ){ continue; }
		My_JsLibrary.selectbox.append(selbx, PACKAGES[i].Name , i );
		// 1 : { Name: 'Starter' , Users:'4', space:'2000'} , 
		// 2 : { Name: 'Team' , Users:'10', space:'5000'} , 
		// 3 : { Name: 'Pro' , Users:'25', space:'15000'}

		t += '<TR> <TD><B>' + PACKAGES[i].Name + '</B></TD><TD> ' + PACKAGES[i].Users + ' users</TD><TD>' + PACKAGES[i].space + ' Mb </TD></TR>' ;
	}

	$('#tblPackages').html(t);
};


var clients_MiscFunctions = {
	suspendActivate: function( subdomain ){ // clients_MiscFunctions.suspendActivate('subdomain');
		if( !confirm("Do you want to change the status of this domain ?") ){ return; }
		DE_SSADMIN_action( 'suspendActivate' , {
			subdomain: subdomain,
			callback:function(a){if(a){ window.location.reload(); }else{ My_JsLibrary.showErrMsg();}}
		});
	},

	deleteSubdomain: function( subdomain ){ // clients_MiscFunctions.deleteSubdomain('subdomain')
		if( !confirm("THIS ACTION CAN NOT BE UNDONE !! \n ALL OF SUBDOMAIN '"+ subdomain +"'s DATA WILL BE LOST. \n\n Clik OK to Proceed ") ){ return; }
		DE_SSADMIN_action( 'deleteClient' , {
			subdomain: subdomain,
			callback:function(a){if(a){ window.location.reload(); }else{ My_JsLibrary.showErrMsg();}}
		});
	},

	generatepass : function(){ // clients_MiscFunctions.generatepass()
		_$('nc_password').value =  My_JsLibrary.getRandomString( 8 ) ;
	},

	showNewClientForm : function(){ // clients_MiscFunctions.showNewClientForm()
		this.generatepass();
		$('#newClient_Form').showWithBgCenter();
	},

	createnewCLient:function(){ // clients_MiscFunctions.createnewCLient()
		var fname = _$('nc_fname').value ;
		var subdomain = _$('nc_subdomain').value ;
		var pkg = _$('nc_pkg').value ;
		var admemail = _$('nc_adminEmail').value ;
		var newpass = _$('nc_password').value;

		DE_SSADMIN_action( 'createNewClient' , {
			fullName : fname,
			subDomain : subdomain,
			packageid : pkg,
			adminEmail : admemail,
			adminPass : newpass,
			callback:function(a){if(a){ window.location.reload(); }else{ My_JsLibrary.showErrMsg();}}
		});
	}

};


</script>

<div id="newClient_Form" style="display:none; padding: 5px; width: 450; margin:auto;" class="divAboveBg">
	<TABLE width="100%" cellpadding=0 cellspacing=2 border=0>
	<TR>
		<TD onmousedown="My_JsLibrary.startDrag(event);" class="drag_dialog_title">
			<span style="color: #972E1C"><b>Create New Client</b></span>
		</TD>
		<TD onclick="My_JsLibrary.hideDrag(event);" width="16">
			<img src="../images/close.gif" border=0>
		</TD>
	</TR>
	</TABLE>
	<TABLE width="445" cellpadding="4" cellspacing=0 border=0>
		<TR>
			<TD align="right">Client Name :</TD>
			<TD>
				<input type="text" size=16 id="nc_fname" class="hilight">
			</TD>
		</TR>
		<TR>
			<TD align="right">Subdomain :</TD>
			<TD>
				<input type="text" size=16 id="nc_subdomain" class="hilight">
			</TD>
		</TR>
		<TR>
			<TD align="right">Package :</TD>
			<TD>
				<select id="nc_pkg"></select>
			</TD>
		</TR>
		<TR>
			<TD align="right">Admin EmailId :</TD>
			<TD>
				<input type="text" size=26 id="nc_adminEmail" class="hilight">
			</TD>
		</TR>
		<TR>
			<TD align="right">Admin Password :</TD>
			<TD>
				<input type="text" size=10 id="nc_password" class="hilight">
				<span class="bluebuttonSmall" onclick="clients_MiscFunctions.generatepass()">generate</span>
			</TD>
		</TR>
		<TR>
			<TD></TD>
			<TD>
				<span class="bluebuttonSmall" onclick="clients_MiscFunctions.createnewCLient()">Create</span>
			</TD>
		</TR>
	</TABLE>		
</div>



<table id="tblPackages" align=center class="ssa_pklistTable" cellpadding=0 cellspacing=0>
</table>

<table id='Table_clientsList' align=center class="ssa_clistTable" cellpadding=0 cellspacing=0>
	<TR>
		<TD colspan=9 align=center>
			<FONT SIZE="+1" COLOR="#7B9CB7">
				<B>Clients List</B>
			</FONT>
			<?php
				$target_path = "../attachments/" ;
				$tmp = getDirectorySize($target_path);
				$currentsize = ceil  (( $tmp['size'] ) / ( 1024 * 1024 ))  ; 
				echo " - Total Disk usage ".$currentsize. " Mb";
			?>
		</TD>
	</TR>
	<tr>
		<td class="firstRow">Client Name <span class="bluebuttonSmall" buttonAction="newuser" onclick="clients_MiscFunctions.showNewClientForm()">New Client</span> </td>
		<td class="firstRow">subdomain</td>
		<td class="firstRow">Package</td>
		<td class="firstRow">Usage</td>
		<td class="firstRow">Admin Email</td>
		<td class="firstRow">Date Created</td>
		<td class="firstRow">Database Name</td>
		<td class="firstRow">Status</td>
		<td class="firstRow">&nbsp;</td>
	</tr>
<?php

	$tdclass = "evenrow";

	$sqlquery= "select subdomain, dbname, DATE_FORMAT(dateCreated, '%b %D') as shortdate, dateCreated, clientName, package, status, createdby from ".MASTERDB.".subdomains ORDER BY pid";
	$query = mysql_query($sqlquery) or die("Invalid query: " . mysql_error()); 
	IF( @mysql_num_rows($query) == 0 ){ // No log !
		echo '<TR><TD colspan=9 align=center>No Entries Found !</TD></TR>' ;
	}

	WHILE ($row = @mysql_fetch_array($query)){
		extract($row); // subdomain, dbname, dateCreated, clientName, package, status
			if( $subdomain == 'centerlimit' ){ continue; }

			if( $status == 'Y' ){ echo "<tr>" ; }
			if( $status == 'N' ){ echo "<tr class='inactive'>" ; }
			$tdclass = ( $tdclass == "evenrow" ) ? "oddrow" : "evenrow" ;

			$uname = executesql_returnArray("select variablevalue from ".$dbname.".sadmin where variable='sadminusername'" );
			$password = executesql_returnArray("select variablevalue from ".$dbname.".sadmin where variable='sadminpass'" );
			$curusers = executesql_returnArray("select count(username) as curnoofusers from ".$dbname.".users");
			$expensesCount = executesql_returnArray("select count(sno) as expensesCount from ".$dbname.".expenses " );
			$target_path = "../attachments/".$subdomain."/" ;
			$tmp = getDirectorySize($target_path);
			$currentsize = ceil(( $tmp['size'] ) / ( 1024 * 1024 ))  ;
			?>
			<td class="<?php echo $tdclass;?>" TITLE="<?php echo $uname."/".$password ; ?>"><?php echo $clientName ?></td>
			<td class="<?php echo $tdclass;?>">
				<?php 
					echo "<A href='http://".$subdomain.".".MAINDOMAIN."/' target='_blank'>".$subdomain."</A>" ;
				?>
			</td>
			<td class="<?php echo $tdclass;?>">
				<span class="pkgnumber"><?php echo $package ?></span>
			</td>
			<td class="<?php echo $tdclass;?>">
				<?php echo "$curusers users , $currentsize Mb , $expensesCount Expenses" ;?>
			</td>
			<td class="<?php echo $tdclass;?>">
			<?php 
					$sadminemail = executesql_returnArray("select variablevalue from ".$dbname.".sadmin where variable='sadminemail'" );
					echo $sadminemail ;
				?>
			</td>
			<td class="<?php echo $tdclass;?>" TITLE="<?php echo "$dateCreated - via $createdby " ?>"><?php echo $shortdate ?></td>
			<td class="<?php echo $tdclass;?>"><?php echo $dbname ?></td>
			<td class="<?php echo $tdclass;?>" onclick="clients_MiscFunctions.suspendActivate('<?php echo $subdomain ;?>');" style="cursor: pointer;" TITLE="Click to change Status of this Client">
				<?php 
					if( $status == 'Y' ){ echo "Active" ; }
					if( $status == 'N' ){ echo "<b>Suspended</b>" ;}
				?>
			</td>
			<td class="<?php echo $tdclass;?>">
				<IMG ALT="Delete this client account" src="../images/trash.gif" onclick="clients_MiscFunctions.deleteSubdomain('<?php echo $subdomain ;?>')">
			</td>
		</tr>
	<?php
	}
?>

</table>




<?php
	include ("include_footer.php");
?>
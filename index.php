<?php
require_once("common.php");

$is_form = false;
if ( isset($_POST["submit"]) && isset($_FILES["file"])) 
{
	$is_form = true;
	$errors = array();
   	if ($_FILES["file"]["error"] > 0) 
	{
        $errors[] = $_FILES["file"]["error"];
    }
    else
    {
    	$tmpName = $_FILES['file']['tmp_name'];
		$csv = array_map('str_getcsv', file($tmpName));
		$errors = ProcessCSVUpload($csv);
    }
}
?>

<html>
	<head>
	<style type="text/css">
	.success {color:green}
	.error {color:red}
	body, html, input, .std, h1, td, th {
		font-size: small;
		font-family: arial,sans-serif;
		color: #222222
	}
	td.details-control {
	    background: url('https://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
	    cursor: pointer;
	}
	tr.shown td.details-control {
	    background: url('https://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
	}
	</style>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/ju-1.12.1/jq-3.2.1/dt-1.10.16/datatables.min.css"/>
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/ju-1.12.1/jq-3.2.1/dt-1.10.16/datatables.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		
		// To display the data I used datatables (https://datatables.net/)
    	LoadGroups();
	} );
	
	function LoadGroups()
	{
		var table = $('#example').DataTable( {
        	ajax: "ajax_groups.php",
        	columns: [
        	{
                className:      'details-control',
                orderable:      false,
                data:           null,
                defaultContent: ''
            },
            { data: "group_id" },
            { data: "group_name" }]
    	} );
    	
    	// Add event listener for opening and closing details
	    $('#example tbody').on('click', 'td.details-control', function () {
	        var tr = $(this).closest('tr');
	        var row = table.row( tr );
	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            d = row.data();
	            row.child( FormatChildTable(d) ).show();
	            // Add sub-table
	            $('#group' + d.group_id).DataTable( {
			        data : d._people,
			        paging: false, searching:false, bInfo : false,
			        columns: [
			        	{ data: "first_name" },
			            { data: "last_name" },
			            { data: "email_address" }]
			    	} );
	            tr.addClass('shown');
	        }
	    } );
	}

	function FormatChildTable ( d ) {
	    // `d` is the original data object for the row
	    return '<div style="padding-left:150px;width:50%"><h4>Active People in group "' + d.group_name + '"</h4><table cellpadding="5" cellspacing="0" border="0" id="group' + d.group_id + '">'+
	        '<thead><tr><th>First Name</th><th>Last Name</th><th>Email Address</th></tr></thead></table></div>';
	}
	</script>
</head>
	<body>
		
	<?php
	if ($is_form)
	{
		if (count($errors) > 0) 
		{
			echo "<div class='error'><b>The following errors were detected:</b><br/>";
			foreach ($errors as $error) echo "{$error}<br/>";
			echo "</div>";
		}
		else
		{
			$num_rows = count($csv) - 1;
			echo "<div class='success'><b>Successfully processed {$num_rows} rows</b></div>";
		}
	}
	?>
	<h2>Groups And People</h2>
		
		<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
            	<th></th>
                <th>Group ID</th>
                <th>Group Name</th>
                
            </tr>
        </thead>
    </table>
<br><br>
<h3>Upload new groups or people</h3>
<form action="" method="post" enctype="multipart/form-data">

<table>
<tr>
<td width="20%">Select file</td>
<td width="80%"><input type="file" name="file" id="file" /></td>
</tr>

<tr>
<td></td>
<td><input type="submit" name="submit" value="Submit"/></td>
</tr>
</table>
</form>
	
	</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title>Event Calendar</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<br>
	<div class="container"> 
		<div class="row">
			<div class="col-md-5">
				<h4>Calendar</h4>
				<form id="form">
					<div class="form-group">
					    <label for="pwd">Event:</label>
					    <input type="text" class="form-control" id="event" required>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
					    	<label for="pwd">From:</label> <br>
					    	<input type="date" class="form-control" id="from" required>
						</div>
						<div class="form-group col-md-6">
					    	<label for="pwd">To:</label> <br>
					    	<input type="date" class="form-control" id="to" required>
						</div>
					</div>
					<input type="checkbox" id="mon"> Mon &nbsp;
					<input type="checkbox" id="tue"> Tue &nbsp;
					<input type="checkbox" id="wed"> Wed &nbsp;
					<input type="checkbox" id="thu"> Thu &nbsp;
					<input type="checkbox" id="fri"> Fri &nbsp;
					<input type="checkbox" id="sat"> Sat &nbsp;
					<input type="checkbox" id="sun"> Sun &nbsp;
					<br>
					<button type="submit" class="btn btn-primary">Save</button>
				</form>
			</div>
			<div class="col-md-7">
				<h4>Event List</h4>
				<div>
					<table class="table">
					  <thead>
					  </thead>
					  <tbody id="eventList">
					  </tbody>
					</table>
				</div>
			</div> 
		</div>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	$(document).ready(function(e) {
		index();
		$("#form").submit(function(e) {
	        e.preventDefault();
	        insert();
	    });
	});

	function insert() {
		var mon = $("#mon").is(":checked") ? "Mon" : "",
			tue = $("#tue").is(":checked") ? "Tue" : "",
			wed = $("#wed").is(":checked") ? "Wed" : "",
			thu = $("#thu").is(":checked") ? "Thu" : "",
			fri = $("#fri").is(":checked") ? "Fri" : "",
			sat = $("#sat").is(":checked") ? "Sat" : "",
			sun = $("#sun").is(":checked") ? "Sun" : "";

	    $.ajax({
	        type:'POST',
	        url: "/insert",
	        data: {
	        	"_token": "{{ csrf_token() }}",
	        	event: $("#event").val(),
	            from: $("#from").val(),
	            to: $("#to").val(),
	            days: [mon, tue, wed, thu, fri, sat, sun]
	        },
	    }).done(function(result){
	    	alert("Successfully Save");
	    	index();
	   	}); 
	};

	function index() {
		$.ajax({
			type: 'GET', 
			data: {"_token": "{{ csrf_token() }}"},
			url: "/index"
		}).done(function(result){
			var row = "";
			$.each(result, function (key, value) {
				row += "<h5>" + key + "</h5>";
				$.each(value, function (key2, value2) {
					var style = value2.event !== "" ? "background: green; color: white" : "";
					row += "<tr style='"+ style +"'>";
					row += "<td>" + value2.date + "</td>";
					row += "<td>" + value2.event + "</td>";
					row += "</tr>";
				});
   			});
			$("#eventList").html(row);
	   	});

		$('#form')[0].reset();
	}
</script> 
</body>
</html>

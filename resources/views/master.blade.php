<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="_token" content="{{ csrf_token() }}">
	<title>Visitor</title>
<!-- 	<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
	<link href="css/simple-sidebar.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" /> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script> -->

	<link href="css/bootstrap-datetimepicker.css" rel="stylesheet">
	<script src="js/bootstrap-datetimepicker.js"></script>
<style type="text/css">
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	input[type=number] {
		-moz-appearance: textfield;
	}

	html, body {
		height:100%;
	}
	body {
		display: flex;
		justify-content: center;
		flex-direction: column;
		text-align: center;
	}

	.toast_position {
		position: absolute; 
		top: 1rem; 
		right: 1rem;
	}
</style>
</head>
<body>
	<div class="d-flex" id="wrapper">
		<div class="bg-light border-right" id="sidebar-wrapper">
			<div class="sidebar-heading">Welcome <b>{{ $_COOKIE["MANAGERNAME"] ?? '' }}</b></div>
			<div class="list-group list-group-flush">
				<a href="/checkin" class="list-group-item list-group-item-action bg-primary active checkin_sidebar">Checkin</a>
				<a href="/visitors" class="list-group-item list-group-item-action bg-light visitor_sidebar">Visitor log</a>
				<a href="/blocks" class="list-group-item list-group-item-action bg-light block_sidebar">Block / Unit</a>
				<br>
				<br>
				<a href="#" class="list-group-item list-group-item-action bg-light" onclick="logout()">Logout</a>
			</div>
		</div>
		<div id="page-content-wrapper">
			<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
				<br><br>
				<button id="menu-toggle" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</nav>
			<div class="container">
				@yield('content')
			</div>
		</div>
	</div>

<div class="toast-container">
	<div class="toast d-flex align-items-center toast_position text-white" role="alert" aria-live="assertive" aria-atomic="true" data-delay="500">
		<div class="toast-body"></div>
		<button type="button" class="btn-close ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
	</div>
</div>
</body>
  <script>
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });

    $(document).ready(function(){
    	var path = window.location.pathname

    	if( path == "/blocks" ) {
    		$(".checkin_sidebar").removeClass("bg-primary")
    		$(".checkin_sidebar").removeClass("active")
    		$(".visitor_sidebar").removeClass("bg-primary")
    		$(".visitor_sidebar").removeClass("active")
    		$(".block_sidebar").removeClass("bg-light")

    		$(".block_sidebar").addClass("bg-primary")
    		$(".block_sidebar").addClass("active")
    	} else if( path == "/visitors" ) {
    		$(".checkin_sidebar").removeClass("bg-primary")
    		$(".checkin_sidebar").removeClass("active")
    		$(".block_sidebar").removeClass("bg-primary")
    		$(".block_sidebar").removeClass("active")
    		$(".visitor_sidebar").removeClass("bg-light")

    		$(".visitor_sidebar").addClass("bg-primary")
    		$(".visitor_sidebar").addClass("active")
    	} else {
    		$(".visitor_sidebar").removeClass("bg-primary")
    		$(".visitor_sidebar").removeClass("active")
    		$(".block_sidebar").removeClass("bg-primary")
    		$(".block_sidebar").removeClass("active")
    		$(".checkin_sidebar").removeClass("bg-light")

    		$(".checkin_sidebar").addClass("bg-primary")
    		$(".checkin_sidebar").addClass("active")
    	}

    	console.log(path)
    })

    function show_toast(prms)
    {
    	if( prms['message'] ) {
    		$(".toast-body").text(prms['message'])
    	}

    	if( prms['status'] ) {
    		$(".toast").addClass(prms['status'])
    	}
    	$(".toast").toast("show")
    }

    function logout()
    {
		$.ajax({
			type: "GET",
			url: "/logout",
			data: {
				"_token": $('meta[name="_token"]').attr('content')
			},
			success: function(data) {

				window.location.href = "/"
			}
		});
    }
  </script>
</html>

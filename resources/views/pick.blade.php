<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="_token" content="{{ csrf_token() }}">
  <title>Visitor</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  <link href="css/stylish-portfolio.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body id="">
	<div class="container-fluid">
		<header class="masthead d-flex">
			<div class="container text-center my-auto">
				<h1 class="mb-4">Visitor's Entry System</h1>
				<div class="row">
					<div class="col-3"></div>
					<div class="col-6">
						<div class="input-group form-floating w-100">
							<input type="text" class="form-control" id="name" placeholder="Enter your name" required aria-describedby="button-addon2">
							<label for="name"><p class="fw-light">Enter your name here</p></label>
							<button class="btn btn-lg btn-dark submit-btn" type="button" id="button-addon2">
								<span class="submit-text">Submit</span>
								<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
							</button>
							@csrf
						</div>
						<div class="invalid-feedback">
							Name is required.
						</div>
					</div>
					<div class="col-3"></div>
				</div>
			</div>
			<div class="overlay"></div>
		</header>
	</div>
</body>
<script type="text/javascript">
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })

    $(function() {
    	$.ajaxSetup({
    		headers: {
    			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
    		}
    	});
    });
})()

$(".submit-btn").on("click", function(){

	$(".spinner-border").removeClass("d-none");
	$(".submit-text").addClass("d-none");

	$.ajax({
	type: "POST",
	url: "/submit",
       data: {
       	"name": $("#name").val(),
       	"_token": $('meta[name="_token"]').attr('content')
       },
       success: function(data) {
           window.location.replace("/")
       }
   });
})

</script>
</html>

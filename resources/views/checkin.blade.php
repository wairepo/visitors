@extends('master')

@section('content')

<div class="card  mt-3">
	<div class="card-body">
		<div class="row">
			<div class="col-10">
				<div class="input-group">
				  <button class="btn btn-outline-secondary dropdown-toggle block_name" type="button" data-bs-toggle="dropdown" aria-expanded="false">Block</button>
				  <ul class="dropdown-menu">
				   	@foreach( $blocks as $block )
				  		<li><a class="dropdown-item" data-block-id="{{$block['id']}}" data-block-name="{{$block['name']}}" onclick="selectedBlock(this)">{{ $block['name'] }}</a></li>
				  	@endforeach
				  </ul>
				  <input type="text" id="level_unit" class="form-control form-control-lg has-validation" placeholder="09-8888">
				</div>
				 <p class="fw-lighter fs-6 text-sm-start">Search block unit to check availability</p>
			</div>
			<div class="col-2">
				<button type="button" class="form-control btn btn-lg btn-primary" id="search_block">Search</button>
			</div>
		</div>
		<div class="row">
			<div class="col-7">
				<table class="table table-bordered w-100">
					<tr>
						<td class="text-start">Block No</td>
						<td class="block_no">-</td>
					</tr>
					<tr>
						<td class="text-start">Level/Unit</td>
						<td class="level_unit">-</td>
					</tr>
					<tr>
						<td class="text-start">Owner name</td>
						<td class="owner_name">-</td>
					</tr>
					<tr>
						<td class="text-start">Phone</td>
						<td class="owner_phone">-</td>
					</tr>
					<tr>
						<td class="text-start">Max Occupancy</td>
						<td class="max_no">-</td>
					</tr>
				</table>
			</div>
			<div class="col-5">
				<ul class="list text-start error-list"></ul>
			</div>
		</div>
	</div>
</div>
<div class="card mt-3 visitor_info d-none">
	<div class="card-body">
		<h5 class="card-title text-start">Visitor's info</h5>
		<div class="mb-3">
			<div class="row">
				<div class="col-12">
					<input type="text" id="visitor_name" class="form-control-lg form-control" placeholder="Visitor's name">
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-6">
					<div class="form-floating">
						<input type="number" class="form-control" id="phone" name="phone">
						<label for="phone">Phone*</label>
						<div class="invalid-feedback text-start phone_error"></div>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating">
						<input type="number" class="form-control" id="nric" name="nric" maxlength="1">
						<label for="nric">NRIC Nn*</label>
						<div class="invalid-feedback text-start nric_error"></div>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-6">
					<div class="form-floating">
						<input type="text" class="form-control form-control-lg" id="checkin" readonly> 
						<label for="nric">Entry Checkin</label>
						<div class="invalid-feedback text-start nric_error"></div>
					</div>
				</div>
				<div class="col-6">
					<div class="form-floating">
						<input type="text" class="form-control form-control-lg" id="checkout" disabled> 
						<label for="nric">Entry Checkout</label>
						<div class="invalid-feedback text-start nric_error"></div>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
						<label class="form-check-label text-start w-100" for="flexCheckChecked">
							Set checkout time now?
						</label>
					</div>
				</div>
			</div>
			<div class="row mt-3 text-end">
				<div class="col-12">
					<button type="button" class="btn btn-link" id="btnReset">Reset</button>
					<button class="btn btn-lg btn-primary" type="button" id="btncheckin">
						<span class="submit-text">Checkin</span>
						<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
					</button>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<ul class="list text-start error-list-visitor"></ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	var block_id = unit_id = count_failed = 0;
	var block_name = "";

	$(document).ready(function(){
		
		let today = new Date()
		var dd = today.getDate();
		var mm = today.getMonth()+1;
		var yy = today.getFullYear();
		var hh = today.getHours();
		var min = today.getMinutes();

		$("#checkin").val(yy + "-" + mm + "-" + dd + " " + hh + ":" + min)
		
		$('#checkin').datetimepicker({
			todayBtn: true,
			pickerPosition: "top-right"
		});

		$('#checkout').datetimepicker({
			todayBtn: true,
			pickerPosition: "top-right"
		});
	})

	$(document).on("click", "#flexCheckChecked", function(){
		if( $(this).prop("checked") === true ) {
			$("#checkout").prop( "disabled", false );
		} else {
			$("#checkout").val("")
			$("#checkout").prop( "disabled", true );
		}
	})

	$("#nric").on("keydown", function(e){
		var keys = [8, 9, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];
		
		if( $.inArray(e.keyCode, keys) == -1) {
			if( this.value.length >= 3 ) {
				show_toast({"message": "Allow 3 digit only", "status": "bg-warning"})
				e.preventDefault();
				return false
			}
		}
	})

	$("#phone").on("keydown", function(e){
		var keys = [8, 9, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];

		if( $.inArray(e.keyCode, keys) == -1) {
			if( this.value.length >= 8 ) {
				show_toast({"message": "Phone number format required", "status": "bg-warning"})
				e.preventDefault();
				return false
			}
		}
	})

	$("#btnReset").on("click", function(){
		$(".visitor_info").addClass("d-none")
		reset()
		clearError()
	})

	function reset()
	{
		$("#phone").val("")
		$("#nric").val("")
		$("#level_unit").val("")
	}

	function clearError()
	{
		$("#phone").removeClass("is-invalid")
		$("#nric").removeClass("is-invalid")
		$(".nric_error").text("")
		$(".phone_error").text("")
	}

	$("#btncheckin").on("click", function(){

		$(".spinner-border").removeClass("d-none");
		$(".submit-text").addClass("d-none");

		if( count_failed == 5 ) {
			show_toast({"message": "Refreshing page", "status": "bg-info"})
			window.location.href = "/"
			return false;
		}

		clearError()

		if( block_id == 0 || unit_id == 0 ) {
			count_failed+=1;
			show_toast({"message": "No block / unit selected", "status": "bg-warning"})
			return false;
		}

		if( $("#phone").val() == "" ) {
			$("#phone").addClass("is-invalid")
			$(".phone_error").text("Phone number is required.")
			$(".spinner-border").addClass("d-none");
	       	$(".submit-text").removeClass("d-none");
			return false;
		}

		if( $("#nric").val() == "" ) {
			$("#nric").addClass("is-invalid")
			$(".nric_error").text("NRIC number is required.")
			$(".spinner-border").addClass("d-none");
	       	$(".submit-text").removeClass("d-none");
			return false;
		}

		if( $("#nric").val().length != 3 ) {
			$("#nric").addClass("is-invalid")
			$(".nric_error").text("NRIC number format is incorrect.")
			$(".spinner-border").addClass("d-none");
	       	$(".submit-text").removeClass("d-none");
			return false;
		}

		$.ajax({
		type: "POST",
		url: "/api/visitors/new",
	       data: {
	       	"checkin": true,
	       	"name": $("#visitor_name").val(),
	       	"phone": $("#phone").val(),
	       	"nric_no": $("#nric").val(),
	       	"block_id": block_id,
	       	"unit_id": unit_id,
	       	"checkinDate": $("#checkin").val(),
	       	"checkoutDate": $("#flexCheckChecked").prop("checked") == true ? $("#checkout").val() : null,
	       	"_token": $('meta[name="_token"]').attr('content')
	       },
	       success: function(data) {

	       	$(".spinner-border").addClass("d-none");
	       	$(".submit-text").removeClass("d-none");

	       	if( data['success'] == true ) {

	       		window.location.href = "/visitors"

	       	} else {
	       		if( typeof data['message'] === 'object' ) {

	       			var html = ""
	       			
	       			$(".error-list-visitor").empty()

	       			Object.values(data['message']).forEach(function (item, index) {

	       				html += "<li class='text-danger'>" + item + "</li>"
	       			});

	       			$(".error-list-visitor").append(html)

	       		} else {
	       			show_toast({"message": data['message'], "status": "bg-danger"})
	       		}
	       	}
	       }
	   });
	})

	$("#search_block").on("click", function(){

		if( block_id == 0 || $("#level_unit").val() == "" ) {
			show_toast({"message": "No block / unit selected", "status": "bg-warning"})
			return false;
		}

		$(".visitor_info").addClass("d-none")
		$("#phone").val("")
		$("#nric").val("")

		$.ajax({
		type: "GET",
		url: "/api/units/search",
	       data: {
	       	"block_id": block_id,
	       	"level_unit": $("#level_unit").val(),
	       	"_token": $('meta[name="_token"]').attr('content')
	       },
	       success: function(data) {

	       	if( data['success'] == true ) {
	       		show_toast({"message": "Get details successfully.", "status": "bg-success"})

	       		block_id = data['data']['block_id']
	       		unit_id = data['data']['id']

	       		$(".block_no").text(block_name)
	       		$(".level_unit").text(data['data']['level'] + " / " + data['data']['unit'] )
	       		$(".owner_name").text(data['data']['occupant_name'])
	       		$(".owner_phone").text(data['data']['phone'])
	       		$(".max_no").text(data['data']['occupancy'])

	       		$(".visitor_info").removeClass("d-none")
	       	} else {

	       		if( typeof data['message'] === 'object' ) {

	       			var html = ""
	       			
	       			$(".error-list").empty()

	       			Object.values(data['message']).forEach(function (item, index) {

	       				html += "<li class='text-danger'>" + item + "</li>"
	       			});

	       			$(".error-list").append(html)

	       		} else {
	       			show_toast({"message": data['message'], "status": "bg-danger"})
	       		}
	       	}
	       }
	   });
	})

	function selectedBlock(data)
	{
		block_id = $(data).data("block-id")
		block_name = $(data).data("block-name")
		$(".block_name").text($(data).data("block-name"))
	}
</script>
@stop
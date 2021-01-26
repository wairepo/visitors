@extends('master')

@section('content')

<div class="row mt-3">
	<label class="text-start">Block filter: </label>
	<div class="col-sm-6 text-start" id="block_list">
			<span class="mt-1 mb-1 ml-1 badge rounded-pill btn {{$block_selected == 0? 'bg-primary' : 'bg-secondary'}} block_select" data-id="0">All</span>
		@foreach( $blocks as $block )
			<span class="mt-1 mb-1 ml-1 badge rounded-pill btn {{$block_selected == $block['id']? 'bg-primary' : 'bg-secondary'}} block_select" data-id="{{$block['id']}}">{{$block['name']}}</span>
		@endforeach
	</div>
	<div class="col-sm-6 text-end"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newBlockModal">New</button></div>
</div>

<div class="card mt-3">
	<div class="card-body">
		<table class="table table-hover">
			<thead>
				<th>Owner</th>
				<th>Phone</th>
				<th>Block</th>
				<th>Unit</th>
				<th>No. of Visitor visited</th>
				<th>Total Occupancy</th>
				<th></th>
			</thead>
			<tbody>
				@if( !empty($units) )

					@foreach( $units as $block )
						<tr>
							<td class="occupant_name" data-id="{{$block['id']}}">{{$block['occupant_name']}}</td>
							<td class="phone" data-id="{{$block['id']}}">{{$block['phone']}}</td>
							<td>{{$block['block_name']}}</td>
							<td>#{{$block['level']}}-{{$block['unit']}}</td>
							<td>{{$block['visitorEntryCount']}}</td>
							<td>{{$block['occupancy']}}</td>
							<td class="text-end"><i data-bs-toggle="modal" data-bs-target="#blockModal" data-id="{{$block['id']}}" data-phone="{{$block['phone']}}" data-occupant-name="{{$block['occupant_name']}}" data-bs-title="{{$block['block_name']}} - #{{$block['level']}}-{{$block['unit']}}" class="btn btn-sm far fa-edit"></i><i data-bs-toggle="modal" data-bs-target="#deleteBlockModal" data-id="{{$block['id']}}" data-phone="{{$block['phone']}}" data-occupant-name="{{$block['occupant_name']}}" data-bs-title="{{$block['block_name']}} - #{{$block['level']}}-{{$block['unit']}}" class="btn btn-sm far fa-trash-alt"></i></td>
						</tr>
					@endforeach

				@else

					<tr>
						<td colspan="5">No record found.</td>
					</tr>

				@endif
			</tbody>
		</table>
		@if( !empty($units) )
			<nav aria-label="Page navigation">
				<ul class="pagination justify-content-center">

					@php
					$i = 1;
					@endphp
					
					@while($i <= $pages)
						<li class="page-item {{$page_no == $i? 'active' : ''}} next_page" data-page-no="{{$i}}"><a class="page-link" href="#">{{$i}}</a></li>
						@php
						$i++;
						@endphp
					@endwhile

				</ul>
			</nav>
		@endif
	</div>
</div>
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Block</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="form-floating mb-3">
							<input type="text" class="form-control" id="name">
							<label for="name">Owner name</label>
							<div class="invalid-feedback text-start name_error"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="form-floating mb-3">
							<input type="number" class="form-control" id="phone">
							<label for="phone">Phone</label>
							<div class="invalid-feedback text-start phone_error"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<ul class="list text-start error-list"></ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveBtn">
					<span class="submit-text">Save</span>
					<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="deleteBlockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Block Unit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-start"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="deleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="newBlockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Block</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<div class="input-group">
							<button class="btn btn-outline-secondary dropdown-toggle block_name_new" type="button" data-bs-toggle="dropdown" aria-expanded="false">Block</button>
							<ul class="dropdown-menu">
								@foreach( $blocks as $block )
								<li><a class="dropdown-item" data-block-id-new="{{$block['id']}}" data-block-name-new="{{$block['name']}}" onclick="selectedBlock(this)">{{ $block['name'] }}</a></li>
								@endforeach
							</ul>
							<input type="text" id="level_unit" class="form-control form-control-lg has-validation" placeholder="09-8888">
						</div>
					</div>
				</div>
				<div class="row mt-5" >
					<div class="col-6">
						<div class="form-floating mb-3">
							<input type="text" class="form-control" id="name_new">
							<label for="name_new">Owner name</label>
							<div class="invalid-feedback text-start name_new_error"></div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-floating mb-3">
							<input type="number" class="form-control" id="phone_new">
							<label for="phone_new">Phone</label>
							<div class="invalid-feedback text-start phone_new_error"></div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<ul class="list text-start error-list"></ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="newBtn">
					<span class="submit-text">Save</span>
					<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	var block_id = block_id_new = 0;
	var block_name_new = '';
	var unit_id = 0;
	var page = "{{$page_no}}";

	$(document).ready(function(){
		var blockModal = document.getElementById('blockModal')
		blockModal.addEventListener('show.bs.modal', function (event) {
			var button = event.relatedTarget
			var title = button.getAttribute('data-bs-title')
			var phone = button.getAttribute('data-phone')
			var occupant_name = button.getAttribute('data-occupant-name')
			unit_id = button.getAttribute('data-id')

			var modalTitle = blockModal.querySelector('.modal-title')

			modalTitle.textContent = 'Block ' + title

			$("#name").val(occupant_name);
			$("#phone").val(phone);

			clearError()
		})

		var deleteBlockModal = document.getElementById('deleteBlockModal')
		deleteBlockModal.addEventListener('show.bs.modal', function (event) {
			var button = event.relatedTarget
			var title = button.getAttribute('data-bs-title')
			unit_id = button.getAttribute('data-id')

			var modalBodyInput = deleteBlockModal.querySelector('.modal-body p')
			modalBodyInput.textContent = "Confirm to delete this block " + title + "?"
		})

		var newBlockModal = document.getElementById('newBlockModal')
		newBlockModal.addEventListener('show.bs.modal', function (event) {
			clearError()
		})
	})

	$('.next_page').on('click', function() {

		window.location.href = "/blocks?page="+$(this).data("page-no")+"&block="+block_id
	});

	$('.block_select').on('click', function() {

		$("span").remove(".bg-primary")

		block_id = $(this).data("id")

		window.location.href = "/blocks?page=1&block="+block_id
	});

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

	$("#phone_new").on("keydown", function(e){
		var keys = [8, 9, 16, 17, 18, 19, 20, 27, 33, 34, 35, 36, 37, 38, 39, 40, 45, 46, 144, 145];

		if( $.inArray(e.keyCode, keys) == -1) {
			if( this.value.length >= 8 ) {
				show_toast({"message": "Phone number format required", "status": "bg-warning"})
				e.preventDefault();
				return false
			}
		}
	})

	$("#deleteBtn").on("click", function(e){
		$.ajax({
			type: "PUT",
			url: "/api/units/delete/"+unit_id,
			data: {
				"_token": $('meta[name="_token"]').attr('content')
			},
			success: function(data) {

				if( data['success'] == true ) {

					show_toast({"message": data['message'], "status": "bg-success"})

					window.location.href = "/blocks?page="+page+"&block="+block_id

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

	function clearError()
	{
		$("#phone").removeClass("is-invalid")
		$("#name").removeClass("is-invalid")
		$(".name_error").text("")
		$(".phone_error").text("")

		$("#phone_new").removeClass("is-invalid")
		$("#name_new").removeClass("is-invalid")		
		$(".name_error_new").text("")
		$(".phone_error_new").text("")
	}

	$("#saveBtn").on("click", function(){

		clearError()

		if( $("#name").val() == "" ) {
			$("#name").addClass("is-invalid")
			$(".name_error").text("Owner name is required.")
			$(".spinner-border").addClass("d-none");
			$(".submit-text").removeClass("d-none");
			return false;
		}

		if( $("#phone").val() == "" ) {
			$("#phone").addClass("is-invalid")
			$(".phone_error").text("Phone number is required.")
			$(".spinner-border").addClass("d-none");
			$(".submit-text").removeClass("d-none");
			return false;
		}

		$.ajax({
			type: "PUT",
			url: "/api/units/edit/"+unit_id,
			data: {
				"occupant_name": $("#name").val(),
				"phone": $("#phone").val(),
				"_token": $('meta[name="_token"]').attr('content')
			},
			success: function(data) {

				$(".spinner-border").addClass("d-none");
				$(".submit-text").removeClass("d-none");

				if( data['success'] == true ) {

					show_toast({"message": data['message'], "status": "bg-success"})

					window.location.href = "/blocks?page="+page+"&block="+block_id

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

	$("#newBtn").on("click", function(){

		clearError()

		if( $("#name_new").val() == "" ) {
			$("#name_new").addClass("is-invalid")
			$(".name_error").text("Owner name is required.")
			$(".spinner-border").addClass("d-none");
			$(".submit-text").removeClass("d-none");
			return false;
		}

		if( $("#phone_new").val() == "" ) {
			$("#phone_new").addClass("is-invalid")
			$(".phone_error").text("Phone number is required.")
			$(".spinner-border").addClass("d-none");
			$(".submit-text").removeClass("d-none");
			return false;
		}

		$.ajax({
			type: "POST",
			url: "/api/units/new/",
			data: {
				"block_id": block_id_new,
				"level_unit": $("#level_unit").val(),
				"occupant_name": $("#name_new").val(),
				"phone": $("#phone_new").val(),
				"_token": $('meta[name="_token"]').attr('content')
			},
			success: function(data) {

				$(".spinner-border").addClass("d-none");
				$(".submit-text").removeClass("d-none");

				if( data['success'] == true ) {

					show_toast({"message": data['message'], "status": "bg-success"})

					window.location.href = "/blocks?page="+page+"&block="+block_id

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
		block_id_new = $(data).data("block-id-new")
		block_name_new = $(data).data("block-name-new")
		$(".block_name_new").text($(data).data("block-name-new"))
	}

</script>
@stop
@extends('master')

@section('content')

<div class="row mt-3">
	<div class="col-sm-4">
		<div class="input-group mb-3">
			<button class="btn btn-outline-secondary dropdown-toggle block_name" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{$block_name ?? "Block"}}</button>
			<ul class="dropdown-menu">
				@foreach( $blocks as $block )
				  	<li><a class="dropdown-item" data-block-id="{{$block['id']}}" data-block-name="{{$block['name']}}" onclick="selectedBlock(this)">{{ $block['name'] }}</a></li>
				@endforeach
			</ul>
			<input type="text" id="level_unit" class="form-control" placeholder="Level/Unit No" value="{{$unit_name ?? ''}}">
		</div>
	</div>
</div>
<div class="row text-end">
	<div class="col-12">
		<button type="button" class="btn btn-link" id="resetBtn">Reset</button>
		<button type="button" class="btn btn-primary" id="searchBtn">Search</button>
	</div>
</div>

<div class="card mt-3">
	<div class="card-body">
		<table class="table table-hover">
			<thead>
				<th>Visitor name</th>
				<th>Phone</th>
				<th>Block / Unit</th>
				<th>Checkin</th>
				<th>Checkout</th>
			</thead>
			<tbody>

				@if( !empty($visitors) )

					@foreach( $visitors as $visitor )
					<tr>
						<td>{{$visitor['id']}}</td>
						<td>{{$visitor['phone']}}</td>
						<td>{{$visitor['block_name']}} - #{{$visitor['level']}}-{{$visitor['unit']}}</td>
						<td>{{$visitor['checkin']}}</td>
						@if( empty($visitor['checkout']) )
							<td><a id="checkout_now" data-id="{{$visitor['id']}}" href="#">Checkout now</a></td>
						@else
							<td class="show_checkin checkinOutput{{$visitor['id']}}" data-id="{{$visitor['id']}}"><span class="checkout_text">{{$visitor['checkout']}}</span>&nbsp;<a href="#"><i class="fas fa-edit"></i></a></td>
							<td class="d-none checkinInput{{$visitor['id']}}"><input type="text" class="form-control form-control-sm" id="checkout" readonly><a class="hide_checkin" data-id="{{$visitor['id']}}" href="#"><i class="fas fa-times btn-danger text-white"></i></a>&nbsp;<a class="edit_checkout" data-id="{{$visitor['id']}}" href="#"><i class="fas fa-check btn-success text-white"></i></a></td>
						@endif
					</tr>
					@endforeach

				@else

					<tr>
						<td colspan="5">No record found.</td>
					</tr>

				@endif
			</tbody>
		</table>
		@if( !empty($visitors) )
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
<script type="text/javascript">

	var block_id = 0;
	var block_name = "";
	var unit_id = "{{$unit ?? 0}}";
	var page = "{{$page_no}}";

	$(document).ready(function(){
		
		let today = new Date()
		var dd = today.getDate();
		var mm = today.getMonth()+1;
		var yy = today.getFullYear();
		var hh = today.getHours();
		var min = today.getMinutes();

		$("#checkout").val(yy + "-" + mm + "-" + dd + " " + hh + ":" + min)
		
		$('#checkout').datetimepicker({
			todayBtn: true,
			pickerPosition: "top-right"
		});
	})

	$(".show_checkin").on("click", function(){
		$(this).addClass("d-none");
		$(".checkinInput"+$(this).data("id")).removeClass("d-none");
	})

	$(".hide_checkin").on("click", function(){
		$(".checkinInput"+$(this).data("id")).addClass("d-none");
		$(".checkinOutput"+$(this).data("id")).removeClass("d-none");
	})

	$(".edit_checkout").on("click", function(){

		updateCheckout($(this).data("id"), $("#checkout").val())
		$(".checkout_text").text($("#checkout").val())
	})

	$("#checkout_now").on("click", function(){
		updateCheckout($(this).data("id"), $("#checkout").val())
		window.location.href = "/visitors?unit="+unit_id+"&page="+page+"&block_name="+block_name+"&unit_name="+$("#level_unit").val()
	})

	function updateCheckout(id, checkout)
	{
		$.ajax({
			type: "PUT",
			url: "/api/visitors/edit/"+id,
			data: {
				"checkout": checkout,
				"_token": $('meta[name="_token"]').attr('content')
			},
			success: function(data) {
				if( data['success'] == true ) {
					
					$(".hide_checkin").trigger("click")
					show_toast({"message": data['message'], "status": "bg-success"})
				} else {
					show_toast({"message": data['message'], "status": "bg-danger"})
					return false
				}
			}
		});
	}

	function searchUnit()
	{
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
					block_id = data['data']['block_id']
					unit_id = data['data']['id']

					window.location.href = "/visitors?unit="+unit_id+"&page="+page+"&block_name="+block_name+"&unit_name="+$("#level_unit").val()
				} else {
					window.location.href = "/visitors?unit=0&page="+page+"&block_name="+block_name+"&unit_name="+$("#level_unit").val()
				}
			}
		});
	}

	$("#resetBtn").on("click", function(){
		window.location.href = "/visitors"
	})

	$("#searchBtn").on("click", function(){
		searchUnit();
	})

	$('.next_page').on('click', function() {
		window.location.href = "/visitors?unit="+unit_id+"&page="+$(this).data("page-no")+"&block_name="+block_name+"&unit_name="+$("#level_unit").val()
	});

	function selectedBlock(data)
	{
		block_id = $(data).data("block-id")
		block_name = $(data).data("block-name")
		$(".block_name").text($(data).data("block-name"))
	}

	$('#checkin').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
	});

	$('#checkout').on('apply.daterangepicker', function(ev, picker) {
		$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
	});

	$('#checkin').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

	$('#checkout').on('cancel.daterangepicker', function(ev, picker) {
		$(this).val('');
	});

	$(document).on("click", "#flexCheckChecked", function(){
		if( $(this).prop("checked") === true ) {
			$("#checkout").prop( "disabled", true );
			$("#checkout").val("")
		} else {
			$("#checkout").prop( "disabled", false );
		}
	})
</script>
@stop
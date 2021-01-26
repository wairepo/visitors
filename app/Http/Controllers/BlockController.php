<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Block;
use App\Models\BlockUnit;
use App\Models\VisitorEntry;

class BlockController extends Controller
{

  public static function list(Request $request)
  {

  	$limit = 5;
    $page = isset($request['page']) ? $request['page'] : 1;

    $blocks = Block::orderBy('name', 'asc')->limit(50)->get();

    if( isset($request['block']) && $request['block'] != 0 ) {
      $units = BlockUnit::where("is_deleted", 0)->where("block_id", $request['block'])->orderBy('id', 'desc')->limit($limit)->offset(($request['page']-1) * $limit)->get();
    } else {
      $units = BlockUnit::where("is_deleted", 0)->orderBy('id', 'desc')->limit($limit)->offset(($request['page']-1) * $limit)->get();
    }

    if( isset($request['block']) && $request['block'] != 0 ) {
      $total_records = BlockUnit::where("is_deleted", 0)->where("block_id", $request['block'])->count();
    } else {
      $total_records = BlockUnit::where("is_deleted", 0)->count();
    }

    $result = [];

    foreach ($units as $key => $value) {
    	$visitorEntryCount = $value->visitorEntries()->count();
    	$block = $value->block()->first();

    	$params = [

    		"id"	=> $value['id'],
    		"block_id" => $block['id'],
    		"block_name" => $block['name'],
    		"occupant_name" => $value['occupant_name'],
    		"phone" => $value['phone'],
    		"level" => $value['level'],
    		"unit" => $value['unit'],
    		"occupancy" => $value['occupancy'],
    		"visitorEntryCount" => $visitorEntryCount
    	];

    	array_push($result, $params);
    }

    $data['units'] = $result;
    $data['blocks'] = $blocks;
    $data['pages'] = ceil($total_records / $limit);
    $data['page_no'] = $page;
    $data['block_selected'] = $request['block'];

    return view('block', $data);

  }

  public function edit(Request $request, $block_id)
  {
    $validator = Validator::make($request->all(), [
      'block_name' => 'required|max:20'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    if( !isset($block_id) ) {
      return response()->json([ "success" => false, "message" => "Block ID not found" ]);
    }

    $block = Block::find($block_id);

    if( empty($block) ) {
      return response()->json([ "success" => false, "message" => "Block not found in the system" ]);
    }

    $updated = $block->update([
      "name" => $request['block_name'],
    ]);

    if( $updated ) {
      return response()->json([ "success" => true, "message" => "Block updated successully" ]);
    }

    return response()->json([ "success" => false, "message" => "Block failed to update. Try again." ]);
  }
}
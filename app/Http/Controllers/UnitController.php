<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Visitor;
use App\Models\Block;
use App\Models\BlockUnit;
use App\Models\VisitorEntry;

class UnitController extends Controller
{

  public function list(Request $request)
  {
    $visitors = Visitor::all();

    return response()->json($visitors);
  }

  public function search(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'block_id' => 'required|integer',
      'level_unit' => 'required'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    $level_unit = explode("-", $request['level_unit']);

    if( !isset($level_unit[0]) || !isset($level_unit[1]) ) {
      return response()->json([ "success" => false, "message" => "Block Unit format incorrect" ]);
    }

    $blockUnit = BlockUnit::where([["block_id", $request['block_id']], ["level", (int)$level_unit[0]], ["unit", $level_unit[1]]])->first();

    if( $blockUnit ) {
      return response()->json([ "success" => true, "data" => $blockUnit ]);
    }
    
    return response()->json([ "success" => false, "message" => "Block Unit not found." ]);
  }

  public function create(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'block_id' => 'required',
      'level_unit' => 'required',
      'occupant_name' => 'required|max:80',
      'phone' => 'required|integer'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    $level_unit = explode("-", $request['level_unit']);

    if( !isset($level_unit[0]) || !isset($level_unit[1]) ) {
      return response()->json([ "success" => false, "message" => "Block Unit format incorrect" ]);
    }

    $block = Block::find($request['block_id']);

    $unit = BlockUnit::where([["block_id", $block['id']], ["level", $level_unit[0]], ["unit", $level_unit[1]]])->get();

    if( count($unit) > 0 ) {
      return response()->json([ "success" => false, "message" => "Block unit already exists" ]);
    }

    if( empty($block) ) {
      $block = Block::create([
        "name" => $request['block_id']
      ]);
    }

    $unitCreated = BlockUnit::create([
      "block_id" => $block['id'],
      "level" => $level_unit[0],
      "unit" => $level_unit[1],
      "occupant_name" => $request['occupant_name'],
      "phone" => $request['phone']
    ]);

    if( $unitCreated ) {
      return response()->json([ "success" => true, "message" => "Block unit created successully." ]);
    }

    return response()->json([ "success" => false, "message" => "Block unit failed to create." ]);
  }

  public function retrieve($id)
  {
    if( !isset($id) ) {
      return response()->json([ "success" => false, "message" => "Block unit ID not found" ]);
    }

    $unit = BlockUnit::where("is_deleted", 0)->find($id);

    if( empty($unit) ) {
      return response()->json([ "success" => false, "message" => "Block unit not found in the system" ]);
    }

    $unit['no_of_visitors'] = $unit->visitorEntries()->Where('checkout', null)->orWhere("checkout", ">=", date("Y-m-d H:i:s"))->count();
    $unit['block_name'] = $unit->block()->first()['name'];

    return response()->json([ "success" => true, "data" => $unit ]);
  }

  public function edit(Request $request, $unit_id)
  {
    $validator = Validator::make($request->all(), [
      'occupant_name' => 'required|max:80',
      'phone' => 'required|integer'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    if( !isset($unit_id) ) {
      return response()->json([ "success" => false, "message" => "Block unit ID not found" ]);
    }

    $unit = BlockUnit::where("is_deleted", 0)->find($unit_id);

    if( empty($unit) ) {
      return response()->json([ "success" => false, "message" => "Block unit not found in the system" ]);
    }

    $updated = $unit->update([
      "occupant_name" => $request['occupant_name'],
      "phone" => $request['phone']
    ]);

    if( $updated ) {
      return response()->json([ "success" => true, "message" => "Block unit updated successully" ]);
    }

    return response()->json([ "success" => false, "message" => "Block unit failed to update. Try again." ]);
  }

  public function delete($unit_id)
  {

    return response()->json(date("Y-m-d H:i:s",strtotime("-3 Months")));

    if( !isset($unit_id) ) {
      return response()->json([ "success" => false, "message" => "Block unit ID not found" ]);
    }

    $unit = BlockUnit::where("is_deleted", 0)->find($unit_id);

    if( empty($unit) ) {
      return response()->json([ "success" => false, "message" => "Block unit not found in the system" ]);
    }

    $updated = $unit->update([
      "is_deleted" => 1,
      "deleted_at" => date("Y-m-d H:i:s")
    ]);

    if( $updated ) {
      return response()->json([ "success" => true, "message" => "Block unit deleted successully" ]);
    }
  }
}
































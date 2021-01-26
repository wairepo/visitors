<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Visitor;
use App\Models\Block;
use App\Models\VisitorEntry;

class VisitorController extends Controller
{

  public function list(Request $request)
  {

    $limit = 5;
    $page = isset($request['page']) ? $request['page'] : 1;

    if( isset($request['unit']) && $request['unit'] != 0 ) {
      $visitors = VisitorEntry::where("unit_id", $request['unit'])->whereBetween("checkin", [date("Y-m-d H:i:s",strtotime("-3 Months")), date("Y-m-d H:i:s")])->orderBy('id', 'asc')->limit($limit)->offset(($page-1) * $limit)->get();
    } else {
      $visitors = VisitorEntry::whereBetween("checkin", [date("Y-m-d H:i:s",strtotime("-3 Months")), date("Y-m-d H:i:s")])->orderBy('id', 'asc')->limit($limit)->offset(($request['page']-1) * $limit)->get();
    }

    $blocks = Block::orderBy('name', 'asc')->limit(50)->get();

    if( isset($request['unit']) && $request['unit'] != 0 ) {
      $total_records = VisitorEntry::where("unit_id", $request['unit'])->whereBetween("checkin", [date("Y-m-d H:i:s",strtotime("-3 Months")), date("Y-m-d H:i:s")])->count();
    } else {
      $total_records = VisitorEntry::whereBetween("checkin", [date("Y-m-d H:i:s",strtotime("-3 Months")), date("Y-m-d H:i:s")])->count();
    }

    $result = [];

    foreach ($visitors as $key => $value) {

      $visitor = $value->visitor()->first();
      $unit = $value->first()->blockUnit()->first();
      $block = $unit->first()->block()->first();

      $params = [
        "id" => $value['id'],
        "visitor_name" => $visitor['name'],
        "phone" => $visitor['phone'],
        "nric_no" => $visitor['nric_no'],
        "checkin" => $value['checkin'],
        "checkout" => $value['checkout'],
        "unit" => $unit['unit'],
        "level" => $unit['level'],
        "block_name" => $block['name'],
      ];

      array_push($result, $params);

    }

    $data['visitors'] = $result;
    $data['blocks'] = $blocks;
    $data['pages'] = ceil($total_records / $limit);
    $data['page_no'] = $page;
    $data['unit'] = isset($request['unit']) ? $request['unit'] : 0;
    $data['block_name'] = isset($request['block_name']) ? $request['block_name'] : "Block";
    $data['unit_name'] = isset($request['unit_name']) ? $request['unit_name'] : "";

    return view("visitor", $data);
  }

  public function createCheckin(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'phone' => 'required',
      'nric_no' => 'required|digits:3'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    if( date_create($request['checkoutDate']) == false ) {
      return response()->json([ "success" => false, "message" => "Checkout date format is incorrect." ]);
    }

    $request['checkinDate'] = isset($request['checkinDate']) && date_create($request['checkinDate']) != false ? date_format(date_create($request['checkinDate']), 'Y-m-d H:i:s') : date("Y-m-d H:i:s");

    if( strtotime(date_format(date_create($request['checkinDate']), 'Y-m-d H:i:s')) > strtotime(date_format(date_create($request['checkoutDate']), 'Y-m-d H:i:s')) ) {
      return response()->json([ "success" => false, "message" => "Checkout datetime is incorrect." ]);
    }

    if( isset($request['checkin']) && $request['checkin'] == true ) {
      if( !isset($request['block_id']) || !isset($request['unit_id']) ) {
        return response()->json([ "success" => false, "message" => "Block unit not found in the system." ]);
      }

      $block = Block::find($request['block_id']);

      if( $block ) {

        $unit = $block->blockUnits()->where("is_deleted", 0)->get()->find($request['unit_id']);

        if( !$unit ) {
          return response()->json([ "success" => false, "message" => "Block unit not found in the system." ]);
        }
      }
    }

    $visitor = Visitor::where([["phone", $request['phone']], ["nric_no", $request['nric_no']]])->first();

    if( empty($visitor) ) {

      $visitor = Visitor::create([
        "name" => $request['name'],
        "phone" => $request['phone'],
        "nric_no" => $request['nric_no']
      ]);
    } else {
      if( isset($request['checkin']) && $request['checkin'] != true ) {
        return response()->json([ "success" => true, "message" => "Visitor's account already exists." ]);
      }
    }

    if( isset($request['checkin']) && $request['checkin'] == true ) {

      $entryWithNull = VisitorEntry::where([["unit_id", $unit['id']], ['checkout', null]])->get();
      $entryWithoutNull = VisitorEntry::where([["unit_id", $unit['id']], ["checkout", ">=", date("Y-m-d H:i:s")]])->get();

      if( (count($entryWithNull) + count($entryWithoutNull)) >= $unit['occupancy'] ) {
        return response()->json([ "success" => false, "message" => "Exceed number of visitors of the unit #" . $unit['level'] . "-" . $unit['unit'] ]);
      }

      $visitorEntryWithNull = VisitorEntry::where([["unit_id", $unit['id']], ['visitor_id', $visitor['id']], ['checkout', null]])->get();
      $visitorEntryWithoutNull = VisitorEntry::where([["unit_id", $unit['id']], ['visitor_id', $visitor['id']], ["checkout", ">=", date("Y-m-d H:i:s")]])->get();

      if( (count($visitorEntryWithNull) + count($visitorEntryWithoutNull)) > 0 ) {
        return response()->json([ "success" => false, "message" => "This visitor still checkin in others " . count($visitorEntry) . " unit.", "data" => $visitorEntry ]);
      }

      $checkin = VisitorEntry::create([
        "visitor_id" => $visitor['id'],
        "unit_id" => $unit['id'],
        "checkin" => $request['checkinDate'],
        "checkout" => isset($request['checkoutDate']) && !is_null($request['checkoutDate']) && date_create($request['checkoutDate']) != false ? date_format(date_create($request['checkoutDate']), 'Y-m-d H:i:s') : null
      ]);

      return response()->json([ "success" => true, "message" => "Visitor checkin successfully." ]);
    }

    return response()->json([ "success" => true, "message" => "Visitor's account created successfully." ]);
  }

  public function checkout(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'checkout' => 'required'
    ]);

    if ( $validator->fails() ) {
      return response()->json([ "success" => false, "message" => $validator->errors() ]);
    }

    if( date_create($request['checkoutDate']) == false ) {
      return response()->json([ "success" => false, "message" => "Checkout Date format wrong." ]);
    }

    if( !isset($id) ) {
      return response()->json([ "success" => false, "message" => "Visitor entry ID not found" ]);
    }

    $entry = VisitorEntry::find($id);

    if( empty($entry) ) {
      return response()->json([ "success" => false, "message" => "Visitor entry record not found in the system" ]);
    }

    $updated = $entry->update([
      "checkout" => date_format(date_create($request['checkoutDate']), 'Y-m-d H:i:s')
    ]);

    if( $updated ) {
      return response()->json([ "success" => true, "message" => "Visitor's checkout time updated successully" ]);
    }

    return response()->json([ "success" => false, "message" => "Update record failed. Try again." ]);
  }
}
































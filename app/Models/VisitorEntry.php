<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorEntry extends Model
{
    protected $fillable = [ 'visitor_id', 'unit_id', 'checkin', 'checkout' ];
    protected $table = 'visitor_entries';

    public function blockUnit()
    {
      return $this->hasOne(BlockUnit::class, "id");
    }

    public function visitor()
    {
      return $this->belongsTo(Visitor::class);
    }
}

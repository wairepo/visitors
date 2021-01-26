<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockUnit extends Model
{
    protected $fillable = [ 'block_id', 'level', 'unit', 'occupant_name', 'phone', 'occupancy', 'is_deleted', 'deleted_at' ];
    protected $table = 'block_units';

    public function block()
    {
      return $this->belongsTo(Block::class, );
    }

    public function visitorEntries()
    {
      return $this->hasMany(VisitorEntry::class, 'unit_id');
    }
}

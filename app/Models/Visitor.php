<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [ 'name', 'phone', 'nric_no', 'status'];
    protected $table = 'visitors';

    public function visitorEntries()
    {
      return $this->hasMany(VisitorEntry::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [ 'name'];
    protected $table = 'blocks';

    public function blockUnits()
    {
      return $this->hasMany(BlockUnit::class);
    }
}

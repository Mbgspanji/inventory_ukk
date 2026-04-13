<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LendingDetail extends Model
{
    protected $guarded = [];
    public function lending() {
        return $this->belongsTo(Lending::class);
    }
    public function item() {
        return $this->belongsTo(Item::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = ['phone_number'];

    // 일대일 역방향 연관관계
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

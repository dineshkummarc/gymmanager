<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Commission extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','beneficiary_type','beneficiary_id','seller_id','source','source_id','amount','rate','status','paid_at'];
    protected $casts = ['amount'=>'decimal:2','rate'=>'decimal:2','paid_at'=>'datetime'];
}

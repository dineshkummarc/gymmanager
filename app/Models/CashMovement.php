<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class CashMovement extends Model {
    use HasFactory;
    protected $fillable = ['cash_register_id','kind','category','amount','method','description','user_id'];
    protected $casts = ['amount'=>'decimal:2'];
}

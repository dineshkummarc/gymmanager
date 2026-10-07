<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class FinancialTransaction extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','kind','category','description','amount','due_date','paid_at','status','cost_center'];
    protected $casts = ['amount'=>'decimal:2','due_date'=>'date','paid_at'=>'datetime'];
}

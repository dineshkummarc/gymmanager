<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Sale extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','student_id','seller_id','kind','description','amount','method','status','sold_at'];
    protected $casts = ['amount'=>'decimal:2','sold_at'=>'datetime'];
    public function student(){ return $this->belongsTo(Student::class); }
}

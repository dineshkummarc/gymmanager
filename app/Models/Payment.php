<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Payment extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','invoice_id','student_id','user_id','amount','method','status','paid_at','receipt','notes','cash_register_id'];
    protected $casts = ['amount'=>'decimal:2','paid_at'=>'datetime'];
    public function invoice(){ return $this->belongsTo(Invoice::class); }
    public function student(){ return $this->belongsTo(Student::class); }
}

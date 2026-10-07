<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Membership extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','student_id','plan_id','seller_id','coach_id','starts_at','ends_at','price','discount','status','payment_method','notes','paused_at'];
    protected $casts = ['starts_at'=>'date','ends_at'=>'date','price'=>'decimal:2','discount'=>'decimal:2','paused_at'=>'datetime'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function plan(){ return $this->belongsTo(Plan::class); }
    public function branch(){ return $this->belongsTo(Branch::class); }
    public function invoices(){ return $this->hasMany(Invoice::class); }
    public function contract(){ return $this->hasOne(Contract::class); }
    public function scopeActive($q){ return $q->where('status','active'); }
    public function scopeExpiring($q,$days=15){ return $q->where('status','active')->whereBetween('ends_at',[now(),now()->addDays($days)]); }
    public function total(): float { return max(0,(float)$this->price-(float)$this->discount); }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Student extends Model {
    use HasFactory, SoftDeletes, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','user_id','name','cpf','rg','birth_date','gender','email','phone','whatsapp','photo','address','city','state','zip','emergency_contact','notes','status','member_code','qr_token','goal','source'];
    protected $casts = ['birth_date'=>'date'];
    public function scopeActive($q){ return $q->where('status','active'); }
    public function scopeSearch($q,$t){ return $q->where(fn($w)=>$w->where('name','like',"%$t%")->orWhere('email','like',"%$t%")->orWhere('cpf','like',"%$t%")->orWhere('member_code','like',"%$t%")); }
    public function scopeByBranch($q,$b){ return $b ? $q->where('branch_id',$b) : $q; }
    public function branch(){ return $this->belongsTo(Branch::class); }
    public function memberships(){ return $this->hasMany(Membership::class); }
    public function activeMembership(){ return $this->hasOne(Membership::class)->where('status','active')->latestOfMany(); }
    public function invoices(){ return $this->hasMany(Invoice::class); }
    public function checkins(){ return $this->hasMany(Checkin::class); }
    public function workouts(){ return $this->hasMany(WorkoutPlan::class); }
    public function assessments(){ return $this->hasMany(PhysicalAssessment::class); }
    public function getAgeAttribute(){ return $this->birth_date?->age; }
}

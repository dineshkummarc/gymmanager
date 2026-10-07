<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Lead extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','owner_id','name','phone','email','source','interest','plan_id','status','estimated_value','next_contact_at','notes','trial_at','converted_student_id'];
    protected $casts = ['estimated_value'=>'decimal:2','next_contact_at'=>'datetime','trial_at'=>'datetime'];
    public function plan(){ return $this->belongsTo(Plan::class); }
    public function activities(){ return $this->hasMany(LeadActivity::class); }
}

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class WorkoutPlan extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','student_id','coach_id','name','goal','starts_at','expires_at','notes','status'];
    protected $casts = ['starts_at'=>'date','expires_at'=>'date'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function coach(){ return $this->belongsTo(Teacher::class,'coach_id'); }
    public function sessions(){ return $this->hasMany(WorkoutSession::class,'workout_plan_id'); }
}

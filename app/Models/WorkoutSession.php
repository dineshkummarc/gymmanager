<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class WorkoutSession extends Model {
    use HasFactory;
    protected $fillable = ['workout_plan_id','name','day_of_week','order','notes'];
    public function plan(){ return $this->belongsTo(WorkoutPlan::class,'workout_plan_id'); }
    public function items(){ return $this->hasMany(WorkoutExercise::class)->orderBy('order'); }
}

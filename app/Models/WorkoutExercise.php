<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class WorkoutExercise extends Model {
    use HasFactory;
    protected $fillable = ['workout_session_id','exercise_id','sets','reps','load','rest_seconds','tempo','distance','order','notes'];
    public function exercise(){ return $this->belongsTo(Exercise::class); }
    public function session(){ return $this->belongsTo(WorkoutSession::class,'workout_session_id'); }
}

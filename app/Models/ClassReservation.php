<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class ClassReservation extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','gym_class_id','student_id','status','notes'];
    public function gymClass(){ return $this->belongsTo(GymClass::class,'gym_class_id'); }
    public function student(){ return $this->belongsTo(Student::class); }
}

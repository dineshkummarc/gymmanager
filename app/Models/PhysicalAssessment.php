<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class PhysicalAssessment extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','student_id','teacher_id','weight','height','body_fat','muscle_mass','arm','chest','waist','hip','thigh','calf','notes','measured_at'];
    protected $casts = ['weight'=>'decimal:2','height'=>'decimal:2','body_fat'=>'decimal:2','muscle_mass'=>'decimal:2','measured_at'=>'date'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function teacher(){ return $this->belongsTo(Teacher::class); }
    public function getBmiAttribute(): ?float { return $this->height ? round($this->weight/(($this->height/100)**2),1) : null; }
}

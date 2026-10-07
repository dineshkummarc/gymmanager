<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class GymClass extends Model {
    use HasFactory, BelongsToGym;
    protected $table = 'gym_classes';
    protected $fillable = ['gym_id','branch_id','teacher_id','name','room','capacity','starts_at','duration_min','weekdays','status'];
    protected $casts = ['starts_at'=>'datetime','weekdays'=>'array'];
    public function teacher(){ return $this->belongsTo(Teacher::class); }
    public function branch(){ return $this->belongsTo(Branch::class); }
    public function reservations(){ return $this->hasMany(ClassReservation::class,'gym_class_id'); }
    public function confirmedCount(){ return $this->reservations()->where('status','reserved')->count(); }
}

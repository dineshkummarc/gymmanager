<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Checkin extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','student_id','method','checked_in_at','allowed','deny_reason','user_id'];
    protected $casts = ['checked_in_at'=>'datetime','allowed'=>'boolean'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function branch(){ return $this->belongsTo(Branch::class); }
}

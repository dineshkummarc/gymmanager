<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Teacher extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','user_id','name','cpf','cref','email','phone','specialty','photo','commission_rate','status','hire_date'];
    protected $casts = ['commission_rate'=>'decimal:2','hire_date'=>'date'];
    public function branch(){ return $this->belongsTo(Branch::class); }
    public function gymClasses(){ return $this->hasMany(GymClass::class,'teacher_id'); }
}

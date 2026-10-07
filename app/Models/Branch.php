<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Branch extends Model {
    use HasFactory;
    protected $fillable = ['gym_id','name','code','address','phone','email','opening_hours','manager_id','active'];
    protected $casts = ['active'=>'boolean'];
    public function gym() { return $this->belongsTo(Gym::class); }
    public function manager() { return $this->belongsTo(User::class,'manager_id'); }
    public function students() { return $this->hasMany(Student::class); }
}

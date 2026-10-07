<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Gym extends Model {
    use HasFactory;
    protected $fillable = ['name','slug','document','email','phone','city','state','plan','active'];
    protected $casts = ['active'=>'boolean'];
    public function branches() { return $this->hasMany(Branch::class); }
    public function users() { return $this->hasMany(User::class); }
    public function students() { return $this->hasMany(Student::class); }
    public function plans() { return $this->hasMany(Plan::class); }
}

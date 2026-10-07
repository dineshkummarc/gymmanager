<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Exercise extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','name','category','muscle_group','equipment','description','instructions','image','video_url','active'];
    protected $casts = ['active'=>'boolean'];
    public function scopeActive($q){ return $q->where('active',true); }
}

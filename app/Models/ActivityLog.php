<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ActivityLog extends Model {
    use HasFactory;
    protected $fillable = ['gym_id','user_id','action','entity','entity_id','ip','meta'];
    protected $casts = ['meta'=>'array'];
    public function user(){ return $this->belongsTo(User::class); }
}

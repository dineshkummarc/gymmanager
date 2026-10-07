<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Contract extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','membership_id','student_id','title','content','version','status','file_path','signed_at'];
    protected $casts = ['signed_at'=>'datetime'];
    public function membership(){ return $this->belongsTo(Membership::class); }
    public function student(){ return $this->belongsTo(Student::class); }
}

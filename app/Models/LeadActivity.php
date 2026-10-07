<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class LeadActivity extends Model {
    use HasFactory;
    protected $fillable = ['lead_id','user_id','type','description'];
    public function lead(){ return $this->belongsTo(Lead::class); }
}

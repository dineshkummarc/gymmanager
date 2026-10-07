<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Plan extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','name','description','price','period','duration_months','signup_fee','allowed_days','allowed_hours','class_limit','all_branches','promo','recurring','corporate','trial','active'];
    protected $casts = ['price'=>'decimal:2','signup_fee'=>'decimal:2','allowed_days'=>'array','all_branches'=>'boolean','promo'=>'boolean','recurring'=>'boolean','corporate'=>'boolean','trial'=>'boolean','active'=>'boolean'];
    public function scopeActive($q){ return $q->where('active',true); }
    public function memberships(){ return $this->hasMany(Membership::class); }
}

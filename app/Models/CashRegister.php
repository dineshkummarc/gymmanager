<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class CashRegister extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','user_id','opening_balance','closing_balance','status','opened_at','closed_at','notes'];
    protected $casts = ['opening_balance'=>'decimal:2','closing_balance'=>'decimal:2','opened_at'=>'datetime','closed_at'=>'datetime'];
    public function movements(){ return $this->hasMany(CashMovement::class); }
    public function balance(): float { return (float)$this->opening_balance + (float)$this->movements()->where('kind','in')->sum('amount') - (float)$this->movements()->where('kind','out')->sum('amount'); }
}

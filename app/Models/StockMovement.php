<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class StockMovement extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','product_id','user_id','type','quantity','reason'];
    public function product(){ return $this->belongsTo(Product::class); }
}

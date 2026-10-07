<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Product extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','name','sku','category','price','cost','stock','min_stock','barcode','image','active'];
    protected $casts = ['price'=>'decimal:2','cost'=>'decimal:2','active'=>'boolean'];
    public function scopeLow($q){ return $q->whereColumn('stock','<=','min_stock'); }
}

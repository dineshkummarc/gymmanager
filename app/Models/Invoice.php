<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BelongsToGym;
class Invoice extends Model {
    use HasFactory, BelongsToGym;
    protected $fillable = ['gym_id','branch_id','student_id','membership_id','description','amount','discount','fine','due_date','paid_at','status','reference_month','notes'];
    protected $casts = ['amount'=>'decimal:2','discount'=>'decimal:2','fine'=>'decimal:2','due_date'=>'date','paid_at'=>'datetime'];
    public function student(){ return $this->belongsTo(Student::class); }
    public function membership(){ return $this->belongsTo(Membership::class); }
    public function payments(){ return $this->hasMany(Payment::class); }
    public function scopePending($q){ return $q->whereIn('status',['pending','partial','overdue']); }
    public function scopeOverdue($q){ return $q->where('status','overdue')->orWhere(fn($w)=>$w->where('status','pending')->where('due_date','<',now()->toDateString())); }
    public function total(): float { return max(0,(float)$this->amount-(float)$this->discount+(float)$this->fine); }
    public function paidTotal(): float { return (float)$this->payments()->where('status','paid')->sum('amount'); }
    public function balance(): float { return $this->total()-$this->paidTotal(); }
}

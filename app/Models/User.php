<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\UserRole;
class User extends Authenticatable {
    use HasFactory, Notifiable;
    protected $fillable = ['gym_id','branch_id','name','email','password','role','phone','avatar','active','last_login_at'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['email_verified_at'=>'datetime','password'=>'hashed','active'=>'boolean','last_login_at'=>'datetime'];
    public function gym() { return $this->belongsTo(Gym::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function canAccess(string $perm): bool {
        $perms = UserRole::permissions(UserRole::tryFrom($this->role) ?? UserRole::Viewer);
        return in_array('*',$perms) || in_array($perm,$perms);
    }
    public function isRole(string ...$roles): bool { return in_array($this->role, $roles); }
}

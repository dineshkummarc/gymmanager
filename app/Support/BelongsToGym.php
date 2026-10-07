<?php
namespace App\Support;
use Illuminate\Database\Eloquent\Builder;
trait BelongsToGym {
    protected static function bootBelongsToGym(): void {
        static::creating(function ($m) {
            if (empty($m->gym_id) && app()->bound('currentGymId') && app('currentGymId')) $m->gym_id = app('currentGymId');
            elseif (empty($m->gym_id) && auth()->check() && auth()->user()->gym_id) $m->gym_id = auth()->user()->gym_id;
        });
        static::addGlobalScope('gym', function (Builder $q) {
            $gid = app()->bound('currentGymId') ? app('currentGymId') : (auth()->check() ? auth()->user()->gym_id : null);
            if ($gid && auth()->check() && auth()->user()->role !== 'super_admin') $q->where($q->getModel()->getTable().'.gym_id', $gid);
        });
    }
    public function gym() { return $this->belongsTo(\App\Models\Gym::class); }
}

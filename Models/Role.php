<?php

namespace Modules\Authentication\Models;

use Modules\Authentication\Support\Traits\Concerns\Model\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes, HasUUID;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}

<?php

namespace Modules\Admin\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ADMIN = 'admin';
    public const GUARD_NAME_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'guard_name',
    ];
}

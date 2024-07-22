<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;


class Register extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticableTrait;

    protected $table = 'users';

    protected $guard_name = 'web';

    use HasRoles, HasPermissions;



}

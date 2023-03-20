<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'group_id',
        'password',
        'image',
        'approver_id',
        'organization_id',
        'api_token',
        'robot_flag',
        'ip_machine',
        'name_machine',
        'user_local_machine',
        'allow_remove_document_flag',
        'npx_manager_flag',
        'tracking_flag',
        'check_ip_address_flag',
        'mfa_flag',
        'can_force_statement_delete',
        'blocked_flag',
        'allow_reprocess_authorization_flag',
        'allow_force_authorization_flag',
        'access_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function Organization()
    {
        return $this->belongsTo('ProjectNpx\Organization', 'organization_id');
    }
}

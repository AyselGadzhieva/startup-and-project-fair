<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserSubjectAreas extends Model
{
    use HasFactory;

    public $timestamps = false;


    public function userSA()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function SAUsers()
    {
        return $this->belongsTo(SubjectArea::class, 'subject_area_id');
    }

    public function roleUsers()
    {
        return $this->hasOne(Role::class, 'id', 'role_id' )->select('name');
    }


}

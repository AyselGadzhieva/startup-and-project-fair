<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSubjectArea extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'updated_at' => 'datetime:d.m.Y',
        'created_at' => 'datetime:d.m.Y',
    ];

    public function roleProject()
    {
        return $this->hasMany(ProjectRole::class, 'role_subject_area_id', 'id');
    }

    public function rolesSubjectArea()
    {
        return $this->belongsTo(SubjectArea::class, 'subject_area_id');
    }
}

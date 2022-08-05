<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectArea extends Model
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

    public function rolesSubjectArea()
    {
        return $this->hasMany(RoleSubjectArea::class, 'subject_area_id', 'id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_subject_areas', 'subject_area_id','project_id');
    }

    public function users()
    {
        return $this->hasMany(UserSubjectAreas::class,'subject_area_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_owner_id',
        'project_type_id',
        'name',
        'description',
        'start_date',
        'finish_date',
        'remuneration',
        'moderator_id',
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
        'start_date' => 'datetime:d.m.Y',
        'finish_date' => 'datetime:d.m.Y',
        'updated_at' => 'datetime:d.m.Y',
        'created_at' => 'datetime:d.m.Y',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'project_owner_id');
    }

    public function projectype()
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function projestatuses()
    {
        return $this->hasMany(ProjectStatus::class, 'project_id', 'id');
    }

    public function projectGoals()
    {
        return $this->hasMany(ObjectiveProject::class, 'project_id', 'id');
    }

    public function projectroles()
    {
        return $this->hasMany(ProjectRole::class, 'project_id', 'id');
    }

    public function projectannouncements()
    {
        return $this->hasMany(ProjectAnnouncement::class, 'project_id', 'id');
    }

    public function subjectAreas()
    {
        return $this->belongsToMany(SubjectArea::class, 'project_subject_areas', 'project_id','subject_area_id');
    }

    public function projectExpertise()
    {
        return $this->hasMany(Expertise::class, 'project_id', 'id');
    }
}

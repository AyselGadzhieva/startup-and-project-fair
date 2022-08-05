<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStatus extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

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
        'datetime' => 'datetime',
    ];


    public function projectstatus()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }


    public function statusproject()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

//    public function deployments()
//    {
//        return $this->hasManyThrough(Status::class, Project::class);
//    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAnnouncement extends Model
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
        'start_datetime' => 'datetime',
        'changes_datetime' => 'datetime',
        'updated_at' => 'datetime:d.m.Y',
        'created_at' => 'datetime:d.m.Y',
    ];


    public function projectan()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

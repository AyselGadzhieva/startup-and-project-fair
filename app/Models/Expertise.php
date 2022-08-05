<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'grade',
        'comment',
        'datetime'
    ];

    protected $casts = [
        'datetime' => 'datetime:d.m.Y H:i:s',
    ];

    public $timestamps = false;

    public function userExpertise()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkingProjects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

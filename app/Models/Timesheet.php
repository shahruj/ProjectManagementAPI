<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_name',
        'date',
        'hours',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'float',
    ];

    // Belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

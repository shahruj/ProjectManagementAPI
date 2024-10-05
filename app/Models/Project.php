<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Many-to-Many with User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // One-to-Many with Timesheet
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}

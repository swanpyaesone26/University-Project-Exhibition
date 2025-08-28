<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'student_id',
        'uni_id',
        'password_hash',
    ];

    //do mass assignment for user_id (important for login)
    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'collaborators','user_id', 'project_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    // public function collaborators()
    // {
    //     return $this->hasMany(Collaborator::class);
    // }

    //to off laravel atomatic timestamps 
    public $timestamps = false;

    use Searchable;
    public function toSearchableArray()
    {
        $array = [
            'email' => $this->email,
            'student_id' => $this->student_id,
            'uni_id' => $this->uni_id
        ];

        // Include student information if available
        if ($this->students) {
            $array['name'] = $this->students->name;
            $array['major'] = $this->students->major;
            $array['batch'] = $this->students->batch;
        }

        return $array;
    }
}

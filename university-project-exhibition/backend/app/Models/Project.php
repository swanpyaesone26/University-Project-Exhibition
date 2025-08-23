<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Project extends Model
{

    protected $primaryKey = 'project_id';
    public $timestamps = false;

    protected $casts = [
        'project_images' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'project_name',
        'project_detail',
        'project_date',
        'project_link',
        'project_images',
        'popularity',
        'liked',
    ];
    
    use HasFactory;
    
    public function users()
{
    return $this->belongsToMany(User::class, 'collaborators','project_id', 'user_id')
                ->withPivot('role')
                ->withTimestamps();
}

    // public function collaborators()
    // {
    //     return $this->belongsToMany(Collaborator::class, 'collaborator_project','project_id','collaborator_id');
    // }

    use Searchable;

    public function  toSearchableArray()
    {
        return [
            'user_id'=>$this->user_id,
            'project_name'=>$this->project_name
        ];
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $primaryKey = 'project_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'project_name',
        'project_detail',
        'project_date',
        'project_link',
        'project_image',
        'popularity',
        'liked',
    ];
    
    use HasFactory;
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collaborators()
    {
        return $this->belongsToMany(Collaborator::class, 'collaborator_project','project_id','collaborator_id');
    }

}

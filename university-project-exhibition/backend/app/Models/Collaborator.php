<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Collaborator extends Model
{
    protected $primaryKey = 'collaborator_id';
    
    use HasFactory;
    protected $fillable = ['user_id','name', 'email', 'major', 'batch', 'image'];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'collaborator_project','collaborator_id', 'project_id');
    }

    //to off laravel atomatic timestamps 
    public $timestamps = false;

    use Searchable;
    public function toSearchableArray()
    {
        return [
            'user_id'=>$this->user_id,
            'name'=>$this->name,
            'email'=>$this->email,
            'major'=>$this->major,
            'batch'=>$this->batch
        ];
    }
}

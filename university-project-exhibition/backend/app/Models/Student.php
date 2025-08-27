<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Student extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->hasOne(User::class,'student_id');
    }

    public function registrations()
    {
        return $this->hasOne(Registration::class,'student_id');
    }

    protected $fillable = ['uni_id','name','name_no_space', 'email','image', 'major', 'batch'];
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $keyType = 'int';

    use Searchable;

    public function toSearchableArray()
    {
        return [
            'uni_id' => $this->uni_id,
            'name' => $this->name,
            'name_no_space' => strtolower(str_replace(' ', '', $this->name)),
            'email' => $this->email,
            'major' => $this->major,
            'batch' => $this->batch

        ];
    }

}

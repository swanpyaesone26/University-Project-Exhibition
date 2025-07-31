<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $primaryKey = 'reg_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'email',
        'purpose',
        'otp_code',
        'expires_at',
        'attempts',
        'is_used',
        'created_at',
    ];

    use HasFactory;

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}

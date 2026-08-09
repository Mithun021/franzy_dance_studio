<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'password',
        'phone',
        'salary',
        'profile_image',
        'signature',
        'city',
        'state',
        'country',
        'pincode',
        'address',
        'is_active',
        'user_type',

        // Personal Details
        'date_of_birth',
        'gender',
        'religion',
        'mother_tongue',
        'occupation',
        'qualification',
        'whatsapp_no',

        // Guardian Details
        'guardian_name',
        'guardian_contact',
        'guardian_occupation',

        // Local Guardian
        'local_guardian_name',
        'local_guardian_relation',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */



    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth'     => 'date',
        ];
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'student_course',
            'user_id',
            'course_id'
        )
        ->withPivot([
            'id',
            'admission_no',
            'admission_date',
            'level_id',
            'category_id',
            'batch_id',
            'instructor_id',
            'registration_fee',
            'admission_fee',
            'course_fee',
            'is_enroll',
            'status',
            'completion_date',
        ])
        ->withTimestamps();
    }

    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class, 'user_id');
    }

    public function instructorCourses()
    {
        return $this->hasMany(StudentCourse::class, 'instructor_id');
    }

    // Check if user is super-admin
    // public function isSuperAdmin()
    // {
    //     return $this->user_type === 'super-admin';
    // }
}

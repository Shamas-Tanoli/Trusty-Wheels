<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\DriverDetail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [


    'name',
    'email',
    'password',
    'role',
    'fcm_token'
  ];
/**
  
   * The attributes that s should be hidden for serialization.
   *
   * @var array<int, string>
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
    ];
  }

  public function driverDetail()
  {
    return $this->hasOne(DriverDetail::class, 'user_id');
  }
  public function customer()
  {
    return $this->hasOne(Customer::class);
  }

  public function driverJobs()
{
    return $this->hasMany(ServiceJob::class, 'driver_id');
}
}

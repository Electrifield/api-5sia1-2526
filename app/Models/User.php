<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Iilluminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
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
        ];
    }

    /** 
     * Relasi one-to-many (inverse) ke tabel products
     * nama method disesuikan dengan bentuk plural/singular
     * dari nama tabel tujuan relasi
     * use Iilluminate\Database\Eloquent\Relations\HasMany;
     */
    public function products(): HasMany
        {
            // $this adalah objek dari model ini (users)
            // hasmany adalah method relasi, selain itu ada:
            // hasOne(), belongsTo(), belongsToMany()
            return $this->hasMany(Product::class);
        }
}

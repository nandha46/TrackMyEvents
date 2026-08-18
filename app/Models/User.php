<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'private_pin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'private_pin',
    ];

    /**
     * Check if the user has set a 4-digit private event PIN.
     */
    public function hasPrivatePin(): bool
    {
        return !empty($this->private_pin);
    }

    /**
     * Verify provided 4-digit PIN against stored hash or plaintext PIN.
     */
    public function verifyPrivatePin(string $pin): bool
    {
        if (empty($this->private_pin)) {
            return false;
        }

        // Support both hashed and legacy/direct string PIN
        if (\Illuminate\Support\Facades\Hash::check($pin, $this->private_pin)) {
            return true;
        }

        return $this->private_pin === $pin;
    }

    /**
     * The model's default values
     * 
     * @var array
     */
    protected $attributes = [
        'avatar' => 'default.jpg'
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
     * Route notifications for the Slack channel.
     */
    public function routeNotificationForSlack(Notification $notification): mixed
    {
        return '#campaign';
    }

    public function events () {
        return $this->hasMany(Event::class);
    }
}

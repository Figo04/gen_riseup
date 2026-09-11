<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'usia', 'jenis_kelamin', 'sekolah', 'kelas'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function hasilKuesioner(): HasMany
    {
        return $this->hasMany(HasilKuesioner::class);
    }

    public function progressModul(): HasMany
    {
        return $this->hasMany(ProgressModul::class);
    }

    public function refleksi(): HasMany
    {
        return $this->hasMany(Refleksi::class);
    }

    public function materiSelesaiSemua(): bool
    {
        return SubBagian::count() === $this->progressModul()->where('materi_selesai', true)->count();
    }
}

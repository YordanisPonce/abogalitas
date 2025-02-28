<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RoleEnum;
use App\Notifications\DinamicNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'phone_number',
        'address',
        'pin',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin',
        'email_verified_at'
    ];

    protected $appends = ['is_verified', 'is_admin'];


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

    public function sendRegisteredEmail()
    {
        $appName = config('app.name');
        $name = $this->attributes['name'];
        $this->notify(new DinamicNotification([
            'subject' => "¡Bienvenido a $appName! Tu viaje hacia la digitalización comienza aquí",
            'message' => [
                "Hola {$name}",
                "¡Estamos emocionados de darte la bienvenida a $appName! 🎉",
                "Gracias por registrarte. Para activar tu cuenta y comenzar a disfrutar de todas nuestras funcionalidades de escaneo y digitalización de documentos, por favor confirma tu dirección de correo electrónico utilizando el siguiente código de verificación:",
                "<h1><strong>{$this->pin}</strong></h1>",
                "Simplemente ingresa este código en la sección correspondiente de nuestra plataforma para completar el proceso de registro.",
                "Una vez que tu cuenta esté confirmada, podrás:",
                [
                    "Organizar tus documentos: Mantén todo en un solo lugar y accede a ellos cuando los necesites.",
                    "Aumentar tu productividad: Olvídate del papel y optimiza tu flujo de trabajo.",
                    "Proteger tu información: Disfruta de la tranquilidad que ofrece el almacenamiento seguro en la nube.",
                ],
                "Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos. Estamos aquí para ayudarte."
            ]
        ]));
    }

    public function getIsVerifiedAttribute()
    {
        return $this->hasVerifiedEmail();
    }

    public function sendPasswordResetCode()
    {
        $this->notify(new DinamicNotification([
            'subject' => 'Notificación de reseteo de contraseña.',
            'message' => [
                "Hola {$this->name},",
                "Usted ha solicitado el cambio de su contraseña",
                "Código de reseteo: <strong style=\"font-size:20px;\">{$this->pin}</strong>",
                "PD: Si no fue usted quien realizó esta operación deseche este correo.",
            ],
        ]));
    }


    public function resetPassword($data)
    {
        try {
            $this->forceFill([
                'password' => Hash::make($data['password']),
                'pin' => null
            ])->save();
            $this->notify(new DinamicNotification([
                'subject' => 'Notificación de reseteo de contraseña satisfactorio.',
                'message' => [
                    "Hola {$this->name},",
                    "Usted ha cambiado su contraseña de manera satisfactoria",
                ],
            ]));
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function verifyAccount()
    {
        $this->markEmailAsVerified();
    }

    public function sendNewPin()
    {
        $this->notify(new DinamicNotification([
            'subject' => 'Aquí tienes el código de verificación.',
            'message' => [
                "Hola {$this->name},",
                "Introduce el siguiente código para verificar su cuenta: <strong style=\"font-size:20px;\">{$this->pin}</strong>",
                "Si tienes alguna duda, ponte en contacto con nosotros.",
            ],
        ]));
    }

    public function documents()
    {

        return $this->hasMany(Document::class);
    }


    public function role()
    {
        return $this->belongsTo(Role::class);

    }
    public function getIsAdminAttribute()
    {
        return $this->role->name == RoleEnum::ADMIN->value;
    }
}

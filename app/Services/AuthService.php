<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Helpers\ResponseHelper;
use App\Interfaces\EloquentRoleRepositoryInterface;
use App\Interfaces\EloquentStudentRepositoryInterface;
use App\Interfaces\EloquentUserRepositoryInterface;
use App\Models\User;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private $tokenName;
    use Upload;
    public function __construct(
        private readonly EloquentUserRepositoryInterface $repository
    ) {
        $this->tokenName = 'accessToken';
    }

    public function register($attributes)
    {
        if (request()->hasFile('photo')) {
            $fileName = request()->file('photo')->getClientOriginalName();
            $path = $this->upload($fileName, request()->file('photo'));
            $attributes['photo'] = $path;
        }

        $user = $this->repository->save($attributes + ['pin' => $this->generatePin()]);


        throw_if(!$user, 'Ha ocurrido un error a la hora de completar el registro');
        $user->sendRegisteredEmail();


        return ResponseHelper::ok(
            "Registro completado satisfactoriamente, verifique su cuenta con un código de acceso enviado a su email",
            $user
        );
    }


    public function login($attributes)
    {
        $user = $this->repository->findByEmail($attributes["email"]);
        throw_if(!$user, 'Credenciales incorrectas', AuthorizationException::class);
        throw_if(!$user->hasVerifiedEmail(), 'Cuenta no verificada', AuthorizationException::class);

        if (
            !Auth::attempt(
                $attributes
            )
        ) {
            return ResponseHelper::fail("Correo o contraseña errónea", 403);
        }
        $token = $user->createToken($this->tokenName)->plainTextToken;

        return ResponseHelper::ok($user->is_verified ? "Acceso al sistema" : "Su cuenta está pendiente de verificación", [
            $this->tokenName => $token,
            'user' => $user
        ]);
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return ResponseHelper::ok("Sesion finalizada satisfactoriamente");
    }

    public function profile()
    {
        $user = request()->user();

        throw_if(!$user, 'No se ha encontrado al usuario con el identificador proporcionado');
        return ResponseHelper::ok(
            "Perfil",
            $this->repository->findById($user->id)

        );
    }

    public function updateProfile($attributes)
    {
        $user = request()->user();
        throw_if(!$user, 'No se ha encontrado al usuario con el identificador proporcionado');
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (request()->hasFile('photo')) {
            $fileName = request()->file('photo')->getClientOriginalName();
            $path = $this->upload($fileName, request()->file('photo'));
            $attributes['photo'] = $path;
        }


        $user->fill($attributes)->save();

        return ResponseHelper::ok(
            "Datos actualizados satisfactoriamente",
            $this->repository->findById($user->id)
        );
    }
    /**
     * @throws \Exception
     */
    public function forgotPassword()
    {
        $email = request()->email;
        $user = $this->repository->findByEmail($email);

        throw_if(!$user, "Parece que el correo electrónico que has ingresado no está registrado en nuestra plataforma");

        $user->forceFill(['pin' => $this->generatePin()])->save();
        $user->sendPasswordResetCode();
        return ResponseHelper::ok("Verifique su cuenta de correo para resetear la contraseña");
    }

    /**
     * @throws \Exception
     */
    public function resetPassword(array $data)
    {
        $user = $this->repository->query()->where('pin', $data['pin'])->first();

        if (!$user) {
            return ResponseHelper::fail("Usuario no encontrado", 404);
        }
        $user->resetPassword($data);
        return ResponseHelper::ok("Contraseña cambiada satisfactoriamente", [
            "user" => $user,
            $this->tokenName => $user->createToken($this->tokenName)->plainTextToken
        ]);
    }

    private function generatePin()
    {
        $pin = rand(1000, 9999);
        $exists = User::wherePin($pin)->exists();
        return $exists ? $this->generatePin() : $pin;
    }

    private function uploadFile($attributes)
    {

    }

    public function verifyAccount($attributes)
    {

        $user = $this->repository->findByEmail($attributes['email']);

        throw_if(!$user || $user->pin != $attributes['pin'], 'Pin incorrecto');

        throw_if($user->is_verified, 'Su cuenta ya ha sido verificada');

        $user->verifyAccount();
        $token = $user->createToken($this->tokenName)->plainTextToken;

        return ResponseHelper::ok("Acceso al sistema", [
            $this->tokenName => $token,
            'user' => $user
        ]);
    }

    public function sendNewPin(array $attributes)
    {
        $email = $attributes['email'];
        $user = $this->repository->findByEmail($attributes['email']);
        throw_if(!$user, "El usuario con el email  $email no se encuentra registrado en nuestro sistema");
        $user->forceFill(['pin' => $this->generatePin()])->save();
        $user->sendNewPin();
        return ResponseHelper::ok("Pin enviado satisfactoriamente, compruebe su cuenta de correo electornico");

    }
    public function deleteMyAccount()
    {
        $this->logout();
        request()->user()->delete();
        return ResponseHelper::ok("Cuenta eliminada satisfactoriamente");

    }
}
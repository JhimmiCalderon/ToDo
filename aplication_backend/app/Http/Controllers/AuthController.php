<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; // Importa la fachada Auth para la autenticación
use App\Http\Controllers\Controller; // Importa el controlador base de Laravel
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request; // Importa la clase Request para manejar las solicitudes HTTP
use Illuminate\Support\Facades\Validator; // Importa el validador para validar los datos de entrada
use App\Models\User; // Importa el modelo User para interactuar con la base de datos

class AuthController extends Controller
{
    /**
     * Crea una nueva instancia de AuthController.
     *
     * @return void
     */
    public function __construct()
    {
        // Aplica el middleware de autenticación a todas las rutas excepto a 'login' y 'register'
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Obtiene un JWT (JSON Web Token) mediante las credenciales dadas.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // Obtiene las credenciales de la solicitud
        $credentials = request(['email', 'password']);
        // Busca el usuario por correo electrónico
        $user = \App\Models\User::where('email', $credentials['email'])->first();
    
        // Si el usuario no existe, devuelve un error
        if (!$user) {
            return response()->json(['error' => 'Email Incorrecto!'], 401);
        }
    
        // Intenta autenticar al usuario con las credenciales
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Password Incorrecto!'], 401);
        }
    
        // Genera el token usando el ID del usuario autenticado
        $token = auth()->tokenById($user->id); 
        // Devuelve el token y la información relacionada
        return $this->respondWithToken($token);
    }
    

    /**
     * Obtiene el usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Asegúrate de que el usuario esté autenticado
        if (auth()->check()) {
            // Devuelve el usuario autenticado
            return response()->json(['user' => auth()->user()]); 
        } else {
            // Maneja el caso donde no hay un usuario autenticado
            return response()->json(['error' => 'No Autorizado'], 401); 
        }
    }
    

    /**
     * Cierra la sesión del usuario (invalidar el token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Invalida el token actual del usuario
        auth()->logout();

        // Devuelve un mensaje de éxito
        return response()->json(['message' => "Sesión cerrada con éxito!"]);
    }

    /**
     * Refresca un token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Devuelve un nuevo token
        return $this->respondWithToken(JWTAuth::refresh());
    }

    /**
     * Obtiene la estructura del array de token.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // Devuelve el token y la información relacionada
        return response()->json([
            'access_token' => $token, // El token de acceso
            'token_type' => 'bearer', // El tipo de token
            'expires_in' => auth()->factory()->getTTL() * 60 // Tiempo de expiración del token
        ]);
    }

    /**
     * Registra un nuevo usuario.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Valida los datos de entrada
        $validator = Validator::make($request->all(), [
            'first_name' => 'required', // El nombre es requerido
            'last_name' => 'required', // El apellido es requerido
            'email' => 'required|string|email|max:100|unique:users', // El email es requerido y debe ser único
            'password' => 'required|string|min:6', // La contraseña es requerida y debe tener al menos 6 caracteres
        ]);
        
        // Si la validación falla, devuelve los errores
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Crea el nuevo usuario
        $user = User::create(array_merge(
            $validator->validated(), // Obtiene solo los campos validados
            ['password' => bcrypt($request->password)] // Cifra la contraseña
        ));

        // Devuelve el usuario creado y un mensaje de éxito
        return response()->json([
            'user' => $user,
            'message' => "Usuario registrado con éxito!",
        ], 201);
    }
}

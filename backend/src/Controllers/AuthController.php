<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;
use Firebase\JWT\JWT;

class AuthController
{
    /**
     * Login de usuario
     */
    public function login(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        // Validar datos requeridos
        if (!isset($data['cedula']) || !isset($data['password'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Cédula y contraseña son requeridos'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Buscar usuario por cédula
        $user = User::where('cedula', $data['cedula'])->first();

        if (!$user) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado. Verifica tu número de cédula.'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Verificar contraseña
        if (!password_verify($data['password'], $user->password)) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Contraseña incorrecta.'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Generar JWT token
        $token = $this->generateToken($user);

        // Preparar respuesta
        $userData = $user->toArray();
        unset($userData['password']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'user' => $userData,
            'token' => $token,
            'message' => 'Inicio de sesión exitoso'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Registro de nuevo usuario (solo pacientes)
     */
    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validar datos requeridos
        $required = ['cedula', 'nombresCompletos', 'password', 'direccion', 'edad', 'sexo', 'tieneSeguro'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => "El campo {$field} es requerido"
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        // Verificar si el usuario ya existe
        $existingUser = User::where('cedula', $data['cedula'])->first();
        if ($existingUser) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Ya existe un usuario registrado con esta cédula.'
            ]));
            return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
        }

        // Crear nuevo usuario
        $user = User::create([
            'cedula' => $data['cedula'],
            'username' => $data['cedula'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'full_name' => $data['nombresCompletos'],
            'role' => 'paciente',
            'direccion' => $data['direccion'],
            'edad' => $data['edad'],
            'sexo' => $data['sexo'],
            'tiene_seguro' => $data['tieneSeguro'],
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null
        ]);

        // Generar JWT token
        $token = $this->generateToken($user);

        // Preparar respuesta
        $userData = $user->toArray();
        unset($userData['password']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'user' => $userData,
            'token' => $token,
            'message' => 'Registro exitoso. Bienvenido al sistema.'
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener usuario actual
     */
    public function me(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');

        $userData = $user->toArray();
        unset($userData['password']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'user' => $userData
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Actualizar perfil de usuario
     */
    public function updateProfile(Request $request, Response $response): Response
    {
        $user = $request->getAttribute('user');
        $data = $request->getParsedBody();

        // Campos actualizables
        $updatable = ['full_name', 'email', 'telefono', 'direccion', 'edad'];
        
        foreach ($updatable as $field) {
            if (isset($data[$field])) {
                $user->$field = $data[$field];
            }
        }

        $user->save();

        $userData = $user->toArray();
        unset($userData['password']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'user' => $userData,
            'message' => 'Perfil actualizado correctamente'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Generar JWT token
     */
    private function generateToken(User $user): string
    {
        $payload = [
            'iss' => $_ENV['APP_URL'],
            'iat' => time(),
            'exp' => time() + (int)$_ENV['JWT_EXPIRATION'],
            'sub' => $user->id,
            'cedula' => $user->cedula,
            'role' => $user->role
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }
}

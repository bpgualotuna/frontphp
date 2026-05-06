<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class MedicoController
{
    /**
     * Obtener todos los médicos
     */
    public function index(Request $request, Response $response): Response
    {
        $medicos = User::where('role', 'medico')->get();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $medicos
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener médico por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $medico = User::where('role', 'medico')->find($args['id']);

        if (!$medico) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Médico no encontrado'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $medico
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener médicos por especialidad
     */
    public function byEspecialidad(Request $request, Response $response, array $args): Response
    {
        $medicos = User::where('role', 'medico')
                       ->where('especialidad', $args['especialidad'])
                       ->get();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $medicos
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}

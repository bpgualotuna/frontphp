<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Terapia;

class TerapiaController
{
    /**
     * Obtener todas las terapias activas
     */
    public function index(Request $request, Response $response): Response
    {
        $terapias = Terapia::activas()->get();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $terapias
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener terapia por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $terapia = Terapia::find($args['id']);

        if (!$terapia) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Terapia no encontrada'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $terapia
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}

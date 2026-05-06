<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Cita;
use App\Models\User;
use DateTime;

class HorarioController
{
    /**
     * Obtener horarios disponibles
     */
    public function disponibles(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        
        if (!isset($params['fecha'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'El parámetro fecha es requerido'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $fecha = $params['fecha'];
        $terapiaId = $params['terapiaId'] ?? null;

        // Obtener todos los médicos
        $medicos = User::where('role', 'medico')->get();

        $horariosDisponibles = [];

        // Generar horarios de 8:00 a 17:00
        foreach ($medicos as $medico) {
            for ($hora = 8; $hora <= 17; $hora++) {
                $horaStr = sprintf('%02d:00', $hora);

                // Verificar si el horario está ocupado
                $ocupado = Cita::where('medico_id', $medico->id)
                               ->where('fecha', $fecha)
                               ->where('hora', $horaStr)
                               ->whereIn('estado', ['pendiente', 'confirmada'])
                               ->exists();

                $horariosDisponibles[] = [
                    'fecha' => $fecha,
                    'hora' => $horaStr,
                    'disponible' => !$ocupado,
                    'medicoId' => $medico->id,
                    'medicoNombre' => $medico->full_name
                ];
            }
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $horariosDisponibles
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}

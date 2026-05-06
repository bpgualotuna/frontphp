<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Cita;

class CitaController
{
    /**
     * Obtener citas de un paciente
     */
    public function byPaciente(Request $request, Response $response, array $args): Response
    {
        $citas = Cita::where('paciente_id', $args['pacienteId'])
                     ->with(['medico', 'terapia'])
                     ->orderBy('fecha', 'desc')
                     ->orderBy('hora', 'desc')
                     ->get();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $citas
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener próximas citas de un paciente
     */
    public function proximasCitas(Request $request, Response $response, array $args): Response
    {
        $citas = Cita::where('paciente_id', $args['pacienteId'])
                     ->proximas()
                     ->with(['medico', 'terapia'])
                     ->limit(5)
                     ->get();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $citas
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener cita por ID
     */
    public function show(Request $request, Response $response, array $args): Response
    {
        $cita = Cita::with(['paciente', 'medico', 'terapia'])->find($args['id']);

        if (!$cita) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Cita no encontrada'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $cita
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Crear nueva cita
     */
    public function create(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validar datos requeridos
        $required = ['pacienteId', 'medicoId', 'terapiaId', 'fecha', 'hora', 'sintomas', 'tieneExamenes'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => "El campo {$field} es requerido"
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
        }

        // Verificar disponibilidad del horario
        $existeCita = Cita::where('medico_id', $data['medicoId'])
                          ->where('fecha', $data['fecha'])
                          ->where('hora', $data['hora'])
                          ->whereIn('estado', ['pendiente', 'confirmada'])
                          ->exists();

        if ($existeCita) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'El horario seleccionado ya no está disponible'
            ]));
            return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
        }

        // Crear cita
        $cita = Cita::create([
            'paciente_id' => $data['pacienteId'],
            'medico_id' => $data['medicoId'],
            'terapia_id' => $data['terapiaId'],
            'fecha' => $data['fecha'],
            'hora' => $data['hora'],
            'estado' => 'pendiente',
            'sintomas' => $data['sintomas'],
            'tiene_examenes' => $data['tieneExamenes'],
            'examenes' => $data['examenes'] ?? null
        ]);

        // Cargar relaciones
        $cita->load(['paciente', 'medico', 'terapia']);

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $cita,
            'message' => 'Cita creada exitosamente'
        ]));

        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    /**
     * Cancelar cita
     */
    public function cancelar(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        
        $cita = Cita::find($args['id']);

        if (!$cita) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Cita no encontrada'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // Verificar que la cita no esté ya cancelada o completada
        if (in_array($cita->estado, ['cancelada', 'completada'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'No se puede cancelar una cita que ya está ' . $cita->estado
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // Cancelar cita
        $cita->estado = 'cancelada';
        $cita->motivo_cancelacion = $data['motivo'] ?? 'Sin motivo especificado';
        $cita->save();

        $response->getBody()->write(json_encode([
            'success' => true,
            'data' => $cita,
            'message' => 'Cita cancelada exitosamente'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}

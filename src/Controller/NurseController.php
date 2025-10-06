<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nurse')]

final class NurseController extends AbstractController
{
    //Ruta para llamar al metodo findByName y poder buscar una enfermera por su nombre
    #[Route('/name/{name}', name: 'app_nurse')]

    /**
     * Buscar una enfermera por su nombre en un archivo JSON.
     *    
     * @param string $name
     * @return JsonResponse
     */
    public function findByName(string $name): JsonResponse
    {
        // Ruta relativa al proyecto: public/nurses.json
        $file = __DIR__ . '/../../public/nurses.json';

        // Intentar leer y decodificar el JSON. `true` para obtener array asociativo.
        $nurses = json_decode(file_get_contents($file), true);

        // Resultado por defecto: null indica no encontrado
        $result = null;

        // Si $nurses no es un array (archivo faltante o JSON inválido), evitamos errores
        if (is_array($nurses)) {
            foreach ($nurses as $nurse) {
                // Comparación estricta por nombre
                if (isset($nurse['name']) && $nurse['name'] === $name) {
                    // Construir el resultado con los campos requeridos
                    $result = [
                        'name' => $nurse['name'],
                        'user' => $nurse['user'] ?? null,
                        'password' => $nurse['password'] ?? null,
                    ];
                    break;
                }
            }
        }

        if ($result) {
            // Devolver la enfermera encontrada
            return $this->json($result);
        } else {
            // Devolver error 404 si no se encontró
            return $this->json(['error' => 'Nurse not found'], 404);
        }
    }
}

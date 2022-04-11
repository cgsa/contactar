<?php

namespace Database\Seeders;

use App\Facades\CsvImporter;
use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{

    private $estados = [];
    private $parsed_estados = [];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
        // Cargar información ------------
        $this->loadEstado();

        // Procesar información ----------
        $this->parseDataToSystemFormat();

        // Guardar información -----------
        $this->saveDataToSystem();

        echo "[ESTADOS] Fin!" . PHP_EOL;
        } catch (\Exception $e) {
        echo "[ESTADOS] Ha ocurrido un error: {$e->getMessage()}" . PHP_EOL;
        }
    }

    private function loadEstado()
    {
        try {
            $this->estados = self::readFile(__DIR__ . '/../data/estado_seeder.csv');
            $rubros_count = count($this->estados);
            echo "[ESTADOS] Se han encontrado {$rubros_count} estados." . PHP_EOL;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function parseDataToSystemFormat()
    {
        echo "[ESTADOS] Inicio de transformación de datos" . PHP_EOL;

        $this->parsed_estados = array_map(function ($item) {
        return [
            'descripcion' => $item['descripcion'],
            'seccion' => $item['seccion'],
            'codigo' => $item['codigo'],
        ];

        }, $this->estados);

        echo "[ESTADOS] Fin de la transformación de datos" . PHP_EOL;
    }

    private function saveDataToSystem()
    {
        Estado::insert($this->parsed_estados);
    }

    static function readFile(string $file)
    {
        try {
        $importer = new CsvImporter($file, true, ';');
        $data = $importer->get();
        return $data;
        } catch (\Exception $e) {
        throw $e;
        }
    }
}

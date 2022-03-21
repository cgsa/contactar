<?php

namespace Database\Seeders;

use App\Facades\CsvImporter;
use App\Models\Enacom;
use Illuminate\Database\Seeder;

class EnacomSeeder extends Seeder
{
  
  private $phones = [];
  private $parsed_phones = [];


  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    try {
      // Cargar información ------------
      $this->loadPhone();

      // Procesar información ----------
      $this->parseDataToSystemFormat();

      // Guardar información -----------
      $this->saveDataToSystem();

      echo "[PHONES] Fin!" . PHP_EOL;
    } catch (\Exception $e) {
      echo "[PHONES] Ha ocurrido un error: {$e->getMessage()}" . PHP_EOL;
    }
  }

  private function loadPhone()
  {
    try {
      $this->phones = self::readFile(__DIR__ . '/../data/enacom_seeder.csv');
      $rubros_count = count($this->phones);
      echo "[PHONES] Se han encontrado {$rubros_count} phones." . PHP_EOL;
    } catch (\Throwable $e) {
      throw $e;
    }
  }

  private function parseDataToSystemFormat()
  {
    echo "[PHONE] Inicio de transformación de datos" . PHP_EOL;

    $this->parsed_phones = array_map(function ($item) {
      //var_dump($item);die;
      return [
        'servicio' => "$item[servicio]",
        'modalidad' => "$item[modalidad]",
        'localidad' => "$item[localidad]",
        'indicativo' => "$item[indicativo]",
        'bloque' => "$item[bloque]",
        'resolucion' => "$item[resolucion]",
        'fecha' => "$item[fecha]",
        'is_cel_pho' => $item['is_cel_pho'],
      ];

    }, $this->phones);

    echo "[PHONE] Fin de la transformación de datos" . PHP_EOL;
  }

  private function saveDataToSystem()
  {
      Enacom::insert($this->parsed_phones);
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

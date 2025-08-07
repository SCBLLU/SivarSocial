<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la columna 'id' existe en la tabla comentarios
        $hasId = Schema::hasColumn('comentarios', 'id');
        
        if (!$hasId) {
            // La tabla no tiene id, necesitamos agregarlo
            DB::statement('ALTER TABLE comentarios ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST');
        } else {
            // La tabla tiene id pero puede tener problemas
            // Verificar si hay problemas con el auto_increment
            $tableInfo = DB::select("SHOW CREATE TABLE comentarios")[0];
            $createTableSql = $tableInfo->{'Create Table'};
            
            if (strpos($createTableSql, 'AUTO_INCREMENT') === false) {
                // La columna id existe pero no es AUTO_INCREMENT
                // Primero, encontrar el ID máximo actual
                $maxId = DB::table('comentarios')->max('id') ?? 0;
                
                // Resetear la secuencia y hacer la columna AUTO_INCREMENT
                DB::statement("ALTER TABLE comentarios MODIFY id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY");
                DB::statement("ALTER TABLE comentarios AUTO_INCREMENT = " . ($maxId + 1));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos nada en el rollback para no perder datos
        // En el peor caso, se puede eliminar manualmente la columna id si es necesario
    }
};

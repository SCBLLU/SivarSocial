<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $this->command->info('Creando carreras...');
        // Insertando carreras
        $carreras = [
            // carreras de la ues
            ['nombre' => 'Licenciatura en Enseñanza de la Matemática'],
            ['nombre' => 'Licenciatura en Matemática'],
            ['nombre' => 'Licenciatura en Estadistica y Ciencia de Datos'],
            ['nombre' => 'Profesorado en Matematica para Tercer Ciclo'], // tambien en ugb
            ['nombre' => 'Licenciatura en Enseñanza de las Ciencias Naturales'],
            ['nombre' => 'Licenciatura en Medicina Veterinaria y Zootecnica'], // usam
            ['nombre' => 'Ingeniería en Agroindustria'], // tambien en ugb, matias, uab
            ['nombre' => 'Ingeniería Geologica'],
            ['nombre' => 'Ingeniería Agronómica'],
            ['nombre' => 'Licenciatura en Contaduría Pública'], // tambien en la uca, utec, udb, ugb, ufg, matias, uab, usam
            ['nombre' => 'Licenciatura en Administración de Empresas'], // tambien en la uca, utec, ubd, ugb, ufg, matias, uab, usam
            ['nombre' => 'Licenciatura en Mercadeo'], //tambien en utec, uab, usam
            ['nombre' => 'Licenciatura en Economía'], // tambien en la uca, ufg, matias
            ['nombre' => 'Licenciatura en Fisica'],
            ['nombre' => 'Licenciatura en Geofisica'],
            ['nombre' => 'Licenciatura en Ciencias Quimicas'],
            ['nombre' => 'Licenciatura en Biologia'],
            ['nombre' => 'Licenciatura en Biologia Marina'],
            ['nombre' => 'Arquitectura'], // tambien en la uca, utec, ugb, ufg, matias
            ['nombre' => 'Ingeniería Civil'], // tambien en la uca, ugb
            ['nombre' => 'Ingeniería Eléctrica'], // tambien en la uca, udb, ugb, 
            ['nombre' => 'Ingeniería Industrial'], // tambien en la uca, utec, udb, ugb, ufg, matias 
            ['nombre' => 'Ingeniería en Sistemas Informaticos'], // tambien en la utec, ugb
            ['nombre' => 'Ingeniería Mecánica'], // tambien en la uca, udb,
            ['nombre' => 'Ingeniería de Alimentos'], // tambien en la uca, matias
            ['nombre' => 'Ingeniería Química'], // tambien en la uca,
            ['nombre' => 'Licenciatura en Ciencias Jurídicas'], // tambien en la uca, utec, ugb, ufg, matias, uab, usam
            ['nombre' => 'Licenciatura en Relaciones internacionales'], // tambien en ugb, ufg, matias
            ['nombre' => 'Licenciatura en Ciencia Politica'], //tambien en ufg
            ['nombre' => 'Medicina'], // tambien en matias, usam
            ['nombre' => 'Doctorado en Cirugía Dental'], // usam
            ['nombre' => 'Licenciatura en Quimica y Farmacia'], // usam
            ['nombre' => 'Tecnico en Farmacia Asistencial'],
            // carreras de la uca
            ['nombre' => 'Técnico en Desarrollo de Software'], // tambien en utec, itca, usam
            ['nombre' => 'Ingeniería Energética'],
            ['nombre' => 'Ingeniería Informática'],
            ['nombre' => 'Licenciatura en Ciencias Sociales'],
            ['nombre' => 'Licenciatura en Filosofía'],
            ['nombre' => 'Licenciatura en Idioma Inglés'], // tambien en utec, ugb, ufg, uab
            ['nombre' => 'Licenciatura en Psicología'], //tambien en utec, ugb, ufg, matias, uab
            ['nombre' => 'Licenciatura en Teología'], // tambien en udb
            ['nombre' => 'Técnico en Marketing Digital'], // tambien en utec, ugb, uab, usam
            ['nombre' => 'Técnico en Produccion Multimedia'], //tambien en utec, udb
            ['nombre' => 'Técnico en Mercadeo'], // tambien en utec, uab
            ['nombre' => 'Licenciatura en Comunicación Social'], // tambien en utec, udb, ugb, uab, usam
            ['nombre' => 'Licenciatura en Diseño'],
            ['nombre' => 'Profesorado en Idioma Ingles'], // tambien en utec, 
            ['nombre' => 'Profesorado en Teologia'], // tambien en udb
            ['nombre' => 'Técnico en Contaduria'], // tambien en ufg, uab
            ['nombre' => 'Licenciatura en Finanzas'], // tambien en matias
            //carreras de la utec
            ['nombre' => 'Licenciatura en Diseño Grafico'], // tambien en udb, ufg, matias, uab
            ['nombre' => 'Licenciatura en Criminologia y Ciencias Forenses'], // tambien en ufg
            ['nombre' => 'Técnico en Exportaciones e Importaciones'],
            ['nombre' => 'Técnico en Redes Computacionales'], // usam
            ['nombre' => 'Técnico en Diseño Grafico'], // tambien en udb, ugb, ufg, uab
            ['nombre' => 'Técnico en Automatizacion Industrial'],
            ['nombre' => 'Técnico en Relaciones Públicas'],
            ['nombre' => 'Técnico en Administración Turística'],
            ['nombre' => 'Técnico en Ciberseguridad'],
            ['nombre' => 'Técnico en Logistica'], // tambien en itca
            ['nombre' => 'Licenciatura en Informática'],
            ['nombre' => 'Técnico en Servicios de Alojamiento Turístico'],
            ['nombre' => 'Licenciatura en Negocios Internacionales'],
            ['nombre' => 'Técnico en Comunicación Digital'],
            // carreras udb
            ['nombre' => 'Ingeniería Biomedica'],
            ['nombre' => 'Ingeniería en Ciencias de la Computacion'], // usam
            ['nombre' => 'Ingeniería Mecatrónica'], // tambien en itca
            ['nombre' => 'Ingeniería Aeronautica'],
            ['nombre' => 'Ingeniería Electronica'], // tambien en itca, matias
            ['nombre' => 'Ingeniería en Telecomunicaciones y Redes'],
            ['nombre' => 'Licenciatura en Idiomas con Especialidad en la Adquisicion de Lenguas Extranjeras'],
            ['nombre' => 'Licenciatura en Idiomas con Especialidad en Turismo'],
            ['nombre' => 'Licenciatura en Diseño Industrial'],
            ['nombre' => 'Licenciatura en Marketing'], // tambien en ugb, matias
            ['nombre' => 'Técnico en Ingenieria Mecánica'],
            ['nombre' => 'Técnico en Ingenieria Electrica'],
            ['nombre' => 'Técnico en Ingenieria Electrónica'], // tambien en itca
            ['nombre' => 'Técnico en Ingenieria Biomedica'],
            ['nombre' => 'Técnico en Ingenieria en Computacion'], // tambien en uab
            ['nombre' => 'Técnico en Control de Calidad'],
            ['nombre' => 'Técnico en Mantenimiento Aeronautico'],
            ['nombre' => 'Técnico en Ortesis y Protesis'],
            ['nombre' => 'Técnico en Guia de Turismo Bilingüe'],
            ['nombre' => 'Técnico en Asesoria Financiera Sostenible'],
            ['nombre' => 'Técnico en Gestion del Talento Humano'],
            // carreras ugb
            ['nombre' => 'Técnico en Idioma Inglés'],
            ['nombre' => 'Técnico en Ingeniería Industrial'], // tambien en itca
            ['nombre' => 'Ingeniería en Inteligencia de Negocios'],
            ['nombre' => 'Ingeniería en Desarrollo de Software'], // tambien en ufg, itca
            ['nombre' => 'Técnico en Ingeniería en Sistemas Redes Informaticas'],
            ['nombre' => 'Licenciatura en Enfermería'], // tambien en matias, uab, usam
            ['nombre' => 'Técnico en Ingeniería Civil y Construcción'],
            ['nombre' => 'Licenciatura en Educación Inicial y Parvularia'],
            ['nombre' => 'Profesorado en Educación Inicial y Parvularia'],
            ['nombre' => 'Profesorado en Lenguaje y Literatura para Tercer Ciclo'],
            ['nombre' => 'Profesorado en Idioma Ingles para Tecer Ciclo'],
            ['nombre' => 'Técnico en Enfermería'], // tambien en uab
            //carreras ufg
            ['nombre' => 'Licenciatura en Animación Digital y Videojuegos'],
            ['nombre' => 'Licenciatura en Diseño de Modas'],
            ['nombre' => 'Técnico en Animación Digital y Videojuegos'],
            ['nombre' => 'Técnico en Decoración'],
            ['nombre' => 'Ingeniería en Control Eléctrico'],
            ['nombre' => 'Ingeniería en Diseño y Desarrollo de Videojuegos'],
            ['nombre' => 'Ingeniería en Inteligencia Artificial y Robótica'],
            ['nombre' => 'Ingeniería en Sistemas y Ciberseguridad'],
            ['nombre' => 'Ingeniería en Telecomunicaciones'],
            ['nombre' => 'Licenciatura en Sistemas Informáticos'], // usam
            ['nombre' => 'Técnico en Sistemas de Computación'], // usam
            ['nombre' => 'Licenciatura en Administración de Empresas Turísticas'],
            ['nombre' => 'Licenciatura en Comunicación Corporativa'],
            ['nombre' => 'Licenciatura en Gestión Estratégica de Hoteles y Restaurantes'],
            ['nombre' => 'Licenciatura en Mercadotecnia y Publicidad'],
            ['nombre' => 'Licenciatura en Sistemas de Computación Administrativa'],
            ['nombre' => 'Técnico en Administración de Restaurantes'],
            ['nombre' => 'Técnico en Guía Turístico'],
            ['nombre' => 'Técnico en Publicidad'], // usam
            ['nombre' => 'Técnico en Ventas'],
            ['nombre' => 'Licenciatura en Atención a la Primera Infancia'],
            ['nombre' => 'Licenciatura en Trabajo Social'], // tambien uab
            // carreras itca
            ['nombre' => 'Técnico en Ingeniería Civil'],
            ['nombre' => 'Técnico en Gastronomía'],
            ['nombre' => 'Técnico en Ingeniería Mecanica Opción Mantenimiento Industrial'],
            ['nombre' => 'Técnico en Laboratorio Químico'],
            ['nombre' => 'Técnico en Administración  de Empresas Gastronómicas'],
            ['nombre' => 'Técnico en Ingeniería Mecanica y Electromovilidad Automotriz'],
            ['nombre' => 'Técnico en Energías Renovables'],
            ['nombre' => 'Técnico en Ingeniería en Informática Inteligente'],
            ['nombre' => 'Técnico en Arquitectura'],
            ['nombre' => 'Técnico en Ingeniería Mecanica Opción CNC'],
            ['nombre' => 'Técnico en Química Industrial'],
            ['nombre' => 'Técnico en Ingeniería en Infraestructura de Redes Informáticas'],
            ['nombre' => 'Técnico en Ingeniería Mecatrónica'],
            ['nombre' => 'Técnico en Hardware Computacional'],
            ['nombre' => 'Técnico en Ingeniería de Manufactura Inteligente'],
            ['nombre' => 'Técnico en Hostelería y Turismo'],
            ['nombre' => 'Ingeniería Logística y Aduana'],
            //carreras matias
            ['nombre' => 'Licenciatura en Gestión Tecnológica y Analítica de Datos'],
            ['nombre' => 'Licenciatura en Turismo'],
            ['nombre' => 'Licenciatura en Ciencias de la Comunicación'],
            ['nombre' => 'Licenciatura en Diseño del Producto Artesanal'],
            ['nombre' => 'Arquitectura de Interiores'],
            ['nombre' => 'Licenciatura en Innovación y Transformación Digital'],
            ['nombre' => 'Ingeniería Logística y Distribución'],
            ['nombre' => 'Ingeniería en Agrobiotecnología'],
            ['nombre' => 'Ingeniería en Gestión Ambiental'],
            ['nombre' => 'Licenciatura en Música'],
            ['nombre' => 'Técnico en Artes Dramáticas'],
            // carreras uab
            ['nombre' => 'Licenciatura en Relaciones Públicas'],
            ['nombre' => 'Licenciatura en Gestión del Turismo'],
            ['nombre' => 'Técnico en Turismo'],
            ['nombre' => 'Técnico en Salvamentos y Extinción de Incendios'],
            ['nombre' => 'Técnico en Gestión de Riesgo de Desastres'],
            ['nombre' => 'Ingeniería en Sistemas y Computación'],
            ['nombre' => 'Licenciatura en Computación Gerencial'],
            ['nombre' => 'Licenciatura en Laboratorio Clínico'],
            ['nombre' => 'Licenciatura en Radiología e Imágenes'],
            ['nombre' => 'Licenciatura en Nutrición'],
            ['nombre' => 'Licenciatura en Optometría'],
            ['nombre' => 'Técnico en Optometría'],
        ];

        foreach ($carreras as $carreraData) {
            Carrera::create($carreraData);
        }

        $totalcarreras = Carrera::count();
        $this->command->info("Total de carreras creadas: {$totalcarreras}");
    }
}

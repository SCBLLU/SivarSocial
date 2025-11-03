<?php

namespace Database\Seeders;

use App\Models\Universidad;
use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraUniversidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los IDs de universidades
        $ues = Universidad::where('nombre', 'Universidad de El Salvador')->first();
        $uca = Universidad::where('nombre', 'Universidad Centroamericana José Simeón Cañas')->first();
        $utec = Universidad::where('nombre', 'Universidad Tecnológica de El Salvador')->first();
        $udb = Universidad::where('nombre', 'Universidad Don Bosco')->first();
        $ugb = Universidad::where('nombre', 'Universidad Gerardo Barrios')->first();
        $ufg = Universidad::where('nombre', 'Universidad Francisco Gavidia')->first();
        $itca = Universidad::where('nombre', 'ITCA-FEPADE')->first();
        $matias = Universidad::where('nombre', 'Universidad Doctor José Matías Delgado')->first();
        $uab = Universidad::where('nombre', 'Universidad Dr. Andrés Bello')->first();
        $usam = Universidad::where('nombre', 'Universidad Salvadoreña Alberto Masferrer')->first();
        // obtener Ids de carreras
        $enmate = Carrera::where('nombre', 'Licenciatura en Enseñanza de la Matemática')->first();
        $mate = Carrera::where('nombre', 'Licenciatura en Matemática')->first();
        $estadistica = Carrera::where('nombre', 'Licenciatura en Estadistica y Ciencia de Datos')->first();
        $pmate = Carrera::where('nombre', 'Profesorado en Matematica para Tercer Ciclo')->first();
        $enciencias = Carrera::where('nombre', 'Licenciatura en Enseñanza de las Ciencias Naturales')->first();
        $vete = Carrera::where('nombre', 'Licenciatura en Medicina Veterinaria y Zootecnica')->first();
        $agroindustria = Carrera::where('nombre', 'Ingeniería en Agroindustria')->first();
        $geologia = Carrera::where('nombre', 'Ingeniería Geologica')->first();
        $agronomica = Carrera::where('nombre', 'Ingeniería Agronómica')->first();
        $conta = Carrera::where('nombre', 'Licenciatura en Contaduría Pública')->first();
        $administracion = Carrera::where('nombre', 'Licenciatura en Administración de Empresas')->first();
        $mercadeo = Carrera::where('nombre', 'Licenciatura en Mercadeo')->first();
        $economia = Carrera::where('nombre', 'Licenciatura en Economía')->first();
        $fisica = Carrera::where('nombre', 'Licenciatura en Fisica')->first();
        $geofisica = Carrera::where('nombre', 'Licenciatura en Geofisica')->first();
        $licquimica = Carrera::where('nombre', 'Licenciatura en Ciencias Quimicas')->first();
        $biologia = Carrera::where('nombre', 'Licenciatura en Biologia')->first();
        $biologiem = Carrera::where('nombre', 'Licenciatura en Biologia Marina')->first();
        $arquitectura = Carrera::where('nombre', 'Arquitectura')->first();
        $civil = Carrera::where('nombre', 'Ingeniería Civil')->first();
        $electrica = Carrera::where('nombre', 'Ingeniería Eléctrica')->first();
        $industrial = Carrera::where('nombre', 'Ingeniería Industrial')->first();
        $sistemas = Carrera::where('nombre', 'Ingeniería en Sistemas Informaticos')->first();
        $mecanica = Carrera::where('nombre', 'Ingeniería Mecánica')->first();
        $alimentos = Carrera::where('nombre', 'Ingeniería de Alimentos')->first();
        $quimica = Carrera::where('nombre', 'Ingeniería Química')->first();
        $derecho = Carrera::where('nombre', 'Licenciatura en Ciencias Jurídicas')->first();
        $relaciones = Carrera::where('nombre', 'Licenciatura en Relaciones internacionales')->first();
        $cienciap = Carrera::where('nombre', 'Licenciatura en Ciencia Politica')->first();
        $medicina = Carrera::where('nombre', 'Medicina')->first();
        $odonto = Carrera::where('nombre', 'Doctorado en Cirugía Dental')->first();
        $farmacia = Carrera::where('nombre', 'Licenciatura en Quimica y Farmacia')->first();
        $tecfarmacia = Carrera::where('nombre', 'Tecnico en Farmacia Asistencial')->first();

        $tecsoftware = Carrera::where('nombre', 'Técnico en Desarrollo de Software')->first();
        $energetica = Carrera::where('nombre', 'Ingeniería Energética')->first();
        $informatica = Carrera::where('nombre', 'Ingeniería Informática')->first();
        $cienciass = Carrera::where('nombre', 'Licenciatura en Ciencias Sociales')->first();
        $filosofia = Carrera::where('nombre', 'Licenciatura en Filosofía')->first();
        $ingles = Carrera::where('nombre', 'Licenciatura en Idioma Inglés')->first();
        $psicologia = Carrera::where('nombre', 'Licenciatura en Psicología')->first();
        $teologia = Carrera::where('nombre', 'Licenciatura en Teología')->first();
        $tecmarketing = Carrera::where('nombre', 'Técnico en Marketing Digital')->first();
        $tecmulti = Carrera::where('nombre', 'Técnico en Produccion Multimedia')->first();
        $tecmerca = Carrera::where('nombre', 'Técnico en Mercadeo')->first();
        $comunicacions = Carrera::where('nombre', 'Licenciatura en Comunicación Social')->first();
        $diseno = Carrera::where('nombre', 'Licenciatura en Diseño')->first();
        $pingles = Carrera::where('nombre', 'Profesorado en Idioma Ingles')->first();
        $pteologia = Carrera::where('nombre', 'Profesorado en Teologia')->first();
        $tecconta = Carrera::where('nombre', 'Técnico en Contaduria')->first();
        $finanzas = Carrera::where('nombre', 'Licenciatura en Finanzas')->first();

        $disenog = Carrera::where('nombre', 'Licenciatura en Diseño Grafico')->first();
        $forense = Carrera::where('nombre', 'Licenciatura en Criminologia y Ciencias Forenses')->first();
        $tecexport = Carrera::where('nombre', 'Técnico en Exportaciones e Importaciones')->first();
        $tecredes = Carrera::where('nombre', 'Técnico en Redes Computacionales')->first();
        $tecdisenog = Carrera::where('nombre', 'Técnico en Diseño Grafico')->first();
        $tecauto = Carrera::where('nombre', 'Técnico en Automatizacion Industrial')->first();
        $tecrela = Carrera::where('nombre', 'Técnico en Relaciones Públicas')->first();
        $tecadmin = Carrera::where('nombre', 'Técnico en Administración Turística')->first();
        $tecciber = Carrera::where('nombre', 'Técnico en Ciberseguridad')->first();
        $teclogis = Carrera::where('nombre', 'Técnico en Logistica')->first();
        $licinfor = Carrera::where('nombre', 'Licenciatura en Informática')->first();
        $terservi = Carrera::where('nombre', 'Técnico en Servicios de Alojamiento Turístico')->first();
        $tecnegocios = Carrera::where('nombre', 'Licenciatura en Negocios Internacionales')->first();
        $teccomu = Carrera::where('nombre', 'Técnico en Comunicación Digital')->first();

        $biomedi = Carrera::where('nombre', 'Ingeniería Biomedica')->first();
        $computacion = Carrera::where('nombre', 'Ingeniería en Ciencias de la Computacion')->first();
        $mecatronica = Carrera::where('nombre', 'Ingeniería Mecatrónica')->first();
        $aero = Carrera::where('nombre', 'Ingeniería Aeronautica')->first();
        $electronica = Carrera::where('nombre', 'Ingeniería Electronica')->first();
        $tele = Carrera::where('nombre', 'Ingeniería en Telecomunicaciones y Redes')->first();
        $idiomas = Carrera::where('nombre', 'Licenciatura en Idiomas con Especialidad en la Adquisicion de Lenguas Extranjeras')->first();
        $idiomast = Carrera::where('nombre', 'Licenciatura en Idiomas con Especialidad en Turismo')->first();
        $disenoi = Carrera::where('nombre', 'Licenciatura en Diseño Industrial')->first();
        $marketing = Carrera::where('nombre', 'Licenciatura en Marketing')->first();
        $tecmeca = Carrera::where('nombre', 'Técnico en Ingenieria Mecánica')->first();
        $tecelec = Carrera::where('nombre', 'Técnico en Ingenieria Electrica')->first();
        $tecelectro = Carrera::where('nombre', 'Técnico en Ingenieria Electrónica')->first();
        $tecbio = Carrera::where('nombre', 'Técnico en Ingenieria Biomedica')->first();
        $teccompu = Carrera::where('nombre', 'Técnico en Ingenieria en Computacion')->first();
        $teccontrol = Carrera::where('nombre', 'Técnico en Control de Calidad')->first();
        $tecaero = Carrera::where('nombre', 'Técnico en Mantenimiento Aeronautico')->first();
        $tecprote = Carrera::where('nombre', 'Técnico en Ortesis y Protesis')->first();
        $tecturi = Carrera::where('nombre', 'Técnico en Guia de Turismo Bilingüe')->first();
        $tecfinan = Carrera::where('nombre', 'Técnico en Asesoria Financiera Sostenible')->first();
        $tecgestion = Carrera::where('nombre', 'Técnico en Gestion del Talento Humano')->first();

        $tecingles = Carrera::where('nombre', 'Técnico en Idioma Inglés')->first();
        $tecindustrial = Carrera::where('nombre', 'Técnico en Ingeniería Industrial')->first();
        $ingnegocios = Carrera::where('nombre', 'Ingeniería en Inteligencia de Negocios')->first();
        $software = Carrera::where('nombre', 'Ingeniería en Desarrollo de Software')->first();
        $redesi = Carrera::where('nombre', 'Técnico en Ingeniería en Sistemas Redes Informaticas')->first();
        $enfermeria = Carrera::where('nombre', 'Licenciatura en Enfermería')->first();
        $teccivil = Carrera::where('nombre', 'Técnico en Ingeniería Civil y Construcción')->first();
        $educacion = Carrera::where('nombre', 'Licenciatura en Educación Inicial y Parvularia')->first();
        $peducacion = Carrera::where('nombre', 'Profesorado en Educación Inicial y Parvularia')->first();
        $plenguaje = Carrera::where('nombre', 'Profesorado en Lenguaje y Literatura para Tercer Ciclo')->first();
        $pidioma = Carrera::where('nombre', 'Profesorado en Idioma Ingles para Tecer Ciclo')->first();
        $tecenfermeria = Carrera::where('nombre', 'Técnico en Enfermería')->first();

        $animacion = Carrera::where('nombre', 'Licenciatura en Animación Digital y Videojuegos')->first();
        $disenom = Carrera::where('nombre', 'Licenciatura en Diseño de Modas')->first();
        $tecanimacion = Carrera::where('nombre', 'Técnico en Animación Digital y Videojuegos')->first();
        $tecdecoracion = Carrera::where('nombre', 'Técnico en Decoración')->first();
        $controle = Carrera::where('nombre', 'Ingeniería en Control Eléctrico')->first();
        $videojuegos = Carrera::where('nombre', 'Ingeniería en Diseño y Desarrollo de Videojuegos')->first();
        $ia = Carrera::where('nombre', 'Ingeniería en Inteligencia Artificial y Robótica')->first();
        $sistemasc = Carrera::where('nombre', 'Ingeniería en Sistemas y Ciberseguridad')->first();
        $ingtele = Carrera::where('nombre', 'Ingeniería en Telecomunicaciones')->first();
        $licsistemas = Carrera::where('nombre', 'Licenciatura en Sistemas Informáticos')->first();
        $tecsistemas = Carrera::where('nombre', 'Técnico en Sistemas de Computación')->first();
        $admturist = Carrera::where('nombre', 'Licenciatura en Administración de Empresas Turísticas')->first();
        $comunicacionc = Carrera::where('nombre', 'Licenciatura en Comunicación Corporativa')->first();
        $gestionh = Carrera::where('nombre', 'Licenciatura en Gestión Estratégica de Hoteles y Restaurantes')->first();
        $mercadotecnia = Carrera::where('nombre', 'Licenciatura en Mercadotecnia y Publicidad')->first();
        $licsistemasc = Carrera::where('nombre', 'Licenciatura en Sistemas de Computación Administrativa')->first();
        $tecadminr = Carrera::where('nombre', 'Técnico en Administración de Restaurantes')->first();
        $tecguiat = Carrera::where('nombre', 'Técnico en Guía Turístico')->first();
        $tecpubli = Carrera::where('nombre', 'Técnico en Publicidad')->first();
        $tecventas = Carrera::where('nombre', 'Técnico en Ventas')->first();
        $atencion = Carrera::where('nombre', 'Licenciatura en Atención a la Primera Infancia')->first();
        $trabajo = Carrera::where('nombre', 'Licenciatura en Trabajo Social')->first();

        $tecciv = Carrera::where('nombre', 'Técnico en Ingeniería Civil')->first();
        $gastro = Carrera::where('nombre', 'Técnico en Gastronomía')->first();
        $tecmecam = Carrera::where('nombre', 'Técnico en Ingeniería Mecanica Opción Mantenimiento Industrial')->first();
        $teclab = Carrera::where('nombre', 'Técnico en Laboratorio Químico')->first();
        $tecempresas = Carrera::where('nombre', 'Técnico en Administración  de Empresas Gastronómicas')->first();
        $tecmecae = Carrera::where('nombre', 'Técnico en Ingeniería Mecanica y Electromovilidad Automotriz')->first();
        $tecenergia = Carrera::where('nombre', 'Técnico en Energías Renovables')->first();
        $tecinfor = Carrera::where('nombre', 'Técnico en Ingeniería en Informática Inteligente')->first();
        $tecarqui = Carrera::where('nombre', 'Técnico en Arquitectura')->first();
        $tecmecac = Carrera::where('nombre', 'Técnico en Ingeniería Mecanica Opción CNC')->first();
        $tecquimica = Carrera::where('nombre', 'Técnico en Química Industrial')->first();
        $tecinfra = Carrera::where('nombre', 'Técnico en Ingeniería en Infraestructura de Redes Informáticas')->first();
        $tecmecat = Carrera::where('nombre', 'Técnico en Ingeniería Mecatrónica')->first();
        $techardware = Carrera::where('nombre', 'Técnico en Hardware Computacional')->first();
        $tecmanu = Carrera::where('nombre', 'Técnico en Ingeniería de Manufactura Inteligente')->first();
        $techosteleria = Carrera::where('nombre', 'Técnico en Hostelería y Turismo')->first();
        $logistica = Carrera::where('nombre', 'Ingeniería Logística y Aduana')->first();
        
        $gestiont = Carrera::where('nombre', 'Licenciatura en Gestión Tecnológica y Analítica de Datos')->first();
        $turismo = Carrera::where('nombre', 'Licenciatura en Turismo')->first();
        $cienciasc = Carrera::where('nombre', 'Licenciatura en Ciencias de la Comunicación')->first();
        $disenop = Carrera::where('nombre', 'Licenciatura en Diseño del Producto Artesanal')->first();
        $arquiint = Carrera::where('nombre', 'Arquitectura de Interiores')->first();
        $innovacion = Carrera::where('nombre', 'Licenciatura en Innovación y Transformación Digital')->first();
        $logisticad = Carrera::where('nombre', 'Ingeniería Logística y Distribución')->first();
        $agrobio = Carrera::where('nombre', 'Ingeniería en Agrobiotecnología')->first();
        $gestiona = Carrera::where('nombre', 'Ingeniería en Gestión Ambiental')->first();
        $musica = Carrera::where('nombre', 'Licenciatura en Música')->first();
        $tecartes = Carrera::where('nombre', 'Técnico en Artes Dramáticas')->first();
    
        $relap = Carrera::where('nombre', 'Licenciatura en Relaciones Públicas')->first();
        $gestionturi = Carrera::where('nombre', 'Licenciatura en Gestión del Turismo')->first();
        $tect = Carrera::where('nombre', 'Técnico en Turismo')->first();
        $tecsal = Carrera::where('nombre', 'Técnico en Salvamentos y Extinción de Incendios')->first();
        $tecriesgos = Carrera::where('nombre', 'Técnico en Gestión de Riesgo de Desastres')->first();
        $sistemasyc = Carrera::where('nombre', 'Ingeniería en Sistemas y Computación')->first();
        $compg = Carrera::where('nombre', 'Licenciatura en Computación Gerencial')->first();
        $labocli = Carrera::where('nombre', 'Licenciatura en Laboratorio Clínico')->first();
        $radio = Carrera::where('nombre', 'Licenciatura en Radiología e Imágenes')->first();
        $nutri = Carrera::where('nombre', 'Licenciatura en Nutrición')->first();
        $opto = Carrera::where('nombre', 'Licenciatura en Optometría')->first();
        $tecopto = Carrera::where('nombre', 'Técnico en Optometría')->first();

        // Relaciones de muchos a muchos
        $ues->carreras()->attach([$enmate->id, $mate->id, $estadistica->id, $pmate->id, $enciencias->id, $vete->id, $agroindustria->id, $geologia->id, $agronomica->id, $conta->id,
            $administracion->id, $mercadeo->id, $economia->id, $fisica->id, $geofisica->id, $licquimica->id, $biologia->id, $biologiem->id, $arquitectura->id, $civil->id, $electrica->id,
            $industrial->id, $sistemas->id, $mecanica->id, $alimentos->id, $quimica->id, $derecho->id, $relaciones->id, $cienciap->id, $medicina->id, $odonto->id, $farmacia->id, $tecfarmacia->id]);

        $uca->carreras()->attach([$tecsoftware->id, $energetica->id, $informatica->id, $cienciass->id, $filosofia->id, $ingles->id, $psicologia->id, $teologia->id, $tecmarketing->id,
            $tecmulti->id, $tecmerca->id, $comunicacions->id, $diseno->id, $pingles->id, $pteologia->id, $tecconta->id, $finanzas->id, $conta->id, $administracion->id, $economia->id, 
            $arquitectura->id, $civil->id,$electrica->id, $industrial->id, $mecanica->id, $alimentos->id, $quimica->id, $derecho->id]);

        $utec->carreras()->attach([$disenog->id, $forense->id, $tecexport->id, $tecredes->id, $tecdisenog->id, $tecauto->id, $tecrela->id, $tecadmin->id, $tecciber->id, $teclogis->id,
            $licinfor->id, $terservi->id, $tecnegocios->id, $teccomu->id, $pingles->id, $comunicacions->id, $tecmerca->id, $tecmulti->id, $tecmarketing->id, $psicologia->id, $ingles->id,
            $tecsoftware->id, $derecho->id, $sistemas->id, $industrial->id, $arquitectura->id, $mercadeo->id, $administracion->id, $conta->id]);

        $udb->carreras()->attach([$biomedi->id, $computacion->id, $mecatronica->id, $aero->id, $electronica->id, $tele->id, $idiomas->id, $idiomast->id, $disenoi->id, $marketing->id, 
            $tecmeca->id, $tecelec->id, $tecelectro->id, $tecbio->id, $teccompu->id, $teccontrol->id, $tecaero->id, $tecprote->id, $tecturi->id, $tecfinan->id, $tecgestion->id, $tecdisenog->id, 
            $disenog->id, $pteologia->id, $comunicacions->id, $tecmulti->id, $teologia->id, $mecanica->id, $industrial->id, $electrica->id, $administracion->id, $conta->id]);

        $ugb->carreras()->attach([$tecingles->id, $tecindustrial->id, $ingnegocios->id, $software->id, $redesi->id, $enfermeria->id, $teccivil->id, $educacion->id, $peducacion->id, $plenguaje->id, 
            $pidioma->id, $marketing->id, $tecdisenog->id, $comunicacions->id, $tecmarketing->id, $psicologia->id, $ingles->id, $relaciones->id, $sistemas->id, $industrial->id, $electrica->id, $civil->id, 
            $arquitectura->id, $tecenfermeria->id, $administracion->id, $conta->id, $agroindustria->id, $pmate->id]);
    
        $ufg->carreras()->attach([$animacion->id, $disenom->id, $tecanimacion->id, $tecdecoracion->id, $controle->id, $videojuegos->id, $ia->id, $sistemasc->id, $ingtele->id, $licsistemas->id, 
            $tecsistemas->id, $admturist->id, $comunicacionc->id, $gestionh->id, $mercadotecnia->id, $licsistemasc->id, $tecadminr->id, $tecguiat->id, $tecpubli->id, $tecventas->id, $atencion->id, $trabajo->id, 
            $software->id, $tecdisenog->id, $forense->id, $tecconta->id, $psicologia->id, $ingles->id, $cienciap->id, $relaciones->id, $derecho->id, $industrial->id, $arquitectura->id, $economia->id, $administracion->id, $conta->id]);

        $itca->carreras()->attach([$tecciv->id, $gastro->id, $tecmecam->id, $teclab->id, $tecempresas->id, $tecmecae->id, $tecenergia->id, $tecinfor->id, $tecarqui->id, $tecmecac->id, 
            $tecquimica->id, $tecinfra->id, $tecmecat->id, $techardware->id, $tecmanu->id, $software->id, $tecindustrial->id, $tecelectro->id, $electronica->id, $mecatronica->id, $tecsoftware->id, $electrica->id, 
            $techosteleria->id, $logistica->id, $teclogis->id]);

        $matias->carreras()->attach([$gestiont->id, $turismo->id, $cienciasc->id, $disenop->id, $arquiint->id, $innovacion->id, $logisticad->id, $agrobio->id, $gestiona->id, $musica->id, 
            $tecartes->id, $enfermeria->id, $marketing->id, $electronica->id, $disenog->id, $finanzas->id, $psicologia->id, $medicina->id, $relaciones->id, $derecho->id, $alimentos->id, $industrial->id,
            $arquitectura->id, $economia->id, $administracion->id, $conta->id, $agroindustria->id]);

        $uab->carreras()->attach([$relap->id, $gestionturi->id, $tect->id, $tecsal->id, $tecriesgos->id, $sistemasyc->id, $compg->id, $labocli->id, $radio->id, $nutri->id, 
            $opto->id, $tecopto->id, $trabajo->id, $tecenfermeria->id, $enfermeria->id, $teccompu->id, $tecdisenog->id, $disenog->id, $tecconta->id, $comunicacions->id, $tecmerca->id, $tecmarketing->id, 
            $psicologia->id, $ingles->id, $derecho->id, $conta->id, $administracion->id, $agroindustria->id]);

        $usam->carreras()->attach([$tecpubli->id, $tecsistemas->id, $licsistemas->id, $enfermeria->id, $computacion->id, $tecredes->id, $comunicacions->id, $tecmarketing->id, $tecsoftware->id, $quimica->id, 
            $odonto->id, $medicina->id, $derecho->id, $mercadeo->id, $administracion->id, $conta->id, $vete->id]);
    }
}

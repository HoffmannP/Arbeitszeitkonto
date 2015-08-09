<?php

namespace Ber\Arbeitszeitkonto;

setlocale(LC_ALL , 'de_DE.UTF8');

spl_autoload_register(function ($class_name) {
    require_once __DIR__ . '/' . str_replace('\\', '/', $class_name) . '.class.php';
});


$sqliteConnection = 'sqlite:Arbeitszeit.sqlite';
$Stundenzettel = new Arbeitszeitkonto($sqliteConnection);

$monat = array_key_exists('monat', $_GET) ? $_GET['monat'] : (new \DateTime())->format('m');
$jahr = array_key_exists('jahr', $_GET) ? $_GET['jahr'] : (new \DateTime())->format('y');

$monatDT = new \DateTimeImmutable($jahr . '-' . $monat . '-1');
$Monat = $Stundenzettel->monat($monatDT);

include('Stundenzettel.template.php');
?>

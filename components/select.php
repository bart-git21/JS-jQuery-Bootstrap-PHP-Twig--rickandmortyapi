<?php
require_once 'vendor/autoload.php';
$twigLoader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($twigLoader);

$nameSelectOptions = [
    'Rick' => 'Rick',
    'The' => 'The',
    'Robot' => 'Robot',
    'Mr.' => 'Mr.',
];
echo $twig->render('select.html.twig', [
    'selectName' => 'Name',
    'selectId' => 'nameSelect',
    'options' => $nameSelectOptions
]);

$statusSelectOptions = [
    'alive' => 'alive',
    'unknown' => 'unknown',
    'Dead' => 'Dead',
];
echo $twig->render('select.html.twig', [
    'selectName' => 'Status',
    'selectId' => 'statusSelect',
    'options' => $statusSelectOptions
]);

$genderSelectOptions = [
    'female' => 'female',
    'male' => 'male',
    'genderless' => 'genderless',
    'unknown' => 'unknown',
];
echo $twig->render('select.html.twig', [
    'selectName' => 'Gender',
    'selectId' => 'genderSelect',
    'options' => $genderSelectOptions
]);

$speciesSelectOptions = [
    'Humanoid' => 'Humanoid',
    'Human' => 'Human',
    'Alien' => 'Alien',
    'Robot' => 'Robot',
];
echo $twig->render('select.html.twig', [
    'selectName' => 'Species',
    'selectId' => 'speciesSelect',
    'options' => $speciesSelectOptions
]);

<?php
require_once 'vendor/autoload.php';
$twigLoader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($twigLoader);

echo "<h2>Location:</h2>";
echo $twig->render('input.html.twig', [
    'inputName' => 'List the ids:',
    'inputId' => 'locationInput',
]);

echo "<h2>Episode:</h2>";
echo $twig->render('input.html.twig', [
    'inputName' => 'List the ids:',
    'inputId' => 'episodeInput',
]);

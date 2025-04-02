<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';

$app = AppFactory::create();

$app->get('/alunni', "AlunniController:indexAlunno");

$app->get('/alunni/{id}', "AlunniController:viewAlunno");

$app->post('/alunni', "AlunniController:createAlunno");

$app->put('/alunni/{id}', "AlunniController:updateAlunno");

$app->delete('/alunni/{id}', "AlunniController:deleteAlunno");

$app->get('/certificazioni', "AlunniController:indexCertificazione");

$app->get('/alunni/{id}/certificazioni', "AlunniController:viewCertificazione");

$app->post('/certificazioni', "AlunniController:createCertificazione");

$app->put('/certificazioni/{id}', "AlunniController:updateCertificazione");

$app->delete('/certificazioni/{id}', "AlunniController:deleteCertificazione");

$app->run();

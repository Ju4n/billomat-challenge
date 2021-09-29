<?php

/** @var \Laravel\Lumen\Routing\Router $router */


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/profiles', 'ProfileController@getProfiles');
$router->get('/profiles/{profileId}', 'ProfileController@getProfile');
$router->get('/profiles/average/age', 'ProfileController@getProfilesAverageAge');
$router->post('/profiles', 'ProfileController@createProfile');
$router->put('/profiles/{profileId}', 'ProfileController@updateProfile');
$router->delete('/profiles/{profileId}', 'ProfileController@deleteProfile');

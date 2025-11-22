<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/

//$router->get('/', 'Welcome::index');
// ===================================================================
// AUTH & PUBLIC ROUTES
// ===================================================================
$router->post('/login', 'ApiController::login');
$router->post('/logout', 'ApiController::logout');
$router->post('/refresh', 'ApiController::refresh');
$router->post('/otp', 'ApiController::sendVerificationCode');
$router->post('/otp/verify', 'ApiController::verifyCode');
$router->get('/email', 'ApiController::sendTestEmail'); // test only
// ===================================================================
// USER MANAGEMENT — CLEAN SINGULAR ROUTES (PRO LEVEL)
// ===================================================================
$router->group('/api/user', function () use ($router) {
    $router->post('create', 'ApiController::create');
    $router->get('list', 'ApiController::list');
    $router->get('profile', 'ApiController::profile');
    $router->put('update/{id}', 'ApiController::update');
    $router->delete('delete/{id}', 'ApiController::delete');
});

// ===================================================================
// CARS MANAGEMENT
// ===================================================================
$router->get('/listcars', 'ApiController::listCars');
$router->post('/createcars', 'ApiController::createCars');
$router->put('/updatecars/{id}', 'ApiController::updateCars');
$router->delete('/deletecars/{id}', 'ApiController::deleteCars');
$router->get('/searchcars', 'ApiController::listCarsPaginated');
$router->post('/upload-car-image', 'ApiController::uploadCarImage');

// ===================================================================
// APPOINTMENTS
// ===================================================================
$router->post('/createappointment', 'ApiController::createAppointment');
$router->get('/listappointment', 'ApiController::listAppointments');
$router->put('/updateappointment/{id}', 'ApiController::updateAppointment');

// ===================================================================
// DEALERS MANAGEMENT
// ===================================================================
$router->get('/dealers', 'ApiController::listDealers');
$router->post('/dealers', 'ApiController::createDealer');
$router->put('/dealers/{id}', 'ApiController::updateDealer');
$router->delete('/dealers/{id}', 'ApiController::deleteDealer');
$router->post('/dealers/upload-logo', 'ApiController::uploadDealerLogo');
// ===================================================================
// FILES
// ===================================================================
$router->get('/download/{filename}', 'ApiController::downloadFile');
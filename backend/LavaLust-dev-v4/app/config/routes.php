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
$router->post('login', 'ApiController::login');
$router->post('logout', 'ApiController::logout');
$router->post('create', 'ApiController::create');
$router->put('update/{id}', 'ApiController::update');
$router->delete('delete/{id}', 'ApiController::delete');
$router->get('list', 'ApiController::list');
$router->get('profile', 'ApiController::profile');
$router->post('refresh', 'ApiController::refresh');
$router->get('listcars', 'ApiController::listCars');
$router->post('createcars', 'ApiController::createCars');
$router->put('updatecars/{id}', 'ApiController::updateCars');
$router->delete('deletecars/{id}', 'ApiController::deleteCars');
$router->post('/otp', 'ApiController::sendVerificationCode');   // send OTP
$router->post('/otp/verify', 'ApiController::verifyCode');      // verify OTP
$router->get('/email', 'ApiController::sendTestEmail');         // test route
$router->get('/searchcars', 'ApiController::listCarsPaginated');
$router->post('createappointment', 'ApiController::createAppointment');
$router->get('listappointment', 'ApiController::listAppointments');
$router->put('updateappointment/{id}', 'ApiController::updateAppointment');




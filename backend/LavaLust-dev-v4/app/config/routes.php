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
//--- Appointment Routes ---
$router->get('/api/appointments', 'AppointmentController::index');
$router->get('/api/appointments/(:num)', 'AppointmentController::show/$1');
$router->post('/api/appointments', 'AppointmentController::store');
$router->put('/api/appointments/(:num)', 'AppointmentController::update/$1');
$router->delete('/api/appointments/(:num)', 'AppointmentController::destroy/$1');


//--- Car Routes ---
$router->get('/api/cars', 'CarController::index');
$router->get('/api/cars/(:num)', 'CarController::show/$1');
$router->post('/api/cars', 'CarController::store');
$router->put('/api/cars/(:num)', 'CarController::update/$1');
$router->delete('/api/cars/(:num)', 'CarController::destroy/$1');

//--- User Routes ---
$router->post('/api/users/login', 'UsersController::login');  
$router->get('/api/users/login', 'UsersController::login');        // Login user
$router->get('/api/users/logout', 'UsersController::logout');        // Logout user
$router->post('/api/users/register', 'UsersController::register');  // Register new user
$router->get('/api/users', 'UsersController::list_users');          // List all users (admin/dealer filtered)
$router->get('/api/users/me', 'UsersController::me');               // Get logged-in user info
$router->put('/api/users/(:num)', 'UsersController::update/$1');    // Update user by ID
$router->delete('/api/users/(:num)', 'UsersController::delete/$1'); // Delete user by ID

//--- Dealer Routes ---
$router->get('/api/dealers', 'DealersController::index');
$router->get('/api/dealers/(:num)', 'DealersController::show/$1');
$router->post('/api/dealers', 'DealersController::store');
$router->put('/api/dealers/(:num)', 'DealersController::update/$1');
$router->delete('/api/dealers/(:num)', 'DealersController::destroy/$1');

//--- Warranty Routes ---
$router->get('/api/warranties', 'WarrantiesController::index');
$router->get('/api/warranties/(:num)', 'WarrantiesController::show/$1');
$router->post('/api/warranties', 'WarrantiesController::store');
$router->put('/api/warranties/(:num)', 'WarrantiesController::update/$1');
$router->delete('/api/warranties/(:num)', 'WarrantiesController::destroy/$1');

//--Services Routes --
$router->get('/api/services', 'ServicesController::index');
$router->get('/api/services/(:num)', 'ServicesController::show/$1');
$router->get('/api/cars/(:num)/services', 'ServicesController::by_car/$1');
$router->post('/api/services', 'ServicesController::store');
$router->put('/api/services/(:num)', 'ServicesController::update/$1');
$router->delete('/api/services/(:num)', 'ServicesController::destroy/$1');

//---Payment Routes ---
$router->get('/api/payments', 'PaymentsController::index'); // Get all payments. Admin can see all, dealers may see theirs, buyers can see their own.
$router->get('/api/payments/(:num)', 'PaymentsController::show/$1'); // Get a specific payment by ID.
$router->post('/api/payments', 'PaymentsController::store'); // Create a new payment record. Usually called when a buyer pays for a car.
$router->put('/api/payments/(:num)', 'PaymentsController::update/$1'); // Update payment status or details (e.g., mark as succeeded).
$router->delete('/api/payments/(:num)', 'PaymentsController::destroy/$1'); // Delete a payment (rare, maybe for cleanup or admin correction).


//---Audit Logs Routes ---
$router->get('/api/audit_logs', 'AuditLogsController::index'); // Get all audit logs (read-only). Usually admin only.
$router->get('/api/audit_logs/(:num)', 'AuditLogsController::show/$1'); // Get a specific log by ID (read-only).
// Note: No POST, PUT, DELETE routes for audit logs as they are system generated and read-only.

//--- Inquiry Routes ---
$router->get('/api/inquiries', 'InquiriesController::index'); // Get all inquiries. Admin sees all, dealers see theirs, buyers see their own messages.
$router->get('/api/inquiries/(:num)', 'InquiriesController::show/$1'); // Get a single inquiry by ID.
$router->post('/api/inquiries', 'InquiriesController::store'); // Submit a new inquiry. Usually from a buyer or visitor on a car page.
$router->put('/api/inquiries/(:num)', 'InquiriesController::update/$1'); // Update inquiry status (e.g., contacted, closed).
$router->delete('/api/inquiries/(:num)', 'InquiriesController::destroy/$1'); // Delete an inquiry (admin action).
//--- End of routes ---

$router->post('login', 'ApiController::login');
$router->post('logout', 'ApiController::logout');
$router->post('create', 'ApiController::create');
$router->put('update/{id}', 'ApiController::update');
$router->delete('delete/{id}', 'ApiController::delete');
$router->get('list', 'ApiController::list');
$router->get('profile', 'ApiController::profile');
$router->post('refresh', 'ApiController::refresh');


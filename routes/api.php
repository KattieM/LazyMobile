<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');


});

Route::get('/home', 'HomeController@returnEventsAndProjects');
Route::put('/home', 'HomeController@attendEvent')->middleware('api');
Route::delete('/home', 'HomeController@unattendEvent')->middleware('api');


//Profile routes
Route::get('/profile/{username}', 'UserController@getProfileDetails')->middleware('api');
Route::put('/profile/{username}', 'UserController@editProfile')->middleware('api');
Route::delete('/profile/{username}', 'UserController@deleteExperienceandEducation')->middleware('api');
Route::post('/profile/{username}', 'UserController@uploadProfileImage')->middleware('api');

//Events routes
Route::get('/events', 'EventsController@showDetails')->middleware('api');
Route::post('/events', 'EventsController@saveNewEvent')->middleware('api');
Route::put('/events', 'EventsController@attendEvent')->middleware('api');
Route::delete('/events', 'EventsController@deleteOrUnattendEvent')->middleware('api');

//Event routes
Route::get('/event/{name}', 'EventController@showDetails')->middleware('api');
Route::post('/event/{name}', 'EventController@editEventOrSaveReview')->middleware('api');
Route::put('/event/{name}', 'EventController@goingEvent')->middleware('api');
Route::delete('/event/{name}','EventController@ungoingEvent')->middleware('api');

//People routes
Route::get('/people','PeopleController@showDetails')->middleware('api');
Route::post('/people', 'PeopleController@sendMail')->middleware('api');

//Projects routes
Route::get('/projects','ProjectsController@showDetails')->middleware('api');
Route::post('/projects','ProjectsController@saveNewProject')->middleware('api');
Route::delete('/projects', 'ProjectsController@deleteProject')->middleware('api');


//Project route
Route::get('/project/{name}', 'ProjectController@showDetails')->middleware('api');
Route::put('/project/{name}', 'ProjectController@editProjectorAddTeamMember')->middleware('api');
Route::post('/project/{name}', 'ProjectController@saveReviewOrSaveApplication')->middleware('api');


//Account
Route::get('/account','AccountController@showDetails')->middleware('api');
//Route::post('/account','AccountController@changePassword')->middleware('auth');
Route::post('/account','AccountController@updatePassOrUsername')->middleware('api');

//Documents

Route::get('/document', 'DocumentsController@showDetails')->middleware('api');
Route::post('/document', 'DocumentsController@uploadDocument')->middleware('api');
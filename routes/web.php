<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authorization
Route::get('/login', ['as' => 'auth.login.form', 'uses' => 'Auth\SessionController@getLogin']);
Route::post('/login', ['as' => 'auth.login.attempt', 'uses' => 'Auth\SessionController@postLogin']);
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'Auth\SessionController@getLogout']);

// Registration
Route::get('register', ['as' => 'auth.register.form', 'uses' => 'Auth\RegistrationController@getRegister']);
Route::post('register', ['as' => 'auth.register.attempt', 'uses' => 'Auth\RegistrationController@postRegister']);

// Activation
Route::get('activate/{code}', ['as' => 'auth.activation.attempt', 'uses' => 'Auth\RegistrationController@getActivate']);
Route::get('resend', ['as' => 'auth.activation.request', 'uses' => 'Auth\RegistrationController@getResend']);
Route::post('resend', ['as' => 'auth.activation.resend', 'uses' => 'Auth\RegistrationController@postResend']);

// Password Reset
Route::get('password/reset/{code}', ['as' => 'auth.password.reset.form', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset/{code}', ['as' => 'auth.password.reset.attempt', 'uses' => 'Auth\PasswordController@postReset']);
Route::get('password/reset', ['as' => 'auth.password.request.form', 'uses' => 'Auth\PasswordController@getRequest']);
Route::post('password/reset', ['as' => 'auth.password.request.attempt', 'uses' => 'Auth\PasswordController@postRequest']);

// Users
Route::resource('users', 'UserController');

// Roles
Route::resource('roles', 'RoleController');

// Dashboard
Route::get('dashboard', ['as' => 'dashboard', 'uses' => function() {
    return view('centaur.dashboard');
}]);

/* END OF USER ENDPOINTS */

Route::get('tasks', ['as' => 'tasks', 'uses' => 'TasksController@createForm']);
Route::get('tasks/list', ['as' => 'tasks.list', 'uses' => 'TasksController@getTasks']);
Route::post('tasks/create', ['as' => 'tasks.create.post', 'uses' => 'TasksController@create']);


Route::post('chat/initiate/task',
  ['as'=>'chat.initiate.task',   'uses'=>'ThreadsController@chatInitiateTask']
);
Route::post('chat/threads',
  ['as'=>'chat.threads',         'uses'=>'ThreadsController@chatThreads']
);
Route::post('chat/send/message',
  ['as'=>'chat.send.message',    'uses'=>'ThreadsController@chatSendMessage']
);
Route::post('chat/messages/read',
  ['as'=>'chat.messages.read',    'uses'=>'ThreadsController@chatMarkMessagesAsRead']
);
Route::post('chat/users/list',
  ['as'=>'chat.users.list',      'uses'=>'ThreadsController@chatUsersList']
);
Route::post('chat/users/add',
  ['as'=>'chat.users.add',       'uses'=>'ThreadsController@chatUsersAdd']
);
/*
Route::post('initiateNegotiation', ['as'=>'thread.findOrInitiate', 'uses' => 'ThreadsController@findOrCreateThread']);
Route::post('mythreads', ['as'=>'thread.findMine', 'uses' => 'ThreadsController@findMineThreads']);
Route::post('sendmessage', ['as'=>'thread.message.send', 'uses'=> 'ThreadsController@saveMessage']);

Route::post('populatePeopleList', ['as'=>'thread.populatePeopleList', 'uses'=>'ThreadsController@populatePeopleList']);
Route::post('addPersonToConversation', ['as'=>'thread.addPersonToConversation', 'uses'=>'ThreadsController@addPersonToConversation']);
*/
Route::get('testing', ['as'=>'testingtesting', 'uses'=>'ThreadsController@testing']);

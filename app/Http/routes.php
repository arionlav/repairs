<?php
// Index page
Route::get('/{page?}', 'PostsController@index')
    ->where('page', 'page[0-9]+');

// Sitemap
Route::get('sitemap.xml', 'SitemapController@getSitemap');

// Article full version
Route::get('post{postId}/{header}', 'PostsController@currentPost')
    ->where(['postId' => '[0-9]+']);

// Articles with keywords
Route::get('key/{key}/{page?}', 'PostsController@key');

// Articles from category
Route::get('category{id}/{categoryName}/{page?}', 'PostsController@category');

// User account pages
Route::controller('account', 'UserController');

// Open user's page
Route::get('user/{id}', 'PostsController@getUser')
    ->where(['id' => '[0-9]+']);

// Advert info
Route::get('info', function () {
    return view('layouts.info');
});

// Increment likes
Route::post('likes', 'PostsController@likes');

// Sending comments
Route::post('comment/send', 'CommentsController@sendComment');

// Admin panel
Route::controller('admin', 'AdminController');

// Authentication routes
Route::get('auth/login', [
    'as'   => 'loginPage',
    'uses' => 'Auth\AuthController@getLogin'
]);
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
// Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
// verify registration
Route::get('register/verify/{confirmationCode}', [
    'as'   => 'confirmation_path',
    'uses' => 'Auth\AuthController@confirmVerify'
]);

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
Route::get('password/reset-success', 'Auth\PasswordController@getResetSuccess');
// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

<?php

/** match */
Route::apiResources(['/matches' => 'MatchController']);

/** player */
Route::get('/players/{id}', 'PlayerController@show');

/** not found route */
Route::fallback('Controller@routeNotFound');
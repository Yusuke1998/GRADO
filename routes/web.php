<?php

// VUE
$router->get('/','PicPixReaderController@index');
$router->get('/imagen/{id}','PicPixReaderController@index');
$router->get('/imagen/modificada/{id}','PicPixReaderController@index');
$router->get('/imagenes','PicPixReaderController@index');
$router->get('/find-image-id/{id}','PicPixReaderController@findID');
$router->get('/action-image/{id}/{make}','PicPixReaderController@action');
$router->get('/delete-image/{id}','PicPixReaderController@destroy');
$router->post('/load-image','PicPixReaderController@save');
$router->get('/all-images','PicPixReaderController@allImages');







// OLD - simple - bootstrap
// $router->get('/picpixreader','PicPixReaderController@picpixreader');
// $router->post('/load','PicPixReaderController@load');
// $router->get('/show/{id}','PicPixReaderController@show');
// $router->get('/modify/{id}/{make}','PicPixReaderController@modify');
// $router->get('/delete/{id}','PicPixReaderController@delete');
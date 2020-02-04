<?php


// Api - Vue
$router->get('/','PicPixReaderController@index');
$router->get('/imagenes','PicPixReaderController@index');

// Vistas Simples - Bootstrap
$router->get('/picpixreader','PicPixReaderController@picpixreader');
$router->post('/load','PicPixReaderController@load');
$router->get('/show/{id}','PicPixReaderController@show');
$router->get('/modify/{id}/{make}','PicPixReaderController@modify');
$router->get('/delete/{id}','PicPixReaderController@delete');
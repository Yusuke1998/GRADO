<?php


$router->get('/','PicPixReaderController@index');
$router->post('/load','PicPixReaderController@load');
$router->get('/show/{id}','PicPixReaderController@show');
$router->get('/modify/{id}/{make}','PicPixReaderController@modify');
$router->get('/delete/{id}','PicPixReaderController@delete');

<?php
require 'vendor/autoload.php';

//setting local timezone for log
date_default_timezone_set('America/Kentucky/Louisville');

////creating an 'alias' for Logger so can reduce repeat use
//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;
////creating a new object variable for logging with following methods:
//$log = new Logger('name');
//$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
//$log->addWarning('Foo');
//$log->addError('Bar');

//creating a new slim object instance in the $app variable
//added in argument with array for Views/Twig
//overwriting the view class and using our own
$app = new \Slim\Slim( array(
  'view' => new \Slim\Views\Twig()
));

$view = $app->view();
$view->parserOptions = array(
  'debug' => true
);

//this extention gives 4 new twig helpers
$view->parserExtensions = array(
  new \Slim\Views\TwigExtension(),
);

//defining a basic router - dynamic URL in string format
//$app->get('/hello/:name', function ($name) {
  //echo "Hello, $name";});

//get method defining homepage url string and closure function
//    aka anonymous function that echos simple string
//these two methods create routing for homepage and contact page
$app->get('/', function() use($app){  // use tells to use the app object
  //echo 'Hello, this is the home page.';
  $app->render('about.twig'); // looks first in templates folder
});

$app->get('/contact', function() use($app){
  //echo 'Feel free to contact us.';
  $app->render('contact.twig');
});


//running the slim application
$app->run();

?>
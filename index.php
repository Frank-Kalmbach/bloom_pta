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
})->name('home'); //naming URL the the function name()

$app->get('/contact', function() use($app){
  //echo 'Feel free to contact us.';
  $app->render('contact.twig');
})->name('contact');

//setting up new route for contact form
//this will dump the post data as an array to the screen at /contact
$app->post('/contact', function() use($app){
  //breaking out the post variables into indiv vars for validation
  $name = $app->request->post('name');
  $email = $app->request->post('email'); 
  $msg = $app->request->post('msg'); 
  
  // conditional to check if vars empty (long form - for loop better)
  if(!empty($name) && !empty($email) && !empty($msg)) {
    // sanitizing data info from user in case of bad input
    $cleanName = filter_var($name, FILTER_SANITIZE_STRING);
    $cleanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);    
    $cleanMsg = filter_var($msg, FILTER_SANITIZE_STRING);    
    
  } else {
    // add later to msg the user that a field(s) were empty
    $app->redirect('/contact'); //slim method to return to URL with form
  }
  
  //creating a mail object variable transport, set equal to class and static (using dbl colon ::) method instance
  $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
  //creating swiftmailer mailer instance
  $mailer = \Swift_Mailer::newInstance($transport);
  //composing the message object
  $message = \Swift_Message::newInstance();
  $message->setSubject('Email From Our Website');
  $message->setFrom(array(
    $cleanEmail => $cleanName // array key-value pair
  ));
  $message->setTo(array('frank.kalmbach@hotmail.com'));
  $message->setBody($cleanMsg);
  
  //using mailer to send out msg with another method
  $result = $mailer->send($message);
  //checking to see if msg sent - $result will have a 0 or integer
  //an integer indicates how many msgs sent
  if($result > 0 {
    // send a msg that says thank you
    $app->redirect('/');
  } else {
    // send msg to user that the msg failed to send
    // log that there was an error
    $app->redirect('/contact');
  }
  
  
  
  // var_dump($app->request->post()); //old code
});

//running the slim application
$app->run();

?>
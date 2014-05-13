<?php

$loader = require __DIR__.'/vendor/autoload.php';
$loader->add('Custom\\', __DIR__);

use Sedra\App;
use Sedra\Locale;
use Sedra\Router;
use Sedra\Request;
use Sedra\Response;

# Enable custom locales
Locale::enable(array(
	'fr_BE',
	'en_US',
));

# Setup the router
Router::setup(array(
	'rewrite' => true,
	//'default_route' => 'MyCustomHomePageRoute',
));

# Configure & register the Sedra CMS and core controllers
App::register(new Sedra\Controller\i18n);
App::register(new Sedra\Controller\Admin);
App::register(new Sedra\Controller\Sedra(array(
	'site_name' => 'My amazing website',
)));

# Add custom controllers
App::register(new Sedra\Controller\Blog);
App::register(new Custom\Controller\MyPhotoGallery);

# Process the request
App::process();

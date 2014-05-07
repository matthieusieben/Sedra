<?php

define('START_TIME', microtime(TRUE));

require __DIR__.'/vendor/autoload.php';

use Sedra\App;
use Sedra\Router;
use Sedra\Request;
use Sedra\Response;

# Configure & register the Sedra CMS controller
App::register(new Sedra\Controller\Sedra(array(
	'locales' => array('en-US', 'fr-BE'),
	'views_data' => array(
		'site_name' => 'My amazing website',
	),
)));

# Add custom controllers
App::register(new Sedra\Controller\i18n);
App::register(new Sedra\Controller\Admin);
App::register(new Sedra\Controller\Blog);
App::register(new Sedra\Controller\Sandbox);

# Get the request object
$request = Request::get();

# Get the corresponding response from the cache
if ($response = $request->cache->get()) {
	# Nothing to do
} else {
	# Generate the response
	$response = Router::process($request);

	# Store the response in the cache
	$request->cache->set($response);
}

# Send the response
$response->send();

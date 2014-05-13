<?php

namespace Sedra\Controller;

use Sedra\Controller;
use Sedra\Database\Model;
use Sedra\Database\ModelProvider;
use Sedra\Database\Exception\ModelNotFound as ModelNotFoundException;
use Sedra\Locale;
use Sedra\Request\HTTP as Request;
use Sedra\Router\Route;
use Sedra\Router\RouteProvider;
use Sedra\Router\Exception\NoSuchRoute as NoSuchRouteException;
use Sedra\View;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class Blog extends Controller implements RouteProvider, ModelProvider
{
	public function handle_index(Request $request) {
		# XXX $articles = $this->models['articles']->get_latest();
		$articles = array();
		return View::factory('blog/articles', array('articles' => $articles));
	}

	public function handle_article(Request $request, $arguments) {
		# XXX $articles = $this->models['articles']->get_latest();
		$article = array();
		return View::factory('blog/article', array('article' => $article));
	}

	public function get_route_names()
	{
		return array('BlogIndex', 'BlogArticle');
	}

	public function get_route($route_name)
	{
		if (isset($this->routes[$route_name]))
			return $this->routes[$route_name];

		switch ($route_name) {
		case 'BlogIndex':
			return $this->routes[$route_name] = Route::factory(array(
				'name' => $route_name,
				'methods' => array('GET'),
				'query' => 'blog',
				'handler' => array($this, 'handle_index'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			));
		case 'BlogArticle':
			return $this->routes[$route_name] = Route::factory(array(
				'name' => $route_name,
				'methods' => array('GET'),
				'query' => 'blog/article/:id',
				'filters' => array(
					'id' => '[0-9]+',
				),
				'handler' => array($this, 'handle_article'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			));
		default:
			throw new NoSuchRouteException($route_name);
		}
	}


	public function get_model_names()
	{
		return array('articles', 'categories');
	}

	public function get_model($model_name)
	{
		if (isset($this->models[$model_name]))
			return $this->models[$model_name];
		switch ($model_name) {
		case 'articles':
			return $this->models[$model_name] = new ArticleModel();
		case 'categories':
			return $this->models[$model_name] = new CategoryModel();
		default:
			throw new ModelNotFoundException($model_name);
		}
	}
}

class ArticleModel extends Model {
	function __construct() {
		parent::__construct('blog_articles', array(
			'fields' => array(
				'id' => array(
					'type' => 'serial',
					'unsigned' => TRUE,
					'not null' => TRUE,
					'hidden' => TRUE,
				),
				'title' => array(
					'type' => 'varchar',
					'length' => 255,
					'not null' => TRUE,
					'default' => '',
					'display name' => 'Article title',
					'show name' => FALSE,
					'view' => 'title',
				),
				'locale' => array(
					'type' => 'varchar',
					'length' => 6,
					'not null' => FALSE,
					'options' => Locale::enabled(),
					'display name' => 'Language',
				),
				'category' => array(
					'type' => 'int',
					'unsigned' => TRUE,
					'not null' => FALSE,
					'display name' => 'Category',
				),
				'uid' => array(
					'type' => 'int',
					'unsigned' => TRUE,
					'not null' => TRUE,
					'display name' => 'Author',
					'show name' => FALSE,
					'view' => 'avatar',
				),
				'published' => array(
					'type' => 'datetime',
					'not null' => TRUE,
					'display name' => 'Publish date',
					'show name' => FALSE,
					'view' => 'date',
				),
				'picture' => array(
					'type' => 'int',
					'unsigned' => TRUE,
					'not null' => FALSE,
					'display name' => 'Picture',
					'show name' => FALSE,
					'view' => 'file',
				),
				'body' => array(
					'type' => 'text',
					'not null' => TRUE,
					'display name' => 'Body',
					'show name' => FALSE,
					'view' => 'text',
				),
			),
			'primary key' => array('id'),
			'foreign keys' => array(
				'article_category' => array(
					'model' => 'blog_categories',
					'columns' => array('category' => 'id'),
					'cascade' => FALSE,
				),
				'article_author' => array(
					'model' => 'users',
					'columns' => array('uid' => 'uid'),
					'cascade' => TRUE,
				),
				'article_picture' => array(
					'model' => 'files',
					'columns' => array('picture' => 'fid'),
					'cascade' => FALSE,
				),
			),
		));
	}
}

class CategoryModel extends Model {
	function __construct() {
		parent::__construct('blog_categories', array(
			'fields' => array(
				'id' => array(
					'type' => 'serial',
					'unsigned' => TRUE,
					'not null' => TRUE,
					'hidden' => TRUE,
				),
				'name' => array(
					'type' => 'varchar',
					'length' => 255,
					'not null' => TRUE,
					'default' => '',
					'display name' => 'Category name',
					'show name' => FALSE,
				),
				'locale' => array(
					'type' => 'varchar',
					'length' => 6,
					'not null' => FALSE,
					'options' => Locale::enabled(),
					'display name' => 'Language',
				),
			),
			'primary key' => array('id'),
		));
	}
}
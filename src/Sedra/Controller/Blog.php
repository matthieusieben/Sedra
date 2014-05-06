<?php

namespace Sedra\Controller;

use Sedra\Controller;
use Sedra\Database\Model;
use Sedra\Database\ModelProvider;
use Sedra\Locale;
use Sedra\Request\HTTP as Request;
use Sedra\Router\RouteProvider;
use Sedra\Router\Route;
use Sedra\View;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class Blog extends Controller implements RouteProvider, ModelProvider
{
	public function index(Request $request) {
		# XXX $articles = $this->models['articles']->get_latest();
		$articles = array();
		return View::factory('blog/articles', array('articles' => $articles));
	}

	public function article(Request $request, $arguments) {
		# XXX $articles = $this->models['articles']->get_latest();
		$article = array();
		return View::factory('blog/article', array('article' => $article));
	}

	public function &get_routes() {
		$this->routes = $this->routes ?: array(
			Route::factory(array(
				'name' => 'BlogIndex',
				'methods' => array('GET'),
				'query' => 'blog',
				'handler' => array(&$this, 'index'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			)),
			Route::factory(array(
				'name' => 'BlogArticle',
				'methods' => array('GET'),
				'query' => 'blog/article/:id',
				'filters' => array(
					'id' => '[0-9]+',
				),
				'handler' => array(&$this, 'article'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			)),
		);
		return $this->routes;
	}

	public function &get_model($name)
	{
		if (isset($this->models[$name]))
			return $this->models[$name];

		$this->models[$name] = $this->init_model($name);

		return $this->models[$name];
	}

	public function &get_models()
	{
		$this->models = $this->models ?: array(
			'articles' => new Model('blog_articles', array(
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
						'options' => Locale::get_locales(),
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
			)),
			'categories' => new Model('blog_categories', array(
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
						'options' => Locale::get_locales(),
						'display name' => 'Language',
					),
				),
				'primary key' => array('id'),
			)),
		);
		return $this->models;
	}
}
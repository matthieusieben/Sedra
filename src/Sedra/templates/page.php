<!DOCTYPE html>
<html>
<head>
	<title><?= $view('page_title') ?: $view('site_name') ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php foreach ((array) $view('html_head') as $item): ?>
		<?php if ($item instanceof \Sedra\View): ?>
			<?= (string) $item ?>
		<?php endif ?>
	<?php endforeach ?>
</head>
<body>

	<?php if ($navbar = $view('navbar')): ?>
		<?= (string) $navbar ?>
	<?php endif ?>

	<div id="page-wrapper" class="container">

		<header id="header" class="row">
			<h1 id="site-name" role="banner"><?= $view('site_name') ?></h1>
		</header>

		<?php if ($menu = $view('menu')): ?>
			<nav id="main-menu">
				<?= (string) $menu ?>
			</nav>
		<?php endif ?>

		<div id="content-wrapper" class="row">
			<section id="content" role="main" class="content span9">
				<?= $content ?>
			</section>
			<aside id="sidebar span3">
				<?php foreach ((array) $view('sidebar') as $block): ?>
					<div class="block">
						<?= (string) $block; ?>
					</div>
				<?php endforeach ?>
			</aside>
		</div>

		<footer id="footer" role="contentinfo">
			<?php foreach ((array) $view('footer') as $block): ?>
				<section class="block">
					<?= (string) $block; ?>
				</section>
			<?php endforeach ?>

			<section>
				<p> <?php /* XXX */ ?>
					Page loaded in <?= round((microtime(TRUE) - START_TIME) * 1000, 3) . ' ms'; ?>.
				</p>
			</section>
		</footer>

	</div>

</body>
</html>
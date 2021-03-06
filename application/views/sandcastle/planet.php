<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<h1>Planet</h1>
<?php foreach($articles as $post): ?>
<article>
	<h1><?php echo $post->title; ?></h1>
	<h2><?php echo $post->feed_title; ?></h2>
	<?php echo $post->content; ?>
	<p>Published <time datetime="<?php echo date('Y-m-d G:i', $post->datetime); ?>"><?php echo date('l, jS F', $post->datetime); ?> at <?php echo date('g:ia', $post->datetime); ?></time></p>
</article>
<?php endforeach; ?>
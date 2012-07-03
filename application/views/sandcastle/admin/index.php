<!doctype html>
<meta charset="utf-8">
<link href="http://kevinburke.bitbucket.org/markdowncss/markdown.css" rel="stylesheet"></link>
<?php echo anchor($this->uri->segment(1) . '/events', 'Events'); ?> 
<?php echo anchor($this->uri->segment(1) . '/feeds', 'Feeds'); ?> 
<?php echo anchor($this->uri->segment(1) . '/users', 'Users'); ?> 
<?php echo anchor($this->uri->segment(1) . '/sign_out', 'Sign Out'); ?>
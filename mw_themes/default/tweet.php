<?php

require_once(THEME_ROOT."/functions.php");

$tid = $_GET['tid'] ?: NULL;
$tweet = mw_get_tweet($tid);

?>
<?php the_tweet($tweet); ?>
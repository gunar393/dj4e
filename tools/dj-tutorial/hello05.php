<?php

require_once "webauto.php";

use Goutte\Client;

$qtext = 'Answer to the Ultimate Question';
?>
<h1>DIY Hello World / Sessions</h1>
<p>
The instructions for this assignment are at
<a href="../../assn/dj4e_hello.md" target="_blank">dj4e_hello.md</a>
</a>.
This assignment extends the previous Django tutorial Part 4.  In addition to
the requirements of the assignment,
you need to keep the <b>/polls/owner</b> view working as well.
</p>
<?php
nameNote();
$check = webauto_get_check();
?>
</p>
<p>
In addition to the session feature in the above assignment also set a cookie
in your <b>/hello</b> view:
<pre>
resp.set_cookie('dj4e_cookie', '<?= $check?>', max_age=1000)
</pre>
Remember that to set a cookie in a Django view, you can't just use
the <b>render()</b> shortcut.  Instead you
need to create the <b>HttpResponse</b> and then add the cookie to the response
before returning it from your view.  Take a look at the
<b>dj4e-sample</b> code to see how this can be done.
</p>
Then submit your Django base site (i.e. with no path) to this autograder.
</p>
<?php

$url = getUrl('http://djtutorial.dj4e.com');
if ( $url === false ) return;
$passed = 0;
error_log("Hello05 ".$url);
//
// http://symfony.com/doc/current/components/dom_crawler.html
$client = new Client();
$client->setMaxRedirects(5);

$owner = $url . '/polls/owner';

$crawler = webauto_retrieve_url($client, $owner);
if ( $crawler === false ) return;
$html = webauto_get_html($crawler);
webauto_search_for($html, 'Hello');

if ( $check && stripos($html,$check) !== false ) {
    markTestPassed("Found ($check) in your html");
} else {
    error_out("Did not find $check in your html");
    error_out("No score will be sent, but the test will continue");
}


$sessurl = $url . '/hello';

$crawler = webauto_retrieve_url($client, $sessurl);
if ( $crawler === false ) return;
$html = webauto_get_html($crawler);
webauto_search_for($html, 'view count=1');

$crawler = webauto_retrieve_url($client, $sessurl);
if ( $crawler === false ) return;
$html = webauto_get_html($crawler);
webauto_search_for($html, 'view count=2');


// -------------------- Send the grade ---------------
line_out(' ');
$perfect = 4;

if ( ! $check ) {
    error_out("No score sent, missing owner name");
    return;
}

$score = webauto_compute_effective_score($perfect, $passed, $penalty);

if ( $score < 1.0 ) autoToggle();

// Send grade
if ( $score > 0.0 ) webauto_test_passed($score, $url);


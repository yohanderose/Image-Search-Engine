<?php

session_destroy();
echo "You have been logged out";

echo '<br><button onclick="return window.location = \'index.php\';" > Go back</button>';

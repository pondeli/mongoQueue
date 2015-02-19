<?php
$command = "nohup php runner/scripts/$argv[1] > ../temp/scripts/$argv[1].out 2>&1&";
$pid = exec($command);

//@TODO exceptions


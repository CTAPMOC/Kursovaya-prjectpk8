<?php
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
echo password_hash($argv[1]??'Study2026!',PASSWORD_DEFAULT),PHP_EOL;

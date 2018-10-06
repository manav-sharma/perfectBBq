<?php
$ffmpeg = trim(exec('which ffmpeg'));                        
if (empty($ffmpeg))
{
    die('ffmpeg not available');
}
phpinfo();

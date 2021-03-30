<?php

shell_exec('"C:\Program Files\Git\bin\git.exe" fetch');
shell_exec('"C:\Program Files\Git\bin\git.exe" checkout dev');
shell_exec('"C:\Program Files\Git\bin\git.exe" reset --hard origin/dev');

$now = date('Y/m/d H:i:s');
file_put_contents('./log.txt', "[$now] updated \n", FILE_APPEND);


//$GIT = 'git';
//$BRANCH = 'dev';
//
//$commands = "{$GIT} fetch; {$GIT} checkout ${BRANCH};{$GIT} reset --hard origin/{$BRANCH} 2>&1";
//exec($commands, $output, $exit);
//$output = shell_exec($commands);
//
//if ($exit == 0) {
//    echo "DEPLOY DONE!!!";
//} else {
//    echo "DEPLOY OUTPUT: <pre>$output</pre>";
//}
//
//// Log running
//$now = date('Y/m/d H:i:s');
//file_put_contents('./log.txt', "[$now][exec] [$commands] $output \n", FILE_APPEND);

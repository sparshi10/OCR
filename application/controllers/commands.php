<?php

$commands = [
    'sudo yum update',
    'sudo yum install gcc openssl-devel bzip2-devel libffi-devel',
    'sudo make altinstall',
    'sudo yum install epel-release',
    'sudo yum install opencv-python',
    'sudo localectl set-locale LANG=en_US.UTF-8',
    'sudo reboot',
    'unset PYTHONPATH',
    'unset PYTHONHOME',
    'pip install dlib==19.19.0',
    'sudo yum update',
    'sudo yum install python3-devel libXext libSM libXrender',
    'pip3 install matplotlib',
    'sudo yum update',
    'sudo yum install python3-devel',
    'pip3 install pillow'
];

foreach ($commands as $command) {
    exec($command . ' 2>&1', $output, $returnVar);
    
    echo "Running command: $command<br>";
    
    if ($returnVar === 0) {
        echo "Command executed successfully.<br>";
    } else {
        echo "Error executing command: " . implode("\n", $output) . "<br>";
    }
    
    echo "<br>";
}

?>

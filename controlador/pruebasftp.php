<?php
 
     include 'Net/SFTP.php';
 
     $sftp = new Net_SFTP('200.53.142.68');
     if (!$sftp->login('servicob', '53rv1c0b_2017')) {
         exit('Login Failed');
     }
 
     echo $sftp->pwd() . "\r\n";
$sftp->put('prueba.txt', 'prueba.txt', NET_SFTP_LOCAL_FILE);
    
     print_r($sftp->nlist());
  ?>

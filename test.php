<?php
    require("FileHandler.php");

    $fh = new FileHandler("teste.txt", "</br>", true);
    if(!is_null($fh->getError())) {
        echo $fh->getError();
    }
    $r = $fh->read();
    test($fh, $r);
    $fh->write("Teste\n");
    test($fh);

    /**
     * @param FileHandler $fh
     * @param null $r
     */
    function test($fh, $r = null) {
        if(!$r || is_null($r)) {
            echo $fh->getError();
        } else {
            echo $r;
        }
    }

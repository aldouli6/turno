<?php


//check whether the folder the exists
$counter=0;
if (!(file_exists('../sinester'))) {
      //create the folder
 mkdir('../sinester');
 //give permission to the folder
 chmod('../sinester', 0777);
    }

    $str = $_FILES['file']['name'];
    for($i = 0; $i <= 20; $i++ ) {
        $char = substr( $str, $i, 1 );


        if($char=="_"){
          $counter++;


          switch($i){
            case 13:
if($counter==1){
            $valid = substr($_FILES['file']['name'], 14,8);
            if (!(file_exists('../sinester/'.$valid))) {
                  //create the folder
             mkdir('../sinester/'.$valid);
             //give permission to the folder
             chmod('../sinester/'.$valid, 0777);
                }
                //check whether the file exists
                if (file_exists('../sinester/'.$valid."/".$_FILES['file']['name'])) {
                    echo $_FILES['file']['name'].' already exists.';

                } else {
                    //move the file into the new folder
                 move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name']);

                 if(move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name'])){
                   echo $_FILES['file']['name'];
                 }else{
                   echo $_FILES['file']['tmp_name'];

                 }
                }
}
            break;

            case 11:
if($counter==1){
            $valid = substr($_FILES['file']['name'], 12,8);
            echo $valid;
            if (!(file_exists('../sinester/'.$valid))) {
                  //create the folder
             mkdir('../sinester/'.$valid);
             //give permission to the folder
             chmod('../sinester/'.$valid, 0777);
                }
                //check whether the file exists
                if (file_exists('../sinester/'.$valid."/".$_FILES['file']['name'])) {
                    echo $_FILES['file']['name'].' already exists.';

                } else {
                    //move the file into the new folder
                 move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name']);

                 if(move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name'])){
                   echo $_FILES['file']['name'];
                 }else{
                   echo $_FILES['file']['tmp_name'];

                 }
                }
}
            break;

            case 20:
            if($counter==1){
            $valid = substr($_FILES['file']['name'], 21,8);
            if (!(file_exists('sinester/'.$valid))) {
                  //create the folder
             mkdir('../sinester/'.$valid);
             //give permission to the folder
             chmod('../sinester/'.$valid, 0777);
                }
                //check whether the file exists
                if (file_exists('../sinester/'.$valid."/".$_FILES['file']['name'])) {
                    echo $_FILES['file']['name'].' already exists.';

                } else {
                    //move the file into the new folder
                 move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name']);

                 if(move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name'])){
                   echo $_FILES['file']['name'];
                 }else{
                   echo $_FILES['file']['tmp_name'];

                 }
                }

              }
            break;

            case 18:
if($counter==1){
            $valid = substr($_FILES['file']['name'], 19,8);
            if (!(file_exists('sinester/'.$valid))) {
                  //create the folder
             mkdir('../sinester/'.$valid);
             //give permission to the folder
             chmod('../sinester/'.$valid, 0777);
                }
                //check whether the file exists
                if (file_exists('../sinester/'.$valid."/".$_FILES['file']['name'])) {
                    echo $_FILES['file']['name'].' already exists.';

                } else {
                    //move the file into the new folder
                 move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name']);

                 if(move_uploaded_file($_FILES['file']['tmp_name'], '../sinester/'.$valid."/".$_FILES['file']['name'])){
                   echo $_FILES['file']['name'];
                 }else{
                   echo $_FILES['file']['tmp_name'];

                 }
                }
              }
            break;

            default;



          }

        }
      }

?>

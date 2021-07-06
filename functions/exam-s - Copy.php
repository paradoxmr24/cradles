<?php

$rdata = json_decode(file_get_contents("php://input"));

if(!$rdata) {

    header('location:../login.php');

    exit();

}

require '../includes/connection.php';
require '../includes/log.php';

date_default_timezone_set("Asia/Calcutta");

session_start();

$sdata;
$e_id = $rdata->e_id;
if($rdata->skipped > 0) {

    $_SESSION['skipped'] .= $rdata->skipped;
    $x = $rdata->skipped;
    addlog($e_id,'success',"Skipped the question $x");
    unset($x);
}

$connect = connectdb($_SESSION['d_name']);


$query = "SELECT * FROM exams WHERE Id = '$e_id'";

$result = mysqli_fetch_assoc(mysqli_query($connect,$query));

if($result['Class'] != $_SESSION['class']) {

    $sdata['status'] = "na";
    addlog($e_id,'error',"Opened the exam of class $result[Class]");
    echo json_encode($sdata);
    exit();

}



if(isAlreadyApplied($e_id) && $rdata->q == '') {

    $sdata['status'] = "na";
    addlog($e_id,'error','Already applied');
    echo json_encode($sdata);

    exit();

}



checkTime();

if($sdata['status'] == 'e') {

    $rdata->q = '';
    addlog($e_id,'error','Exam has been ended');

}

if($rdata->q == '') {

    $_SESSION['showingSkipped'] = false; 

    $_SESSION['skipped'] = '';

    echo json_encode($sdata);

    if($sdata['status'] == 's') {

        $_SESSION['count'] = 0;

        $_SESSION['answer'] = 0;

    }

} else {

    if($sdata['status'] == 's') {

            //initEntry();
            if($_SESSION['count'] < 1)
            addlog($e_id,'success','Successfully started the exam');

        

        $_SESSION['count']++;

        

        $query = "SELECT * FROM `$e_id` WHERE Id='$_SESSION[count]'";

        $result = mysqli_query($connect,$query);

        if(mysqli_num_rows($result) == 0 || $_SESSION['showingSkipped']) {

            $result = getSkipped();

            $_SESSION['showingSkipped'] = true;
            if($result) {
                addlog($e_id,'success',"Question number $_SESSION[count] delivered (skipped)");
            } else {
                addlog($e_id,'success','Successfully finished');
            }
        } else {
            addlog($e_id,'success',"Question number $_SESSION[count] delivered");
        }

        $result = mysqli_fetch_assoc($result);

        $result['count'] = $_SESSION['count'];

        $result['showingSkip'] = $_SESSION['showingSkipped'];

        $result['time'] = $sdata['time'];
        sleep(10);
        
        echo json_encode($result);

    }

}



function toSec($a) {

    return $a*60;

}



function initEntry() {

    global $connect, $e_id;

    $query = "SELECT Questions FROM exams WHERE Id = '$e_id'";

    $result = mysqli_fetch_assoc(mysqli_query($connect,$query));

    $total = '0/' . $result['Questions'];

    $query ="INSERT INTO marks VALUES ('$_SESSION[username]','$e_id','$total',0)";

    mysqli_query($connect,$query);

}

function checkTime() {

    global $result,$sdata;

    if($result['E_Date'] == date('Y') . '-' . date('m') . '-' . date('d')) {

        if (time() >= strtotime($result['E_Time']) && time() <= (strtotime($result['E_Time']) + toSec($result['Duration'])) ) {

            $sdata['status'] = 's';

            $sdata['time'] = strtotime($result['E_Time']) + toSec($result['Duration']);

        } else if(time() > (strtotime($result['E_Time']) + toSec($result['Duration']))) {

            $sdata['status'] = 'e';

        } 

        else {

            $sdata['status'] = 'ns';

            $sdata['time'] = getFormattedTime($result['E_Time']);

            $sdata['date'] = getFormattedDate($result['E_Date']);

        }

    } elseif($result['E_Date'] > date('Y') . '-' . date('m') . '-' . date('d')) {

        $sdata['status'] = 'ns';

        $sdata['time'] = getFormattedTime($result['E_Time']);

        $sdata['date'] = getFormattedDate($result['E_Date']);

    } else {

        $sdata['status'] = 'e';

    }

}



function isAlreadyApplied($id) {

    global $connect;

    $query = "SELECT * FROM marks WHERE Student_id = '$_SESSION[username]' && Exam_id = '$id'";

    return mysqli_num_rows(mysqli_query($connect,$query));

}



function getFormattedDate($date) {

$date = $date[8] . $date[9] . '/' . $date[5] . $date[6] . '/' . $date[0] . $date[1] . $date[2] . $date[3];

return $date;



}



function getFormattedTime($time) {

    $time_h = $time[0] . $time[1];

    $m = 'AM';

    if($time_h > 11) {

    $m = 'PM';

    $time_h -= 12;

    } 

    $time = $time_h . ':' . $time[3] . $time[4] . ' ' . $m;

    return $time;

}

function getSkipped() {

    global $connect,$e_id;
if($_SESSION['skipped'] > 0) {

    $a = floor($_SESSION['skipped']/ pow(10,strlen($_SESSION['skipped']) -1)) ;

    $_SESSION['skipped'] = $_SESSION['skipped'] % pow(10,strlen($_SESSION['skipped']) -1);

    $_SESSION['count'] = $a;

    $query = "SELECT * FROM `$e_id` WHERE Id='$a'";

    $result = mysqli_query($connect,$query);

    return $result;
} else {
    return false;
}

}

?>
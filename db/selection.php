<?php

//unpack data from post request

$choice = $_POST['choice'];
$left_side = $_POST['left_side'];
$right_side = $_POST['right_side'];
$left_small = $_POST['left_small'];
$studyIndex = $_POST['studyIndex'];
$userId = $_POST['userId'];
$case_num = $_POST['case_num'];

// $con = mysql_connect("localhost","web_user","railabs");
//
// if (!$con)
// {
//   die('Could not connect: ' . mysql_error());
// }
//
// mysql_select_db("mafc", $con);
//
// $query = "INSERT INTO `".$userId."` (case_num, left_small, choice, small_dir, large_dir, slices) VALUES ($case_num,$left_small,$choice,$left_side,$right_side,$slices);";
//
// $pre_res = mysql_query($query);
//
// if($pre_res){
//   $studyIndex = $studyIndex + 1;
//   $res=mysql_query("INSERT INTO users WHERE userId='$userId' (studyIndex) VALUES ($studyIndex)");
//   if($res){
//     $res2=mysql_query("SELECT * FROM `".$userId."` WHERE studyIndex='$studyIndex'");
//   if($res2){
//     $userRow2=mysql_fetch_array($res2);
//     $init_case_num = $userRow2['case_num'];
//
//     // load study info database
//     $res3=mysql_query("SELECT * FROM study_info WHERE case_num='$init_case_num'");
//     if($res3){
//       $userRow3=mysql_fetch_array($res3);
//       // get folder names, number of slices
//       $small_dir = $userRow3['small_dir'];
//       $large_dir = $userRow3['large_dir'];
//       $slices = $userRow3['slices'];
//       $left_small = rand(0,1);
//
//       if ($left_small) {
//         $left_side = $small_dir;
//         $right_side = $large_dir;
//       } else {
//         $left_side = $large_dir;
//         $right_side = $large_dir;
//       }
//       echo json_encode(array("left_side"=>$left_side,"right_side"=>$right_side,"left_small"=>$left_small,"slices"=>$slices))
//     }
//   }
// }
//
return($userId)
mysql_close($con);
?>

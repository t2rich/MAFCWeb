<?php

//unpack data from post request

$choice = $_POST['c'];
$left_side = $_POST['ls'];
$right_side = $_POST['rs'];
$left_small = $_POST['small'];
$studyIndex = $_POST['si'];
$userId = $_POST['id'];
$case_num = $_POST['cn'];
$slices= $_POST['ss'];

$con = mysql_connect("localhost","web_user","railabs");

if (!$con)
{
  die('Could not connect: ' . mysql_error());
}

mysql_select_db("mafc", $con);

$query = "UPDATE `".$userId."` SET case_num=$case_num, left_small=$left_small, choice=$choice, small_dir=$left_side, large_dir=$right_side, slices=$slices WHERE case_num=$case_num";

// $pre_res = mysql_query($query);

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
echo $query
mysql_close($con);
?>

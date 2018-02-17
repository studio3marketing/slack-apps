<?php

$slackToken = 'ePqjL5O0n1Or93nMdUMqNkdX';

if($_POST["token"] == $slackToken){

  $text = "lol wut";

  /*
   * Rankings
   * */
  if($_POST["text"] == "rankings"){
    $text = generateRankingsText();
  }

  sendResponse($text);
}

function sendResponse($text){
  $response = array(
    "response_type" => "in_channel",
    "text" => $text
  );
  header('Content-Type: application/json');
  echo json_encode($response);
  return;
}

function generateRankingsText(){
  //formatter
  $nl = "\n";
  //reset text
  $text = "";
  //grab users
  $users = json_decode(file_get_contents('http://foos-api.herokuapp.com/api/v1/users'));

  //generate and format line per user
  foreach ($users as $key => $user){

    $prefixes = array(
      ":first_place_medal:",
      ":second_place_medal:",
      ":third_place_medal:",
    );

    $text .= ($key > 0 ? $nl : "" );
    $text .= ($prefixes[$key] != null ? $prefixes[$key]." *" : "*".($key+1)."th: ")." @".$user->handle."*";
    $text .= $nl;
    $text .= "_";
    $text .= "Rating: ".$user->rating;
    $text .= " - Wins: ".$user->games_won." - Losses: ".$user->games_lost;
    $text .= " (".$user->winning_percentage."%)";
    $text .= "_";

  }

  return $text;
}

?>
<?php
//at beginning
$milliseconds = round(microtime(true) * 1000);


    $MATCHID = $_GET['matchid'];
    $CLUBID = $_GET['clubid'];
    $LEAGUEID = $_GET['leagueid'];

    // $MATCHID = $_GET['matchId'];
    $url1=$_SERVER['REQUEST_URI'];
    header("Refresh: 3; URL=$url1");

    header('Content-type: text/javascript');

    $ch = curl_init();
    $ch2 = curl_init();
    $LTch = curl_init();
    $PTch = curl_init();
    $Partnershipch = curl_init();

    $url = "https://www.cricclubs.com/getScorecard.do?matchId=$MATCHID&clubId=$CLUBID";     // (FOR BATTINGCARD WORKING)
    // $url = "https://sportapi.cricclubs.com/sport/liveScoreOverlay/getScorecard?clubId=$CLUBID&matchId=$MATCHID";   // (FOR BATTINGCARD & BOWLINGCARD WORKING)
    // $url2 = "https://sportapi.cricclubs.com/sport/ball/ballbyballview?matchId=$MATCHID&clubId=$CLUBID";  // (FOR LT WORKING)
    $url2 = "https://www.cricclubs.com/getBallByBall.do?matchId=$MATCHID&clubId=$CLUBID";

    // $url = "https://sportapi.cricclubs.com/sport/liveScoreOverlay/getScorecard?clubId=12047&matchId=515";
    $LTurl = "https://cricclubs.com/liveScoreOverlayData.do?clubId=$CLUBID&matchId=$MATCHID";
    // $Partnershipurl = "https://cricclubs.com/liveScoreOverlayData.do?clubId=$CLUBID&matchId=$MATCHID";   // Matchid=525
    // $url2 = "http://www.score360digital.com/score360/viewScorecard.do?matchId=515&clubId=12047";
    $PTurl = "https://cricclubs.com/getPointsTable.do?clubId=$CLUBID&league=$LEAGUEID";

    //=============== For BattingCard ENCODE=============


    // Set the url
    // curl_setopt($Partnershipch, CURLOPT_URL,$Partnershipurl);
    // // Will return the response, if false it print the response
    // curl_setopt($Partnershipch, CURLOPT_RETURNTRANSFER, true);
    // // Execute
    // $Partnershipresult = curl_exec($Partnershipch);


    // Set the url
    curl_setopt($PTch, CURLOPT_URL,$PTurl);
    // Will return the response, if false it print the response
    curl_setopt($PTch, CURLOPT_RETURNTRANSFER, true);
    // Execute
    $PTresult = curl_exec($PTch);


    // Set the url
    curl_setopt($LTch, CURLOPT_URL,$LTurl);
    // Will return the response, if false it print the response
    curl_setopt($LTch, CURLOPT_RETURNTRANSFER, true);
    // Execute
    $LTresult = curl_exec($LTch);

    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute
    $result = curl_exec($ch);

    //========= FOR LT ENCODE==============

    // Set the url
    curl_setopt($ch2, CURLOPT_URL,$url2);
    // Will return the response, if false it print the response
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    // Execute
    $result2 = curl_exec($ch2);


    if($e = curl_error($ch)){
        echo $e;
    }
    else{

        $urlImage = "https://cricclubs.com";
        $LTtesting = array();   //
        $output = array();      // LT array
        $b1_output = array();   // BattingCard1 array
        $b2_output = array();   // BattingCard2 array
        $bo1_output = array();  // BowlingCard1 array
        $bo2_output = array();  // BowlingCard2 array
        $lastout_output = array();  // LastOutPlayer array
        $squad1_output = array();   // Squad1 array
        $squad2_output = array();   // Squad2 array
        $batsmanstriker_output = array();   // Current Batsman Striker array
        $batsmannonstriker_output = array();   // Current Batsman Non-Striker array
        $bowler_output = array();   // Current Bowler array
        $matchsummary_output = array(); // Match Summary array
        $partnership_output = array();
        // $decoded = (array) json_decode($result);
        $decoded = json_decode($result);
        $decoded2 = json_decode($result2);
        $LTdecoded = json_decode($LTresult);
        $Partnershipdecoded = json_decode($LTresult);
        $PTdecoded = json_decode($PTresult);

        // //==================================================================================================================================
        // //================================================== LT DETAILS ==========================================================
        // //==================================================================================================================================

        $xmlLT = new DOMDocument();
        $root_LT = $xmlLT->appendChild($xmlLT->createElement("DATA"));
        if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "false"){
            foreach($LTdecoded->{"values"} as $key => $value){
                if(array_intersect(explode(",",$key), array("t1Name"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Code"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2Name"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2Code"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Overs"))){
                    if(substr($value, strpos($value, ".")+1) == "0"){
                        $root_LT->appendChild($xmlLT->createElement("overs",strtok($value, '.')));
                        $LTtesting["overs"] = strtok($value, '.');
                    }
                    else{
                        $root_LT->appendChild($xmlLT->createElement("overs",$value));
                        $LTtesting["overs"] = $value;
                    }
                }
                else if(array_intersect(explode(",",$key), array("t1Total"))){
                    $root_LT->appendChild($xmlLT->createElement("total",$value));
                    $LTtesting["total"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1RR"))){
                    $root_LT->appendChild($xmlLT->createElement("RR",$value));
                    $LTtesting["RR"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Wickets"))){
                    $root_LT->appendChild($xmlLT->createElement("Wickets",$value));
                    $LTtesting["Wickets"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("firstLogo"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$urlImage.$value));
                    $LTtesting[$key] = $urlImage.$value;
                }
                else if(array_intersect(explode(",",$key), array("secondLogo"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$urlImage.$value));
                    $LTtesting[$key] = $urlImage.$value;
                }
                else if(array_intersect(explode(",",$key), array("batsman1Name"))){
                    $root_LT->appendChild($xmlLT->createElement("StrikerName",$value));
                    $root_LT->appendChild($xmlLT->createElement("StrikerRuns",$LTdecoded->{"values"}->{"batsman1Runs"}));
                    $root_LT->appendChild($xmlLT->createElement("StrikerBalls",$LTdecoded->{"values"}->{"batsman1Balls"}));

                    $LTtesting["StrikerName"] = $value;
                    $LTtesting["StrikerRuns"] = $LTdecoded->{"values"}->{"batsman1Runs"};
                    $LTtesting["StrikerBalls"] = $LTdecoded->{"values"}->{"batsman1Balls"};
                }
                else if(array_intersect(explode(",",$key), array("batsman2Name"))){
                    $root_LT->appendChild($xmlLT->createElement("RunnerName",$value));
                    $root_LT->appendChild($xmlLT->createElement("RunnerRuns",$LTdecoded->{"values"}->{"batsman2Runs"}));
                    $root_LT->appendChild($xmlLT->createElement("RunnerBalls",$LTdecoded->{"values"}->{"batsman2Balls"}));

                    $LTtesting["RunnerName"] = $value;
                    $LTtesting["RunnerRuns"] = $LTdecoded->{"values"}->{"batsman2Runs"};
                    $LTtesting["RunnerBalls"] = $LTdecoded->{"values"}->{"batsman2Balls"};
                }
                else if(array_intersect(explode(",",$key), array("bowlerName"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $root_LT->appendChild($xmlLT->createElement("bowlerRuns",$LTdecoded->{"values"}->{"bowlerRuns"}));
                    $root_LT->appendChild($xmlLT->createElement("bowlerWickets",$LTdecoded->{"values"}->{"bowlerWickets"}));

                    if(substr($LTdecoded->{"values"}->{"bowlerOvers"}, strpos($LTdecoded->{"values"}->{"bowlerOvers"}, ".")+1) == "0"){
                        $root_LT->appendChild($xmlLT->createElement("bowlerOvers",strtok($LTdecoded->{"values"}->{"bowlerOvers"}, '.')));
                        $LTtesting["bowlerOvers"] = strtok($LTdecoded->{"values"}->{"bowlerOvers"}, '.');
                    }
                    else{
                        $root_LT->appendChild($xmlLT->createElement("bowlerOvers",$LTdecoded->{"values"}->{"bowlerOvers"}));
                        $LTtesting["bowlerOvers"] = $LTdecoded->{"values"}->{"bowlerOvers"};
                    }

                    $LTtesting[$key] = $value;
                    $LTtesting["bowlerRuns"] = $LTdecoded->{"values"}->{"bowlerRuns"};
                    $LTtesting["bowlerWickets"] = $LTdecoded->{"values"}->{"bowlerWickets"};
                }
                else if(array_intersect(explode(",",$key), array("result"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("toss"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
            }
            
            if(isset($LTdecoded->{"balls"})){
                $ballsArray = array();
                $ballsArray = $LTdecoded->{"balls"};
                $rundisplayed = "";
                for($i=0;$i<count($ballsArray);$i++)
                {
                    $newball = $LTdecoded->{"balls"}[$i];
                    if($newball==".")
                    {$newball="0";}else{$newball=$LTdecoded->{"balls"}[$i];}
                    $rundisplayed = $rundisplayed." ".$newball;
                }
                $LTtesting["currentOverDetails"] = $rundisplayed;
            $root_LT->appendChild($xmlLT->createElement("currentOverDetails",$rundisplayed));
            }

        }
        else if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "true"){
            foreach($LTdecoded->{"values"} as $key => $value){
                if(array_intersect(explode(",",$key), array("t2Name"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2Code"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Name"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Code"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2Overs"))){
                    if(substr($value, strpos($value, ".")+1) == "0"){
                        $root_LT->appendChild($xmlLT->createElement("overs",strtok($value, '.')));
                        $LTtesting["overs"] = strtok($value, '.');
                    }
                    else{
                        $root_LT->appendChild($xmlLT->createElement("overs",$value));
                        $LTtesting["overs"] = $value;
                    }
                }
                else if(array_intersect(explode(",",$key), array("t2Total"))){
                    $root_LT->appendChild($xmlLT->createElement("total",$value));
                    $LTtesting["total"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2RR"))){
                    $root_LT->appendChild($xmlLT->createElement("RR",$value));
                    $LTtesting["RR"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t2Wickets"))){
                    $root_LT->appendChild($xmlLT->createElement("Wickets",$value));
                    $LTtesting["Wickets"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("t1Total"))){
                    $root_LT->appendChild($xmlLT->createElement("Target",intval($value+1)));
                    $LTtesting["Target"] = intval($value+1);
                }
                else if(array_intersect(explode(",",$key), array("batsman1Name"))){
                    $root_LT->appendChild($xmlLT->createElement("StrikerName",$value));
                    $root_LT->appendChild($xmlLT->createElement("StrikerRuns",$LTdecoded->{"values"}->{"batsman1Runs"}));
                    $root_LT->appendChild($xmlLT->createElement("StrikerBalls",$LTdecoded->{"values"}->{"batsman1Balls"}));

                    $LTtesting["StrikerName"] = $value;
                    $LTtesting["StrikerRuns"] = $LTdecoded->{"values"}->{"batsman1Runs"};
                    $LTtesting["StrikerBalls"] = $LTdecoded->{"values"}->{"batsman1Balls"};
                }
                else if(array_intersect(explode(",",$key), array("batsman2Name"))){
                    $root_LT->appendChild($xmlLT->createElement("RunnerName",$value));
                    $root_LT->appendChild($xmlLT->createElement("RunnerRuns",$LTdecoded->{"values"}->{"batsman2Runs"}));
                    $root_LT->appendChild($xmlLT->createElement("RunnerBalls",$LTdecoded->{"values"}->{"batsman2Balls"}));

                    $LTtesting["RunnerName"] = $value;
                    $LTtesting["RunnerRuns"] = $LTdecoded->{"values"}->{"batsman2Runs"};
                    $LTtesting["RunnerBalls"] = $LTdecoded->{"values"}->{"batsman2Balls"};
                }
                else if(array_intersect(explode(",",$key), array("bowlerName"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $root_LT->appendChild($xmlLT->createElement("bowlerRuns",$LTdecoded->{"values"}->{"bowlerRuns"}));
                    $root_LT->appendChild($xmlLT->createElement("bowlerWickets",$LTdecoded->{"values"}->{"bowlerWickets"}));

                    if(substr($LTdecoded->{"values"}->{"bowlerOvers"}, strpos($LTdecoded->{"values"}->{"bowlerOvers"}, ".")+1) == "0"){
                        $root_LT->appendChild($xmlLT->createElement("bowlerOvers",strtok($LTdecoded->{"values"}->{"bowlerOvers"}, '.')));
                        $LTtesting["bowlerOvers"] = strtok($LTdecoded->{"values"}->{"bowlerOvers"}, '.');
                    }
                    else{
                        $root_LT->appendChild($xmlLT->createElement("bowlerOvers",$LTdecoded->{"values"}->{"bowlerOvers"}));
                        $LTtesting["bowlerOvers"] = $LTdecoded->{"values"}->{"bowlerOvers"};
                    }

                    $LTtesting[$key] = $value;
                    $LTtesting["bowlerRuns"] = $LTdecoded->{"values"}->{"bowlerRuns"};
                    $LTtesting["bowlerWickets"] = $LTdecoded->{"values"}->{"bowlerWickets"};
                }
                else if(array_intersect(explode(",",$key), array("result"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("toss"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
            }

            if(isset($LTdecoded->{"balls"})){
                $ballsArray = array();
                $ballsArray = $LTdecoded->{"balls"};
                $rundisplayed = "";
                for($i=0;$i<count($ballsArray);$i++)
                {
                    $newball = $LTdecoded->{"balls"}[$i];
                    if($newball==".")
                    {$newball="0";}else{$newball=$LTdecoded->{"balls"}[$i];}
                    $rundisplayed = $rundisplayed." ".$newball;
                }
                $LTtesting["currentOverDetails"] = $rundisplayed;
            $root_LT->appendChild($xmlLT->createElement("currentOverDetails",$rundisplayed));
            }

            
            // foreach($LTdecoded->{"data"} as $key => $value){
            //     if($key == "balls"){
            //         $testoutput2 = implode(" ",$value);
            //     }
            //     $LTtesting["currentOverDetails"] = $testoutput2;
            // }
            foreach($LTdecoded->{"values"} as $key => $value){
                if(array_intersect(explode(",",$key), array("requiredRuns"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("remainingOvers"))){
                    if(substr($value, strpos($value, ".")+1) == "0"){
                        $root_LT->appendChild($xmlLT->createElement($key,strtok($value, '.')));
                        $LTtesting[$key] = strtok($value, '.');
                    }
                    else{
                        $root_LT->appendChild($xmlLT->createElement($key,$value));
                        $LTtesting[$key] = $value;
                    }

                    $findme = ".";
                    // print_r(strstr($value,$findme));
                    
                    if(strstr($value,$findme) !== false)
                    {
                        $dotposition = strpos($value,$findme);
                        $balls = substr($value,-1,1);
                        
                        $over = substr($value,0,2);
                        
                        $ballsint = (int)$balls;
                        $oversint = (int)$over;
                        $remainingballs = $oversint*6 + $ballsint;
                        $root_LT->appendChild($xmlLT->createElement("remainigballs",$remainingballs));
                        $LTtesting["remainigballs"] = $remainingballs;
                    }
                   
                }
                else if(array_intersect(explode(",",$key), array("RRR"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$value));
                    $LTtesting[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("firstLogo"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$urlImage.$value));
                    $LTtesting[$key] = $urlImage.$value;
                }
                else if(array_intersect(explode(",",$key), array("secondLogo"))){
                    $root_LT->appendChild($xmlLT->createElement($key,$urlImage.$value));
                    $LTtesting[$key] = $urlImage.$value;
                }
            }
        }



        //==================================================================================================================================
        //================================================== BATTING CARD DETAILS ==========================================================
        //==================================================================================================================================

        //============================================= Team1 Details =========================================================

        $xmlBatting1 = new DOMDocument();
        $root_Batting1 = $xmlBatting1->appendChild($xmlBatting1->createElement("DATA"));

        if(isset($decoded->{"matchInfo"})){
            $root_Batting1->appendChild($xmlBatting1->createElement("seriesName", $decoded->{"matchInfo"}->{"seriesName"}));
            $b1_output["seriesName"] = $decoded->{"matchInfo"}->{"seriesName"};
            $root_Batting1->appendChild($xmlBatting1->createElement("team1Name", $decoded->{"matchInfo"}->{"teamOneName"}));
            $b1_output["team1Name"] = $decoded->{"matchInfo"}->{"teamOneName"};
            $root_Batting1->appendChild($xmlBatting1->createElement("team1Code", $decoded->{"matchInfo"}->{"teamOneCode"}));
            $b2_output["team1Code"] = $decoded->{"matchInfo"}->{"teamOneCode"};
            $root_Batting1->appendChild($xmlBatting1->createElement("totalScore", $decoded->{"matchInfo"}->{"t1total"}));
            $b1_output["totalScore"] = $decoded->{"matchInfo"}->{"t1total"};
            $root_Batting1->appendChild($xmlBatting1->createElement("totalOut", $decoded->{"matchInfo"}->{"t1wickets"}));
            $b1_output["totalOut"] = $decoded->{"matchInfo"}->{"t1wickets"};
                $quotient = intval($decoded->{"matchInfo"}->{"t1balls"}/6);
                $remainder = fmod($decoded->{"matchInfo"}->{"t1balls"}, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_Batting1->appendChild($xmlBatting1->createElement("overs", $quotient ));
                    $b1_output["overs"] = $quotient;
                }
                else{
                    $root_Batting1->appendChild($xmlBatting1->createElement("overs", $overs ));
                    $b1_output["overs"] = $overs;
                }
            
            $root_Batting1->appendChild($xmlBatting1->createElement("RR", number_format(($decoded->{"matchInfo"}->{"t1total"})/$overs, 2)));
            $b1_output["RR"] = number_format(($decoded->{"matchInfo"}->{"t1total"})/$overs, 2);
            $root_Batting1->appendChild($xmlBatting1->createElement("extras", ($decoded->{"matchInfo"}->{"t1byes"}) + ($decoded->{"matchInfo"}->{"t1lbyes"}) + 
                                                                        ($decoded->{"matchInfo"}->{"t2Wides"}) + ($decoded->{"matchInfo"}->{"t2noballs"}) ));
            $b1_output["extras"] = ($decoded->{"matchInfo"}->{"t1byes"}) + ($decoded->{"matchInfo"}->{"t1lbyes"}) + 
                            ($decoded->{"matchInfo"}->{"t2Wides"}) + ($decoded->{"matchInfo"}->{"t2noballs"});

                //============================= BASIC INFORMATION COMPLETED ==============================================

            }
            $team1BattingArray = $decoded->{"team1Batting"};
            $yetToPlay = array();
            $k=1;
            for($i=0;$i<11;$i++){
                if($team1BattingArray[$i]->{"innings"} == 0){
                    array_push($yetToPlay , $team1BattingArray[$i]);
                }elseif($team1BattingArray[$i]->{"innings"} == 1){
                    if($team1BattingArray[$i]->{"isOut"}==0){
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Name", $team1BattingArray[$i]->{"firstName"}." ".$team1BattingArray[$i]->{"lastName"}));
                        $b1_output["player".$k."Name"] = $team1BattingArray[$i]->{"firstName"}." ".$team1BattingArray[$i]->{"lastName"};
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Score", $team1BattingArray[$i]->{"runsScored"}));
                        $b1_output["player".$k."Score"] = $team1BattingArray[$i]->{"runsScored"};
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."PlayedBalls", $team1BattingArray[$i]->{"ballsFaced"}));
                        $b1_output["player".$k."PlayedBalls"] = $team1BattingArray[$i]->{"ballsFaced"};
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."howOut", "not out"));
                        $b1_output["player".$k."howOut"] = "not out";
                    }elseif($team1BattingArray[$i]->{"isOut"}==1){
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Name", $team1BattingArray[$i]->{"firstName"}." ".$team1BattingArray[$i]->{"lastName"}));
                        $b1_output["player".$k."Name"] = $team1BattingArray[$i]->{"firstName"}." ".$team1BattingArray[$i]->{"lastName"};
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Score", $team1BattingArray[$i]->{"runsScored"}));
                        $b1_output["player".$k."Score"] = $team1BattingArray[$i]->{"runsScored"};
                        $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."PlayedBalls", $team1BattingArray[$i]->{"ballsFaced"}));
                        $b1_output["player".$k."PlayedBalls"] = $team1BattingArray[$i]->{"ballsFaced"};
                        if($team1BattingArray[$i]->{"wicketTaker2"} == 0){
                            for($j=0;$j<=10;$j++){
                                if($decoded->{"team2Batting"}[$j]->{"playerID"} == $team1BattingArray[$i]->{"wicketTaker1"}){
                                    $wickettaker1 = $decoded->{"team2Batting"}[$j]->{"nickName"};
                                }
                            }
                            $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."howOut", $team1BattingArray[$i]->{"howOut"}." ".$wickettaker1));
                            $b1_output["player".$k."howOut"] = $team1BattingArray[$i]->{"howOut"}." ".$wickettaker1;
                        }
                        else{
                            for($j=0;$j<=10;$j++){
                                if($decoded->{"team2Batting"}[$j]->{"playerID"} == $team1BattingArray[$i]->{"wicketTaker1"}){
                                    $wickettaker1 = $decoded->{"team2Batting"}[$j]->{"nickName"};
                                }
                                if($decoded->{"team2Batting"}[$j]->{"playerID"} == $team1BattingArray[$i]->{"wicketTaker2"}){
                                    $wickettaker2 = $decoded->{"team2Batting"}[$j]->{"nickName"};
                                }
                            }
                            $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."howOut", $team1BattingArray[$i]->{"howOut"}." ".$wickettaker2." b ".$wickettaker1));
                            $b1_output["player".$k."howOut"] = $team1BattingArray[$i]->{"howOut"}." ".$wickettaker2." b ".$wickettaker1;
                        }

                    }
                    $k++;
                }
            }
            for($i=0;$i<count($yetToPlay);$i++){
                $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Name", $yetToPlay[$i]->{"firstName"}." ".$yetToPlay[$i]->{"lastName"}));
                $b1_output["player".$k."Name"] = $yetToPlay[$i]->{"firstName"}." ".$yetToPlay[$i]->{"lastName"};
                $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."Score", ""));
                $b1_output["player".$k."Score"] = "";
                $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."PlayedBalls", ""));
                $b1_output["player".$k."PlayedBalls"] = "";
                $root_Batting1->appendChild($xmlBatting1->createElement("player".$k."howOut", ""));
                $b1_output["player".$k."howOut"] = "";
                $k++;
            }
            

        //============================================= Team2 Details =========================================================

        $xmlBatting2 = new DOMDocument();
        $root_Batting2 = $xmlBatting2->appendChild($xmlBatting2->createElement("DATA"));


        if(isset($decoded->{"matchInfo"})){
            $root_Batting2->appendChild($xmlBatting2->createElement("seriesName", $decoded->{"matchInfo"}->{"seriesName"}));
            $b2_output["seriesName"] = $decoded->{"matchInfo"}->{"seriesName"};
            $root_Batting2->appendChild($xmlBatting2->createElement("team2Name", $decoded->{"matchInfo"}->{"teamTwoName"}));
            $b2_output["team2Name"] = $decoded->{"matchInfo"}->{"teamTwoName"};
            $root_Batting2->appendChild($xmlBatting2->createElement("team2Code", $decoded->{"matchInfo"}->{"teamTwoCode"}));
            $b2_output["team2Code"] = $decoded->{"matchInfo"}->{"teamTwoCode"};
            $root_Batting2->appendChild($xmlBatting2->createElement("totalScore", $decoded->{"matchInfo"}->{"t2total"}));
            $b2_output["totalScore"] = $decoded->{"matchInfo"}->{"t2total"};
            $root_Batting2->appendChild($xmlBatting2->createElement("totalOut", $decoded->{"matchInfo"}->{"t2wickets"}));
            $b2_output["totalOut"] = $decoded->{"matchInfo"}->{"t2wickets"};
                $quotient = intval($decoded->{"matchInfo"}->{"t2balls"}/6);
                $remainder = fmod($decoded->{"matchInfo"}->{"t2balls"}, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_Batting2->appendChild($xmlBatting2->createElement("overs", $quotient ));
                    $b2_output["overs"] = $quotient;
                }
                else{
                    $root_Batting2->appendChild($xmlBatting2->createElement("overs", $overs ));
                    $b2_output["overs"] = $overs;
                }
            if($overs!=0){
                $root_Batting2->appendChild($xmlBatting2->createElement("RR", number_format(($decoded->{"matchInfo"}->{"t2total"})/$overs, 2)));
                $b2_output["RR"] = number_format(($decoded->{"matchInfo"}->{"t2total"})/$overs, 2);
            }
            $root_Batting2->appendChild($xmlBatting2->createElement("extras", ($decoded->{"matchInfo"}->{"t2byes"}) + ($decoded->{"matchInfo"}->{"t2lbyes"}) + 
                                                                        ($decoded->{"matchInfo"}->{"t1Wides"}) + ($decoded->{"matchInfo"}->{"t1noballs"}) ));
            $b2_output["extras"] = ($decoded->{"matchInfo"}->{"t2byes"}) + ($decoded->{"matchInfo"}->{"t2lbyes"}) + 
                            ($decoded->{"matchInfo"}->{"t1Wides"}) + ($decoded->{"matchInfo"}->{"t1noballs"});

                //============================= BASIC INFORMATION COMPLETED ==============================================

            }
            $team2BattingArray = $decoded->{"team2Batting"};
            $yetToPlay = array();
            $k=1;
            for($i=0;$i<11;$i++){
                if($team2BattingArray[$i]->{"innings"} == 0){
                    array_push($yetToPlay , $team2BattingArray[$i]);
                }elseif($team2BattingArray[$i]->{"innings"} == 1){
                    if($team2BattingArray[$i]->{"isOut"}==0){
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Name", $team2BattingArray[$i]->{"firstName"}." ".$team2BattingArray[$i]->{"lastName"}));
                        $b2_output["player".$k."Name"] = $team2BattingArray[$i]->{"firstName"}." ".$team2BattingArray[$i]->{"lastName"};
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Score", $team2BattingArray[$i]->{"runsScored"}));
                        $b2_output["player".$k."Score"] = $team2BattingArray[$i]->{"runsScored"};
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."PlayedBalls", $team2BattingArray[$i]->{"ballsFaced"}));
                        $b2_output["player".$k."PlayedBalls"] = $team2BattingArray[$i]->{"ballsFaced"};
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."howOut", "not out"));
                        $b2_output["player".$k."howOut"] = "not out";
                    }elseif($team2BattingArray[$i]->{"isOut"}==1){
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Name", $team2BattingArray[$i]->{"firstName"}." ".$team2BattingArray[$i]->{"lastName"}));
                        $b2_output["player".$k."Name"] = $team2BattingArray[$i]->{"firstName"}." ".$team2BattingArray[$i]->{"lastName"};
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Score", $team2BattingArray[$i]->{"runsScored"}));
                        $b2_output["player".$k."Score"] = $team2BattingArray[$i]->{"runsScored"};
                        $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."PlayedBalls", $team2BattingArray[$i]->{"ballsFaced"}));
                        $b2_output["player".$k."PlayedBalls"] = $team2BattingArray[$i]->{"ballsFaced"};
                        if($team1BattingArray[$i]->{"wicketTaker2"} == 0){
                            for($j=0;$j<=10;$j++){
                                if($decoded->{"team1Batting"}[$j]->{"playerID"} == $team2BattingArray[$i]->{"wicketTaker1"}){
                                    $wickettaker1 = $decoded->{"team1Batting"}[$j]->{"nickName"};
                                }
                            }
                            $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."howOut", $team2BattingArray[$i]->{"howOut"}." ".$wickettaker1));
                            $b2_output["player".$k."howOut"] = $team2BattingArray[$i]->{"howOut"}." ".$wickettaker1;
                        }
                        else{
                            for($j=0;$j<=10;$j++){
                                if($decoded->{"team1Batting"}[$j]->{"playerID"} == $team2BattingArray[$i]->{"wicketTaker1"}){
                                    $wickettaker1 = $decoded->{"team1Batting"}[$j]->{"nickName"};
                                }
                                if($decoded->{"team1Batting"}[$j]->{"playerID"} == $team2BattingArray[$i]->{"wicketTaker2"}){
                                    $wickettaker2 = $decoded->{"team1Batting"}[$j]->{"nickName"};
                                }
                            }
                            $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."howOut", $team2BattingArray[$i]->{"howOut"}." ".$wickettaker2." b ".$wickettaker1));
                            $b2_output["player".$k."howOut"] = $team2BattingArray[$i]->{"howOut"}." ".$wickettaker2." b ".$wickettaker1;
                        }

                    }
                    $k++;
                }
            }
            for($i=0;$i<count($yetToPlay);$i++){
                $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Name", $yetToPlay[$i]->{"firstName"}." ".$yetToPlay[$i]->{"lastName"}));
                $b2_output["player".$k."Name"] = $yetToPlay[$i]->{"firstName"}." ".$yetToPlay[$i]->{"lastName"};
                $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."Score", ""));
                $b2_output["player".$k."Score"] = "";
                $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."PlayedBalls", ""));
                $b2_output["player".$k."PlayedBalls"] = "";
                $root_Batting2->appendChild($xmlBatting2->createElement("player".$k."howOut", ""));
                $b2_output["player".$k."howOut"] = "";
                $k++;
            }
            
            



         //==================================================================================================================================
        //================================================== BATTING CARD DETAILS FINISHED ==================================================
        //==================================================================================================================================

        
        //==================================================================================================================================
        //================================================== BOWLING CARD DETAILS ==========================================================
        //==================================================================================================================================


        // ============================================= MatchInfo Team1 Details =========================================================

        $xmlBowling1 = new DOMDocument();
        $root_Bowling1 = $xmlBowling1->appendChild($xmlBowling1->createElement("DATA"));


        $innings1 = "1";
        $innings2 = "2";

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("teamOneName"))){
                $root_Bowling1->appendChild($xmlBowling1->createElement("team1Name", $value));
                $bo1_output["team1Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("seriesName"))){
                $root_Bowling1->appendChild($xmlBowling1->createElement($key, $value));
                $bo1_output[$key] = $value;
            }
            $bo1_output["innings"] = $innings1;
        }
        $root_Bowling1->appendChild($xmlBowling1->createElement("innings", $innings1));

        //============================================= Total Bowler Wickets =========================================================
        // $bo1_output['==='] = "========================== PLAYER DETAILS =========================================";

            for($i=0;$i<11;$i++){
                if(isset($decoded->{"team1Bowling"}[$i])){
                    foreach($decoded->{"team1Bowling"}[$i] as $key => $value){
                        if("wickets" != ""){
                            if(array_intersect(explode(",",$key), array("firstName"))){
                                $val = $value;
                            }
                            if(array_intersect(explode(",",$key), array("lastName"))){
                                $root_Bowling1->appendChild($xmlBowling1->createElement("player".strval($i+1)."Name", $val." ".$value));
                                $bo1_output["player".strval($i+1)."Name"] = $val." ".$value;
                            }
                            else if(array_intersect(explode(",",$key), array("balls"))){
                                $quotient = intval($value/6);
                                $remainder = fmod($value, 6);
                                $overs = $quotient.".".$remainder;
                                if($remainder == "0"){
                                    $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), $quotient));
                                    $bo1_output["overs".($i+1)] = $quotient;
                                }
                                else{
                                    $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), $overs));
                                    $bo1_output["overs".($i+1)] = $overs;
                                }
                            }
                            else if(array_intersect(explode(",",$key), array("maidens"))){
                                $root_Bowling1->appendChild($xmlBowling1->createElement("maidens".($i+1), $value));
                                $bo1_output["maidens".($i+1)] = $value;
                            }
                            else if(array_intersect(explode(",",$key), array("wickets"))){
                                $root_Bowling1->appendChild($xmlBowling1->createElement("wickets".($i+1), $value));
                                $bo1_output["wickets".($i+1)] = $value;
                            }
                            else if(array_intersect(explode(",",$key), array("runs"))){
                                $root_Bowling1->appendChild($xmlBowling1->createElement("runs".($i+1), $value));
                                $bo1_output["runs".($i+1)] = $value;
                            }
                            else if(array_intersect(explode(",",$key), array("playerEconRate"))){
                                $root_Bowling1->appendChild($xmlBowling1->createElement("economy".($i+1), $value));
                                $bo1_output["economy".($i+1)] = $value;
                            }
                        }
                    }
                }
                else{
                    $root_Bowling1->appendChild($xmlBowling1->createElement("player".strval($i+1)."Name", ""));
                    $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), ""));
                    $root_Bowling1->appendChild($xmlBowling1->createElement("maidens".($i+1), ""));
                    $root_Bowling1->appendChild($xmlBowling1->createElement("wickets".($i+1), ""));
                    $root_Bowling1->appendChild($xmlBowling1->createElement("runs".($i+1), ""));
                    $root_Bowling1->appendChild($xmlBowling1->createElement("economy".($i+1), ""));
                    $bo1_output["player".strval($i+1)."Name"] = "";
                    $bo1_output["overs".($i+1)] = "";
                    $bo1_output["maidens".($i+1)] = "";
                    $bo1_output["wickets".($i+1)] = "";
                    $bo1_output["runs".($i+1)] = "";
                    $bo1_output["economy".($i+1)] = "";
                }
            }


        // ============================================= Total Wickets & Scores =========================================================
        

        // $bo1_output['========'] = "========================== WICKETS DETAILS =========================================";

        for($i=1;$i<=11;$i++){
            if(isset($decoded->{"partnershipMap"}->{$innings2."-".$i})){
                foreach($decoded->{"partnershipMap"}->{$innings2."-".$i}[0] as $key => $value){
                    if(array_intersect(explode(",",$key), array("teamTotal"))){   
                        $root_Bowling1->appendChild($xmlBowling1->createElement("fow".$i."label", $i));             
                        $bo1_output["fow".$i."label"] = $value;
                        $root_Bowling1->appendChild($xmlBowling1->createElement("fow".$i."value", $value));             
                        $bo1_output["fow".$i."value"] = $value;
                    }
                }
            }
            // else{
            //     $root_Bowling1->appendChild($xmlBowling1->createElement("fow".$i."label", $i));             
            //     $bo1_output["fow".$i."label"] = $i;
            //     $root_Bowling1->appendChild($xmlBowling1->createElement("fow".$i."value", " "));             
            //     $bo1_output["fow".$i."value"] = " ";
            // }
        }

        $quotient1 = intval($decoded->{"matchInfo"}->{"t2balls"}/6);
        $remainder1 = fmod($decoded->{"matchInfo"}->{"t2balls"}, 6);
        $total_overs1 = $quotient1.".".$remainder1;
        $total_score1 = $decoded->{"matchInfo"}->{"t2total"};

        if($remainder1 == "0"){
            $root_Bowling1->appendChild($xmlBowling1->createElement("overs", $quotient1));  
            $bo1_output["overs"] = $quotient1;   
        }
        else{
            $root_Bowling1->appendChild($xmlBowling1->createElement("overs", $total_overs1));  
            $bo1_output["overs"] = $total_overs1; 
        } 
        @$root_Bowling1->appendChild($xmlBowling1->createElement("runrate", ($total_score1/$total_overs1) ? number_format($total_score1/$total_overs1, 2) : 0));  
        $root_Bowling1->appendChild($xmlBowling1->createElement("totalScore", $decoded->{"matchInfo"}->{"t2total"}));  
        $root_Bowling1->appendChild($xmlBowling1->createElement("totalWicket", $decoded->{"matchInfo"}->{"t2wickets"}));  

        @$bo1_output["runrate"] = ($total_score1/$total_overs1) ? number_format($total_score1/$total_overs1, 2) : 0;
        $bo1_output["totalScore"] = $decoded->{"matchInfo"}->{"t2total"};
        $bo1_output["totalWicket"] = $decoded->{"matchInfo"}->{"t2wickets"};

        $root_Bowling1->appendChild($xmlBowling1->createElement("extras", ($decoded->{"matchInfo"}->{"t2byes"}) + ($decoded->{"matchInfo"}->{"t2lbyes"}) + 
                                                                        ($decoded->{"matchInfo"}->{"t1Wides"}) + ($decoded->{"matchInfo"}->{"t1noballs"}) ));
        $bo1_output["extras"] = ($decoded->{"matchInfo"}->{"t2byes"}) + ($decoded->{"matchInfo"}->{"t2lbyes"}) + 
                            ($decoded->{"matchInfo"}->{"t2Wides"}) + ($decoded->{"matchInfo"}->{"t2noballs"});


        //============================================= MatchInfo Team2 Details =========================================================

        $xmlBowling2 = new DOMDocument();
        $root_Bowling2 = $xmlBowling2->appendChild($xmlBowling2->createElement("DATA"));

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("teamTwoName"))){
                $root_Bowling2->appendChild($xmlBowling2->createElement("team2Name", $value));
                $bo2_output["team2Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("seriesName"))){
                $root_Bowling2->appendChild($xmlBowling2->createElement($key, $value));
                $bo2_output[$key] = $value;
            }
            $bo2_output["innings"] = $innings2;
        }
        $root_Bowling2->appendChild($xmlBowling2->createElement("innings", $innings2));

        //============================================= Total Bowler Wickets =========================================================
        // $bo2_output['==='] = "========================== PLAYER DETAILS =========================================";

        for($i=0;$i<11;$i++){
            if(isset($decoded->{"team2Bowling"}[$i])){
                foreach($decoded->{"team2Bowling"}[$i] as $key => $value){
                    if("wickets" != ""){
                        if(array_intersect(explode(",",$key), array("firstName"))){
                            $val = $value;
                        }
                        if(array_intersect(explode(",",$key), array("lastName"))){
                            $root_Bowling2->appendChild($xmlBowling2->createElement("player".strval($i+1)."Name", $val." ".$value));
                            $bo2_output["player".strval($i+1)."Name"] = $val." ".$value;
                        }
                        else if(array_intersect(explode(",",$key), array("balls"))){
                            $quotient = intval($value/6);
                            $remainder = fmod($value, 6);
                            $overs = $quotient.".".$remainder;
                            if($remainder == "0"){
                                $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), $quotient));  
                                $bo2_output["overs".($i+1)] = $quotient;   
                            }
                            else{
                                $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), $overs));  
                                $bo2_output["overs".($i+1)] = $overs; 
                            } 
                        }
                        else if(array_intersect(explode(",",$key), array("maidens"))){
                            $root_Bowling2->appendChild($xmlBowling2->createElement("maidens".($i+1), $value));
                            $bo2_output["maidens".($i+1)] = $value;
                        }
                        else if(array_intersect(explode(",",$key), array("wickets"))){
                            $root_Bowling2->appendChild($xmlBowling2->createElement("wickets".($i+1), $value));
                            $bo2_output["wickets".($i+1)] = $value;
                        }
                        else if(array_intersect(explode(",",$key), array("runs"))){
                            $root_Bowling2->appendChild($xmlBowling2->createElement("runs".($i+1), $value));
                            $bo2_output["runs".($i+1)] = $value;
                        }
                        else if(array_intersect(explode(",",$key), array("playerEconRate"))){
                            $root_Bowling2->appendChild($xmlBowling2->createElement("economy".($i+1), $value));
                            $bo2_output["economy".($i+1)] = $value;
                        }
                    }
                }
            }
            else{

                $root_Bowling2->appendChild($xmlBowling2->createElement("player".strval($i+1)."Name", ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("maidens".($i+1), ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("wickets".($i+1), ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("runs".($i+1), ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("economy".($i+1), ""));

                $bo2_output["player".strval($i+1)."Name"] = "";
                $bo2_output["overs".($i+1)] = "";
                $bo2_output["maidens".($i+1)] = "";
                $bo2_output["wickets".($i+1)] = "";
                $bo2_output["runs".($i+1)] = "";
                $bo2_output["economy".($i+1)] = "";
            }
        }


        // ============================================= Total Wickets & Scores =========================================================
        

        // $bo2_output['========'] = "========================== WICKETS DETAILS =========================================";

        for($i=1;$i<=11;$i++){
            if(isset($decoded->{"partnershipMap"}->{$innings1."-".$i})){
                foreach($decoded->{"partnershipMap"}->{$innings1."-".$i}[0] as $key => $value){
                    if(array_intersect(explode(",",$key), array("teamTotal"))){  
                        $root_Bowling2->appendChild($xmlBowling2->createElement("fow".$i."label", $i));              
                        $bo2_output["fow".$i."label"] = $i;
                        $root_Bowling2->appendChild($xmlBowling2->createElement("fow".$i."value", $value));
                        $bo2_output["fow".$i."value"] = $value;
                    }
                }
            }
            // else{
            //     $root_Bowling2->appendChild($xmlBowling2->createElement("fow".$i."label", $i));              
            //     $bo2_output["fow".$i."label"] = $i;
            //     $root_Bowling2->appendChild($xmlBowling2->createElement("fow".$i."value", ""));
            //             $bo2_output["fow".$i."value"] = "";
            // }
            
        }

        $quotient2 = intval($decoded->{"matchInfo"}->{"t1balls"}/6);
        $remainder2 = fmod($decoded->{"matchInfo"}->{"t1balls"}, 6);
        $total_overs2 = $quotient2.".".$remainder2;
        $total_score2 = $decoded->{"matchInfo"}->{"t1total"};

        if($remainder2 == "0"){
            $root_Bowling2->appendChild($xmlBowling2->createElement("overs", $quotient2)); 
            $bo2_output["overs"] = $quotient2;
        }
        else{
            $root_Bowling2->appendChild($xmlBowling2->createElement("overs", $total_overs2)); 
            $bo2_output["overs"] = $total_overs2;
        }

        @$root_Bowling2->appendChild($xmlBowling2->createElement("runrate", ($total_score2/$total_overs2) ? number_format($total_score2/$total_overs2, 2) : 0)); 
        $root_Bowling2->appendChild($xmlBowling2->createElement("totalScore", $decoded->{"matchInfo"}->{"t1total"})); 
        $root_Bowling2->appendChild($xmlBowling2->createElement("totalWicket", $decoded->{"matchInfo"}->{"t1wickets"})); 

        @$bo2_output["runrate"] = ($total_score2/$total_overs2) ? number_format($total_score2/$total_overs2, 2) : 0;
        $bo2_output["totalScore"] = $decoded->{"matchInfo"}->{"t1total"};
        $bo2_output["totalWicket"] = $decoded->{"matchInfo"}->{"t1wickets"};

        $root_Bowling2->appendChild($xmlBowling2->createElement("extras", ($decoded->{"matchInfo"}->{"t1byes"}) + ($decoded->{"matchInfo"}->{"t1lbyes"}) + 
                                                                        ($decoded->{"matchInfo"}->{"t1Wides"}) + ($decoded->{"matchInfo"}->{"t1noballs"}) ));
        $bo2_output["extras"] = ($decoded->{"matchInfo"}->{"t1byes"}) + ($decoded->{"matchInfo"}->{"t1lbyes"}) + 
                            ($decoded->{"matchInfo"}->{"t1Wides"}) + ($decoded->{"matchInfo"}->{"t1noballs"});





        //==================================================================================================================================
        //================================================== AfterOvers DETAILS ==========================================================
        //==================================================================================================================================


        $xmlAfterOvers = new DOMDocument();
        $root_AfterOvers = $xmlAfterOvers->appendChild($xmlAfterOvers->createElement("DATA"));
                    
        if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "true"){
            $totalOvers = $LTdecoded->{"values"}->{"overs"};
            $teamoneName = $decoded2->{"matchInfo"}->{"teamOneName"};
            $teamtwoName = $decoded2->{"matchInfo"}->{"teamTwoName"};

            for($i=0;$i<$totalOvers;$i++){

                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("overs-".($i+1), $decoded2->{"overMap"}->{"1-".$i}->{"overNumber"}+1));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team1name-".($i+1), $teamoneName));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team1runs-".($i+1), $decoded2->{"overMap"}->{"1-".$i}->{"matchTotal"}));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team1wickets-".($i+1), $decoded2->{"overMap"}->{"1-".$i}->{"matchWickets"}));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team2name-".($i+1), $teamtwoName));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team2runs-".($i+1), $decoded2->{"overMap"}->{"2-".$i}->{"matchTotal"}));
                $root_AfterOvers->appendChild($xmlAfterOvers->createElement("team2wickets-".($i+1), $decoded2->{"overMap"}->{"2-".$i}->{"matchWickets"}));
            }
        }
        
        







        //==================================================================================================================================
        //================================================== AfterOvers DETAILS FINISHED ==========================================================
        //==================================================================================================================================





        //==================================================================================================================================
        //================================================== LastOutPlayer DETAILS ==========================================================
        //==================================================================================================================================

        $xmllastoutplayer = new DOMDocument();
        $root_lastoutplayer = $xmllastoutplayer->appendChild($xmllastoutplayer->createElement("DATA"));



        if(isset($decoded2->{"lastOutPlayer"})){
            foreach($decoded2->{"lastOutPlayer"} as $key => $value){
                if(array_intersect(explode(",",$key), array("firstName"))){
                    $val = $value;
                }
                if(array_intersect(explode(",",$key), array("lastName"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("outBatsmanName", $val." ".$value)); 
                    $lastout_output["outBatsmanName"] = $val." ".$value;
                }
                if(array_intersect(explode(",",$key), array("runsScored"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("totalScore", $value)); 
                    $lastout_output["totalScore"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("ballsFaced"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("totalBallsPlayed", $value)); 
                    $lastout_output["totalBallsPlayed"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("howOut"))){
                    if(isset($decoded2->{"lastOutPlayer"}->{"howOut"})){
                        $lastoutplayerId = $decoded2->{"lastOutPlayer"}->{"playerID"};
                        if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "false"){
                            for($i=0;$i<11;$i++){
                                if($decoded->{"team1Batting"}[$i]->{"playerID"} == $lastoutplayerId){
                                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $decoded->{"team1Batting"}[$i]->{"outStringNoLink"})); 
                                    $lastout_output[$key] = $decoded->{"team1Batting"}[$i]->{"outStringNoLink"};
                                    break;
                                }
                            }
                        }
                        else if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "true"){
                            for($i=0;$i<11;$i++){
                                if($decoded->{"team2Batting"}[$i]->{"playerID"} == $lastoutplayerId){
                                    // print_r($decoded->{"team2Batting"}[$i]);
                                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $decoded->{"team2Batting"}[$i]->{"outStringNoLink"})); 
                                    $lastout_output[$key] = $decoded->{"team2Batting"}[$i]->{"outStringNoLink"};
                                    break;
                                }
                            }
                        }
                        else{
                            if(isset($decoded->{"team1Batting"})){
                                for($i=0;$i<11;$i++){
                                    if($decoded->{"team1Batting"}[$i]->{"playerID"} == $lastoutplayerId){
                                        $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $decoded->{"team1Batting"}[$i]->{"outStringNoLink"})); 
                                        $lastout_output[$key] = $decoded->{"team1Batting"}[$i]->{"outStringNoLink"};
                                        break;
                                    }
                                }
                            }
                            else if(isset($decoded->{"team2Batting"})){
                                for($i=0;$i<11;$i++){
                                    if($decoded->{"team2Batting"}[$i]->{"playerID"} == $lastoutplayerId){
                                        $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $decoded->{"team2Batting"}[$i]->{"outStringNoLink"})); 
                                        $lastout_output[$key] = $decoded->{"team2Batting"}[$i]->{"outStringNoLink"};
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, "")); 
                        $lastout_output[$key] = "";
                    }
                }
                else if(array_intersect(explode(",",$key), array("fours"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $value)); 
                    $lastout_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("sixers"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("six", $value)); 
                    $lastout_output["six"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                    $root_lastoutplayer->appendChild($xmllastoutplayer->createElement($key, $urlImage.$value));
                    $lastout_output[$key] = $urlImage.$value;
                }
                $runs = $decoded2->{"lastOutPlayer"}->{"runsScored"};
                $balls = $decoded2->{"lastOutPlayer"}->{"ballsFaced"};
                $lastout_output["strikeRate"] = number_format(($runs/$balls)*100, 2);
            }
            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("strikeRate", number_format(($runs/$balls)*100, 2))); 
        }
        else{
            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("outBatsmanName", "")); 
            $lastout_output["outBatsmanName"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("totalScore", "")); 
            $lastout_output["totalScore"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("totalBallsPlayed", "")); 
            $lastout_output["totalBallsPlayed"] = "";        

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("howOut", "")); 
            $lastout_output["howOut"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("fours", "")); 
            $lastout_output["fours"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("six", "")); 
            $lastout_output["six"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("profilepic_file_path", "")); 
            $lastout_output["profilepic_file_path"] = "";

            $root_lastoutplayer->appendChild($xmllastoutplayer->createElement("strikeRate", "")); 
            $lastout_output["strikeRate"] = "";
        }


        //==================================================================================================================================
        //================================================== SQUADTEAM DETAILS ==========================================================
        //==================================================================================================================================


        //================================================ TEAM 1 Details =======================================================

        $xmlSquad1 = new DOMDocument();
        $root_Squad1 = $xmlSquad1->appendChild($xmlSquad1->createElement("DATA"));

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("teamOneName"))){
                $root_Squad1->appendChild($xmlSquad1->createElement("team1Name",$value));
                $squad1_output["team1Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("seriesName"))){
                $root_Squad1->appendChild($xmlSquad1->createElement($key,$value));
                $squad1_output[$key] = $value;
            }
        }

        for($i=0;$i<11;$i++){
            if(isset($decoded->{"team1Batting"}[$i])){
                foreach($decoded->{"team1Batting"}[$i] as $key => $value){
                    if(array_intersect(explode(",",$key), array("firstName"))){
                        $val = $value;
                    }
                    if(array_intersect(explode(",",$key), array("lastName"))){
                        $root_Squad1->appendChild($xmlSquad1->createElement("player".strval($i+1)."Name", $val." ".$value));
                        $squad1_output["player".strval($i+1)."Name"] = $val." ".$value;
                    }
                    else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                        $root_Squad1->appendChild($xmlSquad1->createElement("player".strval($i+1)."ImageURL", $urlImage.$value));
                        $squad1_output["player".strval($i+1)."ImageURL"] = $urlImage.$value;
                    }
                }
            }
        }


        //================================================ TEAM 2 Details =======================================================

        $xmlSquad2 = new DOMDocument();
        $root_Squad2 = $xmlSquad2->appendChild($xmlSquad2->createElement("DATA"));

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("teamTwoName"))){
                $root_Squad2->appendChild($xmlSquad2->createElement("team2Name",$value));
                $squad2_output["team2Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("seriesName"))){
                $root_Squad2->appendChild($xmlSquad2->createElement($key,$value));
                $squad2_output[$key] = $value;
            }
        }

        for($i=0;$i<11;$i++){
            if(isset($decoded->{"team2Batting"}[$i])){
                foreach($decoded->{"team2Batting"}[$i] as $key => $value){
                    if(array_intersect(explode(",",$key), array("firstName"))){
                        $val = $value;
                    }
                    if(array_intersect(explode(",",$key), array("lastName"))){
                        $root_Squad2->appendChild($xmlSquad2->createElement("player".strval($i+1)."Name", $val." ".$value));
                        $squad2_output["player".strval($i+1)."Name"] = $val." ".$value;
                    }
                    else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                        $root_Squad2->appendChild($xmlSquad2->createElement("player".strval($i+1)."ImageURL", $urlImage." ".$value));
                        $squad2_output["player".strval($i+1)."ImageURL"] = $urlImage.$value;
                    }
                }
            }
        }



        //==================================================================================================================================
        //================================================== BATSMAN Stats DETAILS ==========================================================
        //==================================================================================================================================


        //================================================Striker Details=======================================================

        $xmlbatsmanstriker = new DOMDocument();
        $root_batsmanstriker = $xmlbatsmanstriker->appendChild($xmlbatsmanstriker->createElement("DATA"));

        if(isset($decoded2->{"latestBatting"})){
            foreach($decoded2->{"latestBatting"}[0] as $key => $value){
                if(array_intersect(explode(",",$key), array("firstName"))){
                    $val = $value;
                }
                if(array_intersect(explode(",",$key), array("lastName"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("batsmanName", $val." ".$value));
                    $batsmanstriker_output["batsmanName"] = $val." ".$value;
                }
                else if(array_intersect(explode(",",$key), array("ballsFaced"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("playedBalls", $value));
                    $batsmanstriker_output["playedBalls"] = $value;

                }
                else if(array_intersect(explode(",",$key), array("runsScored"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("totalScore", $value."*"));
                    $batsmanstriker_output["totalScore"] = $value."*";
                }
                else if(array_intersect(explode(",",$key), array("fours"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement($key, $value));
                    $batsmanstriker_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("sixers"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("six", $value));
                    $batsmanstriker_output["six"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                    $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement($key, $urlImage.$value));
                    $batsmanstriker_output[$key] = $urlImage.$value;
                }
            }
            $runs = $decoded2->{"latestBatting"}[0]->{"runsScored"};
            $balls = $decoded2->{"latestBatting"}[0]->{"ballsFaced"};
            @$root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("strikeRate", number_format(($runs/$balls)*100, 2)));
            @$batsmanstriker_output["strikeRate"] = number_format(($runs/$balls)*100, 2);
            
        }
        else{
            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("batsmanName", ""));
            $batsmanstriker_output["batsmanName"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("playedBalls", ""));
            $batsmanstriker_output["playedBalls"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("totalScore", ""));
            $batsmanstriker_output["totalScore"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("fours", ""));
            $batsmanstriker_output["fours"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("six", ""));
            $batsmanstriker_output["six"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("profilepic_file_path", ""));
            $batsmanstriker_output["profilepic_file_path"] = "";

            $root_batsmanstriker->appendChild($xmlbatsmanstriker->createElement("strikeRate", ""));
            $batsmanstriker_output["strikeRate"] = "";
        }


        //================================================Non-Striker Details=======================================================

        $xmlbatsmannonstriker = new DOMDocument();
        $root_batsmannonstriker = $xmlbatsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("DATA"));

        if(isset($decoded2->{"latestBatting"})){
            foreach($decoded2->{"latestBatting"}[1] as $key => $value){
                if(array_intersect(explode(",",$key), array("firstName"))){
                    $val = $value;
                }
                if(array_intersect(explode(",",$key), array("lastName"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("batsmanName", $val." ".$value));
                    $batsmannonstriker_output["batsmanName"] = $val." ".$value;
                }
                else if(array_intersect(explode(",",$key), array("ballsFaced"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("playedBalls", $value));
                    $batsmannonstriker_output["playedBalls"] = $value;

                }
                else if(array_intersect(explode(",",$key), array("runsScored"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("totalScore", $value."*"));
                    $batsmannonstriker_output["totalScore"] = $value."*";
                }
                else if(array_intersect(explode(",",$key), array("fours"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement($key, $value));
                    $batsmannonstriker_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("sixers"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("six", $value));
                    $batsmannonstriker_output["six"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                    $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement($key, $urlImage.$value));
                    $batsmannonstriker_output[$key] = $urlImage.$value;
                }
            }
            $runs = $decoded2->{"latestBatting"}[1]->{"runsScored"};
            $balls = $decoded2->{"latestBatting"}[1]->{"ballsFaced"};
            @$root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("strikeRate", number_format(($runs/$balls)*100, 2)));
            @$batsmannonstriker_output["strikeRate"] = number_format(($runs/$balls)*100, 2);
        }
        else{
            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("batsmanName", ""));
            $batsmannonstriker_output["batsmanName"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("playedBalls", ""));
            $batsmannonstriker_output["playedBalls"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("totalScore", ""));
            $batsmannonstriker_output["totalScore"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("fours", ""));
            $batsmannonstriker_output["fours"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("six", ""));
            $batsmannonstriker_output["six"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("profilepic_file_path", ""));
            $batsmannonstriker_output["profilepic_file_path"] = "";

            $root_batsmannonstriker->appendChild($xmlbatsmannonstriker->createElement("strikeRate", ""));
            $batsmannonstriker_output["strikeRate"] = "";
        }



        //==================================================================================================================================
        //================================================== BOWLER Stats DETAILS ==========================================================
        //==================================================================================================================================
        $xmlbowler = new DOMDocument();
        $root_bowler = $xmlbowler->appendChild($xmlbowler->createElement("DATA"));

        if(isset($decoded2->{"currentBowler"})){
            foreach($decoded2->{"currentBowler"} as $key => $value){
                if(array_intersect(explode(",",$key), array("firstName"))){
                    $val = $value;
                }
                if(array_intersect(explode(",",$key), array("lastName"))){
                    $root_bowler->appendChild($xmlbowler->createElement("bowlerName", $val." ".$value));
                    $bowler_output["bowlerName"] = $val." ".$value;
                }
                else if(array_intersect(explode(",",$key), array("balls"))){
                    $quotient = intval($value/6);
                    $remainder = fmod($value, 6);
                    $overs = $quotient.".".$remainder;
                    if($remainder == "0"){
                        $root_bowler->appendChild($xmlbowler->createElement("overs",$quotient));
                        $bowler_output["overs"] = $quotient;
                    }
                    else{
                        $root_bowler->appendChild($xmlbowler->createElement("overs",$overs));
                        $bowler_output["overs"] = $overs;
                    }
                    $runs = $decoded2->{"currentBowler"}->{"runs"};
                    @$root_bowler->appendChild($xmlbowler->createElement("economy",number_format($runs/$overs, 2)));
                    @$bowler_output["economy"] = number_format($runs/$overs, 2);
                }
                else if(array_intersect(explode(",",$key), array("maidens"))){
                    $root_bowler->appendChild($xmlbowler->createElement($key, $value));
                    $bowler_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("runs"))){
                    $root_bowler->appendChild($xmlbowler->createElement($key, $value));
                    $bowler_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("wickets"))){
                    $root_bowler->appendChild($xmlbowler->createElement($key, $value));
                    $bowler_output[$key] = $value;
                }
                else if(array_intersect(explode(",",$key), array("profilepic_file_path"))){
                    $root_bowler->appendChild($xmlbowler->createElement($key, $urlImage.$value));
                    $bowler_output[$key] = $urlImage.$value;
                }
            }
        }
        else{
            $root_bowler->appendChild($xmlbowler->createElement("bowlerName", ""));
            $bowler_output["bowlerName"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("overs", ""));
            $bowler_output["overs"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("economy", ""));
            $bowler_output["economy"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("maidens", ""));
            $bowler_output["maidens"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("runs", ""));
            $bowler_output["runs"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("wickets", ""));
            $bowler_output["wickets"] = "";

            $root_bowler->appendChild($xmlbowler->createElement("profilepic_file_path", ""));
            $bowler_output["profilepic_file_path"] = "";
        }


        //==================================================================================================================================
        //================================================== MATCH SUMMARY DETAILS ==========================================================
        //==================================================================================================================================

        $xmlmatchsummary = new DOMDocument();
        $root_matchsummary = $xmlmatchsummary->appendChild($xmlmatchsummary->createElement("DATA"));

        //================================================Team 1 Details=======================================================

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("seriesName"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement($key, $value));
                $matchsummary_output[$key] = $value;
            }
            else if(array_intersect(explode(",",$key), array("teamOneName"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Name", $value));
                $matchsummary_output["team1Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("t1balls"))){
                $quotient = intval($value/6);
                $remainder = fmod($value, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Overs", $quotient));
                    $matchsummary_output["team1Overs"] = $quotient;
                }
                else{
                    $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Overs", $overs));
                    $matchsummary_output["team1Overs"] = $overs;
                }
            }
            else if(array_intersect(explode(",",$key), array("t1total"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1TotalScore", $value));
                $matchsummary_output["team1TotalScore"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("t1wickets"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1TotalOut", $value));
                $matchsummary_output["team1TotalOut"] = $value;
            }
        }

        $matchsummary_bt1testing = array();


                    $k=1;
                    $name;
                    $playerScore;
                    $playerPlayedBalls;
                    $playerArray = array();
                    $playerScoreArray = array();
                    
                for($i=0;$i<11;$i++){
                    
                    $playerArray=$decoded->{"team1Batting"}[$i];
                    if($playerArray->{"ballsFaced"} > 0 && $playerArray->{"runsScored"} > 0)
                    {
                        $playerScoreArray[$i]=$playerArray->{"runsScored"};
                    }
                
                }
                rsort($playerScoreArray);
                $acount = count($playerScoreArray);
                if($acount>3){$acount=3;}
                for($j=0;$j<$acount;$j++)
                {
                    
                    for($i=0;$i<11;$i++){
                    
                        $playerArray=$decoded->{"team1Batting"}[$i];
                        if($playerArray->{"runsScored"}==$playerScoreArray[$j])
                        {
                        $val = $playerArray->{"firstName"};
                        $name = $val." ".$playerArray->{"lastName"};
                        $playerScore=$playerArray->{"runsScored"};
                        $playerPlayedBalls= $playerArray->{"ballsFaced"};
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Batsman".strval($k)."Name", $name));            
                        $matchsummary_output["team1Batsman".strval($k)."Name"] = $name;
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Batsman".strval($k)."Score", $playerScore == 0 ? "" : $playerScore));            
                        $matchsummary_output["team1Batsman".strval($k)."Score"] = $playerScore == 0 ? "" : $playerScore;
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Batsman".strval($k)."PlayedBalls", $playerPlayedBalls == 0 ? "" : $playerPlayedBalls));            
                        $matchsummary_output["team1Batsman".strval($k)."PlayedBalls"] = $playerPlayedBalls == 0 ? "" : $playerPlayedBalls;
                        $k++;
                        }
                    }
                }


        $matchsummary_bo1testing = array();


        $k=1;
        $name;
        $playerWickets;
        $playerGivenScore;
        $playerBowledOvers;
        $playerArray = array();
        $playerWicketsArray = array();
        $playerArrayCount;
        $playerArray=$decoded->{"team1Bowling"};
        $playerArrayCount=count($playerArray);
        $bowlingTeamArray = array();
        
    for($i=0;$i<$playerArrayCount;$i++){
        
        $playerArray=$decoded->{"team1Bowling"}[$i];
        $playerWicketsArray[$i]=$playerArray->{"wickets"};
        
    
    }
    rsort($playerWicketsArray);
    $bowlingTeamArray=(array)$decoded->{"team1Bowling"};
    $acount = count($playerWicketsArray);
    if($acount>3){$acount=3;};
    for($j=0;$j<$acount;$j++)
    {
        
        for($i=0;$i<count($playerWicketsArray);$i++){
            $playerArray=$bowlingTeamArray[$i];
            if($playerArray->{"wickets"}==$playerWicketsArray[$j])
            {
            $val = $playerArray->{"firstName"};
            $name = $val." ".$playerArray->{"lastName"};
            $playerGivenScore=$playerArray->{"runs"};
            $playerWickets = $playerArray->{"wickets"};
            $playerBowledBalls = $playerArray->{"balls"};
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Bowler".strval($k)."Name", $name));
            $matchsummary_output["team1Bowler".strval($k)."Name"] = $name;
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Bowler".strval($k)."Score", $playerGivenScore));
            $matchsummary_output["team1Bowler".strval($k)."Score"] = $playerGivenScore;
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Bowler".strval($k)."Out", $playerWickets));
            $matchsummary_output["team1Bowler".strval($k)."Out"] = $playerWickets;
            $quotient = intval($playerBowledBalls/6);
            $remainder = fmod($playerBowledBalls, 6);
            $playerBowledOvers = $quotient.".".$remainder;
            if($remainder == "0"){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Bowler".strval($k)."Over", $quotient));
                $matchsummary_output["team1Bowler".strval($k)."Over"] = $quotient;
            }
            else{
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team1Bowler".strval($k)."Over", $playerBowledOvers));
                $matchsummary_output["team1Bowler".strval($k)."Over"] = $playerBowledOvers;
            }
            $bowlingTeamArray[$i]->{"wickets"} =  "9";
            $k++;
            }
        
        }
    }


        //================================================Team 2 Details=======================================================

        foreach($decoded->{"matchInfo"} as $key => $value){
            if(array_intersect(explode(",",$key), array("teamTwoName"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Name", $value));
                $matchsummary_output["team2Name"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("t2balls"))){
                $quotient = intval($value/6);
                $remainder = fmod($value, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Overs", $quotient));
                    $matchsummary_output["team2Overs"] = $quotient;
                }
                else{
                    $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Overs", $overs));
                    $matchsummary_output["team2Overs"] = $overs;
                }   
            }
            else if(array_intersect(explode(",",$key), array("t2total"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2TotalScore", $value));
                $matchsummary_output["team2TotalScore"] = $value;
            }
            else if(array_intersect(explode(",",$key), array("t2wickets"))){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2TotalOut", $value));
                $matchsummary_output["team2TotalOut"] = $value;
            }
        }

        $matchsummary_bt2testing2 = array();
        $matchsummary_bt2testing22 = array();



                    $k=1;
                    $name;
                    $playerScore;
                    $playerPlayedBalls;
                    $playerArray = array();
                    $playerScoreArray = array();
                    
                for($i=0;$i<11;$i++){
                    
                    $playerArray=$decoded->{"team2Batting"}[$i];
                    if($playerArray->{"ballsFaced"} > 0 && $playerArray->{"runsScored"} > 0)
                    {
                        $playerScoreArray[$i]=$playerArray->{"runsScored"};
                    }
                
                }
                rsort($playerScoreArray);
                $acount = count($playerScoreArray);
                if($acount>3){$acount=3;}
                for($j=0;$j<$acount;$j++)
                {
                    
                    for($i=0;$i<11;$i++){
                    
                        $playerArray=$decoded->{"team2Batting"}[$i];
                        if($playerArray->{"runsScored"}==$playerScoreArray[$j])
                        {
                        $val = $playerArray->{"firstName"};
                        $name = $val." ".$playerArray->{"lastName"};
                        $playerScore=$playerArray->{"runsScored"};
                        $playerPlayedBalls= $playerArray->{"ballsFaced"};
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Batsman".strval($k)."Name", $name));            
                        $matchsummary_output["team2Batsman".strval($k)."Name"] = $name;
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Batsman".strval($k)."Score", $playerScore == 0 ? "" : $playerScore));            
                        $matchsummary_output["team2Batsman".strval($k)."Score"] = $playerScore == 0 ? "" : $playerScore;
                        $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Batsman".strval($k)."PlayedBalls", $playerPlayedBalls == 0 ? "" : $playerPlayedBalls));            
                        $matchsummary_output["team2Batsman".strval($k)."PlayedBalls"] = $playerPlayedBalls == 0 ? "" : $playerPlayedBalls;
                        $k++;
                        }
                    }
                }
                // print_r($matchsummary_output);


        $k=1;
        $name;
        $playerWickets;
        $playerGivenScore;
        $playerBowledOvers;
        $playerArray = array();
        $playerWicketsArray = array();
        $playerArrayCount;
        $playerArray=$decoded->{"team2Bowling"};
        $playerArrayCount=count($playerArray);
        $bowlingTeamArray = array();
        
    for($i=0;$i<$playerArrayCount;$i++){
        
        $playerArray=$decoded->{"team2Bowling"}[$i];
        $playerWicketsArray[$i]=$playerArray->{"wickets"};
        
    }
    rsort($playerWicketsArray);
    $bowlingTeamArray=(array)$decoded->{"team2Bowling"};
    $acount = count($playerWicketsArray);
    if($acount>3){$acount=3;};
    for($j=0;$j<$acount;$j++)
    {
        
        for($i=0;$i<count($playerWicketsArray);$i++){
            $playerArray=$bowlingTeamArray[$i];
            if($playerArray->{"wickets"}==$playerWicketsArray[$j])
            {
            $val = $playerArray->{"firstName"};
            $name = $val." ".$playerArray->{"lastName"};
            $playerGivenScore=$playerArray->{"runs"};
            $playerWickets = $playerArray->{"wickets"};
            $playerBowledBalls = $playerArray->{"balls"};
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Bowler".strval($k)."Name", $name));
            $matchsummary_output["team2Bowler".strval($k)."Name"] = $name;
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Bowler".strval($k)."Score", $playerGivenScore));
            $matchsummary_output["team2Bowler".strval($k)."Score"] = $playerGivenScore;
            $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Bowler".strval($k)."Out", $playerWickets));
            $matchsummary_output["team2Bowler".strval($k)."Out"] = $playerWickets;
            $quotient = intval($playerBowledBalls/6);
            $remainder = fmod($playerBowledBalls, 6);
            $playerBowledOvers = $quotient.".".$remainder;
            if($remainder == "0"){
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Bowler".strval($k)."Over", $quotient));
                $matchsummary_output["team2Bowler".strval($k)."Over"] = $quotient;
            }
            else{
                $root_matchsummary->appendChild($xmlmatchsummary->createElement("team2Bowler".strval($k)."Over", $playerBowledOvers));
                $matchsummary_output["team2Bowler".strval($k)."Over"] = $playerBowledOvers;
            }
            $bowlingTeamArray[$i]->{"wickets"} =  "9";
            $k++;
            }
        }
    }

                // print_r($matchsummary_output);


        //==================================================================================================================================
        //================================================== Partnership DETAILS ==========================================================
        //==================================================================================================================================


        //================================================Team1 Details=======================================================


        $xmlpartnership = new DOMDocument("1.0","UTF-8");
        $root_partnership = $xmlpartnership->appendChild($xmlpartnership->createElement("DATA"));

        if(isset($Partnershipdecoded->{"values"}->{"currentPartnershipMap"}->{"partnershipBatsman1Name"})){
            foreach($Partnershipdecoded->{"values"}->{"currentPartnershipMap"} as $key => $value){

                //=============================== Batsman1 Detail ===============================

                if(array_intersect(explode(",",$key), array("partnershipBatsman1ProfilePic"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ProfilePic", $urlImage.$value));
                    $partnership_output["partnershipBatsman1ProfilePic"] = $urlImage.$value;
                    $root_partnership->appendChild($xmlpartnership->createElement("batsman1Name", $Partnershipdecoded->{"values"}->{"batsman1Name"}));
                    $partnership_output["batsman1Name"] = $Partnershipdecoded->{"values"}->{"batsman1Name"};
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman1TotalRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1TotalRuns", $value));
                    $partnership_output["partnershipBatsman1TotalRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman1TotalRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1TotalRuns", $value));
                    $partnership_output["partnershipBatsman1TotalRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman1Balls"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1Balls", $value));
                    $partnership_output["partnershipBatsman1Balls"] = $value;
                }

                //=============================== Batsman2 Detail ===============================

                if(array_intersect(explode(",",$key), array("partnershipBatsman2ProfilePic"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ProfilePic", $urlImage.$value));
                    $partnership_output["partnershipBatsman2ProfilePic"] = $urlImage.$value;
                    $root_partnership->appendChild($xmlpartnership->createElement("batsman2Name", $Partnershipdecoded->{"values"}->{"batsman2Name"}));
                    $partnership_output["batsman2Name"] = $Partnershipdecoded->{"values"}->{"batsman2Name"};
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman2TotalRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2TotalRuns", $value));
                    $partnership_output["partnershipBatsman2TotalRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman2Balls"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2Balls", $value));
                    $partnership_output["partnershipBatsman2Balls"] = $value;
                }
                
                //=============================== Partnership-Overview Detail ===============================

                if(array_intersect(explode(",",$key), array("partnershipTotalRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipTotalRuns", $value));
                    $partnership_output["partnershipTotalRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipTotalBalls"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipTotalBalls", $value));
                    $partnership_output["partnershipTotalBalls"] = $value;
                }

                //=============================== Contribution Detail ===============================

                if(array_intersect(explode(",",$key), array("partnershipBatsman1ContributionRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ContributionRuns", $value));
                    $partnership_output["partnershipBatsman1ContributionRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman1ContributionBalls"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ContributionBalls", $value));
                    $partnership_output["partnershipBatsman1ContributionBalls"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman2ContributionRuns"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ContributionRuns", $value));
                    $partnership_output["partnershipBatsman2ContributionRuns"] = $value;
                }
                else if(array_intersect(explode(",",$key), array("partnershipBatsman2ContributionBalls"))){
                    $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ContributionBalls", $value));
                    $partnership_output["partnershipBatsman2ContributionBalls"] = $value;
                }
            }
        }
        else{
            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ProfilePic", ""));
            $partnership_output["partnershipBatsman1ProfilePic"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1TotalRuns", ""));
            $partnership_output["partnershipBatsman1TotalRuns"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1Balls", ""));
            $partnership_output["partnershipBatsman1Balls"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ProfilePic", ""));
            $partnership_output["partnershipBatsman2ProfilePic"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2TotalRuns", ""));
            $partnership_output["partnershipBatsman2TotalRuns"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2Balls", ""));
            $partnership_output["partnershipBatsman2Balls"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipTotalRuns", ""));
            $partnership_output["partnershipTotalRuns"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipTotalBalls", ""));
            $partnership_output["partnershipTotalBalls"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ContributionRuns", ""));
            $partnership_output["partnershipBatsman1ContributionRuns"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman1ContributionBalls", ""));
            $partnership_output["partnershipBatsman1ContributionBalls"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ContributionRuns", ""));
            $partnership_output["partnershipBatsman2ContributionRuns"] = "";

            $root_partnership->appendChild($xmlpartnership->createElement("partnershipBatsman2ContributionBalls", ""));
            $partnership_output["partnershipBatsman2ContributionBalls"] = "";
        }




        // //==================================================================================================================================
        // //================================================== POINTS TABLE DETAILs ==========================================================
        // //==================================================================================================================================


        $xmlPT = new DOMDocument();
        $root_PT = $xmlPT->appendChild($xmlPT->createElement("DATA"));
        // if(@$LTdecoded->{"values"}->{"isSecondInningsStarted"} == "true"){
            for($i=0;$i<count($PTdecoded);$i++){
                $root_PT->appendChild($xmlPT->createElement("group".strval($i+1)."Name", $PTdecoded[$i]->{"groupName"}));
                for($k=0;$k<count($PTdecoded[$i]->{"teams"});$k++){
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."Name", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"teamName"}));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."Matches", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"matches"}));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."Won", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"won"}));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."Lost", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"lost"}));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."NR", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"noResult"}));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."PTS", $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"points"}));
                    $totalMatches = $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"matches"};
                    $totalWin = $PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"won"};
                    if($totalWin == 0){$winper = "0.00";}else{
                        $winper = ( $totalWin / $totalMatches ) * 100;
                        $winper = number_format($winper, 2, '.', '');
                    }
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."Win", $winper."%"));
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."NRR", round($PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"netRunRate"},2)));
                    $runsScored=$PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"runsScored"};
                    $ballsFaced=$PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"ballsFaced"};
                    $remainder = fmod($ballsFaced,6);
                    $quotient = intdiv($ballsFaced,6);
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."FOR", $runsScored."/".$quotient.".".$remainder));
                    $runsGiven=$PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"runsGiven"};
                    $ballsBowled=$PTdecoded[$i]->{"teams"}[$k]->{"team"}->{"ballsBowled"};
                    $remainder = fmod($ballsBowled,6);
                    $quotient = intdiv($ballsBowled,6);
                    $root_PT->appendChild($xmlPT->createElement("team".strval($k+1)."AGAINST", $runsGiven."/".$quotient.".".$remainder));
                }
            }
            // if(isset($PTdecoded[0]->{"groupName"})){
            //     print_r("group found");
            // }
        // }

        // //==================================================================================================================================
        // //================================================== POINTS TABLE DETAILs FINISHED ==========================================================
        // //==================================================================================================================================





        //==================================================================================================================================
        //================================================== DISPLAY INDIVIDUAL DETAILS ==========================================================
        //==================================================================================================================================


        //================================================LT Details=======================================================

        // print_r(json_encode(array($LTtesting), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("LT.json", json_encode(array($LTtesting), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // print_r(json_encode(array($output), JSON_PRETTY_PRINT));
        // file_put_contents("LT.json", json_encode(array($output), JSON_PRETTY_PRINT));

        // //================================================BattingCard Details=======================================================

        print_r(json_encode(array($b1_output), JSON_PRETTY_PRINT));
        file_put_contents("BattingCard1.json", json_encode(array($b1_output), JSON_PRETTY_PRINT)); 

        print_r(json_encode(array($b2_output), JSON_PRETTY_PRINT));
        file_put_contents("BattingCard2.json", json_encode(array($b2_output), JSON_PRETTY_PRINT));


        // //================================================BowlingCard Details=======================================================

        // print_r(json_encode(array($bo1_output), JSON_PRETTY_PRINT));
        // file_put_contents("BowlingCard1.json", json_encode(array($bo1_output), JSON_PRETTY_PRINT)); 

        // print_r(json_encode(array($bo2_output), JSON_PRETTY_PRINT));
        // file_put_contents("BowlingCard2.json", json_encode(array($bo2_output), JSON_PRETTY_PRINT)); 


        // //================================================LastOutPlayer Details=======================================================

        // print_r(json_encode(array($lastout_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("lastOutBatsman.json", json_encode(array($lastout_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


        //================================================SquadTeam Details=======================================================

        // print_r(json_encode(array($squad1_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("SquadTeam1.json", json_encode(array($squad1_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); 

        // print_r(json_encode(array($squad2_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("SquadTeam2.json", json_encode(array($squad2_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


        //================================================ Batsman Stats Details =======================================================

        // print_r(json_encode(array($batsmanstriker_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("StrikerStats.json", json_encode(array($batsmanstriker_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // print_r(json_encode(array($batsmannonstriker_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("NonStrikerStats.json", json_encode(array($batsmannonstriker_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


        // //================================================ Bowler Stats Details =======================================================

        // print_r(json_encode(array($bowler_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("bowlerStats.json", json_encode(array($bowler_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));


        //================================================ Match Summary Details =======================================================

        // print_r(json_encode(array($matchsummary_output), JSON_PRETTY_PRINT));
        // file_put_contents("MatchSummary.json", json_encode(array($matchsummary_output), JSON_PRETTY_PRINT));


        // ================================================ Partnership Details =======================================================

        // print_r(json_encode(array($partnership_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        // file_put_contents("Partnership.json", json_encode(array($partnership_output), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        //================================================JSON TO XML Details=======================================================
    
        //make the output pretty
        $xmlLT->formatOutput = true;
        $xmlBatting1->formatOutput = true;
        $xmlBatting2->formatOutput = true;
        $xmlBowling1->formatOutput = true;
        $xmlBowling2->formatOutput = true;
        $xmlAfterOvers->formatOutput = true;
        $xmllastoutplayer->formatOutput = true;
        $xmlSquad1->formatOutput = true;
        $xmlSquad2->formatOutput = true;
        $xmlbatsmanstriker->formatOutput = true;
        $xmlbatsmannonstriker->formatOutput = true;
        $xmlbowler->formatOutput = true;
        $xmlmatchsummary->formatOutput = true;
        $xmlpartnership->formatOutput = true;
        $xmlPT->formatOutput = true;
        
        //save xml file
        $xmlLT->save('LT.xml');
        $xmlBatting1->save('BattingCard1.xml');
        $xmlBatting2->save('BattingCard2.xml');
        $xmlBowling1->save('BowlingCard1.xml');
        $xmlBowling2->save('BowlingCard2.xml');
        $xmlAfterOvers->save('AfterOvers.xml');
        $xmllastoutplayer->save('LastOutBatsman.xml');
        $xmlSquad1->save('SquadTeam1.xml');
        $xmlSquad2->save('SquadTeam2.xml');
        $xmlbatsmanstriker->save('StrikerStats.xml');
        $xmlbatsmannonstriker->save('NonStrikerStats.xml');
        $xmlbowler->save('bowlerStats.xml');
        $xmlmatchsummary->save('MatchSummary.xml');
        $xmlpartnership->save('Partnership.xml');
        $xmlPT->save('PointsTable.xml');
        

        
        // print_r($LTdecoded);
        print_r($decoded);
        // print_r($decoded2);
        // print_r($Partnershipdecoded);
        
    }
    // Closing
    //and at the end
    // echo round(microtime(true) * 1000) - $milliseconds;
    curl_close($ch);

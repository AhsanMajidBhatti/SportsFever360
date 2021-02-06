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

    //================================= Get Score-Card =======================================
    $Scorecardch = curl_init();
    $Scorecardurl = "https://ccapi.cricclubs.com/CCAPI/scoreCard/getScoreCard?matchId=$MATCHID&clubId=$CLUBID";
    
    curl_setopt($Scorecardch, CURLOPT_URL,$Scorecardurl);
    curl_setopt($Scorecardch, CURLOPT_RETURNTRANSFER, true);
    $Scorecardresult = curl_exec($Scorecardch);

    //================================= Get Ball-by-Ball =======================================
    $Ballbyballch = curl_init();
    $Ballbyballurl = "https://ccapi.cricclubs.com/CCAPI/scoreCard/getBallByBall?matchId=$MATCHID&clubId=$CLUBID";
    
    curl_setopt($Ballbyballch, CURLOPT_URL,$Ballbyballurl);
    curl_setopt($Ballbyballch, CURLOPT_RETURNTRANSFER, true);
    $Ballbyballresult = curl_exec($Ballbyballch);

    if(($e = curl_error($Scorecardch))){
        echo $e;
    }
    else{

        $Scorecarddecoded = json_decode($Scorecardresult);
        $Ballbyballdecoded = json_decode($Ballbyballresult);


    //======================================================== BATTING CARD 1 ===========================================================

        $Batting1yettoplay = array();
        $xmlBatting1 = new DOMDocument();
        $root_Batting1 = $xmlBatting1->appendChild($xmlBatting1->createElement("DATA"));
        $extras_total1 = 0;
        $index = 1;

        foreach($Scorecarddecoded->data->innings1->extras as $key => $value){
            $extras_total1 += $value; 
        }
        $root_Batting1->appendChild($xmlBatting1->createElement("teamOneName",$Scorecarddecoded->data->matchInfo->teamOneName));
        $root_Batting1->appendChild($xmlBatting1->createElement("totalScore",$Scorecarddecoded->data->innings1->total));
        $root_Batting1->appendChild($xmlBatting1->createElement("overs",$Scorecarddecoded->data->innings1->overs));
        $root_Batting1->appendChild($xmlBatting1->createElement("RR",$Scorecarddecoded->data->innings1->runRate));
        $root_Batting1->appendChild($xmlBatting1->createElement("totalOut",array_key_last($Scorecarddecoded->data->innings1->fallOfWickets)+1));
        $root_Batting1->appendChild($xmlBatting1->createElement("extras",$extras_total1));

        for($i=0;$i<11;$i++){
            if($Scorecarddecoded->data->innings1->batting[$i]->ballsFaced == 0 || $Scorecarddecoded->data->innings1->batting[$i]->runsScored == 0){
                array_push($Batting1yettoplay, $Scorecarddecoded->data->innings1->batting[$i]);
            }else{
                $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."Name",$Scorecarddecoded->data->innings1->batting[$i]->firstName."".$Scorecarddecoded->data->innings1->batting[$i]->lastName));
                $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."Score",$Scorecarddecoded->data->innings1->batting[$i]->runsScored));
                $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."PlayedBalls",$Scorecarddecoded->data->innings1->batting[$i]->ballsFaced));
                $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."howOut",$Scorecarddecoded->data->innings1->batting[$i]->outStringNoLink));
                $index++;
            }
        }
        for($i=0;$i<count($Batting1yettoplay);$i++){
            $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."Name",$Batting1yettoplay[$i]->firstName."".$Batting1yettoplay[$i]->lastName));
            $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."Score",""));
            $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."PlayedBalls",""));
            $root_Batting1->appendChild($xmlBatting1->createElement("player".($index)."howOut",""));
            $index++;
        }   


//======================================================== BATTING CARD 2 ===========================================================

        $Batting2yettoplay = array();
        $xmlBatting2 = new DOMDocument();
        $root_Batting2 = $xmlBatting2->appendChild($xmlBatting2->createElement("DATA"));
        $extras_total2 = 0;
        $index2 = 1;

        foreach($Scorecarddecoded->data->innings2->extras as $key => $value){
            $extras_total2 += $value; 
        }
        $root_Batting2->appendChild($xmlBatting2->createElement("teamTwoName",$Scorecarddecoded->data->matchInfo->teamTwoName));
        $root_Batting2->appendChild($xmlBatting2->createElement("totalScore",$Scorecarddecoded->data->innings2->total));
        $root_Batting2->appendChild($xmlBatting2->createElement("overs",$Scorecarddecoded->data->innings2->overs));
        $root_Batting2->appendChild($xmlBatting2->createElement("RR",$Scorecarddecoded->data->innings2->runRate));
        $root_Batting2->appendChild($xmlBatting2->createElement("totalOut",array_key_last($Scorecarddecoded->data->innings2->fallOfWickets)+1));
        $root_Batting2->appendChild($xmlBatting2->createElement("extras",$extras_total2));

        for($i=0;$i<11;$i++){
            if($Scorecarddecoded->data->innings2->batting[$i]->ballsFaced == 0 || $Scorecarddecoded->data->innings2->batting[$i]->runsScored == 0){
                array_push($Batting2yettoplay, $Scorecarddecoded->data->innings2->batting[$i]);
            }else{
                $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."Name",$Scorecarddecoded->data->innings2->batting[$i]->firstName."".$Scorecarddecoded->data->innings2->batting[$i]->lastName));
                $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."Score",$Scorecarddecoded->data->innings2->batting[$i]->runsScored));
                $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."PlayedBalls",$Scorecarddecoded->data->innings2->batting[$i]->ballsFaced));
                $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."howOut",$Scorecarddecoded->data->innings2->batting[$i]->outStringNoLink));
                $index2++;
            }
        }
        for($i=0;$i<count($Batting2yettoplay);$i++){
            $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."Name",$Batting2yettoplay[$i]->firstName."".$Batting2yettoplay[$i]->lastName));
            $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."Score",""));
            $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."PlayedBalls",""));
            $root_Batting2->appendChild($xmlBatting2->createElement("player".($index2)."howOut",""));
            $index2++;
        }


        //======================================================== BOWLING CARD 1 ===========================================================

        $xmlBowling1 = new DOMDocument();
        $root_Bowling1 = $xmlBowling1->appendChild($xmlBowling1->createElement("DATA"));
        $extras_total1 = 0;

        foreach($Scorecarddecoded->data->innings1->extras as $key => $value){
            $extras_total1 += $value; 
        }
        $root_Bowling1->appendChild($xmlBowling1->createElement("teamOneName",$Scorecarddecoded->data->matchInfo->teamOneName));
        $root_Bowling1->appendChild($xmlBowling1->createElement("totalScore",$Scorecarddecoded->data->innings1->total));
        $root_Bowling1->appendChild($xmlBowling1->createElement("overs",$Scorecarddecoded->data->innings1->overs));
        $root_Bowling1->appendChild($xmlBowling1->createElement("RR",$Scorecarddecoded->data->innings1->runRate));
        $root_Bowling1->appendChild($xmlBowling1->createElement("totalWicket",array_key_last($Scorecarddecoded->data->innings1->fallOfWickets)+1));
        $root_Bowling1->appendChild($xmlBowling1->createElement("extras",$extras_total1));

        for($i=0;$i<11;$i++){
            if(isset($Scorecarddecoded->data->innings1->bowling[$i])){
                $root_Bowling1->appendChild($xmlBowling1->createElement("player".($i+1)."Name",$Scorecarddecoded->data->innings1->bowling[$i]->firstName."".$Scorecarddecoded->data->innings1->bowling[$i]->lastName));
                
                $quotient = intval($Scorecarddecoded->data->innings1->bowling[$i]->balls/6);
                $remainder = fmod($Scorecarddecoded->data->innings1->bowling[$i]->balls, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), $quotient));
                }
                else{
                    $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), $overs));
                }

                $root_Bowling1->appendChild($xmlBowling1->createElement("runs".($i+1),$Scorecarddecoded->data->innings1->bowling[$i]->runs));
                $root_Bowling1->appendChild($xmlBowling1->createElement("wickets".($i+1),$Scorecarddecoded->data->innings1->bowling[$i]->wickets));
                $root_Bowling1->appendChild($xmlBowling1->createElement("maidens".($i+1),$Scorecarddecoded->data->innings1->bowling[$i]->maidens));
            }
            else{
                $root_Bowling1->appendChild($xmlBowling1->createElement("player".($i+1)."Name",""));
                $root_Bowling1->appendChild($xmlBowling1->createElement("overs".($i+1), ""));
                $root_Bowling1->appendChild($xmlBowling1->createElement("runs".($i+1),""));
                $root_Bowling1->appendChild($xmlBowling1->createElement("wickets".($i+1),""));
                $root_Bowling1->appendChild($xmlBowling1->createElement("maidens".($i+1),""));
            }
        }

        for($i=0;$i<=array_key_last($Scorecarddecoded->data->innings1->fallOfWickets);$i++){
            $root_Bowling1->appendChild($xmlBowling1->createElement("fow".($i+1)."label",$i+1));
            $root_Bowling1->appendChild($xmlBowling1->createElement("fow".($i+1)."value",strtok($Scorecarddecoded->data->innings1->fallOfWickets[$i]->total, '-')));
        }


        //======================================================== BOWLING CARD 2 ===========================================================

        $xmlBowling2 = new DOMDocument();
        $root_Bowling2 = $xmlBowling2->appendChild($xmlBowling2->createElement("DATA"));
        $extras_total2 = 0;

        foreach($Scorecarddecoded->data->innings2->extras as $key => $value){
            $extras_total2 += $value; 
        }
        $root_Bowling2->appendChild($xmlBowling2->createElement("teamOneName",$Scorecarddecoded->data->matchInfo->teamTwoName));
        $root_Bowling2->appendChild($xmlBowling2->createElement("totalScore",$Scorecarddecoded->data->innings2->total));
        $root_Bowling2->appendChild($xmlBowling2->createElement("overs",$Scorecarddecoded->data->innings2->overs));
        $root_Bowling2->appendChild($xmlBowling2->createElement("RR",$Scorecarddecoded->data->innings2->runRate));
        $root_Bowling2->appendChild($xmlBowling2->createElement("totalWicket",array_key_last($Scorecarddecoded->data->innings2->fallOfWickets)+1));
        $root_Bowling2->appendChild($xmlBowling2->createElement("extras",$extras_total2));

        for($i=0;$i<11;$i++){
            if(isset($Scorecarddecoded->data->innings2->bowling[$i])){
                $root_Bowling2->appendChild($xmlBowling2->createElement("player".($i+1)."Name",$Scorecarddecoded->data->innings2->bowling[$i]->firstName."".$Scorecarddecoded->data->innings2->bowling[$i]->lastName));
                
                $quotient = intval($Scorecarddecoded->data->innings2->bowling[$i]->balls/6);
                $remainder = fmod($Scorecarddecoded->data->innings2->bowling[$i]->balls, 6);
                $overs = $quotient.".".$remainder;
                if($remainder == "0"){
                    $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), $quotient));
                }
                else{
                    $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), $overs));
                }

                $root_Bowling2->appendChild($xmlBowling2->createElement("runs".($i+1),$Scorecarddecoded->data->innings2->bowling[$i]->runs));
                $root_Bowling2->appendChild($xmlBowling2->createElement("wickets".($i+1),$Scorecarddecoded->data->innings2->bowling[$i]->wickets));
                $root_Bowling2->appendChild($xmlBowling2->createElement("maidens".($i+1),$Scorecarddecoded->data->innings2->bowling[$i]->maidens));
            }
            else{
                $root_Bowling2->appendChild($xmlBowling2->createElement("player".($i+1)."Name",""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("overs".($i+1), ""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("runs".($i+1),""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("wickets".($i+1),""));
                $root_Bowling2->appendChild($xmlBowling2->createElement("maidens".($i+1),""));
            }
        }

        for($i=0;$i<=array_key_last($Scorecarddecoded->data->innings2->fallOfWickets);$i++){
            $root_Bowling2->appendChild($xmlBowling2->createElement("fow".($i+1)."label",$i+1));
            $root_Bowling2->appendChild($xmlBowling2->createElement("fow".($i+1)."value",strtok($Scorecarddecoded->data->innings2->fallOfWickets[$i]->total, '-')));
        }


        //======================================= Print Result ======================================
        $xmlBatting1->formatOutput = true;
        $xmlBatting1->save('BattingCard1-new.xml');    
    
        $xmlBatting2->formatOutput = true;
        $xmlBatting2->save('BattingCard2-new.xml');

        $xmlBowling1->formatOutput = true;
        $xmlBowling1->save('BowlingCard1-new.xml');

        $xmlBowling2->formatOutput = true;
        $xmlBowling2->save('BowlingCard2-new.xml');


        print_r($Scorecarddecoded);
        // print_r($Ballbyballdecoded);
    }
?>
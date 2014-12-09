<?php
#################################################################################
##                                                                             ##
##              -= YOU MUST NOT REMOVE OR CHANGE THIS NOTICE =-                ##
##                                                                             ##
## --------------------------------------------------------------------------- ##
##                                                                             ##
##  Project:       TravianZ                                                    ##
##  Version:       05.03.2014                                                  ##
##  Filename:      Admin/function.php  				                           ##
##  Developed by:  Dzoki                                                       ##
##  Edited by:     Shadow and ronix                                            ##
##  License:       Creative Commons BY-NC-SA 3.0                               ##
##  Copyright:     TravianZ (c) 2014 - All rights reserved                     ##
##  URLs:          http://travian.shadowss/ro                                  ##
##  Source code:   https://github.com/Shadowss/TravianZ	                       ##
##                                                                             ##
#################################################################################

class funct {

  function CheckLogin(){
	if($_SESSION['access'] >= MULTIHUNTER and $_SESSION['id']){
	  return true;
	}else{
	  return false;
	}
  }

   function Act($get){
    global $admin,$database;

    switch($get['action']){
      case "recountPop":
        $admin->recountPop($get['did']);
        $admin->recountCP($get['did']);
      break;
      case "recountPopUsr":
        $admin->recountPopUser($get['uid']);
      break;
      case "StopDel":
        //stop deleting
      break;
      case "delVil":
        if($get['mode'] != 1){
        $admin->DelVillage($get['did']);
        }else{
        $admin->DelVillage($get['did'], 1);
        }
      break;
      case "delBan":
        $admin->DelBan($get['uid'],$get['id']);
        //remove ban
      break;
      case "addBan":
        if($get['time']){$end = time()+$get['time']; }else{$end = '';}

          if(is_numeric($get['uid'])){
          $get['uid'] = $get['uid'];
          }else{
          $get['uid'] = $database->getUserField(addslashes($get['uid']),'id',1);
          }

        $admin->AddBan($get['uid'],$end,$get['reason']);
        //add ban
      break;
      case "delOas":
        //oaza
      break;
      case "logout":
        $this->LogOut();
      break;
      case "killHero":
        $varray = $database->getProfileVillages($get['uid']);
        $killhero=false;
        $error="";
        for ($i = 0; $i <= count($varray)-1; $i++) {
            $killhero=$database->FindHeroInVil($varray[$i]['wref']);
            if ($killhero) break;
            $killhero=$database->FindHeroInDef($varray[$i]['wref']);
            if ($killhero) break;
            $killhero=$database->FindHeroInMovement($varray[$i]['wref']);
            if ($killhero) break;
        }
        if (!$killhero){
            $killhero=$database->FindHeroInOasis($get['uid']);
        }
        if ($killhero) {
            $database->KillMyHero($get['uid']);
            $error="&kc=1";
        }else $error="&e=1";
        header("Location: admin.php?p=player&uid=".$get['uid'].$error);
        exit;
      case "reviveHero":
        $result=$database->query("SELECT * FROM ".TB_PREFIX."hero WHERE uid='".$get['uid']."'");
        $hdata=mysql_fetch_array($result);
        $database->query("UPDATE ".TB_PREFIX."units SET hero = 1 WHERE vref = ".$hdata['wref']);
        $database->query("UPDATE ".TB_PREFIX."hero SET `dead` = '0', `inrevive` = '0', `health` = '100', `lastupdate` = ".time()." WHERE `uid` = '".$get['uid']."'");
        header("Location: admin.php?p=player&uid=".$get['uid']."&rc=1");
        exit;
      case "addHero":
        $user = $database->getUserArray($get['uid'],1);
        $vilarray=$database->getVrefCapital($get['uid']);

        $database->query("INSERT INTO ".TB_PREFIX."hero (`uid`, `wref`, `regeneration`, `unit`, `name`, `level`, `points`,
        `experience`, `dead`, `health`, `attack`, `defence`, `attackbonus`, `defencebonus`, `trainingtime`, `autoregen`,
        `intraining`) VALUES ('".$get['uid']."', '" . $vilarray['wref'] . "', '0', '".$get['u']."', '".addslashes($user['username'])."',
        '0', '5', '0', '0', '100', '0', '0', '0', '0', '".time()."', '50', '0')");

        $database->query("UPDATE ".TB_PREFIX."units SET hero = 1 WHERE vref = ".$vilarray['wref']);

        header("Location: admin.php?p=player&uid=".$get['uid']."&ac=1");
        exit;
		}
    if($get['action'] == 'logout'){
      header("Location: admin.php");
    }else{
      header("Location: ".$_SERVER['HTTP_REFERER']);
    }
  }

  function Act2($post){
    global $admin,$database;
      switch($post['action']){
      case "DelPlayer":
        $admin->DelPlayer($post['uid'],$post['pass']);
        header("Location: ?p=search&msg=ursdel");
      break;
      case "punish":
        $admin->Punish($post);
        header("Location: ".$_SERVER['HTTP_REFERER']);
      break;
      case "addVillage":
        $admin->AddVillage($post);
        header("Location: ".$_SERVER['HTTP_REFERER']);
      break;
      }
  }

  function LogIN($username,$password){
	global $admin,$database;
	if($admin->Login($username,$password)){
	  //$_SESSION['username'] = $username;
	  $_SESSION['access'] = $database->getUserField($username,'access',1);
	  $_SESSION['id'] = $database->getUserField($username,'id',1);
	  header("Location: ".$_SERVER['HTTP_REFERER']);
	  //header("Location: admin.php");
	}else{
	  echo "Error";
	}
  }

  function LogOut(){
	$_SESSION['access'] = '';
	$_SESSION['id'] = '';
  }

	public function procResType($ref) {
		global $session;
		switch($ref) {
			case 1: $build = "伐木廠"; break;
			case 2: $build = "泥坑"; break;
			case 3: $build = "鐵礦場"; break;
			case 4: $build = "農田"; break;
			case 5: $build = "鋸木廠"; break;
			case 6: $build = "磚瓦廠"; break;
			case 7: $build = "鋼鐵鑄造廠"; break;
			case 8: $build = "磨坊"; break;
			case 9: $build = "麵包房"; break;
			case 10: $build = "倉庫"; break;
			case 11: $build = "糧倉"; break;
			case 12: $build = "鐵匠"; break;
			case 13: $build = "軍械庫"; break;
			case 14: $build = "競技場"; break;
			case 15: $build = "村莊大樓"; break;
			case 16: $build = "集結點"; break;
			case 17: $build = "市集"; break;
			case 18: $build = "大使館"; break;
			case 19: $build = "軍營"; break;
			case 20: $build = "馬廄"; break;
			case 21: $build = "工場"; break;
			case 22: $build = "研究院"; break;
			case 23: $build = "山洞"; break;
			case 24: $build = "城鎮廳"; break;
			case 25: $build = "行宮"; break;
			case 26: $build = "皇宮"; break;
			case 27: $build = "寶物庫"; break;
			case 28: $build = "市場"; break;
			case 29: $build = "大兵營"; break;
			case 30: $build = "大馬廄"; break;
			case 31: $build = "城牆"; break;
			case 32: $build = "土牆"; break;
			case 33: $build = "木牆"; break;
			case 34: $build = "石匠"; break;
			case 35: $build = "釀造廠"; break;
			case 36: $build = "陷阱"; break;
			case 37: $build = "英雄宅"; break;
			case 38: $build = "大倉庫"; break;
			case 39: $build = "大穀倉"; break;
			case 40: $build = "世界奇觀"; break;
			case 41: $build = "馬飲水槽"; break;
			default: $build = "錯誤"; break;
		}
		return $build;
	}

};

$funct = new funct;
if($funct->CheckLogin()){
  if($_GET['action']){
	$funct->Act($_GET);
  }
  if($_POST['action']){
	$funct->Act2($_POST);
  }
}
if($_POST['action']=='login'){
  $funct->LogIN($_POST['name'],$_POST['pw']);
}
?>

<?php
// Put your CodinGame profile ID (from https://www.codingame.com/profile) here
$publicId = "c989a5597d46ef37ec056d273a7975045623153";

$data = fetchProfile($publicId);

if (@$data) {
  $gamer = $data['codingamer'];
} else {
  exit(json_encode(["error" => "Unable to fetch data."]));
}

$stats = [
  ""               => $gamer['pseudo'],
  "Rank: "         => $gamer['rank'] . suffix($gamer['rank']) . " globally",
  "Level: "        => $gamer['level'],
  "CG Points: "    => $data['codingamerPoints'],
  "Achievements: " => $data['achievementCount'],
  "XP: "           => $gamer['xp'],
];

$tooltip = array_reduce(array_keys($stats), function ($carry, $key) use ($stats){ return $carry . $key. $stats[$key] . "\n"; }, "");
$rank    = rank($gamer['rank']);
$color   = color($gamer['rank']);
$suffix  = suffix($gamer['rank']);
$percent = percent($gamer['rank']);

if ($_GET['fetch']=='json'){
  $data['tooltip'] = $tooltip;
  $data['rank']    = $rank;
  $data['color']   = $color;
  $data['suffix']  = $suffix;
  $data['percent'] = $percent;
  header('Content-Type: application/json; charset=utf-8');
  exit(json_encode($data));
}

function suffix($n){
  $s = $n % 10;
  return $s==1 ? "st" : ($s==2 ? "nd" : ($s==3 ? 'rd' : 'th'));
}

function rank($n){
  $ranks = [100=>"Guru", 500=>"Grand Master", 2500=>"Master", 5000=>"Mentor", 10000=>"Disciple", 20000=>"Crafter"];
  foreach (array_keys($ranks) as $rank) if ($n<=$rank) return $ranks[$rank];
  return "Rookie";
}

function color($n){
  $ranks = [
    100   => "rgb(249, 98, 73)",   // amber
    2500  => "rgb(244, 174, 61)",  // gold
    5000  => "rgb(132, 154, 164)", // silver
    20000 => "rgb(182, 162, 139)"  // bronze
  ];
  foreach (array_keys($ranks) as $rank) if ($n<=$rank) return $ranks[$rank];
  return "rgb(124, 197, 118)";   // green
}

function percent($rank){
  $total = 4000000;   // current estimated accounts on Codingame
  $percent = min(100,max(0.1, $rank / $total * 100));
  return number_format($percent, $percent<1 ? 1 : 0);
}

function fetchProfile($publicId){
  $url = "https://www.codingame.com/services/CodinGamer/findCodingamePointsStatsByHandle";
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([$publicId]));
  $response = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($response, true);
  $data['codingamePointsRankingDto'] = [];
  $data['xpThresholds'] = [];
  return $data;
}

$html = <<<EOT
<div class="xpRanking">

  <div class="ranking">
    <div class="sectionTitle">
      <a href="https://www.codingame.com">Codin<span style="color:#f2bb13;">Game</span></a>&nbsp; Ranking
    </div>
    <section class="sectionContainer" title="$tooltip">
      <div class="profileContainer">
        <div class="avatarImage" style="background-image: url(https://static.codingame.com/servlet/fileservlet?id=$gamer[avatar]);"></div>
        <div class="profileName"><a href="https://www.codingame.com/profile/$publicId">$gamer[pseudo]</a></div>
      </div>
      <div class="rankContainer" style="color:$color;">
        <div class="rankIconContainer" style="border-color:$color;">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24">
            <g>
              <path class="cls-2" d="M-544,636.769H466V-422H-544Z" transform="translate(32 -70)"></path>
              <path class="cls-3" d="M19,7a1,1,0,0,1-1-1V2H6V6A1,1,0,0,1,4,6V1A1,1,0,0,1,5,0H19a1,1,0,0,1,1,1V6A1,1,0,0,1,19,7Zm-7,7A8.009,8.009,0,0,1,4,6,1,1,0,1,1,6,6,6,6,0,0,0,18,6a1,1,0,1,1,2,0,8.01,8.01,0,0,1-8,8Zm7-6a1,1,0,0,1,0-2c1.323,0,2.492-1.476,2.871-3H19a1,1,0,0,1,0-2h4a1,1,0,0,1,1,1C24,4.832,21.86,8,19,8ZM5,8C2.138,8,0,4.832,0,2A1,1,0,0,1,1,1H5A1,1,0,0,1,5,3H2.127C2.506,4.523,3.675,6,5,6A1,1,0,0,1,5,8Zm7,13a1,1,0,0,1-1-1V13.132a1,1,0,0,1,2,0V20A1,1,0,0,1,12,21Zm5,3H7a1,1,0,0,1,0-2H17a1,1,0,0,1,0,2Zm-2-3H9a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Z"></path>
              <path class="cls-3" d="M18,6V2H6V6A1,1,0,0,1,5,7c-.552,0-1-.7-1-1.248V1A1,1,0,0,1,5,0H19a1,1,0,0,1,1,1V6l-1.9.229Zm-6,8c-4.416,0-6-1.863-6-6.279C6,7.168,6,5.448,6,6A6,6,0,0,0,18,6l.1.229h0L20,6a8.01,8.01,0,0,1-8,8ZM21.871,3C20.491,3,20.282,1,23,1a1,1,0,0,1,1,1C24,4.832,21.492,4.524,21.871,3ZM5,8C2.138,8,0,4.832,0,2A1,1,0,0,1,1,1c1.917,0,2.378.405,2.66.905S4.11,3,2.127,3C2.506,4.523,3.675,6,5,6A1,1,0,0,1,5,8Zm7,7C13,20.552,12,14.448,12,15Zm5,9H7a1,1,0,0,1,0-2H17a1,1,0,0,1,0,2Zm-2-3H9a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Z"></path>
            </g>
          </svg>
        </div>
        <div class="rank">
          <span class="rankNumber">$gamer[rank]</span><sup>$suffix</sup></div>
          <span class="rankPercent">(top $percent%)</span>
        </div>
      <div class="rankTitle">$rank</div>
      <div class="progress">
        <div class="progressbar" style="width: 100%; background-color:$color;"></div>
      </div>
    </section>
  </div>

</div>
EOT;

if ($_GET['fetch']=='html'){
  $reply = ['html'=>$html];
  header('Content-Type: application/json; charset=utf-8');
  exit(json_encode($reply));
}

echo $html;
?>

<style>
  body {
    height: 100%;
    display: flex; flex-direction: column; overflow: hidden;
    color: #454c55; font-weight: 300; font-family: "Open Sans", Lato, sans-serif !important;
  }

  .xpRanking {
    width: 225px;
    display: flex; flex-direction: column;
    background-color: #e7e9eb;
  }
  .ranking {
    flex: 1;
    margin: 10px;
    height: 240px;
  }
  .sectionContainer {
    padding: 11px 15px 15px 15px;
    position: relative;
    background-color: white;
  }
  .sectionTitle {
    display: flex; align-items: center;
    justify-content: center;
    padding: 12px 0 12px 0;
    background-color: #252e38;
    border-bottom: 1px #aaa solid;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
  }

  .profileContainer {
    display: flex; align-items: center;
    height: 40px; width:100%; overflow: hidden;
  }
  .avatarImage {
    width: 40px; height: 40px;
    background-size: 40px 40px;
    margin-right: 15px;
  }
  .profileName {
    width:120px; overflow: hidden;
  }

  .rankContainer {
    display: flex;
    padding: 16px 0 16px 0;
    align-items: center;
    flex-direction: column;
  }
  .rankIconContainer {
    display: flex; align-items: center; justify-content: center;
    width: 38px; height: 38px;
    border: 4px solid #f2bb13; border-radius: 50%;
    background-color: #20252a;
  }
  .rankTitle {
    color: #454c55;
    font-size: 14px;
    text-align: center;
    font-weight: 600;
    margin-bottom: 5px;
  }

  .rank { margin-top: 12px; }
  .rankNumber { font-size: 20px; font-weight: 700; }
  .rankPercent { font-size: 14px; font-weight: 700; }

  .progress {
    height: 10px;
    border-radius: 5px;
    background-color: #dadada;
  }
  .progressbar {
    height: 100%;
    transition: width .2s ease-in-out, background-color .2s ease-in-out;
    border-radius: 5px;
  }

  .cls-2 { fill: none; }
  .cls-3 { fill: currentColor; fill-rule: evenodd; }

  a { text-decoration: none; }
  a:hover { text-decoration: underline; }
  a:visited { color: inherit; }
</style>

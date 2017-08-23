<?php
include('config.php');

if (!isset($_GET['id'])) {
    http_response_code(400);
    die();
}

$id = $_GET['id'];

global $db;

global $map;

$row = $db->query("select t3.external_id, t3.lat, t3.lon, t1.last_modified, t1.team, t1.slots_available, t1.guard_pokemon_id from (select fort_id, MAX(last_modified) AS MaxLastModified from fort_sightings group by fort_id) t2 left join fort_sightings t1 on t2.fort_id = t1.fort_id and t2.MaxLastModified = t1.last_modified left join forts t3 on t1.fort_id = t3.id where t3.external_id = '" . $id . "'")->fetch();
$raid = $db->query("select t3.external_id, t1.fort_id, raid_level as level, pokemon_id, cp, move_1, move_2, raid_start, raid_end from (select fort_id, MAX(raid_end) AS MaxTimeEnd from raid_info group by fort_id) t1 left join raid_info t2 on t1.fort_id = t2.fort_id and MaxTimeEnd = raid_end join forts t3 on t2.fort_id = t3.id where t3.external_id = '" . $id . "'")->fetch();

$json_poke = "../static/data/pokemon.json";
$json_contents = file_get_contents($json_poke);
$data = json_decode($json_contents, TRUE);

$p = array();
$r = array();

$lat = floatval($row["lat"]);
$lon = floatval($row["lon"]);
$gpid = intval($row["guard_pokemon_id"]);
$sa = intval($row["slots_available"]);
$lm = $row["last_modified"] * 1000;
$ls = isset($row["last_scanned"]) ? $row["last_scanned"] * 1000 : null;
$ti = isset($row["team"]) ? intval($row["team"]) : null;

$p["enabled"] = isset($row["enabled"]) ? boolval($row["enabled"]) : true;
$p["guard_pokemon_id"] = $gpid;
$p["gym_id"] = $row["external_id"];
$p["slots_available"] = $sa;
$p["last_modified"] = $lm;
$p["last_scanned"] = $ls;
$p["latitude"] = $lat;
$p["longitude"] = $lon;
$p["name"] = isset($row["name"]) ? $row["name"] : null;
$p["team_id"] = $ti;
$p['pokemon'] = [];

if ($gpid){
    $p["guard_pokemon_name"] = i8ln($data[$gpid]['name']);
}

$rpid = intval($raid['pokemon_id']);
$r['gym_id'] = $raid["external_id"];
$r['level'] = intval($raid['level']);
$r['pokemon_id'] = $rpid;
$r['pokemon_name'] = i8ln($data[$rpid]['name']);
$r['cp'] = isset($raid['cp']) ? intval($raid['cp']) : null;
$r['move_1'] = isset($raid['move_1']) ? intval($raid['move_1']) : null;
$r['move_2'] = isset($raid['move_2']) ? intval($raid['move_2']) : null;
$r['start'] = $raid["raid_start"] * 1000;
$r['end'] = $raid["raid_end"] * 1000;
$r["pokemon_rarity"] = i8ln($data[$rpid]['rarity']);
      $types = $data[$rpid]["types"];
      foreach($types as $k => $v)
      {
        $types[$k]['type'] = i8ln($v['type']);
      }
$r["pokemon_types"] = $types;


$p['raid'] = $r;



unset($row);
unset($raid);
echo json_encode($p);




// $j = 0;

// $ids = join("','", $gym_ids);
// $raid = $db->query("select t3.external_id, t1.fort_id, raid_level as level, pokemon_id, cp, move_1, move_2, raid_start, raid_end from (select fort_id, MAX(raid_end) AS MaxTimeEnd from raid_info group by fort_id) t1 left join raid_info t2 on t1.fort_id = t2.fort_id and MaxTimeEnd = raid_end join forts t3 on t2.fort_id = t3.id where t3.external_id in ('" . $id . "')")->fetch();
// $rpid = intval($raid['pokemon_id']);
// $p['raid']['raid_level'] = intval($raid['level']);

// if ($rpid){
//     $p['raid']['raid_pokemon_name'] = i8ln($data[$rpid]['name']);
//     $p['raid']['raid_pokemon_id'] = $rpid;
// }
// $p['raid']['raid_pokemon_cp'] = isset($raid['cp']) ? intval($raid['cp']) : null;
// $p['raid']['raid_pokemon_move_1'] = isset($raid['move_1']) ? intval($raid['move_1']) : null;
// $p['raid']['raid_pokemon_move_2'] = isset($raid['move_2']) ? intval($raid['move_2']) : null;
// $p['raid']['raid_start'] = $raid["raid_start"] * 1000;
// $p['raid']['raid_end'] = $raid["raid_end"] * 1000;


// unset($raid);

// echo json_encode($p);
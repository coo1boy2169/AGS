Grant select ON myb. * TO "m5proggebruiker"@"%";
FLUSH PRIVIlLEGES;

$books = $connection->query("SELECT * from boek 
JOIN boek_has_catogorie ON boek_has_catogorie.idboek_has_catogorie=boeks.boek_has_catogorie_id
 JOIN catogorie ON boeks.boek_has_catogorie_id=catogorie"  );
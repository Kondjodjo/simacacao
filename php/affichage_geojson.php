<?php
// Chemin vers votre fichier GeoJSON
session_start();
require_once 'connect.php';

$code = $_GET['code_parcelle'];
$sql1 = "SELECT * FROM parcelles_cacaoyeres WHERE code_parcelle = :code";
$stmt1 = $connexion->prepare($sql1);
$stmt1->bindParam(':code', $code);
$stmt1->execute();
$resultats1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
$count1 = count($resultats1);
$row1= $resultats1[0];

$geojsonContentEscaped = json_encode($row1['url_geojson_parcelle']);
$acces="../GEOJSON/DIBEKATF0001P0001.geojson";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte GeoJSON</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        #map { height: 400px; width: 100%; }
    </style>
</head>
<body>
    <div id="map"></div>
    <button onclick="downloadGeoJSON()">Télécharger GeoJSON</button>
    <button onclick="captureMap()">Capturer la carte</button>

    <script>
    var map = L.map('map').setView([0, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var geojsonData = JSON.parse(<?php echo $acces; ?>);
    
    L.geoJSON(geojsonData, {
        pointToLayer: function (feature, latlng) {
            return L.circleMarker(latlng, {
                radius: 8,
                fillColor: "#ff7800",
                color: "#000",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            });
        }
    }).addTo(map);

    function downloadGeoJSON() {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(geojsonData)));
        element.setAttribute('download', 'data.geojson');
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }

    function captureMap() {
        html2canvas(document.getElementById('map')).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'map_capture.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }
</script>
</body>
</html>
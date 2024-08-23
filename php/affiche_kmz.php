<?php 
session_start();
require_once 'connect.php';

// Vérifier si le code_parcelle est présent dans l'URL
if (!isset($_GET['code_parcelle'])) {
    die("Le code de la parcelle est manquant.");
}

$code = $_GET['code_parcelle'];
$sql1 = "SELECT * FROM parcelles_cacaoyeres WHERE code_parcelle = :code";
$stmt1 = $connexion->prepare($sql1);
$stmt1->bindParam(':code', $code);
$stmt1->execute();
$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

// Vérifier si la parcelle existe
if (!$row1) {
    die("Aucune parcelle trouvée avec ce code.");
}

$kmzFile = $row1['url_kmz_parcelle'];

// Vérifier si le fichier KMZ existe
if (!file_exists($kmzFile)) {
    die("Le fichier KMZ n'existe pas: " . $kmzFile);
}

// Assurez-vous que $geojsonContentEscaped est défini
$geojsonContentEscaped = json_encode($row1['url_kmz_parcelle'] ?? null);

// Afficher des informations de débogage
echo "<!-- Debug: KMZ file: " . htmlspecialchars($kmzFile) . " -->\n";
echo "<!-- Debug: GeoJSON: " . htmlspecialchars($geojsonContentEscaped) . " -->\n";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte GeoJSON et KMZ</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-kmz@1.0.6/dist/leaflet-kmz.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        #map { height: 400px; width: 100%; }
    </style>
</head>
<body>
    <div id="map"></div>
    <button onclick="downloadGeoJSON()">Télécharger GeoJSON</button>
    <button onclick="downloadKMZ()">Télécharger KMZ</button>
    <button onclick="captureMap()">Capturer la carte</button>

    <script>
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        console.log('Map initialized');

        var kmzFile = '<?php echo addslashes($kmzFile); ?>';
        console.log('KMZ file:', kmzFile);

        var kmzParser = new L.KMZParser({
            onKMZLoaded: function(layer, name) {
                console.log('KMZ loaded:', name);
                kmzLayer.eachLayer(function(layer) {
                    if (layer.setStyle) {
                        layer.setStyle({
                            color: 'red',
                            fillColor: 'red',
                            fillOpacity: 0.3,
                            weight: 2
                        });
                    }
                });
                map.addLayer(layer);
                map.fitBounds(layer.getBounds());
            }
        });
        kmzParser.load(kmzFile);
        fetch(kmzFile)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.blob();
    })
    .then(blob => {
        console.log('KMZ file loaded successfully. Size:', blob.size, 'bytes');
    })
    .catch(e => {
        console.error('There was a problem with fetching the KMZ file:', e);
    });

        var geojsonData = <?php echo $geojsonContentEscaped; ?>;
        console.log('GeoJSON data:', geojsonData);

        if (geojsonData) {
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
            console.log('GeoJSON added to map');
        } else {
            console.log('No GeoJSON data available');
        }

        function downloadGeoJSON() {
            if (!geojsonData) {
                alert('Pas de données GeoJSON disponibles');
                return;
            }
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(geojsonData)));
            element.setAttribute('download', 'data.geojson');
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }

        function downloadKMZ() {
            window.location.href = kmzFile;
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
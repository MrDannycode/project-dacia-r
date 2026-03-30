<?php
include __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map {
        height: 600px;
        width: 100%;
        margin-bottom: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .map-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    h1.harta-title {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
        font-weight: 600;
    }
</style>

<div class="map-container">
    <h1 class="harta-title">Rețeaua de Dealeri Dacia în România</h1>
    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Inițializare hartă și centrare pe România
    // Coordonatele centrului României și zoom-ul
    var map = L.map('map').setView([45.9432, 24.9668], 7);

    // Adăugare layer de hartă de la OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Adăugare markere pentru principali dealeri (exemple)
    var dealeri = [
        { nume: "Dealer Dacia București", lat: 44.4268, lng: 26.1025, adresa: "B-dul Iuliu Maniu, Nr. 15" },
        { nume: "Dealer Dacia Cluj", lat: 46.7712, lng: 23.5901, adresa: "Calea Turzii, Nr. 120" },
        { nume: "Dealer Dacia Timișoara", lat: 45.7489, lng: 21.2087, adresa: "Calea Șagului, Nr. 100" },
        { nume: "Dealer Dacia Iași", lat: 47.1585, lng: 27.6014, adresa: "Șoseaua Păcurari, Nr. 45" },
        { nume: "Dealer Dacia Constanța", lat: 44.1792, lng: 28.6498, adresa: "B-dul Tomis, Nr. 200" },
        { nume: "Dealer Dacia Brașov", lat: 45.6427, lng: 25.5887, adresa: "Calea București, Nr. 50" },
        { nume: "Dealer Dacia Craiova", lat: 44.3302, lng: 23.7949, adresa: "Strada Caracal, Nr. 12" },
        { nume: "Dealer Dacia Galați", lat: 45.4353, lng: 28.0080, adresa: "Strada Brăilei, Nr. 150" }
    ];

    // Iterare prin array-ul de dealeri și adăugarea lor pe hartă
    dealeri.forEach(function(dealer) {
        L.marker([dealer.lat, dealer.lng]).addTo(map)
            .bindPopup('<b>' + dealer.nume + '</b><br>' + dealer.adresa);
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
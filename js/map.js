+9-3
document.addEventListener("DOMContentLoaded", function () {
  const map = L.map('map').setView([39.7684, -86.1581], 6);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  L.circle([39.7684, -86.1581], {
    radius: 321869,
    color: 'blue',
    fillOpacity: 0.1
  }).addTo(map);
});
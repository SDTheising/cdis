document.addEventListener("DOMContentLoaded", function () {
  const map = L.map('map').setView([39.7684, -86.1581], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
});
document.addEventListener('DOMContentLoaded', function () {
  const serviceItems = document.querySelectorAll('.service-list li');
  serviceItems.forEach(item => {
    item.addEventListener('click', function () {
      this.classList.toggle('show');
    });
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const accountButton = document.getElementById("avt-btn");
  const accountDropdown = document.getElementById("dd");
  const hotelWrap = document.getElementById("nav-hotel-wrap");
  const hotelButton = document.getElementById("nav-hotel-btn");
  const hotelDropdown = document.getElementById("nav-hotel-dropdown");

  if (accountButton && accountDropdown) {
    accountButton.addEventListener("click", function (event) {
      event.stopPropagation();
      const isOpen = accountDropdown.classList.toggle("open");
      accountButton.setAttribute("aria-expanded", String(isOpen));
    });

    document.addEventListener("click", function (event) {
      if (!accountDropdown.contains(event.target) && !accountButton.contains(event.target)) {
        accountDropdown.classList.remove("open");
        accountButton.setAttribute("aria-expanded", "false");
      }
    });
  }

  if (hotelWrap && hotelButton && hotelDropdown) {
    let closeTimer = null;

    const openHotelDropdown = function () {
      clearTimeout(closeTimer);
      hotelDropdown.classList.add("open");
      hotelButton.setAttribute("aria-expanded", "true");
    };

    const scheduleCloseHotelDropdown = function () {
      closeTimer = setTimeout(function () {
        hotelDropdown.classList.remove("open");
        hotelButton.setAttribute("aria-expanded", "false");
      }, 120);
    };

    hotelWrap.addEventListener("mouseenter", openHotelDropdown);
    hotelWrap.addEventListener("mouseleave", scheduleCloseHotelDropdown);
    hotelWrap.addEventListener("focusin", openHotelDropdown);
    hotelWrap.addEventListener("focusout", function (event) {
      if (!hotelWrap.contains(event.relatedTarget)) {
        scheduleCloseHotelDropdown();
      }
    });
  }
});

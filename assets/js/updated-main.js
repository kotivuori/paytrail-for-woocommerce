require(__dirname + "/../scss/updated_payment_fields.scss");
// require(__dirname + '/../scss/introStyles.scss');

window.initPaytrail = () => {
  const paytrailToggleView = () => {
    var collapsedContainer = document.querySelector(".paytrail-bank-dropdown");
    var toggleBtn = document.querySelector(
      ".paytrail-bank-dropdown__toggle-btn"
    );
    var expandedContainer = document.getElementById("paytrail-bank-expanded");
    var collapsedText = document.querySelector(
      ".paytrail-bank-dropdown__text"
    );
    if (!collapsedContainer || !toggleBtn || !expandedContainer) {
      return;
    }

    // Toggle between collapsed and expanded views.
    if (expandedContainer.classList.contains("hidden")) {
      // Show expanded view and hide collapsed view.
      expandedContainer.classList.remove("hidden");
      // Enable tab navigation for all focusable elements inside expanded container.
      var focusableElements = expandedContainer.querySelectorAll("input");
      focusableElements.forEach(function (el) {
        el.removeAttribute("tabindex");
      });

      var collapsedChildren = collapsedContainer.children[1];
      if (
        collapsedChildren &&
        collapsedChildren.classList.contains(
          "paytrail-bank-dropdown__providers"
        )
      ) {
        collapsedChildren.classList.add("hidden");
      }
      collapsedContainer.setAttribute("aria-expanded", "true");
      collapsedContainer.classList.add("expanded");
      toggleBtn.classList.add("rotated");
      collapsedText.classList.remove("hidden");
    } else {
      // Hide expanded view and show collapsed view.
      expandedContainer.classList.add("hidden");

      // Disable tab navigation for focusable elements inside the expanded container.
      var focusableElements = expandedContainer.querySelectorAll("input");
      focusableElements.forEach(function (el) {
        el.setAttribute("tabindex", "-1");
      });

      collapsedContainer.classList.remove("hidden"); // Match the default display.
      var collapsedChildren = collapsedContainer.children[1];
      if (
        collapsedChildren &&
        collapsedChildren.classList.contains(
          "paytrail-bank-dropdown__providers"
        )
      ) {
        collapsedChildren.classList.remove("hidden");
      }
      collapsedContainer.setAttribute("aria-expanded", "false");
      collapsedContainer.classList.remove("expanded");
      toggleBtn.classList.remove("rotated");
      collapsedText.classList.add("hidden");
    }
  };

  var collapsedContainer = document.querySelector(".paytrail-bank-dropdown");
  if (collapsedContainer) {
    collapsedContainer.addEventListener("click", function (e) {
      e.stopPropagation();
      paytrailToggleView();
    });

    // Check if space or enter key is pressed
    collapsedContainer.addEventListener("keydown", function (e) {
      if (e.keyCode === 32 || e.keyCode === 13) {
        e.preventDefault();
        e.stopPropagation();
        paytrailToggleView();
      }
    });
  }
};

document.addEventListener("DOMContentLoaded", function () {
  initPaytrail();
});

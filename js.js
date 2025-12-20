
function toggleParams() {
      const menu = document.getElementById("submenu");
      menu.classList.toggle("hidden");
    }

    document.addEventListener("click", function(event) {
      const submenu = document.getElementById("submenu");
      const button = document.querySelector("button[onclick='toggleParams()']");
      if (!button.contains(event.target) && !submenu.contains(event.target)) {
        submenu.classList.add("hidden");
      }
    });
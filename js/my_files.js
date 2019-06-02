function handleResponse(response) {
	populateNavigation(response);
}

var cells = document.querySelectorAll("#main-table tbody .file-menu")
for (var i = 0; i < cells.length; i++) {
    cells[i].onclick = function(event) {
        console.log(event.target);
        // event.target.querySelector(".dropdown-menu").classList.toggle("dropdown-menu");
    }
}


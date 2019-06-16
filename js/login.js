document.getElementById("login-form").addEventListener("submit", function(event) {
    event.preventDefault();
    resetError();
    var fields = document.querySelectorAll(".field-input");
    var jsonObject = {};
    fields.forEach(function(element) {
        jsonObject[element.name] = element.value;
    });
    var settings = {};
    settings["method"] = "POST";
    settings["data"] = JSON.stringify(jsonObject);
    ajax("api.php/login", settings, handleResponse);
});

function handleResponse(response) {
    if (response.error_description) {
        var errorElement = document.querySelector(".error");
        errorElement.appendChild(document.createTextNode(response.error_description));
    } else if (response.login) {
        window.location.replace("index.php");
    } else {
        populateNavigation(response);
    }
}

function resetError() {
    document.querySelector(".error").innerHTML = "";
}
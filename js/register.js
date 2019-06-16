document.getElementById("register-form").addEventListener("submit", function(event) {
    event.preventDefault();
    resetContent();
    if (validate()) {
        var fields = document.querySelectorAll(".field-input");
        var jsonObject = {};
        fields.forEach(function(element) {
            jsonObject[element.name] = element.value;
        });

        var settings = {};
        settings["method"] = "POST";
        settings["data"] = JSON.stringify(jsonObject);
        ajax("api.php/register", settings, handleResponse);
    }
});

function resetContent() {
    var errors = document.getElementsByClassName("error");
    Array.prototype.forEach.call(errors, function(el) {
        el.innerHTML = '';
    });
}

function validate() {
    var isValid = true;
    var usernameRegex = new RegExp("^[A-Za-z0-9_]{4,15}$");
    var passwordRegex = new RegExp("^(?=.*[a-z])(?=.*[0-9])(?=.{6,})");
    var passwordToMatch;
    var fields = document.querySelectorAll(".input-container");
    fields.forEach(function(element) {
        var field = element.querySelector("input");
        var errorElement = element.querySelector("div");

        if (field.name == "username" && (!field.value || !usernameRegex.test(field.value))) {
            errorElement.appendChild(document.createTextNode("Невалидно потребителско име"));
            isValid = false;
        }

        if (field.name == "password") {
            passwordToMatch = field.value;
            if (!field.value || !passwordRegex.test(field.value)) {
                errorElement.appendChild(document.createTextNode("Невалидна парола"));
                isValid = false;
            }
        }

        if (field.name == "password-repeat" && field.value !== passwordToMatch) {
            errorElement.appendChild(document.createTextNode("Паролите не съвпадат"));
            isValid = false;
        }
    });

    return isValid;
}

function handleResponse(response) {
    console.log(response.error_description);
    if (response.error_description) {
        // var errors = JSON.parse(response.error_description);
        // console.log(errors);
        if (response.error_description.email) {
            var errorElement = document.querySelector("#email").parentElement.querySelector(".error");
            errorElement.appendChild(document.createTextNode(response.error_description.email));
        } else if (response.error_description.username) {
            var errorElement = document.querySelector("#username").parentElement.querySelector(".error");
            errorElement.appendChild(document.createTextNode(response.error_description.username));
        }
    } else if (response.register) {
        var errorElement = document.querySelector("#register").parentElement.querySelector(".message");
        errorElement.appendChild(document.createTextNode("Вашата регистрация беше успешна."));
        resetInput();
    } else {
        populateNavigation(response);
    }
}
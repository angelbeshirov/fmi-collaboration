var Server;
var fileID;

function append(text) {
    log = document.getElementById("editor");
    //Add text to log
    // $log.append(text);
    log.value = log.value + text;
    //Autoscroll
    // $log.scrollTop = $log.scrollHeight - $log.clientHeight;
}

function send(text) {
    Server.send("message", text);
    console.log("Sending " + text);
}

window.onload = function() {
    setUp();
};

function setUp() {
    // append("Connecting...");
    Server = new FancyWebSocket("ws://127.0.0.1:9300");

    //Let the user know we"re connected
    Server.bind("open", function() {
        //append("Connected.");
    });

    //OH NOES! Disconnection occurred.
    Server.bind("close", function(data) {
        alert("Can't connect to the server.");
    });

    //Log any messages sent from server
    Server.bind("message", function(payload) {
        handleMessage(payload);
        //document.getElementById("editor").value = payload;
    });

    Server.connect();

    var el = document.getElementById("editor");
    el.addEventListener("keypress", function(event) {
        handleSelectedArea(el);
        var x = event.charCode || event.keyCode; // Get the Unicode value
        action = {};
        action["type"] = "INSERT";
        action["position"] = event.target.selectionStart;
        action["file_id"] = fileID;
        action["data"] = String.fromCharCode(x);

        send(JSON.stringify(action));
    });

    el.addEventListener("paste", function(event) {
        var clipboardData, pastedData;
        handleSelectedArea(el);
        clipboardData = event.clipboardData || window.clipboardData;
        pastedData = clipboardData.getData('Text');

        action = {};
        action["type"] = "INSERT";
        action["position"] = event.target.selectionStart;
        action["file_id"] = fileID;
        action["data"] = pastedData;

        send(JSON.stringify(action));
    });

    el.addEventListener("keydown", function(event) {
        if (event.keyCode === 8) {
            var cursorPosition = event.target.selectionStart;
            if (!handleSelectedArea(el) && cursorPosition != 0) {
                action = {};
                action["type"] = "DELETE";
                action["from"] = cursorPosition - 1;
                action["to"] = cursorPosition;
                action["file_id"] = fileID;

                send(JSON.stringify(action));
            }
        }
    });

    // var filename = (new URL(window.location.href)).searchParams.get("file");
    // var url = "docs.php/retrieve_file_id?filename=" + filename;
    // var username = (new URL(window.location.href)).searchParams.get("shared_by");

    // if (username) {
    //     url += ("&username=" + username);
    // }
    // var settings = {};
    // settings["method"] = "GET";
    // ajax(url, settings, handleInitializeResponse);
};

function handleSelectedArea(textarea) {
    var start = textarea.selectionStart;
    var finish = textarea.selectionEnd;

    if (start != finish) {
        action = {};
        action["type"] = "DELETE";
        action["from"] = start;
        action["to"] = finish;
        action["file_id"] = fileID;

        send(JSON.stringify(action));
        return true;
    }

    return false;
}

function handleMessage(payload) {
    // handler = new Handler(Server);
    // handler.handleMessage(payload);

    var message = JSON.parse(payload);
    console.log("Received message from server: " + payload);
    switch (message.type) {
        case "INITIALIZE":
            var filename = (new URL(window.location.href)).searchParams.get("file");
            var url = "docs.php/retrieve_file_id?filename=" + filename;
            var sharedBy = (new URL(window.location.href)).searchParams.get("shared_by");

            if (sharedBy) {
                url += "&shared_by=" + sharedBy;
            }
            var settings = {};
            settings["method"] = "GET";
            ajax(url, settings, handleInitializeResponse);
        case "UPDATE USERS":
            var titleValue = document.querySelector("#users-online-value");
            titleValue.innerHTML = message.value;
            break;
        case "INSERT":
            var editor = document.querySelector("#editor");
            editor.value = editor.value.splice(message.position, 0, message.data);
            break;
        case "DELETE":
            var editor = document.querySelector("#editor");
            editor.value = editor.value.splice(message.from, message.to - message.from, "");
            break;
        default:
            console.log("Unknown type " + message.type);
    };
}

function handleInitializeResponse(response) {
    if (response.file_id) {
        fileID = response.file_id;
        action = {};
        action["type"] = "INITIALIZE";
        action["file_id"] = fileID;
        send(JSON.stringify(action));
    }
}

String.prototype.splice = function(idx, rem, str) {
    return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
};
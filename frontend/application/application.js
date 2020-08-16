window.addEventListener("load", async function () {
    // Await module imports
    await Module.import("UI");

    if (localStorage.getItem("token") !== null)
        UI.write("token", localStorage.getItem("token"));
});

function issue() {
    // Send an issue request to the database
    Database.execute("issue", {
        password: UI.read("password")
    }).then(value => {
        // Write token to UI
        UI.write("token", value);

        // Change page
        UI.view("access");
    });
}

function execute() {
    // Assemble the request
    let action = UI.read("action");
    let parameters = {};

    // Check parameters and append to object
    if (UI.read("token").length > 0) {
        parameters.token = UI.read("token");

        // Save token to localStorage
        localStorage.setItem("token", UI.read("token"));

        if (UI.read("scope").length > 0) {
            parameters.scope = UI.read("scope");

            if (UI.read("table").length > 0) {
                parameters.table = UI.read("table");

                if (UI.read("entry").length > 0) {
                    parameters.entry = UI.read("entry");

                    if (UI.read("key").length > 0) {
                        parameters.key = UI.read("key");

                        if (UI.read("value").length > 0) {
                            parameters.value = UI.read("value");
                        }
                    }
                }
            }
        }
    }

    let callback = (result) => {
        // Write output
        UI.write("output", result);

        // Show results
        UI.view("results");
    };

    // Call the database
    Database.execute(action, parameters).then(callback).catch(callback);
}

function download() {
    // Create element
    let element = document.createElement("a");

    // Set attributes
    element.setAttribute("href", URL.createObjectURL(new Blob([UI.read("output")], {type: "text/plain"})));
    element.setAttribute("download", Date.now() + ".txt");

    // Click on element
    element.click();
}

class Database {

    /**
     * Executes a database request.
     * @param action Action
     * @param parameters Parameters
     * @returns Promise
     */
    static execute(action, parameters) {
        // Create a promise
        return new Promise((resolve, reject) => {
            // Create a form
            let form = new FormData();

            // Append parameters to form
            for (let key in parameters) {
                if (parameters.hasOwnProperty(key))
                    form.append(key, parameters[key]);
            }

            // Send the request
            fetch("/?" + action.toLowerCase(), {
                method: "post",
                body: form
            }).then(response => {
                // Parse response as JSON
                response.json().then(result => {
                    // Check the result's integrity
                    if (result.hasOwnProperty("status") && result.hasOwnProperty("result")) {
                        // Callbacks
                        if (result["status"]) {
                            resolve(result["result"]);
                        } else {
                            reject(result["result"]);
                        }
                    } else {
                        reject("API response malformed");
                    }
                });
            });
        });
    }
}
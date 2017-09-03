// Simple JS object class for the captcha

'use strict';

class captchaClass {

    // Runs on creation of object.
    // 1] Pass the DOM id to attach the Captcha to.
    // 2] Pass the id of the form to attach to its onSubmit event. Needed to validate the captcha on submission.
    constructor(idOfCaptcha, idOfForm, idOfLog, source = "local") {

        // Setup first captcha code on run
        this.idOfCaptcha = idOfCaptcha;
        this.idOfCaptcha2 = document.getElementById(this.idOfCaptcha.id + "2"); // Set the text input id. It is hard-coded to whatever the captcha id is + "2".
        this.idOfCaptcha3 = document.getElementById(this.idOfCaptcha.id + "3"); // The Reset button

        // Create the captcha and set it to the above ID's
        this.setCaptcha(source);

        // Bind the onsubmit event on the passed form to the validate method
        this.formId = idOfForm;
        if (document.addEventListener) { // avoid errors in incapable browsers. IE8 and under.
            this.formId.addEventListener("submit", this.validateForm.bind(this));
        } else {
            console.log("'addEventListener' method not found. Incapable browser ?")
        }

        // Bind the log for spitting out messages
        this.idOfLog = idOfLog;

        // Bind the Reset button click event
        if (document.addEventListener) { // avoid errors in incapable browsers. IE8 and under.

            this.idOfCaptcha3.addEventListener("click", function() {
                let dummy = this.captchaCode(); // Getter call
            }.bind(this)); // Watch the "this" context. "this" is not the class unless we bind it here. Normally it is the idOfCaptcha3 context since it is invoked from it.
        }
    }

    // This is what fires when requesting the code. It is read only so no one messes with the captcha in other JS code.
    get captchaCode() {

        // Clear the log
        this.appendLog();

        // ReCall the method to create a new code.
        // Return it, if called by another function.
        return this.setCaptcha();
    }

    validateForm(evt) {

        this.appendLog();

        let string1 = this.removeSpaces(this.idOfCaptcha.value);
        let string2 = this.removeSpaces(this.idOfCaptcha2.value);
        //alert(string1 + " " + string2);

        if (string1 != string2 || string2 == "") {

            // The following line will reset the code again. Comment out if you want them to reenter the same code after a mistake.
            this.setCaptcha();

            this.appendLog('Entered Invalid Captcha');

            evt.preventDefault();

        } // else {
        //
        //			alert("CORRECT");
        //		}
    }

    setCaptcha(source = "local") { // php or js or local

        // Assign some temp variable of the Captcha Id.
        let idOfCaptcha = this.idOfCaptcha;
        let code = "";

        // Increase the length of characters here.
        let alpha = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

        // If the source is local, create the captcha in browser.
        // Else get it from a .PHP or NodeJS file
        if (source == "local") {
            let i;
            for (i = 0; i < 6; i++) {
                var a = alpha[Math.floor(Math.random() * alpha.length)];
                var b = alpha[Math.floor(Math.random() * alpha.length)];
                var c = alpha[Math.floor(Math.random() * alpha.length)];
                var d = alpha[Math.floor(Math.random() * alpha.length)];
                var e = alpha[Math.floor(Math.random() * alpha.length)];
                var f = alpha[Math.floor(Math.random() * alpha.length)];
                var g = alpha[Math.floor(Math.random() * alpha.length)];
            }
            let code = a + ' ' + b + ' ' + ' ' + c + ' ' + d + ' ' + e + ' ' + f + ' ' + g;

            // Attach to DOM
            idOfCaptcha.value = code;
        }

        if (source == "php") {
        	console.log(source);
            // Setup AJAX request
            var request = new XMLHttpRequest();

            // Fill in variable
            request.onreadystatechange = function() {
                if (request.readyState === 4) {
                	console.log("4");
                    var tmpcss = idOfCaptcha.style.border;
                    idOfCaptcha.style.border = '1px dashed blue';

                    if (request.status === 200) {
                    	console.log("200");
                        idOfCaptcha.style.border = tmpcss;

						// Attach to DOM
						code = request.responseText;
                        idOfCaptcha.value = code;

                    } else {
                        console.log('An error occurred during your request: ' + request.status + ' ' + request.statusText);
                    }
                }
            }

            request.open('GET', 'php/captcha.php');
            request.send();
        }

        if (source == "js") {

        }

        // Set Colors of code
        let colors = ["#B40404", "#beb1dd", "#b200ff", "#faff00", "#0000FF", "#FE2E9A", "#FF0080", "#2EFE2E"];
        let rand = Math.floor(Math.random() * colors.length);
        idOfCaptcha.style.color = colors[rand];

        // Return the code
        return code;
    }

    // Internal use in the validateForm method.
    removeSpaces(string) {
        return string.split(' ').join('');
    }

    appendLog(logString = "") {
        if (logString != "") {
            // Append to the log
            this.idOfLog.innerHTML += logString;
            // Reveal the div
            this.idOfLog.style.display = "block";
        } else {
            // Empty the text
            this.idOfLog.innerHTML = "";
            // Hide the div
            this.idOfLog.style.display = "none";
        }

        return;
    }

}
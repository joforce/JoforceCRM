$(document).ready(function () {
    $(".supportmailsubmit").click(function () {
        let name = document.getElementById("name").value;
        let email = document.getElementById("email").value;
        let description = document.getElementById("describe").value;

        //Add validation name and descrption
        if ((name == "") || (description == "") || (email == "")) {
            app.helper.showAlertNotification({
                'message': app.vtranslate('Fields are Empty')
            });
        }
        //Add validation email

        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

        if (email.match(mailformat)) {
            // console.log(email.match(mailformat));
            var val = true;
        } else {
            app.helper.showAlertNotification({
                'message': app.vtranslate('Invalid Email')
            });
        }
        //Add Ajax fuction and pass values to sendmailfunction

        if (name != "" && description != "" && val == true) {
            console.log("hello");
            var params = {
                module: 'Users',
                view: 'Supportmail',
                name: document.getElementById("name").value,
                email: document.getElementById("email").value,
                description: document.getElementById("describe").value,

            };
            alert('Test');
            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                alert('RESULT');
                app.helper.hideProgress();
                //window.location.reload();
            });
            console.log('TESS');
            // app.helper.showSuccessNotification({'message': app.vtranslate('Success Mail')}); 
            //return true;
        } else {
            //show success or failuere message
            app.helper.showAlertNotification({
                'message': app.vtranslate('Not Success Mail')
            });
        }

    });
});
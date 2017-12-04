function submitEmail() {
    var email = document.getElementsByName("inputtedEmail")[0];
	var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

	if(!emailPattern.test(email.value)){
        alert("Email format is not correct, please retry!");
        email.focus;
        return false;
	}
	else {
	document.getElementById("email").innerHTML = "<h3>Thank you for entering your email. Welcome back!</h3> <h1></h1>";
	}
}


function submitReturnEmail() {
    var email = document.getElementsByName("inputtedReturnEmail")[0];
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if(!emailPattern.test(email.value)){
        alert("Email format is not correct, please retry!");
        email.focus;
        return false;
    }
    else {
        document.getElementById("returnEmail").innerHTML = "<h3>Please also fill in and submit the next field</h3> <h1></h1>";
    }
}


function submitDateOfReturn(){
    var today=new Date().setHours(0,0,0,0);
    var dateOfReturn=new Date(document.getElementById("returnDate").value).setHours(0,0,0,0);

    if (dateOfReturn==today)
        alert("The date is valid");
    else
        alert("The date is not valid");
}


function submitDvdTitle() {
	document.getElementById("dvdTitle").innerHTML = "<h3>Please also fill in and submit the next field</h3> <h1></h1>";
	}	

/*
	Author: Wei Lai
	Date: 9/10/2018
*/
var xhr = false;
if (window.XMLHttpRequest) {
	xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject) {
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}


// access user inputs from customer page and pass them
// to custRegister.php
function testGet() {
	
	var name = document.getElementById('name').value;
	var email = document.getElementById('email').value;
	var password = document.getElementById('password').value;

	// MODIFIED: extract phone number from HTML input element
	var phone = document.getElementById('phone').value;


	xhr.open("GET", "testRegister.php?name=" + name + 
		"&email=" + encodeURIComponent(email) + 
		"&password=" +password + 
		"&phone=" + phone + // submit new GET parameter
		"&id=" + Number(new Date), true);

	xhr.onreadystatechange = testInput;
	xhr.send(null);
	
}

function testInput() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById('msg').innerHTML = xhr.responseText;
	}
	
}

function getXML() {

xhr.open ("GET", "getData.php", true);

xhr.onreadystatechange = testXML;

xhr.send(null);
}

function testXML() {
	
	if ((xhr.readyState == 4) && (xhr.status == 200)) {
		//var xmlDoc = xhr.responseXML;
		var xmlDoc = xhr.responseText;
		alert(xmlDoc);
	}
	
}

function deleteXML() {

xhr.open ("GET", "deleteFile.php", true);

xhr.onreadystatechange = function () {
if ((xhr.readyState == 4) && (xhr.status == 200)) {
		document.getElementById('msg').innerHTML = xhr.responseText;
	}
}
xhr.send(null);
}
function clearForm() {
document.getElementById("name").value ="";
document.getElementById('email').value="";
document.getElementById('password').value="";
document.getElementById("phone").value = ""; // Modified
document.getElementById('msg').innerHTML ="";


}

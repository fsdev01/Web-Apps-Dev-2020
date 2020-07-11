var xHRObject = false;

if (window.XMLHttpRequest)
{
    // creates XMLHttpRequest Object
    xHRObject = new XMLHttpRequest();
}
else if (window.ActiveXObject) // Internet Explorer
{
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}

function sendRequest(data)
{
    
    // https://www.w3schools.com/xml/ajax_xmlhttprequest_send.asp
    // ANSWER FOR TASK A -  3rd argument: false - async request and true - sync request
    // Number(new Date) - to avoid IE cache problem
    xHRObject.open("GET", "display.php?id=" + Number(new Date) +"&value=" + data, false);
    // Add HTTP Header - Avoid IE Cache Problem
    xHRObject.setRequestHeader('If-Modified-Since', 'Sat, 1 Jan 2000 00:00:00 GMT' );

    // Assign the callback function to the onreadystatechange event
    xHRObject.onreadystatechange = getData;

    // Send the HTTP Request with empty request body
    xHRObject.send(null); 
}

function getData()
{
    // called many times; processes the response
    // Data is Loaded and status is OK
    if (xHRObject.readyState == 4 && xHRObject.status == 200)
    {
        // store HTTP response (data from server) as text
        var serverText = xHRObject.responseText;

        // test that text returned includes a seperated character
        // If the test fails, it means that bad data has returned.
        // Our code, then does nothing. It really should show an error alert!
        if(serverText.indexOf('|') != -1) 
	    {
            // Split the text on the separator character - an array
            // e.g. split "box1|<br><b>Contacts</b><br>William Doe 1, Acadia Aevnue "
            element = serverText.split('|');
            document.getElementById(element[0]).innerHTML = element[1];
        }
    }
}


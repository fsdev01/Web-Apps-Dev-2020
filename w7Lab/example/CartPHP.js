// Create XMLHttpRequest Object
xHRObject = new XMLHttpRequest();

// Callback function
function getData() {
  if (xHRObject.readyState == 4 && xHRObject.status == 200) {
    alert(xHRObject.responseXML);
    alert(xHRObject.responseText);

    // Extract Server's resonse as XML DOM object
    var serverResponse = xHRObject.responseXML;
    // Extract "book title" tag from XML DOM
    var header = serverResponse.getElementsByTagName("book");

    // Get reference of <div> FROM HTML page
    var spantag = document.getElementById("cart");
    spantag.innerHTML = "";

    //if(header!=null){ // EMPTY XML DOM
    // LOOP Through items in the cart (# of book items)
    for (i = 0; i < header.length; i++) {
      // Get the Book Title  from XML DOM header
      spantag.innerHTML += " " + header[0].firstChild.textContent;
      // Get the Qty from the XML DOM header
      spantag.innerHTML +=
        " " +
        header[0].lastChild.textContent +
        " " +
        "<a href='#' onclick='AddRemoveItem(\"Remove\");'>Remove Item</a>";

      //}
    }
  }
}

function AddRemoveItem(action) {
  // Gets the book title of the HTML Element
  var book = document.getElementById("book").innerHTML;

  // User's Action: (1) "Add" an item or (2) "Remove" an item from cart
  // Case 1: Add item to cart
  if (action == "Add") {
    xHRObject.open(
      "GET",
      "ManageCart.php?action=" +
        action +
        "&book=" +
        encodeURIComponent(book) +
        "&value=" +
        Number(new Date()),
      true
    );
    // Cart 2: Remove item from cart
  } else {
    xHRObject.open(
      "GET",
      "ManageCart.php?action=" +
        action +
        "&book=" +
        encodeURIComponent(book) +
        "&value=" +
        Number(new Date()),
      true
    );
  }

  // Assign callback function to state changes
  xHRObject.onreadystatechange = getData;
  // Send GET HTTP Request with NULL body
  xHRObject.send(null);
}

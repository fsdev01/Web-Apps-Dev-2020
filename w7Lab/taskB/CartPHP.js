// Create XMLHttpRequest Object
xHRObject = new XMLHttpRequest();

// Callback function
function getData() {
  if (xHRObject.readyState == 4 && xHRObject.status == 200) {
    //alert(xHRObject.responseXML);
    //alert(xHRObject.responseText);

    // Extract Server's resonse as XML DOM object
    var serverResponse = xHRObject.responseXML;
    // Extract "book title" tag from XML DOM
    var header = serverResponse.getElementsByTagName("book");

    // Get reference of <div> FROM HTML page
    var spantag = document.getElementById("cart");
    spantag.innerHTML = "";

    // EMPTY XML DOM
    if (header != null) {
      spantag.innerHTML =
        "<table border='1'><tbody id='tableBody'><tr><td>Title</td><td>ISBN</td><td>Qty</td><td>Total</td><td>Remove</td></tbody></table>";

      // LOOP Through items in the cart (# of book items)
      let tableRef = document.getElementById("tableBody");

      for (i = 0; i < header.length; i++) {
        // Get the Book Title  from XML DOM
        let bookTitle = header[i].childNodes[0].textContent;
        // Get Qty of the book from XML DOM
        let bookQty = header[i].childNodes[1].textContent;
        // Get the ISBN of the book from XML DOM
        let bookIsbn = header[i].childNodes[2].textContent;
        // Get the total of the book from XML DOM
        let bookTotal = header[i].childNodes[3].textContent;

        // Create <td> element
        let row = document.createElement("tr");
        let rowString = "<td>" + bookTitle + "</td>";
        rowString += "<td>" + bookIsbn + "</td>";
        rowString += "<td>" +  bookQty+ "</td>";
        rowString += "<td>" + bookTotal + "</td>";
        rowString +=
          "<td><a href='#' onclick='AddRemoveItem(\"Remove\");'>Remove Item</a></td>";
        row.innerHTML = rowString;

        // Append <td> to <tbody> of the table.
        tableRef.appendChild(row);
      }
    }
  }
}

function AddRemoveItem(action) {
  // Parameter 1: Gets the book title of the HTML Element
  var book = document.getElementById("book").innerHTML;
  // Parameter 2: Get the ISBN of the book
  var isbn = document.getElementById("ISBN").innerHTML;
  // Parameter 3: Get the price of the book
  var price = document.getElementById("price").innerHTML;

  //alert(book + " " + isbn + " " + price);

  // User's Action: (1) "Add" an item or (2) "Remove" an item from cart
  // Case 1: Add item to cart
  if (action == "Add") {
    xHRObject.open(
      "GET",
      "ManageCart.php?action=" +
        action +
        "&book=" +
        encodeURIComponent(book) +
        "&isbn=" +
        encodeURIComponent(isbn) +
        "&price=" +
        encodeURIComponent(price) +
        "&value=" +
        Number(new Date()),
      true // sycn true
    );
    // Cart 2: Remove item from cart
  } else {
    xHRObject.open(
      "GET",
      "ManageCart.php?action=" +
        action +
        "&book=" +
        encodeURIComponent(book) +
        "&isbn=" +
        encodeURIComponent(isbn) +
        "&price=" +
        encodeURIComponent(price) +
        "&value=" +
        Number(new Date()),
      true // sync true
    );
  }

  // Assign callback function to state changes
  xHRObject.onreadystatechange = getData;
  // Send GET HTTP Request with NULL body
  xHRObject.send(null);
}

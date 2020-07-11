// file simpleajax.js
var xhr = createRequest();
function getData(dataSource, divID, aName, aPwd, gender, age, email) {
  if (xhr) {
    var place = document.getElementById(divID);
    place.innerHTML = gender;
    var url =
      dataSource +
      "?namefield=" +
      aName +
      "&pwdfield=" +
      aPwd +
      "&gender=" +
      gender +
      "&age=" +
      age +
      "&email=" +
      email;
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function() {
      //alert(xhr.readyState);
      if (xhr.readyState == 4 && xhr.status == 200) {
        place.innerHTML = xhr.responseText;
      } // end if
    }; // end anonymous call-back function
    xhr.send(null);
  } // end if
} // end function getData()

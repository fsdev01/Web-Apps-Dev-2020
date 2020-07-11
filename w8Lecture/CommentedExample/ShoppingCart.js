var xHRObject = false;


// Create XML Object
if (window.ActiveXObject)
{
  xHRObject = new ActiveXObject("Microsoft.XMLHTTP");
}
else if (window.XMLHttpRequest)
{
  xHRObject = new XMLHttpRequest();
}

// Creates arguments for POST REQUEST BODY
function getBody(action)
{
     var argument = "book=";
     argument += encodeURI(document.getElementById("book").innerHTML); 
     argument += "&ISBN=";
     argument += encodeURI(document.getElementById("ISBN").innerHTML); 
     argument += "&authors=";
     argument += encodeURI(document.getElementById("authors").innerHTML); 
     argument += "&price=";
     argument += encodeURI(document.getElementById("price").innerHTML); 
     argument += "&action=";
     argument += encodeURI(action);
     return argument;
}


function getData()
{
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
          if (window.ActiveXObject)
          {
            //Load XML
            var xml = xHRObject.responseXML;
       
            //Load XSL
            var xsl = new ActiveXObject("Microsoft.XMLDOM");
            xsl.async = false;
            xsl.load("Cart.xsl");
            
            //Transform
            var transform = xml.transformNode(xsl);
            var spanb = document.getElementById("cart");
            spanb.innerHTML = transform; 
          }
          else
          {
           
           // Create XSLT processor object
            var xsltProcessor = new XSLTProcessor();

            // Retrieve XML Response from the server
            var xml = xHRObject.responseXML;
            alert(xHRObject.responseText);


            // Load the XSL file "Cart.xsl" using XHR(XHLHttpRequest) object
            // Note: SYNC request - false argument 
            // FUTURE FIX: readyState == 4 and xHRObject.status = 2000;
            var xhrXSL = new XMLHttpRequest();
            xhrXSL.open("GET","Cart.xsl",false);
            xhrXSL.send(null);
            xsl =  xhrXSL.responseXML; 

            // Import Stylesheet into Processor
            xsltProcessor.importStylesheet(xsl);

            // Apply XSL Instructions to XML document 
            var fragment = xsltProcessor.transformToFragment(xml,document);

            // Retrieve transformed XML into HTML element
            // (1) Convert transformed XML object into String
            // (2) Store string into innerHTML 
            document.getElementById("cart").innerHTML = new XMLSerializer().serializeToString(fragment);



              /**
              var xsltProcessor = new XSLTProcessor();
              //Load XSL
              xslStylesheet = document.implementation.createDocument("", "doc", null);
              xslStylesheet.async = false;
              xslStylesheet.load("Cart.xsl"); 
              xsltProcessor.importStylesheet(xslStylesheet);

              //Load XML
              xmlDoc = xHRObject.responseXML;
              
              //Transform
              var fragment = xsltProcessor.transformToFragment(xmlDoc, document);
              document.getElementById("cart").innerHTML = new XMLSerializer().serializeToString(fragment);
              **/
            }
         }
}

// MAIN FUNCTION - EVENT HANDLER FOR "ADD SHOPPING CART BUTTON"
function AddRemoveItem(action)
{
           // GET BOOK TITLE
           var book  = document.getElementById("book").innerHTML;
           // CREATE BODY FOR POST REUEST
           var bodyofform = getBody( action); 
           // Setup parameters for HTTP POST 
           xHRObject.open("POST", "cartdisplay.php", true);
           // SET HEADER DETAILS
           xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
           // ASSIGN CALLBACK FUNCTION
           xHRObject.onreadystatechange = getData;
           // SEND XMLHTTPREQUEST
           xHRObject.send(bodyofform); 
}




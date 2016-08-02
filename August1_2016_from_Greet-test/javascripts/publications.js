/*
Set the value of an element
*/
function setVal(id, val) {
	//alert("Setting value of " + id + " to: " + val);
	document.getElementById(id).value = val;
}

/*
Display the loader and display the articles related to the search term
*/
function findpubs(srchterm, optval){
	var loader = document.getElementById("loading_image");
	loader.style.display = "block";
	
	srchterm = srchterm.replace(/'/g, "").replace(/"/g, '');
	var javascriptDiv = "pubsfound";
	var btnid = "btn_srch" + optval;
	var url = "../frames/action.php?research=";
	url = url+ srchterm;
	url = url + "&range=" + optval;

	document.getElementById(javascriptDiv).innerHTML = "";
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	if (xmlhttp.readyState == 4 || xmlhttp.readyState == 0)
	{
		
	  xmlhttp.onreadystatechange=function()
	  {
			if(xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				document.getElementById(javascriptDiv).innerHTML = xmlhttp.responseText;
				document.getElementById(btnid).focus();
				loader.style.display = "none";
				sorttabs();
			}
			else 
			{// a HTTP status different than 200 signals an error
				//alert("There was a problem accessing the server: " + xmlhttp.statusText);
			}
		}

		 xmlhttp.open("GET",url,true);
		 xmlhttp.send(null);
	}
	else{//if the connection is busy, try again after 5 seconds
		setTimeout('findpubs()', 1000);
	}
}

function sorttabs(){
	var $table = $("table"),
	size = 10;

	$table
	.tablesorter({
		theme : 'blue',

		headers: { 
            // assign the 4th column (we start counting zero) 
            3: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            }
        } 
    })
	;
}

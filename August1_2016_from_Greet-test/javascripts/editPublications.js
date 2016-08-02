function selectAll(drpId) 
{ //alert("Set all items in " + drpId + " to be selected");
    var drp = document.getElementById(drpId);
	var val = "";

    for (var i = 0; i < drp.length - 1; i++) 
    { 
        drp.options[i].selected = true; 
    }
}

function resetRelKeys()
{
	var val = "";
	var drp = document.getElementById("relatedKeys");
	if( drp.length > 0)
	{
	  for (var i = 0; i < drp.length - 1; i++) 
      { 
		val = val + drp.options[i].text + "/RELK/";
      }
	  val += drp.options[drp.length - 1].text;
	}
	alert(val);
	document.getElementById("relKeys").value = val;
}
function refreshDropdown(drpsrcId, drpdestId, flg)
{
	//alert ("In refreshDropdown: " + drpsrcId + "----" + drpdestId); 
	var drpsrc = document.getElementById(drpsrcId);
	var drpdest = document.getElementById(drpdestId);
	var txt;
	var hasOpt = 0;

	if( flg == 1 )
	{ //alert("adding option(s)");
		var option;
	
		for( var i = 0; i < drpsrc.length; i++)
		{
			if (drpsrc.options[i].selected)
			{
				txt = drpsrc.options[i].value;
				
				if( drpdest.length > 0 )
				{//check if this item is already in the destination list
					for( var j = 0; j < drpdest.length; j++)
					{
						if (drpdest.options[j].text == txt)
						{
							hasOpt = 1;
							break;
						}
					}
				}
				if( hasOpt == 0 )
				{//alert("New related key");
					option = document.createElement("option");
					option.text = txt;
					drpdest.add(option);
				}
			}
			hasOpt = 0;
		}
	
	}		
	else if( flg == -1 ) 
	{ //alert("removing option(s)");
		for( var k = drpdest.length - 1; k >= 0; k--)
		{
			if (drpdest.options[k].selected)
			{
				drpdest.remove(k);
			}
		}
	}
}

function setBtnColor(btnid, cl) {
    var btn = document.getElementById(btnid);
    if (btn.style.backgroundColor != cl)
        btn.style.backgroundColor = cl;
}

function loadForm(frmNM) {
	var javascriptDiv = "formNM";
	var url = "../EditPublications/" + frmNM + ".php";

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
		setTimeout('loadForm()', 5000);
	}
}

function getArticlesByAuthor(author_nm) {
	var javascriptDiv = "title_from_author";
	var url = "../EditPublications/articles_per_author.php?author=";
	url = url + author_nm;
	
	document.getElementById(javascriptDiv).innerHTML = "";
	document.getElementById("fields_filled").innerHTML = "";
	
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
		setTimeout('getArticlesByAuthor()', 5000);
	}
}

function validChange(){
	
	if(confirm("Are you sure you want to delete this file?"))
	{
		document.getElementById("remove").name = "remove";
	}
}

function fillallfields(key){
	var javascriptDiv = "fields_filled";
	var url = "../EditPublications/fields_filled.php?key=";
	url = url+key;
	//alert(url);
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
				//condition is not verified here
				document.getElementById(javascriptDiv).innerHTML = xmlhttp.responseText;
			}
			else 
			{	// a HTTP status different than 200 signals an error
				//alert("There was a problem accessing the server: " + xmlhttp.statusText);
			}
		}

		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
	else{//if the connection is busy, try again after 5 seconds
		setTimeout('fillallfields()', 5000);
	}
}
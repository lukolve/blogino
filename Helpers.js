

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function acceptCookie() {
  //if (document.cookie.replace(/(?:(?:^|.*;\s*)acceptCookie\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
    // document.cookie = "acceptCookie=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
    setCookie("cookieTest", "true", 30);
  //}
}

function checkCookie() {
  var ct=getCookie("cookieTest");
  if (ct != "") {
    document.getElementById('cookiePanel').style.display='none';
  } else {
  }
}


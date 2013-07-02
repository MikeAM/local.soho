<?php
#===============================================================================================
# Forms Manager Module v2.0 - ajaxDor() function include
# Modified version of ajaxDo() used elsewhere in soho code
# ajaxDor() accepts offset paramenter
# makerObject() meant to be better about browser detection than original makeObject()
#===============================================================================================
?>
<script type="text/javascript">
//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
function makerObject() {
   var x;
   var browser = navigator.appName;
   try {
      x = new XMLHttpRequest();
   } catch(e) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   }

   return x;
}

var requestr = makerObject();

function ajaxDor(qryString, boxid, offset) {

   var qryactive_status = document.getElementById('qryactive').value;

   if ( qryactive_status != 0 ) {
      // BUSY - Save pending qry to be executed when currently-executing qry is done
      document.getElementById('qrywaiting').value = "ajaxDor('"+qryString+"', '"+boxid+"', '"+offset+"')";

   } else {
      // READY - Run this qry now!

      // Set qry active flag
      document.getElementById('qryactive').value = 1;

      // Output box ID - Make global so parseInfo can get it
      rezBox = boxid;

      // Optional, So output box can be-realigned with affeced element - Make global so parseInfo can get it
      rescroll = offset;

      // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
      requestr.open('get', qryString);

      // This tells the script to call parseInfo() when the ready state is changed
      requestr.onreadystatechange = parseInfo;

      // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
      requestr.send(null);
   }

}

function parseInfo() {
   // Loading
   if ( requestr.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( requestr.readyState == 4 ) {
      var answer = requestr.responseText;
      document.getElementById(rezBox).innerHTML = answer;

      if ( rescroll > 0 ) {
         document.getElementById(rezBox).scrollTop = rescroll;
      }

      // Un-set qry active flag
      document.getElementById('qryactive').value = 0;

      // Pending ajax qry?
      var pending_qry = document.getElementById('qrywaiting').value;
      if ( pending_qry != '' ) {
         // Clear pending qry box (b/c it's about to be processed)
         document.getElementById('qrywaiting').value = '';

         // Process pending qry
         eval(pending_qry);
      }

   }
}
</script>
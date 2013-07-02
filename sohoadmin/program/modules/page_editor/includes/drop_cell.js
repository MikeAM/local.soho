// These functions define the movement of cells and their content.
// Joe Lain 11-15-05

var ie=document.all;
var nn6=document.getElementById&&!document.all;

var daMoving, fobj_parent;
var isdrag=false;
var x,y;
var dobj;

var cursor = 
   document.layers ? document.cursor :
   document.all ? document.all.cursor :
   document.getElementById ? document.getElementById('cursor') : null;

// While dragging a cell, this keeps track of the coodinates.
function movemouse(e)
{
  if (isdrag)
  {
   //$('dadrag').value='drag'
   
   //alert(moving);
//    endX = dobj.style.left = nn6 ? tx + e.clientX - x : tx + event.clientX - x;
//    endY = dobj.style.top  = nn6 ? ty + e.clientY - y : ty + event.clientY - y;
    realEndX = nn6 ? e.clientX : event.clientX;
    realEndY = nn6 ? e.clientY : event.clientY;
//    $('dadragx').value=realEndX
//    $('dadragy').value=realEndY
    
//   numEndX = Number(endX);
//   midEndX = (numEndX+100);
//   numEndY = Number(endY);
//   midEndY = (numEndY+40);
   
   return false;
  } else {
   //$('dadrag').value='no'
   if (ie)
   {
   	document.onmousemove=null;
   }
}
}

// Determines which cell is being dragged.
// Makes the cells id(daMoving) availible for the
// page editor to use.
function selectmouse(e)
{
  realEndX='';
  realEndY='';
  fobj_parent='';
  daMoving='';
  
  var fobj       = nn6 ? e.target : event.srcElement;
  
  var topelement = nn6 ? "HTML" : "BODY";

  while (fobj.tagName != topelement && fobj.className != "droppedItem")
  {
    fobj = nn6 ? fobj.parentNode : fobj.parentElement;
  }
  //alert(fobj.id)
//  $('daele').value=fobj.id
//  $('dadragx').value=fobj.className
//  $('dadragy').value=$('objectbar').style.visibility

  if (fobj.className=="droppedItem" && $('objectbar').style.visibility=='visible')
  {
    fobj_parent = nn6 ? fobj.parentNode : fobj.parentElement;
    daMoving = fobj.id;
    isdrag = true;
    dobj = fobj;
//    $('dadragy').value = 'ok2';
//    $('dadragx').value= $(fobj.id);
//    $('dadragy').value = fobj_parent.id;
//    tx = parseInt($(fobj.id).getPosition().x+0);
//    ty = parseInt($(fobj.id).getPosition().y+0);
    //$('dadragy').value= tx+'---'+ty
    
    //alert('tx='+tx+'\n\nty='+ty);
    x = nn6 ? e.clientX : event.clientX;
    y = nn6 ? e.clientY : event.clientY;
    //$('dadragy').value = 'YAY';
    document.body.style.cursor = 'move';
    document.onmousemove=movemouse;
    //if (document.captureEvents) document.captureEvents(Event.MOUSEMOVE);
    return false;
  }
}

// When cell is dropped, gets X and Y coords.
// Then loops through boxes to determine which
// box it was dropped in.
function dropMe(e) {
   document.body.style.cursor = 'default';
   n=0;
   if(isdrag && realEndX != ''){
      $('dadrag').value='yessss'
      isdrag=false;
      
         
         
      numEndX = Number(realEndX);
      numEndY = Number(realEndY);
//      numEndY = (numEndY+win_scroll);
//      alert(numEndY)
      
      var win_scroll = $('cell_container').pageYOffset || $('cell_container').scrollTop;
      numEndY = numEndY + win_scroll;
      
      
   //   alert(daMoving);
      var daMovingHTML = $(daMoving).innerHTML;
      //alert(daMovingHTML);
      //alert('tx:'+tx+' ty:'+ty+' x:'+x+' y:'+y);
   
      // Loop through boxes
      for ( r = 1; r <= 10; r++ ) {
         for ( c = 1; c <= 3; c++ ) {
            var box_Id = 'TDR'+r+'C'+c;
            
            
            X_left = $(box_Id).getCoordinates().left;
            X_right = $(box_Id).getCoordinates().right;
            Y_top = $(box_Id).getCoordinates().top;
            Y_bottom = $(box_Id).getCoordinates().bottom;
   
            
            if (numEndX >= X_left && numEndX < X_right && numEndY >= Y_top && numEndY < Y_bottom && fobj_parent.id != box_Id) {
               //alert(fobj_parent.id+'===='+box_Id);
               //alert(numEndX+'-- >= --'+X_left+'---'+numEndX+'-- < --'+X_right+'---'+numEndY+'-- >= --'+Y_top+'---'+numEndY+'-- < --'+Y_bottom)
               //$('dadrag').value=box_Id
               thisBox = box_Id;
               thisBox2 = box_Id;
               //alert(thisBox)
               
               var emptyCell = $(thisBox).innerHTML;
               var checkCell = emptyCell.search('pixel.gif');
//               alert(thisBox);
//               alert(emptyCell);
//               alert(checkCell);

               // Determine what obj's parent is (cell)
               var topelement = nn6 ? "HTML" : "BODY";
               var isParent = $(daMoving)
               while (isParent.tagName != topelement && isParent.className != "editTable"){
                  isParent = nn6 ? isParent.parentNode : isParent.parentElement;
               }
               //alert(isParent.id+'---'+daMoving+'---'+thisBox)
               
               // Remove obj from parent
               var removedItem = isParent.removeChild($(daMoving))
               //$('dadragx').value='removed'
               
               
               // Is parent empty now that we removed the obj?
               var startCell = isParent.innerHTML;
               // Search for droppedItem class
               var startCellSearch = startCell.search('droppedItem');
               if(startCellSearch == -1){    // Parent is empty, make it 'empty' with our trusty pixel.gif
                  isParent.innerHTML= '<IMG height="50%" src="pixel.gif" width="199" border="0">';
               }

               //###############################################
               //##         Empty Drop Area
               //###############################################
               if(checkCell > 0 || thisBox==daMoving) {
                  // Drop cell is empty, clear pixel.gif
                  $(thisBox).innerHTML= '';
               }
               
               // Add obj to drop cell
               $(thisBox).appendChild(removedItem);
               //alert($(thisBox).innerHTML);
               $(thisBox).innerHTML += "<!-- ~~~ -->";
               //alert($(thisBox).innerHTML);
               //$('dadragy').value='added'
               
               checkRow(thisBox2)
               checkRow(isParent.id)
               thisBox='';
               daMoving='';
               box_Id='';
               realEndX='';
               realEndY='';
            } else {
               n++;
   //            if (n==30) {
   //               // Move cell back to original pos.
   //               $(daMoving).style.left= tx;
   //               $(daMoving).style.top= ty;
   //            }
            }
         }
      }
      if(n==30){
         
         numEndX = Number(realEndX);
         numEndY = Number(realEndY);
         
         box_Id = 'oDeleteIt'
         X_left = $(box_Id).getCoordinates().left;
         X_right = $(box_Id).getCoordinates().right;
         Y_top = $(box_Id).getCoordinates().top;
         Y_bottom = $(box_Id).getCoordinates().bottom;
         
         //alert('LEFT-- '+numEndX+' >= '+X_left+'\n\nRIGHT-- '+numEndX+' < '+X_right+'\n\nTOP-- '+numEndY+' >= '+Y_top+'\n\nBOTTOM-- '+numEndY+' < '+Y_bottom)
         
         if (numEndX >= X_left && numEndX < X_right && numEndY >= Y_top && numEndY < Y_bottom) {
            
            var reallyDelete = confirm('This object will be deleted, are you sure?')
            
            if(reallyDelete){
            
               // Determine what obj's parent is (cell)
               var topelement = nn6 ? "HTML" : "BODY";
               var isParent = $(daMoving)
               while (isParent.tagName != topelement && isParent.className != "editTable"){
                  isParent = nn6 ? isParent.parentNode : isParent.parentElement;
               }
               //alert(isParent.id+'---'+daMoving)
               
               // Remove obj from parent
               var removedItem = isParent.removeChild($(daMoving))
               checkRow(isParent.id)
            }
         }

      }
      
      thisBox='';
      daMoving='';
      box_Id='';
   }else{
      //$('dadrag').value='noooo'
   }
   //alert('checking...')
   //checkPageAreas('start');
//   window.blur()
//   setTimeout("window.focus()", 2000);
} // End DropMe funct.


document.onmousedown=selectmouse;
document.onmouseup=dropMe;
// Used with long-form input fields

function setSelectionRange(input, selectionStart, selectionEnd) {
  if (input.setSelectionRange) {
    input.focus();
    input.setSelectionRange(selectionStart, selectionEnd);
  }
  else if (input.createTextRange) {
    var range = input.createTextRange();
    range.collapse(true);
    range.moveEnd('character', selectionEnd);
    range.moveStart('character', selectionStart);
    range.select();
  }
}

function replaceSelection (input, replaceString) {
  if (input.setSelectionRange) {
    var selectionStart = input.selectionStart;
    var selectionEnd = input.selectionEnd;

    // Hack by kennethburgener to avoid Firefox scrolling to top after using tab key when textarea has been scrolled downwards.
    // Modified by Chitetskoy 2008-04-30: it works
    var scrollTop = input.scrollTop; // fix scrolling issue with Firefox

    input.value = input.value.substring(0, selectionStart)+ replaceString + input.value.substring(selectionEnd);
    
    if (selectionStart != selectionEnd){ 
      setSelectionRange(input, selectionStart, selectionStart + replaceString.length);
    }else{
      setSelectionRange(input, selectionStart + replaceString.length, selectionStart + replaceString.length);
    }

    // Set the scrollTop of an input AFTER changes has been made in the input (or the textbox) content
    // Modified by Chitetskoy
    input.scrollTop = scrollTop;

  }else if (document.selection) {
    var range = document.selection.createRange();

    if (range.parentElement() == input) {
      var isCollapsed = range.text == '';
      range.text = replaceString;

      if (!isCollapsed)  {
	range.moveStart('character', -replaceString.length);
	range.select();
      }
    }
  }
}

// We are going to catch the TAB key so that we can use it, Hooray!
function catchTab(item,e){
  if(navigator.userAgent.match("Gecko")){
    c=e.which;
  }else{
    c=e.keyCode;
  }
  if(c==9){
    replaceSelection(item,String.fromCharCode(9));

    // magistus 2006-11-06 Comment out the timeout as advised by kennethburgener to avoid IE jumping to top of page, works OK!
    //setTimeout("document.getElementById('"+item.id+"').focus();",0);

    return false;
  }
      
}

// our oninput comment textarea resize function
function commentResize(textarea) {

  // reset style height to auto in order to get the current height of content
  textarea.style.height = "auto";
  
  // set the new height based on the comment textarea scroll height
  textarea.style.height = textarea.scrollHeight + "px";
  
}



var coll = document.getElementById("selector");

//used to toggle drop down menu based on user's clicks
coll.addEventListener("click", function() {
	this.classList.toggle("active");
	var content = document.getElementById("contentOptions");
	if (content.style.maxHeight){
	  content.style.maxHeight = null;
	} else {
	  content.style.maxHeight = content.scrollHeight + "px";
	} 
});

//if the content drop down is open and the width of the screen is expanded,
//then the drop down menu will close 
function removeDropdown(x) {
    if (x.matches) { // If media query matches
    	var element = document.getElementById("selector");
    	var content = document.getElementById("contentOptions");
    	if(element.classList.contains("active")){
    		element.classList.remove("active");
			if (content.style.maxHeight){
			  content.style.maxHeight = null;
			} else {
			  content.style.maxHeight = content.scrollHeight + "px";
			} 
    	}
    	
    } 
   else {
        
    }
}


var x = window.matchMedia("(min-width: 735px)")

x.addListener(removeDropdown) // Attach listener function on state changes


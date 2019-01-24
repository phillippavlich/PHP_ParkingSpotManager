//On the makeBooking.html page, this js function is used to update the cost of the booking
//for the user so that they know how much they would be spending prior to doing a booking
function updatePrice() {
    var getCost=document.getElementById("costTag");
    var dollarSymbol=getCost.innerHTML.indexOf("$");
    
    var modifiedString=getCost.innerHTML.slice(dollarSymbol+1);
    
    var actualCost=modifiedString.slice(0,modifiedString.indexOf(" "))
	
	var dt1= new Date(document.getElementById("fromDate").value);   
	var dt2= new Date(document.getElementById("toDate").value.split("-"));  

    var numdays=daysBetween(dt1, dt2);
    
    if(isNaN(numdays)){
    	numdays=0;
    }
    
    getCost.innerHTML="Cost: "+numdays+" days x $"+actualCost + " = $"+ (actualCost*numdays).toFixed(2);
    document.getElementById("costVal").value=(actualCost*numdays).toFixed(2);
}

//function to determine how many days are between 2 dates (from date and to date)
function daysBetween( date1, date2 ) {   
	var one_day=1000*60*60*24;    
	var date1_ms = date1.getTime();   
	var date2_ms = date2.getTime();    
	var difference_ms = date2_ms - date1_ms;        
	return Math.round(difference_ms/one_day); 
} 
//metodo aceptar solo numeros en las cajas de testo dinamicas
var nav4 = window.Event ? true : false;

function acceptNum(evt){ 
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57 
var key = nav4 ? evt.which : evt.keyCode; 

return (key === 13 || (key >= 48 && key <= 57) || key === 8 || key===0 );
}

function acceptNumDot(evt){ 

var key = nav4 ? evt.which : evt.keyCode; 

return (key === 13 || (key >= 48 && key <= 57) || key === 8 || key===46 ||  key===0 );
}
//-->

function acceptString(evt){
	
	var key = nav4 ? evt.which : evt.keyCode; 
	
return (key <= 13  || (key >= 48 && key <= 57) ||  (key >= 65 && key <= 90) || (key >= 97 && key <= 122)
		|| key ==209 || key==225 || key==241);
	
}

function acceptPass(evt){
	
	var key = nav4 ? evt.which : evt.keyCode; 
	
return (key <= 13  || (key >= 48 && key <= 57) ||  (key >= 65 && key <= 90) || (key >= 97 && key <= 122)
		|| key ==209 || key==241);
	
}

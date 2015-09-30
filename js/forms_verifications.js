// JavaScript Document

function VerifFormIntervention() 
{ 
	 	if(document.intervention.fdate.value == "") 
		{ 
        alert ('Veuillez renseigner la date de l\'intervention'); 
        document.intervention.fdate.focus(); 
        return false; 
    	}
       
       	if(document.intervention.fintervention.value == "") 
		{ 
        alert ('Veuillez renseigner le type d\'intervention'); 
        document.intervention.fintervention.focus(); 
        return false; 
    	}
		
		if(document.intervention.fcomm.value == "") 
		{ 
        alert ('Veuillez localiser l\'intervention'); 
        document.intervention.fintervention.focus(); 
        return false; 
    	}
		
		if((!document.intervention.fnbcontrev.value.match(/^[0-9]+$/))&&(document.intervention.fnbcontrev.value !== "")){
			alert('Le nombre de contrevenants doit etre un chiffre.');
			document.intervention.fnbcontrev.focus();
		return false;
		}
		
		if((!document.intervention.famende.value.match(/^[0-9]+$/))&&(document.intervention.famende.value !== "")){
			alert('L\'amende doit etre un chiffre.');
			document.intervention.famende.focus();
		return false;
		}
		
		if((!document.intervention.fdommages.value.match(/^[0-9]+$/))&&(document.intervention.fdommages.value !== "")){
			alert('Les dommages et interets doivent etre un chiffre.');
			document.intervention.fdommages.focus();
		return false;
		}
				
else {return true;}
}

function VerifFormGestionAgent() 
{ 
	 	if(document.gestionagent.fdroits.value == "") 
		{ 
        alert ('Veuillez renseigner les droits de l\'agent'); 
        document.gestionagent.fdroits.focus(); 
        return false; 
    	}
       
else {return true;}
}

function VerifFormInfraction() 
{ 
	 	if(document.infraction.finfr.value == "") 
		{ 
        alert ('Veuillez renseigner le type d\'infraction'); 
        document.infraction.finfr.focus(); 
        return false; 
    	}
       
       	if(document.infraction.fqual.value == "") 
		{ 
        alert ('Veuillez renseigner la qualification de l\'infraction'); 
        document.infraction.fqual.focus(); 
        return false; 
    	}
  
else {return true;}
}

function VerifFormAgent() 
{ 
	 	if(document.agent.fagent.value == "") 
		{ 
        alert ('Veuillez renseigner l\'agent'); 
        document.agent.fagent.focus(); 
        return false; 
    	}
       
else {return true;}
}


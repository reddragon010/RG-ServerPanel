textNoCheck = "formElementName!='nocheck1' && formElementName!='nocheck2'";
withSubCheck = true;

missingText   = "Bitte fuellen Sie alle ausgelassenen Felder!";
missingMail   = "Ihre Email Adresse ist nicht korrekt!";

missing  = "*fehlt*"; // text
newEntry = "*email*"; // email

function checkForm(){

    formElementsCount = document.form1.elements.length;

    for(i=0; i<formElementsCount; i++){

        formElementName = document.form1.elements[i].name;

        if(eval(textNoCheck)){

            formElement = document.form1.elements[i];

            if(formElement.value=="" || formElement.value==missing){

                if(formElement.type=="text" || formElement.type=="textarea" || formElement.type=="password"){

                    document.getElementById("fehlercodeRegister").innerHTML = missingText;

                    formElement.focus();

                    if(document.all || document.getElementById){

                        formElement.blur();

                        if(formElement.type != "password")
                        formElement.value=missing;

                        formElement.onclick = clean;

                    }

                    return false;

                }
            }
        }
    }

    if(withSubCheck){
        return startSubCheck();
    }
}

function checkMailAddress(){

	if(document.form1.email){

	    dofem = document.form1.email

	    expression = /[a-zA-Z0-9]{1,}@[a-zA-Z0-9]{1,}\.[a-zA-Z]{2,}/;

	    if(!expression.exec(dofem.value)){

	        document.getElementById("fehlercodeRegister").innerHTML = missingMail;

	        dofem.focus();

	            if(document.all || document.getElementById){

	                dofem.blur();

	                dofem.value = newEntry;

	                dofem.onclick = clean;

	            }

	        return false;

	     }
    }
}

function checkPasswordValidity(text){
  if(!text.match(/[a-zA-Z0-9]$/)){
      return false;
  }else{
      return true;
  }
}

function checkNameValidity(text){
  if(!text.match(/[a-zA-Z_]$/)){
      return false;
  }else{
      return true;
  }
}

function checkPasswords(){
    if(document.form1.password.value != document.form1.passconf.value){
      document.getElementById("fehlercodeRegister").innerHTML = "Die Passwörter stimmen nicht \u00fcberein!";
      return false;
    }
    expression = /.{5,}/;
    if(!expression.exec(document.form1.password.value)){
      document.getElementById("fehlercodeRegister").innerHTML = "Ihr Passwort ist zu kurz [mind. 5 zeichen]!";
      return false;
    }
    test = checkPasswordValidity(document.form1.password.value);
    if(test==false){
        document.getElementById("fehlercodeRegister").innerHTML = "Ihr Passwort enth\u00e4lt ung\u00fcltige Zeichen!";
        return false;
    }
}

function checkUsername(){
    expression = /.{4,}/;
    if(!expression.exec(document.form1.username.value)){
      document.getElementById("fehlercodeRegister").innerHTML = "Username ist zu kurz [mind. 4 zeichen]!";
      return false;
    }
    test = checkNameValidity(document.form1.username.value);
    if(test==false){
        document.getElementById("fehlercodeRegister").innerHTML = "Ihr Username enth\u00e4lt ung\u00fcltige Zeichen!";
        return false;
    }    
}

function clean(){
    this.value="";
}

function startSubCheck(){

  cp = checkUsername();
  if(cp==false){
      return false;
  }
  
  cp = checkPasswords();
  if(cp==false){
      return false;
  }

  cm = checkMailAddress();
  if(cm==false){
      return false;
  }

}

textNoCheck = "formElementName!='nocheck1' && formElementName!='nocheck2'";
withSubCheck = true;

missingText   = "Bitte fuellen Sie alle ausgelassenen Felder!";
missingMail   = "Ihre Email Adresse ist nicht korrekt!";

missing  = "*fehlt*"; // text
newEntry = "*email*"; // email

function checkForm2(){

    formElementsCount = document.form2.elements.length;

    for(i=0; i<formElementsCount; i++){

        formElementName = document.form2.elements[i].name;

        if(eval(textNoCheck)){

            formElement = document.form2.elements[i];

            if(formElement.value=="" || formElement.value==missing){

                if(formElement.type=="text" || formElement.type=="textarea" || formElement.type=="password"){

                    document.getElementById("fehlercodeLogin").innerHTML = missingText;

                    formElement.focus();

                    if(document.all || document.getElementById){

                        formElement.blur();

                        if(formElement.type != "password")
                        formElement.value=missing;

                        formElement.onclick = clean;

                    }

                    return false;

                }
            }
        }
    }

    if(withSubCheck){
        return startSubCheck2();
    }
}

function checkMailAddress2(){

	if(document.form2.email){

	    dofem = document.form2.email

	    expression = /[a-zA-Z]+@[a-zA-Z]+\.[a-zA-Z]{2,}/;

	    if(!expression.exec(dofem.value)){

	        document.getElementById("fehlercodeLogin").innerHTML = missingMail;

	        dofem.focus();

	            if(document.all || document.getElementById){

	                dofem.blur();

	                dofem.value = newEntry;

	                dofem.onclick = clean;

	            }

	        return false;

	     }
    }
}

function checkPasswordValidity2(text){
  if(!text.match(/[a-zA-Z0-9]$/)){
      return false;
  }else{
      return true;
  }
}

function checkNameValidity2(text){
  if(!text.match(/[a-zA-Z_]$/)){
      return false;
  }else{
      return true;
  }
}

function checkPasswords2(){
    expression = /.{5,}/;
    if(!expression.exec(document.form2.password.value)){
      document.getElementById("fehlercodeLogin").innerHTML = "Ihr Passwort ist zu kurz [mind. 5 zeichen]!";
      return false;
    }
    test = checkPasswordValidity2(document.form2.password.value);
    if(test==false){
        document.getElementById("fehlercodeLogin").innerHTML = "Ihr Passwort enth\u00e4lt ung\u00fcltige Zeichen!";
        return false;
    }
}

function checkUsername2(){
    expression = /.{4,}/;
    if(!expression.exec(document.form2.username.value)){
      document.getElementById("fehlercodeLogin").innerHTML = "Username ist zu kurz [mind. 4 zeichen]!";
      return false;
    }
    test = checkNameValidity2(document.form2.username.value);
    if(test==false){
        document.getElementById("fehlercodeLogin").innerHTML = "Ihr Username enth\u00e4lt ung\u00fcltige Zeichen!";
        return false;
    }    
}

function clean2(){
    this.value="";
}

function startSubCheck2(){

  cp = checkUsername2();
  if(cp==false){
      return false;
  }
  
  cp = checkPasswords2();
  if(cp==false){
      return false;
  }

  cm = checkMailAddress2();
  if(cm==false){
      return false;
  }

}
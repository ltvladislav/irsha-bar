var Field = {
	Name: "name",
	Phone: "phone",
	Email: "email",
	Password: "password",
	PasswordRepeat: "password-repeat"
};
var Classes = {
	Correct: "validation-true",
	Incorrect: "validation-false"
};


$(function(){
	$('#' + Field.Email).on("keyup", function() {
		ValueSetClass(this, EMailIsValidate($(this).val()));
	});
	$('#' + Field.Email).on("change", function() {
		CheckEmailExist($(this).val());
	});
	$('#' + Field.Phone).on("keyup", function() {
		if(IsEmpty($(this).val())) {
			$(this).addClass(Classes.Correct);
		}
		else {
			$(this).removeClass(Classes.Correct);
		}
	});
	$('#' + Field.Name).on("keyup", function() {
		ValueSetClass(this);
	});
	$('#' + Field.Password).on("keyup", function() {
		ValueSetClass(this);
		ValueSetClass($('#' + Field.PasswordRepeat), IsEquals(Get(Field.Password), Get(Field.PasswordRepeat)));
	});
	$('#' + Field.PasswordRepeat).on("keyup", function() {
		ValueSetClass(this, IsEquals(Get(Field.Password), Get(Field.PasswordRepeat)));
	});

	$('#reg-form').submit(function() {
	    var ok = ValueIsEmpty(Field.Email) && EMailIsValidate(Get(Field.Email)) &&
	    	ValueIsEmpty(Field.Name) &&
	    	ValueIsEmpty(Field.Password) && ValueIsEmpty(Field.PasswordRepeat) &&
	    	IsEquals(Get(Field.Password), Get(Field.PasswordRepeat));
		
		if (!ok) {
			alert('Введені некоректні дані!');
			return false;
		}
	});
});


function ValueSetClass(elem, bolVal) {
	bolVal = bolVal === false ? false : true;
	if(IsEmpty($(elem).val()) != "" && bolVal){
			$(elem).removeClass(Classes.Incorrect).addClass(Classes.Correct);
		}
		else{
			$(elem).removeClass(Classes.Correct).addClass(Classes.Incorrect);
		}
}
function ValueIsEmpty(arg) {
	return IsEmpty(Get(arg));
}
function IsEmpty(arg) {
	return arg != "" && arg != null && arg != undefined;
}
function EMailIsValidate(emailStr) {
	var pattern  = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return pattern.test(emailStr) ? true : false; 
}
function IsEquals(arg1, arg2) {
	return arg1 == arg2;
}


function CheckEmailExist(email) {
	$.post('../php/fetchFromDB.php', {
			tableName: 'User',
			filter: [
				{
					key: 'Email',
					value: email,
					type: 1
				}
			]
		}, function(data) {
			if (data.data.length) {
				alert("Існує користувач з таким email!");
			}
		});
}
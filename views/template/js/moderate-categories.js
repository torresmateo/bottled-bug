jQuery(document).ready(function($) {

	//utilities from http://dl.dropboxusercontent.com/u/35146/js/tests/isNumber.html
	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}

	//check for a valid role
	$("#targetForm").submit(function(e){
		var target = $("#moderateTarget").val();
		if(target != "moderate-NO-TARGET")
			return true;
		alert("You must " + $("#targetValue").html());
		return false;
	});

	$('#moderateTarget').change(function(){
		var target = $(this).val();
		//userValues are numeric, a prefix is needed
		var targetCurrentSettings = isNumber(target)?window["moderate_uid_" + target]:window[target];
		if(targetCurrentSettings != undefined){
			var dropDown = $('input[name=rule\\[\\]]');
			for (var i = 0; i < dropDown.length; i++) {
				if($.inArray(dropDown[i].value,targetCurrentSettings) != -1)
					dropDown[i].checked = true;
				else
					dropDown[i].checked = false;
				
			};
		}
	});

	$("#post").submit(function(e){
		var split = location.search.replace('?', '').split('&').map(function(val){
			return val.split('=');
		});
		if(split[0][0] == 'post' && split[1][0] == 'action' && split[1][1] == 'edit' ){
			var categories = $('input[name=post_category\\[\\]]');
			var atLeastOneChecked = false;
			if(categories.length > 1)
				for (var i = 0; i < categories.length; i++)
					if(categories[i].checked == true && !(categories[i].value == 0)){
						return true;
					}
			alert("You Must Select at least one of your visible categories");
			return false;
		}
		return true;
	});
});

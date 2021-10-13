
//custom part2

var gkey = "AIzaSyDFSDuybsLt7DiOvrjaxcRe_cR5hntpbTY";


function get_cities(cid) {
	$.getJSON(base_url + "hotel_registration/aj_country_cities?country=" + cid, function (data) {
		$("#cities").html("");
		for (el in data.city) {
			$("#cities").append('<option value="' + data.city[el].id + '">' + data.city[el].name + "</option>");
		}
	});
}
// $("#searchtxt").on("invalid", () => {
// 	$.getJSON(base_url + "search/tr?text=searchfieldempty", function (data) {
// 		if (data.status) {
// 			document.getElementById("searchtxt").setCustomValidity(data.result);
// 		}
// 	});
// });

function checkuname(name) {
	$("#uerror").html("");
	$.getJSON(
		"/hotel_registration/ajusername_check?name=" + name,
		function (data) {
			$("#uerror").html('<i style="color:red">' + data.error + "</i>");
		}
	);
}

function checkhemail(mail) {
	$('#merror').html('');
	$.getJSON("/hotel_registration/ajemail_check?email=" + mail, function (data) {
		$('#merror').html('<i style="color:red">' + data.error + '</i>');
	}
	);
}

function chlng2(lng) {
	window.location = "/home/chlang?tolng=" + lng;
}

function chlng(lng) {
	var tk = document.getElementsByName("csrftkn")[0].name;
	var params = {
		tolang: lng
	};
	params[tk] = document.getElementsByName("csrftkn")[0].value;
	$.post(base_url + "home/chlang", params).done(function (res) {
		if (res.status) {
			location.reload();
		}
	});
}

function chcur(code) {
	var tk = document.getElementsByName("csrftkn")[0].name;
	var objects = {
		curr: code
	};
	objects[tk] = document.getElementsByName("csrftkn")[0].value;
	$.post(base_url + "/home/chcur",objects).done(function (res) {
		if (res.status) {
			location.reload();
		}
	});
}

function ulogin() {
	$.post(base_url + "plogin/booklogin", {
		email: $("#uemail").val(),
		password: $("#upassword").val()
	}).done(function (res) {
		if (res.status) {
			$("#ForMsg").addClass("alert alert-success");
			$("#ForMsg").html(res.result);
			setTimeout("location.reload()", 2000);
		} else {
			$("#ForMsg").addClass("alert alert-danger");
			$("#ForMsg").html(res.result);
		}
	});
}

function gulogin() {
	$.post(base_url + "plogin/glogin", {
    email: $("#guemail").val(),
    password: $("#gupassword").val(),
    csrftkn: document.getElementsByName("csrftkn")[0].value,
  }).done(function (res) {
    if (res.status) {
      $("#gForMsg").addClass("alert alert-success");
      $("#gForMsg").html(res.result);
      setTimeout("location.reload()", 2000);
    } else {
      $("#gForMsg").addClass("alert alert-danger");
      $("#gForMsg").html(res.result);
    }
  });
}

function get_regions(gid) {
	$.getJSON(
		base_url + "hotel_registration/aj_country_regions?city=" + gid,
		function (data) {
			$("#regions").html("");
			if (data.status) {
				for (c in data.city) {
					$("#regions").append(
						'<option value="' +
						data.city[c].id +
						'">' +
						data.city[c].name +
						"</option>"
					);
				}
			} else {
				$("#regions").append('<option value="0"> No citeis </option>');
			}
		}
	);
}

function get_cities(cid) {
	$.getJSON(base_url + "hotel_registration/aj_country_cities?country=" + cid,
		function (data) {
			$("#cities").html("");
			if (data.status) {
				console.log(data);
				for (el in data.city) {
					$("#cities").append(
						'<option value="' +
						data.city[el].id +
						'">' +
						data.city[el].name +
						"</option>"
					);
				}
			} else {
				$("#cities").append('<option value="0">No City Av</option>');
			}
		}
	);
}

/*discode_tell
var pvid = $("#pvid").val();
var dt1 = $("#dt1").val();
var dt2 = $("#dt2").val();
var chk = $("#dschk").val();
if (chk == 1) {
	$.getJSON(
		"/home/jdiscodecheck?pvid=" +
		pvid +
		"&dt1=" +
		dt1 +
		"&dt2=" +
		dt2,
		function (data) {
			if (data.available == true) {
				$("#discount").show();
			}
		}
	);
}

$("#codecheck").click(function () {
	var tp = $("#tp").val();
	var discode = $("#discode").val();
	var discode_tell = $("#discode_tell");
	var discountid = $("#discountid");
	var netprice = $("#netprice");
	$.getJSON(
		"/home/jdiscodecheckav?pvid=" +
		pvid +
		"&dt1=" +
		dt1 +
		"&dt2=" +
		dt2 +
		"&tprice=" +
		tp +
		"&discount=" +
		discode,
		function (data) {
			if (data.error) {
				discode_tell.css("color", "red");
				discode_tell.text(data.error);
			} else if (!data.codestatus) {
				discode_tell.css("color", "red");
				discode_tell.text("the code is not valid");
			} else if (data.codestatus) {
				discode_tell.css("color", "green");
				discode_tell.text("valid Code");
				discountid.val(data.discount);
				netprice.val(data.discounted);
				$(
					"<tr id='disamount'><th scope='row'>Discount Amount: </th><td>" +
					data.discount_amount +
					"</td></tr>"
				).appendTo("#res_summary");
				$(
					"<tr id='discounted'><th scope='row'>Net Price after Discount: </th><td>" +
					data.discounted +
					"</td></tr>"
				).appendTo("#res_summary");
				$("#codecheck").attr("disabled", true);
			}
		}
	);
});
*/
// $("#searchtxt").autocomplete({
// 	source: function (request, response) {
// 		var txt = request.term;
// 		$.getJSON("/search/s_bar?find=" + txt, function (data) {
// 			response(
// 				$.map(data, function (value, key) {
// 					return {
// 						label: value
// 					};
// 				})
// 			);
// 		});
// 	}
// });
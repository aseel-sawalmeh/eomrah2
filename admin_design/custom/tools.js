
var base_url = '/';
function check_dates(getedate) {
    $("#dateconflicts").html("");
    $.getJSON(
        base_url + "chotel/hotel_manage/aj_dates_conflicts/" +
        $("#provider_id").val() +
        "/" +
        $("#startdate").val() +
        "/" +
        getedate,
        function (response) {
            console.log(response);
            for (res in response.data) {
                console.log(response.data[res].conflict);
                $("#dateconflicts").append(
                    '<i style="color:red">' + response.data[res].conflict + "</i><br>"
                );
            }
        }
    );
}

function fillAttrTable(RoomId) {
    $("#Data-Table-attrs").html('');
    $.getJSON(base_url + "chotel/hotel/getroomattr/" + RoomId, function (res) {
        $("#Data-Table-attrs").html(res.data);
        if (res.roomfiled) {
            $("#adultAtrr").attr('disabled', true);
            $("#childAtrr").attr('selected', true);
        } else {
            $("#adultAtrr").attr('disabled', false);
            $("#adultAtrr").attr('selected', true);
        }
    });
}

function fillMealsTable() {
    var hid = $("#hotelId").val();
    $.post(base_url + "chotel/hotel/get_hotel_meal", {
        hid: $("#hotelId").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
    }).done(function (res) {
        $("#Data-Table-Meals").html(res.data);
    });
}

function showgm(RoomId, attrAdMax) {
    $("#AttrRoomID").val(RoomId);
    $("#AttrMax").val(attrAdMax);
    fillAttrTable(RoomId);
}

function age_set(typstr) {
    var age_from;
    var age_to;
    var idwhere;

    if (typstr == "a_age") {
        age_from = $("#wizard_adult_age_from").val();
        age_to = $("#wizard_adult_age_to").val();
        idwhere = "amodmessage";
    } else if (typstr == "c_age") {
        age_from = $("#wizard_child_age_from").val();
        age_to = $("#wizard_child_age_to").val();
        idwhere = "cmodmessage";
    } else if (typstr == "i_age") {
        age_from = $("#wizard_infant_age_from").val();
        age_to = $("#wizard_infant_age_to").val();
        idwhere = "imodmessage";
    }
    $.post(base_url + "chotel/hotel/set_occ_age", {
        hid: $("#hotelId").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        agetype: typstr,
        agefrom: age_from,
        ageto: age_to
    }).done(function (res) {
        $("#" + idwhere).html(res.data);
    });
}

function age_update(typstr) {
    var age_from;
    var age_to;
    var idwhere;

    if (typstr == "a_age") {
        age_from = $("#wizard_adult_age_from").val();
        age_to = $("#wizard_adult_age_to").val();
        idwhere = "amodmessage";
    } else if (typstr == "c_age") {
        age_from = $("#wizard_child_age_from").val();
        age_to = $("#wizard_child_age_to").val();
        idwhere = "cmodmessage";
    } else if (typstr == "i_age") {
        age_from = $("#wizard_infant_age_from").val();
        age_to = $("#wizard_infant_age_to").val();
        idwhere = "imodmessage";
    }
    $.post(base_url + "chotel/hotel/update_occ_age", {
        hid: $("#hotelId").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        agetype: typstr,
        agefrom: age_from,
        ageto: age_to
    }).done(function (res) {
        $("#" + idwhere).html(res.data);
    });
}


function ins_room() {
    var hid = $("#hotelId").val();
    var R_Type = $("#roomtype").val();
    var Max_occ = $("#maxocc").val();
    $.post(base_url + "chotel/hotel/ins_room", {
        hotelid: hid,
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        rtype: R_Type,
        mxocc: Max_occ
    }).done(function (res) {
        $("#Data-Table-Rooms").html(res.datatable);
    });
}

$("#pordimgup").on('submit', (fd) => {
    fd.preventDefault();
    console.log('submitted');
    var form = document.forms.namedItem("pordimgup");
    var postData = new FormData(form);
    $.ajax({
        url: base_url + "gman/hotel_system/husers/prodImgs",
        type: "POST",
        data: postData,
        processData: false,
        contentType: false,
        success: function (data, textStatus, jqXHR) {
            location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('errorororororoorrororooror uploaded');
        }
    });
});


function insAttr() {
    $("#Data-Table-attrs").html('');
    $.post(base_url + "chotel/hotel/add_occ_attr", {
        rid: $("#AttrRoomID").val(),
        attrtype: $("#attrType").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        mxadult: $("#AttrMax").val()
    }).done(function (res) {
        if (res.status == true) {
            fillAttrTable($("#AttrRoomID").val());
        } else {
            fillAttrTable($("#AttrRoomID").val());
            $("#AttrAddErrorMessage").html(res.error);
        }
    });
}

function delRoomAttr(attrtyp) {
    $("#Data-Table-attrs").html('');
    $.post(base_url + "chotel/hotel/del_occ_attr", {
        rid: $("#AttrRoomID").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        attrtype: attrtyp,
    }).done(function (res) {
        if (res.status == true) {
            fillAttrTable($("#AttrRoomID").val());
        } else {
            $("#AttrAddErrorMessage").html(res.error);
        }
    });
}


function insHotelMeal() {
    $.post(base_url + "chotel/hotel/add_hotel_meal", {
        hid: $("#hotelId").val(),
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        mid: $("#wizard_Meal").val()
    }).done(function (res) {
        if (res.status == true) {
            fillMealsTable();
        } else {
            $("#MealAddErrorMessage").html(res.error);
        }
    });
}

function maxocc_update(RoomId) {
    $.post(base_url + "chotel/hotel/room_maxocc_update", {
        rid: RoomId,
        csrftkn: document.getElementsByName("csrftkn")[0].value,
        mx: $("#" + RoomId + "_maxocc").val()
    }).done(function (res) {
        if (res.result) {
            $("#" + RoomId + "MxMessage").html("<span style='color: green'>Updated</span>");
        } else {
            $("#" + RoomId + "MxMessage").html("<span style='color: red'" + res.error + '<span>');
        }

    });
}

function delHotelMeal(hmealid) {
    $.post(base_url + "chotel/hotel/del_hotel_meal", {
        mid: hmealid,
        csrftkn: document.getElementsByName("csrftkn")[0].value,
    }).done(function (res) {
        fillMealsTable();
        $("#MealAddErrorMessage").html(res.error);
    });
}


function calc_margin() {
    var targetset = document.getElementsByClassName("mrpriced");
    var pmarginmop = document.getElementById("pmarginmop").value;
    var pmargintype = document.getElementById("pmargintype").value;
    var period_mrprice = document.getElementById("period_mrprice");

    if (period_mrprice.value.length == 0) {
        return;
    } else {
        for (i = 0; i < targetset.length; i++) {
            var targetvalue = eval(targetset[i].value);
            if (pmargintype == "#") {
                targetset[i].value = eval(
                    targetvalue + (pmarginmop + period_mrprice.value)
                );
            } else if (pmargintype == "%") {
                targetset[i].value = eval(
                    (period_mrprice.value / 100) * targetvalue + targetvalue
                );
            }
        }
        period_mrprice.value = 0;
    }
}


function showResult(str,page) {
    var cpage = document.getElementById('currentpage').innerHTML;
    if (page == Number(cpage)) {
        return;
    }
    if (str.length == 0) {
        location.reload();
    }
    xmlhttp = new XMLHttpRequest();
    var url = '';
    var cpage;
    if (page != null) {
        url = "/chotel/main/search_b2c?ref=" + str + '&page=' + page;
        var cpage = document.getElementById('currentpage').innerHTML = page;
    } else {
        url = "/chotel/main/search_b2c?ref=" + str;
    }
    xmlhttp.open("GET", url, true);

    xmlhttp.onload = function () {
        if (this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("s_results").innerHTML = "";
            if (res.status) {

                for (el in res.data) {
                    document.getElementById("s_results").innerHTML += res.data[el];
                }
                console.log(res.totalpages);
                if (res.totalpages > 0) {
                    var pg = document.getElementById('pagination');
                    pg.innerHTML ="";
                    var ul = document.createElement("UL");  
                    ul.classList.add('pagination');              
                    var li = document.createElement("LI");
                    li.classList.add('page-item');  
                    li.innerHTML = '<span class="page-link" onClick="showResult(' + str + ',' + 0 + ')" ><a href="#">' + 1 + '</a></span>';
                    ul.appendChild(li);
                    for (var n = 1; n < Number(res.totalpages); n++) {
                        var li2 = document.createElement("LI");  
                        li2.classList.add('page-item'); 
                        li2.innerHTML = '<span class="page-link" onClick="showResult(' + str + ',' + n + ')"><a href="#">' + (n + 1) + '</a></span>';
                        ul.appendChild(li2);
                    }
                    pg.appendChild(ul);
                }
            } else {
                document.getElementById("s_results").innerHTML = "No Results";
            }
        }
    }
    xmlhttp.send();
}



function ShowB2bResult(str,page) {
    var cpage = document.getElementById('currentpage').innerHTML;
    if (page == Number(cpage)) {
        return;
    }
    if (str.length == 0) {
        location.reload();
    }
    xmlhttp = new XMLHttpRequest();
    var url = '';
    var cpage;
    if (page != null) {
        url = "/chotel/main/search_b2b?ref=" + str + '&page=' + page;
        var cpage = document.getElementById('currentpage').innerHTML = page;
    } else {
        url = "/chotel/main/search_b2b?ref=" + str;
    }
    xmlhttp.open("GET", url, true);

    xmlhttp.onload = function () {
        if (this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("s_results").innerHTML = "";
            if (res.status) {
                for (el in res.data) {
                    document.getElementById("s_results").innerHTML += res.data[el];
                }
                console.log(res.totalpages);
                if (res.totalpages > 0) {
                    var pg = document.getElementById('pagination');
                    pg.innerHTML ="";
                    var ul = document.createElement("UL");  
                    ul.classList.add('pagination');              
                    var li = document.createElement("LI");
                    li.classList.add('page-item');  
                    li.innerHTML = '<span class="page-link" onClick="ShowB2bResult(' + str + ',' + 0 + ')" ><a href="#">' + 1 + '</a></span>';
                    ul.appendChild(li);
                    for (var n = 1; n < Number(res.totalpages); n++) {
                        var li2 = document.createElement("LI");  
                        li2.classList.add('page-item'); 
                        li2.innerHTML = '<span class="page-link" onClick="ShowB2bResult(' + str + ',' + n + ')"><a href="#">' + (n + 1) + '</a></span>';
                        ul.appendChild(li2);
                    }
                    pg.appendChild(ul);
                }
            } else {
                document.getElementById("s_results").innerHTML = "No Results";
            }
        }
    }
    xmlhttp.send();
}


function showb2c(str, page = null) {
    var cpage = document.getElementById('currentpage').innerHTML;
    if (page == Number(cpage)) {
        return;
    }
    if (str.length == 0) {
        location.reload();
    }
    xmlhttp = new XMLHttpRequest();
    var url = '';
    var cpage;
    if (page != null) {
        url = "/gman/main/search_b2c?term=" + str + '&page=' + page;
        var cpage = document.getElementById('currentpage').innerHTML = page;
    } else {
        url = "/gman/main/search_b2c?term=" + str;
    }
    xmlhttp.open("GET", url, true);

    xmlhttp.onload = function () {
        if (this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("admin_results").innerHTML = "";
            if (res.status) {

                for (el in res.data) {
                    document.getElementById("admin_results").innerHTML += res.data[el];
                }
                console.log(res.totalpages);
                if (res.totalpages > 0) {
                    var pg = document.getElementById('pagination');
                    pg.innerHTML ="";
                    var ul = document.createElement("UL");  
                    ul.classList.add('pagination');              
                    var li = document.createElement("LI");
                    li.classList.add('page-item');  
                    li.innerHTML = '<span class="page-link" onClick="showb2c(' + str + ',' + 0 + ')" ><a href="#">' + 1 + '</a></span>';
                    ul.appendChild(li);
                    for (var n = 1; n < Number(res.totalpages); n++) {
                        var li2 = document.createElement("LI");  
                        li2.classList.add('page-item'); 
                        li2.innerHTML = '<span class="page-link" onClick="showb2c(' + str + ',' + n + ')"><a href="#">' + (n + 1) + '</a></span>';
                        ul.appendChild(li2);
                    }
                    pg.appendChild(ul);
                }
            } else {
                document.getElementById("admin_results").innerHTML = "No Results";
            }
        }
    }
    xmlhttp.send();
}

function showb2b(str, page = null) {
    var cpage = document.getElementById('currentpage').innerHTML;
    if (page == Number(cpage)) {
        return;
    }
    if (str.length == 0) {
        location.reload();
    }
    xmlhttp = new XMLHttpRequest();
    var url = '';
    var cpage;
    if (page != null) {
        url = "/gman/main/search_b2b?term=" + str + '&page=' + page;
        var cpage = document.getElementById('currentpage').innerHTML = page;
    } else {
        url = "/gman/main/search_b2b?term=" + str;
    }
    xmlhttp.open("GET", url, true);

    xmlhttp.onload = function () {
        if (this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("admin_results").innerHTML = "";
            if (res.status) {
                for (el in res.data) {
                    document.getElementById("admin_results").innerHTML += res.data[el];
                }
                console.log(res.totalpages);
                if (res.totalpages > 0) {
                    var pg = document.getElementById('pagination');
                    pg.innerHTML ="";
                    var ul = document.createElement("UL");  
                    ul.classList.add('pagination');              
                    var li = document.createElement("LI");
                    li.classList.add('page-item');  
                    li.innerHTML = '<span class="page-link" onClick="showb2b(' + str + ',' + 0 + ')"><a href="#">' + 1 + '</a></span>';
                    ul.appendChild(li);
                    for (var n = 1; n < Number(res.totalpages); n++) {
                        var li2 = document.createElement("LI");  
                        li2.classList.add('page-item'); 
                        li2.innerHTML = '<span class="page-link" onClick="showb2b(' + str + ',' + n + ')" ><a href="#">' + (n + 1) + '</a></span>';
                        ul.appendChild(li2);
                    }
                    pg.appendChild(ul);
                }
            } else {
                document.getElementById("admin_results").innerHTML = "No Results";
            }
        }
    }
    xmlhttp.send();
}




function show_h(str) {
    if (str.length > 0) {
        document.getElementById("searchresults").style.display = 'table';
        document.getElementById("resulttable").style.display = 'none';
    } else {
        document.getElementById("searchresults").style.display = 'none';
        document.getElementById("resulttable").style.display = 'table';
    }
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/chotel/provider/search_hotels?text=" + str, true);
    xmlhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("replaced_result").innerHTML = "";
            if (res.status) {
                for (el in res.data) {
                    document.getElementById("replaced_result").innerHTML += res.data[el];
                }
            } else {
                document.getElementById("replaced_result").innerHTML = "No Results";
            }
        }
    }
    xmlhttp.send();
}






function show_provider_res(str, id) {
    if (str.length == 0) {
        location.reload();
    }
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "/gman/providers/search_res?paid=" + str + "&hid=" + id, true);
    xmlhttp.onload = function () {
        if (this.readyState == 4 && this.status == 200) {
            var res = JSON.parse(this.responseText);
            document.getElementById("provider_results").innerHTML = "";
            if (res.status) {
                for (el in res.data) {
                    document.getElementById("provider_results").innerHTML += res.data[el];
                }
            } else {
                document.getElementById("provider_results").innerHTML = res.error;
            }
        }
    }
    xmlhttp.send();
}



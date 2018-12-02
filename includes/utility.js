


/**
 * 
 * @param {*} membershipType 
 */
function setMemberType(membershipType){
    var m = document.getElementById("memberType");
    var i;
    for(i = 0; i < m.length; i++){
        if(m.options[i].value == membershipType){
            m.options[i].selected=true;
        }
    }
}

/**
 * 
 * @param {*} mealAmt 
 */
function setMeals(mealAmt){
    var m = document.getElementById("meals");
    var i;
    for(i = 0; i < m.length; i++){
        if(m.options[i].value == mealAmt){
            m.options[i].selected=true;
        }
    }
}

/**
 * 
 * @param {*} priceArray 
 */
function calculateAmtDue(priceArray) {
    var prices = JSON.parse(JSON.stringify(priceArray));
    var due = document.getElementById("renewalAmount");
    due.readOnly = false;
    due.readOnly = true;
    var type = document.getElementById("memberType").value;
    var meals = document.getElementById("meals").value;
    var amountDue;
    if(type != "" && meals != ""){
        type = Number(type) - 1;
        meals = Number(meals);
        amountDue = Number(prices[type].membership_price) + (Number(prices[type].meal_price) * meals);
        due.readOnly = false;
        due.value = amountDue.toFixed(2);
        due.readOnly = true;
    }
}

function functionRouter(parent, text, id) {
    switch (parent) {
        case 'types':
        getMemberType(parent, text, id);
        break;
        case 'reports':
        getReport(parent, text, id);
        break;
        default:
        break;
    }

}

function getMemberType(parent, text, id) {

    location.href = "relay.php?query=" + id + "&title=" + text;
    // if(id == '0') {
    //     location.href = "relay.php?query=" + id;
    // }

    // if(id == '1') {
    //     location.href = "relay.php?query=" + id;
    // }

    // if(id == '2') {
    //     location.href = "relay.php?query=" + id;
    // }

    // if(id == '3') {
    //     location.href = "relay.php?query=" + id;
    // }

    // if(id == '4') {
    //     location.href = "relay.php?query=" + id;
    // }

    // if(id == '5') {
    //     location.href = "relay.php?query=" + id;
    // }
}

function getReport(parent, text, id) {
      
    if(id == '15') {

    }

    if(id == '16') {
        
    }

    if(id == '17') {
        
    }

    if(id == '18') {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var now = new Date().toDateString();
                list = JSON.parse(this.responseText);
                mailList = "<div><h1>NSBA Mailing List</h1></br>" +
                "<p>" + now + "</p></br></div>" +
                "<div class='table-responsive'><table class='table'><tbody><tr id='mailRow'>";
                for(i in list.users) { 
                    mailList += "<td class='address'>" + list.users[i].first_name + " " + list.users[i].last_name + "</br>" +
                    list.users[i].company + "</br>" +
                    list.users[i].line_1 + "</br>";
                    if(list.users[i].line_2 != ''){
                        mailList += list.users[i].line_2 + "</br>";
                    }
                    mailList += list.users[i].city + ", " + list.users[i].state + " " + list.users[i].zip + "</td>";

                    if(i > 0 && (i + 1) % 3 == 0) {
                        mailList += "</tr><tr>";
                    }
                }
                mailList += "</tr></tbody></table></div>";
                document.getElementById("mail").innerHTML = mailList;
                $("#reportModal").modal();
            }
        };
    xmlhttp.open("GET", "relay.php?query=" + id, true);
    xmlhttp.send();
    }

    if(id == '19') {
        
    }

    if(id == '20') {
        
    }

    if(id == '21') {
        
    }

    if(id == '22') {
        
    }
}



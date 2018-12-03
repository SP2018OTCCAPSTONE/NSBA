
/***************************************************************************************
 * Reports and Queries
 * 
 ***************************************************************************************/

/**
 * 
 * @param {*} parent 
 * @param {*} text 
 * @param {*} id 
 */
function functionRouter(parent, text, id) {
    switch (parent) {

        case 'types':
        getMemberType(parent, text, id);
        break;

        case 'reports':
        getReport(parent, text, id);
        break;

        case 'searchByActive':
        getMemberType(parent, text, id);
        break;

        default:
        break;
    }

}

/**
 * 
 * @param {*} parent 
 * @param {*} text 
 * @param {*} id 
 */
function getMemberType(parent, text, id) {

    location.href = "relay.php?query=" + id + "&title=" + text;
}

/**
 * 
 * @param {*} parent 
 * @param {*} text 
 * @param {*} id 
 */
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



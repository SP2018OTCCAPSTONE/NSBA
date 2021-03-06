
//console.log(membershipType);// troubleshooter

  document.addEventListener("load", setMemberType(Number(membershipType)));
  document.addEventListener("load", setMeals(mealAmt));
  document.addEventListener("load", calculateAmtDue(priceArray));

  // // JQuery versions of the on load listeners
  // $(function() {setMemberType(Number(membershipType))});
  // $(function() {setMeals(mealAmt)});
  // $(function() {calculateAmtDue(priceArray)});

  document.getElementById("memberType").onchange = function(){calculateAmtDue(priceArray)}; ;
  document.getElementById("meals").onchange = function(){calculateAmtDue(priceArray)};

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
            //alert(m.options[i].value + " " + m.options[i].selected);// troubleshooter
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
    // due.readOnly = false;
    // due.readOnly = true;
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

    <div id = "searchByForm">
        <h1> Reports & Utilities</h1>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="SearchByMemType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Membership Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Administrators</a>
                <a class="dropdown-item" href="#">Non-Member User</a>
                <a class="dropdown-item" href="#">Gratis</a>
                <a class="dropdown-item" href="#">Individual</a>
                <a class="dropdown-item" href="#">Neighborhood Association</a>
                <a class="dropdown-item" href="#">Retired</a>
                <a class="dropdown-item" href="#">Corporate 2</a>
                <a class="dropdown-item" href="#">Corporate 3</a>
                <a class="dropdown-item" href="#">Corporate Associate</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="SearchByName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Name
            </button>
            <div class= "dropdown-menu p-5" aria-labelledby="dropdownMenuButton">
                <input id="firstNameDrop" name="firstName" placeholder = "First Name" />
                <input id="lastNameDrop" name="lastName" placeholder = "Last Name" />
                <input type="submit" value="Submit" class = "btn btn-light" />
            </div>
        </div>

        <!-- This might be better as a select element -->
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="SearchByYear" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Year
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "searchByYear">
                <a class="dropdown-item" href="#">All Years</a>
                <a class="dropdown-item" href="#"><?php echo $yr - 6 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr - 5 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr - 4 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr - 3 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr - 2 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr - 1 ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr ?></a>
                <a class="dropdown-item" href="#"><?php echo $yr + 1 ?></a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="SearchByActive" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Toggle Current/Inactive
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "searchByActive">
                <a class="dropdown-item" href="#">All Membership</a> 
                <a class="dropdown-item" href="#">Active Membership</a>
                <a class="dropdown-item" href="#">Inactive Membership</a>   
            </div>
        </div>
        <!-- <h1>TEST</h1> -->
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="reports" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reports
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="reports">
                <a class="dropdown-item" id="1" href="#">Generate Annual Renewal Invoices</a>
                <a class="dropdown-item" id="2" href="#">Generate Paid Member List</a>
                <a class="dropdown-item" id="3" href="#">Generate Unpaid Member List</a>
                <a class="dropdown-item" id="4" href="#">Generate Mailing List</a>
                <a class="dropdown-item" id="5" href="#">Annual Membership Aging Report</a>
                <a class="dropdown-item" id="6" href="#">Member Roster Report</a>
                <a class="dropdown-item" id="7" href="#">Member Record Aging Report</a>
                <a class="dropdown-item" id="8" href="#">Member Record Missing Field Report</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="Utilities" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Utilities
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "utilities">
                <a class="dropdown-item" href="#">Change Membership Prices</a>
                <a class="dropdown-item" href="#">Utility</a>
                <a class="dropdown-item" href="#">Utility</a>
                <a class="dropdown-item" href="#">Utility</a>
                <a class="dropdown-item" href="#">Utility</a>
            </div>
        </div>
        
    </div>

    <script>
        $('.dropdown-item').click(function(e) {
            e.preventDefault();// prevent the default anchor functionality
            // 'this' is the clicked anchor
            var text = this.text;
            var id = this.id;
            //alert(id);
            // Call Js function to retrieve report
            getReport(text, id);
        });
    </script>
    
    
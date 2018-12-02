
    <div id = "searchByForm">
        <h2>Search Options</h2>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="SearchByMemType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Membership Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="types">
                <a class="dropdown-item" id="0" href="#">All Membership</a>
                <a class="dropdown-item" id="1" href="#">Administrators</a>
                <a class="dropdown-item" id="2" href="#">Permissioned Users</a>
                <a class="dropdown-item" id="3" href="#">Board Members</a>
                <a class="dropdown-item" id="4" href="#">Non-Member User</a>
                <a class="dropdown-item" id="5" href="#">Gratis</a>
                <a class="dropdown-item" id="6" href="#">Individual</a>
                <a class="dropdown-item" id="7" href="#">Retired</a>
                <a class="dropdown-item" id="8" href="#">Neighborhood Association</a>
                <a class="dropdown-item" id="9" href="#">Corporate 2</a>
                <a class="dropdown-item" id="10" href="#">Corporate 3</a>
                <a class="dropdown-item" id="11" href="#">Corporate Associate</a>
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

        <!-- This might be better as a select element or drop down checkboxes/radios with multi-select-->
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
                <a class="dropdown-item" id="12" href="#">All Membership</a> 
                <a class="dropdown-item" id="13" href="#">Active Membership</a>
                <a class="dropdown-item" id="14" href="#">Inactive Membership</a>   
            </div>
        </div>
        <h2>Reports & Utilities</h2>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="reports" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reports
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="reports">
                <a class="dropdown-item" id="15" href="#">Generate Annual Renewal Invoices</a>
                <a class="dropdown-item" id="16" href="#">Generate Paid Member List</a>
                <a class="dropdown-item" id="17" href="#">Generate Unpaid Member List</a>
                <a class="dropdown-item" id="18" href="#">Generate Mailing List</a>
                <a class="dropdown-item" id="19" href="#">Annual Membership Aging Report</a>
                <a class="dropdown-item" id="20" href="#">Member Roster Report</a>
                <a class="dropdown-item" id="21" href="#">Member Record Aging Report</a>
                <a class="dropdown-item" id="22" href="#">Member Record Missing Field Report</a>
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
            var parent = $(this).parent().attr('id');
            var id = this.id;
            //alert(parent);// troubleshooter
            // Call Js function to retrieve report
            functionRouter(parent, text, id);
        });
    </script>
    
    
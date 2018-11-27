
    <div id = "searchByForm">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByMemType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Membership Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Non-Member</a>
                <a class="dropdown-item" href="#">Gratis</a>
                <a class="dropdown-item" href="#">Individual</a>
                <a class="dropdown-item" href="#">Neighborhood</a>
                <a class="dropdown-item" href="#">Retired</a>
                <a class="dropdown-item" href="#">Corporate 2</a>
                <a class="dropdown-item" href="#">Corporate 3</a>
                <a class="dropdown-item" href="#">Associate</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Name
            </button>
            <div class= "dropdown-menu p-5" aria-labelledby="dropdownMenuButton">
                <input id="firstNameDrop" name="firstName" placeholder = "First Name" />
                <input id="lastNameDrop" name="lastName" placeholder = "Last Name" />
                <input type="submit" value="Submit" class = "btn btn-light" />
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByYear" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Year
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "SearchByYear">
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
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByPaid" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Current/Inactive
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "SearchByPaid">
                <a class="dropdown-item" href="#">Current Membership</a>
                <a class="dropdown-item" href="#">Inactive Membership</a>
            </div>
        </div>
    </div>

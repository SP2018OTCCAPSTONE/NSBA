
    <div id = "searchByForm">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByMemType" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Membership Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Corporate</a>
                <a class="dropdown-item" href="#">Individual</a>
                <a class="dropdown-item" href="#">Neighborhood</a>
                <a class="dropdown-item" href="#">Retired</a>
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
                <a class="dropdown-item" href="#">2016</a>
                <a class="dropdown-item" href="#">2017</a>
                <a class="dropdown-item" href="#">2018</a>
                <a class="dropdown-item" href="#">2019</a>
                <a class="dropdown-item" href="#">2020</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="SearchByPaid" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Search By Paid / Unpaid
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id = "SearchByPaid">
                <a class="dropdown-item" href="#">Paid</a>
                <a class="dropdown-item" href="#">Unpaid</a>
            </div>
        </div>
    </div>

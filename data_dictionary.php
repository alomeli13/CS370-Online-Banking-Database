<?php include('_header.php');?>
    <style>
        section {
            margin: 40px;
        }

        h2 {
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        dl {
            margin-top: 15px;
        }

        dt {
            font-weight: bold;
            margin-top: 10px;
        }

        dd {
            margin-left: 20px;
            color: #444;
        }
    </style>
    <body>

    <section>
        <h2>Branch</h2>
        <dl>
            <dt>BranchID</dt>
            <dd>The unique identifier for a branch. Auto-incrementing and not null.</dd>

            <dt>BranchName</dt>
            <dd>The name of a bank branch. Unique and not null.</dd>

            <dt>StreetAddress</dt>
            <dd>The physical location of the branch. Unique and not null.</dd>

            <dt>City</dt>
            <dd>The city where the branch is located. Not null.</dd>

            <dt>State</dt>
            <dd>2-character U.S. state code. Not null.</dd>

            <dt>ZipCode</dt>
            <dd>The zipcode of the branch location. Not null.</dd>

            <dt>PhoneNumber</dt>
            <dd>The branch phone number. Unique and not null.</dd>

            <dt>RoutingNumber</dt>
            <dd>9-character routing number. Unique and not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Customer</h2>
        <dl>
            <dt>CustomerID</dt>
            <dd>Unique identifier for a customer. Auto-incrementing and not null.</dd>

            <dt>Fname</dt>
            <dd>Customer's first name. Not null.</dd>

            <dt>Lname</dt>
            <dd>Customer's last name. Not null.</dd>

            <dt>Address</dt>
            <dd>Customer's home address. Not null.</dd>

            <dt>PhoneNumber</dt>
            <dd>Customer's phone number. Not null.</dd>

            <dt>Email</dt>
            <dd>Customer's email address. Unique and not null.</dd>

            <dt>Ssn</dt>
            <dd>9-character Social Security Number. Unique and not null.</dd>

            <dt>DateOfBirth</dt>
            <dd>Customer's date of birth. Not null.</dd>

        </dl>
    </section>

    <section>
        <h2>ATM</h2>
        <dl>
            <dt>ATMID</dt>
            <dd>Unique identifier for the ATM. Auto-incrementing and not null.</dd>

            <dt>StreetAddress</dt>
            <dd>ATM street address. Unique and not null.</dd>

            <dt>City</dt>
            <dd>City where the ATM is located. Not null.</dd>

            <dt>State</dt>
            <dd>2-character U.S. state code. Not null.</dd>

            <dt>ZipCode</dt>
            <dd>Zipcode of the ATM location. Not null.</dd>

            <dt>CurrentCash</dt>
            <dd>Amount of cash currently in the ATM. Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Currency</h2>
        <dl>
            <dt>CurrencyID</dt>
            <dd>Unique identifier for a currency. Auto-incrementing and not null.</dd>

            <dt>CurrencyCode</dt>
            <dd>Unique 3-character currency code. Not null.</dd>

            <dt>CurrencyName</dt>
            <dd>Name of the currency. Unique and not null.</dd>

            <dt>ExchangeRateToUSD</dt>
            <dd>Exchange rate relative to USD. Not null.</dd>

            <dt>Symbol</dt>
            <dd>Currency symbol.</dd>
        </dl>
    </section>

    <section>
        <h2>Account_Type</h2>
        <dl>
            <dt>AccountTypeID</dt>
            <dd>Unique identifier for an account type. Auto-incrementing and not null.</dd>

            <dt>TypeName</dt>
            <dd>Name of the account type (e.g., checking, savings). Unique and not null.</dd>

            <dt>InterestRate</dt>
            <dd>Interest rate for the account type. Not null.</dd>

            <dt>MonthlyFee</dt>
            <dd>Monthly maintenance fee. Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Transaction_Type</h2>
        <dl>
            <dt>TransactionTypeID</dt>
            <dd>Unique identifier for the transaction type. Auto-incrementing and not null.</dd>

            <dt>TypeName</dt>
            <dd>Name of the transaction type (e.g., deposit, withdrawal). Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Account</h2>
        <dl>
            <dt>AccountID</dt>
            <dd>Unique identifier for an account. Auto-incrementing and not null.</dd>

            <dt>CustomerID</dt>
            <dd>Foreign key referencing Customer.CustomerID. Not null.</dd>

            <dt>AccountTypeID</dt>
            <dd>Foreign key referencing Account_Type.AccountTypeID. Unique and not null.</dd>

            <dt>Balance</dt>
            <dd>Current account balance. Not null.</dd>

            <dt>InterestRate</dt>
            <dd>Interest rate for the account.</dd>

            <dt>DateOpened</dt>
            <dd>Timestamp when the account was opened. Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Loan</h2>
        <dl>
            <dt>LoanID</dt>
            <dd>Unique identifier for the loan. Auto-incrementing and not null.</dd>

            <dt>CustomerID</dt>
            <dd>Foreign key referencing Customer.CustomerID. Not null.</dd>

            <dt>BranchID</dt>
            <dd>Foreign key referencing Branch.BranchID. Not null.</dd>

            <dt>InterestRate</dt>
            <dd>Loan interest rate. Not null.</dd>

            <dt>Type</dt>
            <dd>Type of loan (e.g., mortgage, auto). Not null.</dd>

            <dt>TermMonths</dt>
            <dd>Loan repayment term in months. Not null.</dd>

            <dt>Amount</dt>
            <dd>Loan amount issued. Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Transaction</h2>
        <dl>
            <dt>TransactionID</dt>
            <dd>Unique identifier for the transaction. Auto-incrementing and not null.</dd>

            <dt>TransactionTypeID</dt>
            <dd>Foreign key referencing Transaction_Type.TransactionTypeID. Not null.</dd>

            <dt>AccountID</dt>
            <dd>Foreign key referencing Account.AccountID. Not null.</dd>

            <dt>CurrencyID</dt>
            <dd>Foreign key referencing Currency.CurrencyID. Not null.</dd>

            <dt>Amount</dt>
            <dd>Transaction amount. Not null.</dd>

            <dt>Date</dt>
            <dd>Timestamp of the transaction. Defaults to current timestamp. Not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Job_Title</h2>
        <dl>
            <dt>Job_TitleID</dt>
            <dd>Unique identifier for a job title. Auto-incrementing and not null.</dd>

            <dt>BranchID</dt>
            <dd>Foreign key referencing Branch.BranchID. Not null.</dd>

            <dt>Job_Description</dt>
            <dd>Description of the job.</dd>

        </dl>
    </section>

    <section>
        <h2>Employee</h2>
        <dl>
            <dt>EmployeeID</dt>
            <dd>Unique identifier for the employee. Auto-incrementing and not null.</dd>

            <dt>BranchID</dt>
            <dd>Foreign key referencing Branch.BranchID. Not null.</dd>

            <dt>Job_TitleID</dt>
            <dd>Foreign key referencing Job_Title.Job_TitleID.</dd>

            <dt>Fname</dt>
            <dd>Employee's first name. Not null.</dd>

            <dt>Lname</dt>
            <dd>Employee's last name. Not null.</dd>

            <dt>Salary</dt>
            <dd>Base salary for the job title.</dd>

            <dt>Essn</dt>
            <dd>Employee's Social Security Number. Unique and not null.</dd>
        </dl>
    </section>

    <section>
        <h2>Dependent</h2>
        <dl>
            <dt>DependentID</dt>
            <dd>Unique identifier for the dependent. Auto-incrementing and not null.</dd>

            <dt>EmployeeID</dt>
            <dd>Foreign key referencing Employee.EmployeeID. Not null.</dd>

            <dt>Fname</dt>
            <dd>Dependent's first name. Not null.</dd>

            <dt>Lname</dt>
            <dd>Dependent's last name. Not null.</dd>

        </dl>
    </section>

    </body>

<?php include('_footer.php');?>
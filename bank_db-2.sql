-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 17, 2026 at 05:43 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bank_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE `Account` (
  `AccountID` int NOT NULL,
  `CustomerID` int NOT NULL,
  `AccountTypeID` int NOT NULL,
  `Balance` decimal(15,2) NOT NULL,
  `InterestRate` decimal(5,4) DEFAULT NULL,
  `DateOpened` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Account_Type`
--

CREATE TABLE `Account_Type` (
  `AccountTypeID` int NOT NULL,
  `TypeName` varchar(50) NOT NULL,
  `InterestRate` decimal(5,4) NOT NULL,
  `MonthlyFee` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ATM`
--

CREATE TABLE `ATM` (
  `ATMID` int NOT NULL,
  `StreetAddress` varchar(250) NOT NULL,
  `City` varchar(100) NOT NULL,
  `State` char(2) NOT NULL,
  `ZipCode` varchar(10) NOT NULL,
  `CurrentCash` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Branch`
--

CREATE TABLE `Branch` (
  `BranchID` int NOT NULL,
  `BranchName` varchar(100) NOT NULL,
  `StreetAddress` varchar(250) NOT NULL,
  `City` varchar(100) NOT NULL,
  `State` char(2) NOT NULL,
  `ZipCode` varchar(10) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `RoutingNumber` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Currency`
--

CREATE TABLE `Currency` (
  `CurrencyID` int NOT NULL,
  `CurrencyCode` char(3) NOT NULL,
  `CurrencyName` varchar(50) NOT NULL,
  `ExchangeRateToUSD` decimal(15,6) NOT NULL,
  `Symbol` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `CustomerID` int NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Address` varchar(250) NOT NULL,
  `PhoneNumber` varchar(15) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Ssn` char(9) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `LastLogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Dependent`
--

CREATE TABLE `Dependent` (
  `DependentID` int NOT NULL,
  `EmployeeID` int NOT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Essn` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Employee`
--

CREATE TABLE `Employee` (
  `EmployeeID` int NOT NULL,
  `BranchID` int NOT NULL,
  `Job_TitleID` int DEFAULT NULL,
  `Fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `Essn` char(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Job_Title`
--

CREATE TABLE `Job_Title` (
  `Job_TitleID` int NOT NULL,
  `BranchID` int NOT NULL,
  `Job_Description` text,
  `Salary` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Loan`
--

CREATE TABLE `Loan` (
  `LoanID` int NOT NULL,
  `CustomerID` int NOT NULL,
  `BranchID` int NOT NULL,
  `InterestRate` decimal(5,4) NOT NULL,
  `LoanType` varchar(20) NOT NULL,
  `TermMonths` int NOT NULL,
  `Amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Transaction`
--

CREATE TABLE `Transaction` (
  `TransactionID` int NOT NULL,
  `AccountID` int NOT NULL,
  `CurrencyID` int NOT NULL,
  `TypeName` varchar(20) NOT NULL,
  `Amount` decimal(15,2) NOT NULL,
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Transaction_Type`
--

CREATE TABLE `Transaction_Type` (
  `TransactionTypeID` int NOT NULL,
  `TypeName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`AccountID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `AccountTypeID` (`AccountTypeID`);

--
-- Indexes for table `Account_Type`
--
ALTER TABLE `Account_Type`
  ADD PRIMARY KEY (`AccountTypeID`),
  ADD UNIQUE KEY `TypeName` (`TypeName`);

--
-- Indexes for table `ATM`
--
ALTER TABLE `ATM`
  ADD PRIMARY KEY (`ATMID`),
  ADD UNIQUE KEY `StreetAddress` (`StreetAddress`);

--
-- Indexes for table `Branch`
--
ALTER TABLE `Branch`
  ADD PRIMARY KEY (`BranchID`),
  ADD UNIQUE KEY `BranchName` (`BranchName`),
  ADD UNIQUE KEY `StreetAddress` (`StreetAddress`),
  ADD UNIQUE KEY `PhoneNumber` (`PhoneNumber`),
  ADD UNIQUE KEY `RoutingNumber` (`RoutingNumber`);

--
-- Indexes for table `Currency`
--
ALTER TABLE `Currency`
  ADD PRIMARY KEY (`CurrencyID`),
  ADD UNIQUE KEY `CurrencyCode` (`CurrencyCode`),
  ADD UNIQUE KEY `CurrencyName` (`CurrencyName`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Ssn` (`Ssn`);

--
-- Indexes for table `Dependent`
--
ALTER TABLE `Dependent`
  ADD PRIMARY KEY (`DependentID`),
  ADD UNIQUE KEY `Essn` (`Essn`),
  ADD KEY `EmployeeID` (`EmployeeID`);

--
-- Indexes for table `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Essn` (`Essn`),
  ADD KEY `BranchID` (`BranchID`),
  ADD KEY `Job_TitleID` (`Job_TitleID`);

--
-- Indexes for table `Job_Title`
--
ALTER TABLE `Job_Title`
  ADD PRIMARY KEY (`Job_TitleID`),
  ADD KEY `BranchID` (`BranchID`);

--
-- Indexes for table `Loan`
--
ALTER TABLE `Loan`
  ADD PRIMARY KEY (`LoanID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `BranchID` (`BranchID`);

--
-- Indexes for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD PRIMARY KEY (`TransactionID`),
  ADD KEY `AccountID` (`AccountID`),
  ADD KEY `CurrencyID` (`CurrencyID`),
  ADD KEY `TypeName` (`TypeName`);

--
-- Indexes for table `Transaction_Type`
--
ALTER TABLE `Transaction_Type`
  ADD PRIMARY KEY (`TransactionTypeID`),
  ADD UNIQUE KEY `TypeName` (`TypeName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account`
--
ALTER TABLE `Account`
  MODIFY `AccountID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Account_Type`
--
ALTER TABLE `Account_Type`
  MODIFY `AccountTypeID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ATM`
--
ALTER TABLE `ATM`
  MODIFY `ATMID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Branch`
--
ALTER TABLE `Branch`
  MODIFY `BranchID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Currency`
--
ALTER TABLE `Currency`
  MODIFY `CurrencyID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Customer`
--
ALTER TABLE `Customer`
  MODIFY `CustomerID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Dependent`
--
ALTER TABLE `Dependent`
  MODIFY `DependentID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Employee`
--
ALTER TABLE `Employee`
  MODIFY `EmployeeID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Job_Title`
--
ALTER TABLE `Job_Title`
  MODIFY `Job_TitleID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Loan`
--
ALTER TABLE `Loan`
  MODIFY `LoanID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Transaction`
--
ALTER TABLE `Transaction`
  MODIFY `TransactionID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Transaction_Type`
--
ALTER TABLE `Transaction_Type`
  MODIFY `TransactionTypeID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Account`
--
ALTER TABLE `Account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`),
  ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`AccountTypeID`) REFERENCES `Account_Type` (`AccountTypeID`);

--
-- Constraints for table `Dependent`
--
ALTER TABLE `Dependent`
  ADD CONSTRAINT `dependent_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `Employee` (`EmployeeID`);

--
-- Constraints for table `Employee`
--
ALTER TABLE `Employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`Job_TitleID`) REFERENCES `Job_Title` (`Job_TitleID`);

--
-- Constraints for table `Job_Title`
--
ALTER TABLE `Job_Title`
  ADD CONSTRAINT `job_title_ibfk_1` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`);

--
-- Constraints for table `Loan`
--
ALTER TABLE `Loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`),
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`);

--
-- Constraints for table `Transaction`
--
ALTER TABLE `Transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `Account` (`AccountID`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`CurrencyID`) REFERENCES `Currency` (`CurrencyID`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`TypeName`) REFERENCES `Transaction_Type` (`TypeName`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

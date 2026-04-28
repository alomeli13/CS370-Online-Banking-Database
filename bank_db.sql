-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 27, 2026 at 09:45 PM
-- Server version: 8.0.44
-- PHP Version: 8.3.30

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `bank_db`
CREATE DATABASE IF NOT EXISTS `bank_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `bank_db`;

-- 1. Account_Type
DROP TABLE IF EXISTS `Account_Type`;
CREATE TABLE `Account_Type` (
                                `AccountTypeID` int NOT NULL AUTO_INCREMENT,
                                `TypeName` varchar(50) NOT NULL,
                                `InterestRate` decimal(5,4) NOT NULL,
                                `MonthlyFee` decimal(10,2) NOT NULL,
                                PRIMARY KEY (`AccountTypeID`),
                                UNIQUE KEY `TypeName` (`TypeName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Customer
DROP TABLE IF EXISTS `Customer`;
CREATE TABLE `Customer` (
                            `CustomerID` int NOT NULL AUTO_INCREMENT,
                            `Fname` varchar(50) NOT NULL,
                            `Lname` varchar(50) NOT NULL,
                            `Address` varchar(250) NOT NULL,
                            `PhoneNumber` varchar(15) NOT NULL,
                            `Email` varchar(100) NOT NULL,
                            `Ssn` char(9) NOT NULL,
                            `DateOfBirth` date NOT NULL,
                            PRIMARY KEY (`CustomerID`),
                            UNIQUE KEY `Email` (`Email`),
                            UNIQUE KEY `Ssn` (`Ssn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Account
DROP TABLE IF EXISTS `Account`;
CREATE TABLE `Account` (
                           `AccountID` int NOT NULL AUTO_INCREMENT,
                           `CustomerID` int NOT NULL,
                           `AccountTypeID` int NOT NULL,
                           `Balance` decimal(15,2) NOT NULL,
                           `InterestRate` decimal(5,4) DEFAULT NULL,
                           `DateOpened` datetime NOT NULL,
                           PRIMARY KEY (`AccountID`),
                           CONSTRAINT `account_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`),
                           CONSTRAINT `account_ibfk_2` FOREIGN KEY (`AccountTypeID`) REFERENCES `Account_Type` (`AccountTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Branch
DROP TABLE IF EXISTS `Branch`;
CREATE TABLE `Branch` (
                          `BranchID` int NOT NULL AUTO_INCREMENT,
                          `BranchName` varchar(100) NOT NULL,
                          `StreetAddress` varchar(250) NOT NULL,
                          `City` varchar(100) NOT NULL,
                          `State` char(2) NOT NULL,
                          `ZipCode` varchar(10) NOT NULL,
                          `PhoneNumber` varchar(15) NOT NULL,
                          `RoutingNumber` char(9) NOT NULL,
                          PRIMARY KEY (`BranchID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Job_Title
DROP TABLE IF EXISTS `Job_Title`;
CREATE TABLE `Job_Title` (
                             `Job_TitleID` int NOT NULL AUTO_INCREMENT,
                             `BranchID` int NOT NULL,
                             `Job_Description` text,
                             `Salary` decimal(15,2) DEFAULT NULL,
                             PRIMARY KEY (`Job_TitleID`),
                             CONSTRAINT `job_title_ibfk_1` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Employee
DROP TABLE IF EXISTS `Employee`;
CREATE TABLE `Employee` (
                            `EmployeeID` int NOT NULL AUTO_INCREMENT,
                            `BranchID` int NOT NULL,
                            `Job_TitleID` int DEFAULT NULL,
                            `Fname` varchar(50) NOT NULL,
                            `Lname` varchar(50) NOT NULL,
                            `Essn` char(9) NOT NULL,
                            PRIMARY KEY (`EmployeeID`),
                            UNIQUE KEY `Essn` (`Essn`),
                            CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`BranchID`) REFERENCES `Branch` (`BranchID`),
                            CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`Job_TitleID`) REFERENCES `Job_Title` (`Job_TitleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Dependent (Essn Removed as requested)
DROP TABLE IF EXISTS `Dependent`;
CREATE TABLE `Dependent` (
                             `DependentID` int NOT NULL AUTO_INCREMENT,
                             `EmployeeID` int NOT NULL,
                             `Fname` varchar(50) NOT NULL,
                             `Lname` varchar(50) NOT NULL,
                             PRIMARY KEY (`DependentID`),
                             CONSTRAINT `dependent_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `Employee` (`EmployeeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Transaction_Type
DROP TABLE IF EXISTS `Transaction_Type`;
CREATE TABLE `Transaction_Type` (
                                    `TransactionTypeID` int NOT NULL AUTO_INCREMENT,
                                    `TypeName` varchar(20) NOT NULL,
                                    PRIMARY KEY (`TransactionTypeID`),
                                    UNIQUE KEY `TypeName` (`TypeName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Transaction
DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
                               `TransactionID` int NOT NULL AUTO_INCREMENT,
                               `AccountID` int NOT NULL,
                               `TransactionTypeID` int NOT NULL,
                               `Amount` decimal(15,2) NOT NULL,
                               `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                               PRIMARY KEY (`TransactionID`),
                               CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`AccountID`) REFERENCES `Account` (`AccountID`),
                               CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`TransactionTypeID`) REFERENCES `Transaction_Type` (`TransactionTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
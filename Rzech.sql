-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Lis 09, 2024 at 01:41 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Rzech`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Ad`
--

CREATE TABLE `Ad` (
  `adID` int(11) NOT NULL,
  `adOwner` int(11) NOT NULL,
  `brandmodelID` int(11) NOT NULL,
  `version` varchar(255) DEFAULT NULL,
  `productionDate` year(4) NOT NULL,
  `mileage` int(11) NOT NULL,
  `engineDisplacement` smallint(6) NOT NULL,
  `fuel` smallint(6) NOT NULL,
  `enginePower` smallint(6) NOT NULL,
  `picture` mediumblob NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `priceNegotiable` tinyint(1) NOT NULL,
  `adStatus` tinyint(1) NOT NULL,
  `blockStatus` tinyint(1) NOT NULL,
  `detailsID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `AdDetails`
--

CREATE TABLE `AdDetails` (
  `adDetailsID` int(11) NOT NULL,
  `VIN` char(17) NOT NULL,
  `gearbox` smallint(6) NOT NULL,
  `drivetrain` smallint(6) NOT NULL,
  `bodyType` smallint(6) NOT NULL,
  `wheel` smallint(6) NOT NULL,
  `description` text NOT NULL,
  `videoYT` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Admin`
--

CREATE TABLE `Admin` (
  `adminID` int(11) NOT NULL,
  `adminLogin` varchar(25) NOT NULL,
  `adminPasswordHash` varchar(128) NOT NULL,
  `adminSalt` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Advertiser`
--

CREATE TABLE `Advertiser` (
  `userID` int(11) NOT NULL,
  `login` varchar(25) NOT NULL,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `accountType` tinyint(1) NOT NULL,
  `location` varchar(60) NOT NULL,
  `phone` char(9) NOT NULL,
  `email` varchar(255) NOT NULL,
  `registrationDate` date DEFAULT NULL,
  `blockadeStatus` tinyint(1) DEFAULT NULL,
  `passwordHash` varchar(128) NOT NULL,
  `userSalt` char(10) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `BodyType`
--

CREATE TABLE `BodyType` (
  `bodyTypeID` smallint(6) NOT NULL,
  `bodyTypeName` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `BrandModel`
--

CREATE TABLE `BrandModel` (
  `IDBrandModel` int(11) NOT NULL,
  `brand` varchar(30) NOT NULL,
  `model` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Drivetrain`
--

CREATE TABLE `Drivetrain` (
  `drivetrainID` smallint(6) NOT NULL,
  `drivertrainName` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Fuel`
--

CREATE TABLE `Fuel` (
  `fuelID` smallint(6) NOT NULL,
  `fuelName` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Gearbox`
--

CREATE TABLE `Gearbox` (
  `gearboxID` smallint(6) NOT NULL,
  `gearboxName` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Report`
--

CREATE TABLE `Report` (
  `reportID` int(11) NOT NULL,
  `adID` int(11) NOT NULL,
  `reportEmail` varchar(255) NOT NULL,
  `reportDetails` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ReportDetails`
--

CREATE TABLE `ReportDetails` (
  `reportDetailsID` int(11) NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Wheel`
--

CREATE TABLE `Wheel` (
  `wheelID` smallint(6) NOT NULL,
  `wheelName` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `Ad`
--
ALTER TABLE `Ad`
  ADD PRIMARY KEY (`adID`),
  ADD UNIQUE KEY `UNIQUE_adDetails` (`detailsID`),
  ADD KEY `FK_brandmodel` (`brandmodelID`),
  ADD KEY `FK_fuel` (`fuel`),
  ADD KEY `FK_Owner` (`adOwner`);

--
-- Indeksy dla tabeli `AdDetails`
--
ALTER TABLE `AdDetails`
  ADD PRIMARY KEY (`adDetailsID`),
  ADD UNIQUE KEY `VIN` (`VIN`),
  ADD KEY `FK_gearbox` (`gearbox`),
  ADD KEY `FK_drivetrain` (`drivetrain`),
  ADD KEY `FK_bodytype` (`bodyType`),
  ADD KEY `FK_wheel` (`wheel`);

--
-- Indeksy dla tabeli `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`adminID`),
  ADD UNIQUE KEY `adminLogin` (`adminLogin`);

--
-- Indeksy dla tabeli `Advertiser`
--
ALTER TABLE `Advertiser`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `BodyType`
--
ALTER TABLE `BodyType`
  ADD PRIMARY KEY (`bodyTypeID`),
  ADD UNIQUE KEY `bodyTypeName` (`bodyTypeName`);

--
-- Indeksy dla tabeli `BrandModel`
--
ALTER TABLE `BrandModel`
  ADD PRIMARY KEY (`IDBrandModel`),
  ADD UNIQUE KEY `UNIQUE_BrandModel` (`brand`,`model`);

--
-- Indeksy dla tabeli `Drivetrain`
--
ALTER TABLE `Drivetrain`
  ADD PRIMARY KEY (`drivetrainID`),
  ADD UNIQUE KEY `drivertrainName` (`drivertrainName`);

--
-- Indeksy dla tabeli `Fuel`
--
ALTER TABLE `Fuel`
  ADD PRIMARY KEY (`fuelID`),
  ADD UNIQUE KEY `fuelName` (`fuelName`);

--
-- Indeksy dla tabeli `Gearbox`
--
ALTER TABLE `Gearbox`
  ADD PRIMARY KEY (`gearboxID`),
  ADD UNIQUE KEY `gearboxName` (`gearboxName`);

--
-- Indeksy dla tabeli `Report`
--
ALTER TABLE `Report`
  ADD PRIMARY KEY (`reportID`),
  ADD UNIQUE KEY `UNIQUE_reportDetails` (`reportDetails`),
  ADD KEY `FK_adID` (`adID`);

--
-- Indeksy dla tabeli `ReportDetails`
--
ALTER TABLE `ReportDetails`
  ADD PRIMARY KEY (`reportDetailsID`);

--
-- Indeksy dla tabeli `Wheel`
--
ALTER TABLE `Wheel`
  ADD PRIMARY KEY (`wheelID`),
  ADD UNIQUE KEY `wheelName` (`wheelName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `adminID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Advertiser`
--
ALTER TABLE `Advertiser`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Ad`
--
ALTER TABLE `Ad`
  ADD CONSTRAINT `FK_Owner` FOREIGN KEY (`adOwner`) REFERENCES `Advertiser` (`userID`),
  ADD CONSTRAINT `FK_adDetails` FOREIGN KEY (`detailsID`) REFERENCES `AdDetails` (`adDetailsID`),
  ADD CONSTRAINT `FK_brandmodel` FOREIGN KEY (`brandmodelID`) REFERENCES `BrandModel` (`IDBrandModel`),
  ADD CONSTRAINT `FK_fuel` FOREIGN KEY (`fuel`) REFERENCES `Fuel` (`fuelID`);

--
-- Constraints for table `AdDetails`
--
ALTER TABLE `AdDetails`
  ADD CONSTRAINT `FK_bodytype` FOREIGN KEY (`bodyType`) REFERENCES `BodyType` (`bodyTypeID`),
  ADD CONSTRAINT `FK_drivetrain` FOREIGN KEY (`drivetrain`) REFERENCES `Drivetrain` (`drivetrainID`),
  ADD CONSTRAINT `FK_gearbox` FOREIGN KEY (`gearbox`) REFERENCES `Gearbox` (`gearboxID`),
  ADD CONSTRAINT `FK_wheel` FOREIGN KEY (`wheel`) REFERENCES `Wheel` (`wheelID`);

--
-- Constraints for table `Report`
--
ALTER TABLE `Report`
  ADD CONSTRAINT `FK_adID` FOREIGN KEY (`adID`) REFERENCES `Ad` (`adID`),
  ADD CONSTRAINT `FK_reportDetails` FOREIGN KEY (`reportDetails`) REFERENCES `ReportDetails` (`reportDetailsID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

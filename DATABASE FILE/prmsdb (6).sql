-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2024 at 07:43 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` varchar(20) NOT NULL,
  `pass` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `pass`) VALUES
('admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `consultation`
--

CREATE TABLE `consultation` (
  `Patient_SSN` varchar(20) NOT NULL,
  `Doctor_SSN` varchar(20) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `PatientName` longtext NOT NULL,
  `Complications` longtext NOT NULL,
  `Medicines` longtext NOT NULL,
  `Description1` longtext NOT NULL,
  `Treatments` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `consultation`
--

INSERT INTO `consultation` (`Patient_SSN`, `Doctor_SSN`, `Date_Time`, `PatientName`, `Complications`, `Medicines`, `Description1`, `Treatments`) VALUES
('010101011233', '010203051336', '2024-02-04 09:30:00', '', 'depression', 'typical antidepressants', 'no other symptoms, but patients seem much better than the other day', 'cognitive behavioral tratment (CBT)'),
('010101011233', '010203051336', '2024-02-06 17:30:00', '', 'depression', 'antidepression', 'patients having difficulty to express his feelings ', 'consultation '),
('010101011233', '010203051336', '2024-02-16 09:15:00', '', 'depression', 'antidepressants', 'patient nervous', 'consultation'),
('010101011233', '010203051336', '2024-02-23 20:20:00', '', 'anxiety', 'Paroxetine', 'patient seems like having a difficulty to open up about her family', 'psychotherapy'),
('011112035042', '010203051336', '2024-02-04 12:30:00', '', 'anxiety', 'benzodiazepine', 'no changes since the previous visit, need to reconsult to the counselling ', 'consultation'),
('011112035042', '020930110336', '2024-02-03 09:15:00', '', 'test', 'test', 'test', 'test'),
('011112035042', '951115122885', '2023-12-16 11:10:10', '', 'test', 'test', 'test', 'test'),
('011112035042', '951115122885', '2023-12-18 10:19:59', '', 'test', 'test', 'test', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE `diagnosis` (
  `Patient_SSN` varchar(20) NOT NULL,
  `Doctor_SSN` varchar(20) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `Diagnosis_Name` varchar(25) NOT NULL,
  `Description` longtext NOT NULL,
  `Complications` longtext DEFAULT NULL,
  `Allergies` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`Patient_SSN`, `Doctor_SSN`, `Date_Time`, `Diagnosis_Name`, `Description`, `Complications`, `Allergies`) VALUES
('020202021235', '951115122885', '2021-04-08 12:15:00', 'Ineffective Breathing Pat', 'Related to decreased lung expansion; evidenced by dyspnea, coughing', 'Risk for Ineffective Airway Clearance; evidenced by accumulation of secretions in lungs', '-'),
('991231141231', '010203051336', '2023-12-03 23:49:00', '', 'test1', 'test1', 'test1'),
('991231141231', '020930110336', '2020-03-19 15:00:00', 'CT Scan', 'Found tumor in the left lobe', '-', '-'),
('991231141231', '951115122885', '2019-12-26 15:00:00', 'Angiogram', 'Blocked artery', '-', '-'),
('991231141231', '951115122885', '2023-12-27 11:00:00', '', 'test1', 'test2', '');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `SSN` varchar(20) NOT NULL,
  `F_Name` char(15) NOT NULL,
  `L_Name` char(15) NOT NULL,
  `Hospital_ID` varchar(25) NOT NULL,
  `Address` varchar(30) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Department` char(35) NOT NULL,
  `Speciality` varchar(30) NOT NULL,
  `Designation` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`SSN`, `F_Name`, `L_Name`, `Hospital_ID`, `Address`, `Contact_No`, `Email`, `Department`, `Speciality`, `Designation`) VALUES
('000102101115', 'Dr. Yuzana', 'Mohd Yusop', 'MDCR69', '747  Aviation Way', '4 09-6275504', 'yuzanayusop', 'Department of Psychiatry', 'Adult Psychiatry', 'Attending Psychiatrist'),
('010203051336', 'Dr. Rosliza', 'Binti Yahaya', 'LCGH96', 'Kuala Terengganu', '4 09-6275527', 'roslizayahaya@gmail.com', 'Counselling and  Psychotherapy', 'Psychiatry', 'Addiction Psychiatrist'),
('020930110336', 'Dr. Hanisah', 'Mohd Noor', 'NHCH22', 'Kuala Terengganu', '3479998885', 'hanisahmnoor@gmail.com', 'Integrative Mental Health', 'Psychosomatic Medicine', 'Medicine Physician'),
('921008071991', 'Prof. Madya Dr.', 'Husain', 'GRND0', 'Kuala Terengganu', '409-6275658', ' rohayahi@gmail.com', 'Pensyarah Perubatan Gred Khas C', 'Psychiatry', 'Counselling and  Psychoth'),
('940720011353', 'Dr. Hj. Khairi', 'Che Mat', 'NHCH22', 'Kuala Terengganu', '09-6275621', 'khairicm@gmail.com', ' Pensyarah Perubatan Gred Khas C', 'Psychiatry', 'Mental Health'),
('951115122885', 'Dr. Ismawati', 'Ismail', 'GRND0', 'Kuala Terengganu', '2545454545', 'ismawatiismail@gmail.com', 'Pensyarah Perubatan Gred Khas C', ' Emergency Medicine', 'Medicine');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_login`
--

CREATE TABLE `doctor_login` (
  `d_ssn` varchar(20) NOT NULL,
  `pass` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor_login`
--

INSERT INTO `doctor_login` (`d_ssn`, `pass`) VALUES
('000102101115', 'password'),
('010203051336', 'Password@1234'),
('020930110336', 'password'),
('921008071991', 'password'),
('940720011353', 'password'),
('951115122885', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `ID` varchar(25) NOT NULL,
  `Email` varchar(20) NOT NULL,
  `Address` varchar(30) NOT NULL,
  `name` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`ID`, `Email`, `Address`, `name`) VALUES
('GRND0', 'info@grnd.com', '4505  Briarhill Lane', 'Grandee Hospital'),
('LCGH96', 'info@lcgh.com', '1439  Despard Street', 'Lifecare General Hospital'),
('MDCR69', 'info@mdcr.com', '321  Trainer Avenue', 'Medwin Cares'),
('NHCH22', 'info@nhch.com', '658  Woodstock Drive', 'New Horizons Community Hospital'),
('WVHP6', 'info@wvhp.com', '321  Trainer Avenue', 'West Valley Hospital');

-- --------------------------------------------------------

--
-- Table structure for table `medical_administration`
--

CREATE TABLE `medical_administration` (
  `Patient_SSN` varchar(20) NOT NULL,
  `Doctor_SSN` varchar(20) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `Description` longtext NOT NULL,
  `Complication` longtext DEFAULT NULL,
  `Medicine` longtext DEFAULT NULL,
  `Allergies` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medical_administration`
--

INSERT INTO `medical_administration` (`Patient_SSN`, `Doctor_SSN`, `Date_Time`, `Description`, `Complication`, `Medicine`, `Allergies`) VALUES
('020202021235', '', '2020-01-17 10:35:00', 'Possible cardiac arrest', 'Shortness of Breath,', 'Xanax 12 mg', ''),
('030303031234', '010203051336', '2024-02-04 07:06:00', 'patients start to develop another character after getting abused by her biological father', 'bipolar disorder', 'lithium', 'no allergies'),
('010101011233', '010203051336', '2024-02-04 07:10:29', 'patient been having a mood swings lately \r\nsleep patterns are disrupted', 'hallmark symptoms ', 'antipsychotics', 'penicillin'),
('010101011233', '010203051336', '2024-02-04 10:02:18', 'patients having difficulties to sleep', 'anxiety', 'antidepressants', 'no allergies');

-- --------------------------------------------------------

--
-- Table structure for table `medical_staff`
--

CREATE TABLE `medical_staff` (
  `SSN` varchar(20) NOT NULL,
  `F_Name` char(15) NOT NULL,
  `L_Name` char(15) NOT NULL,
  `Hospital_ID` varchar(25) NOT NULL,
  `Department` char(35) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Designation` varchar(25) NOT NULL,
  `Address` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medical_staff`
--

INSERT INTO `medical_staff` (`SSN`, `F_Name`, `L_Name`, `Hospital_ID`, `Department`, `Contact_No`, `Email`, `Designation`, `Address`) VALUES
('021108110280', 'Hanis', 'Nabilaa', 'GRND0', 'Paramedics', '720001569', 'Hanisnabila@gmail.com', 'Child Psychiatrist', '2770  Union Street'),
('920815101234', 'Haris', 'Zainal', 'GRND0', 'Department of Nurses', '9745111111', 'Haris00@gmail.com', 'Addiction Psychiatrist', '2103  Washington Avenue');

-- --------------------------------------------------------

--
-- Table structure for table `operation`
--

CREATE TABLE `operation` (
  `Patient_SSN` varchar(20) NOT NULL,
  `Doctor_SSN` varchar(20) NOT NULL,
  `Date_Time` datetime NOT NULL,
  `Description` longtext NOT NULL,
  `Complications` longtext DEFAULT NULL,
  `Allergies` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `operation`
--

INSERT INTO `operation` (`Patient_SSN`, `Doctor_SSN`, `Date_Time`, `Description`, `Complications`, `Allergies`) VALUES
('020202021235', '951115122885', '2020-01-30 10:00:00', 'Bypass Surgery', '-', '-'),
('991231141231', '951115122885', '2020-02-12 14:26:00', 'Artery Block Remove', 'Vein cut in the left', 'Reactive to sedatives');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `SSN` varchar(20) NOT NULL,
  `F_Name` char(15) NOT NULL,
  `L_Name` char(15) NOT NULL,
  `Address` varchar(30) NOT NULL,
  `Contact_No` varchar(20) NOT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Gender` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`SSN`, `F_Name`, `L_Name`, `Address`, `Contact_No`, `Email`, `Date_Of_Birth`, `Gender`) VALUES
('010101011233', 'Hayden', 'Noah', '', '', '', '1997-03-01', 'Male'),
('010327035532', 'Anis', 'Suhailah', '203 Gong Medang', '4578884500', 'Nisu@gmail.com', '1991-07-26', 'Female'),
('011112035042', 'jasmine', 'alia', 'guntong', '0134567898', 'aliajasmine@gmail.com', '1996-05-21', 'female'),
('020202021235', 'usamah', 'Zaini', '2770  Taman Equin', '3478885540', 'usamah@gmail.com', '1991-10-11', 'Male'),
('030303031234', 'farisha', 'izati', '3025 Berek 12', '7548964555', 'syazati@gmail.com', '1981-08-12', 'female'),
('961120112128', 'Lily', 'Amira', '17 Jalan Raya, 21200 Kuala Ter', '0142019876', 'lily@gmail.com', '1996-11-20', 'Female'),
('991020115878', 'Amar', 'Alif', '17 Jalan Raya, 21200 Kuala Ter', '0123415678', 'amar@gmail.com', '1933-02-16', 'Male'),
('991231141231', 'Saef', 'Izzaril', '1090 Taman Koporat', '1597534568', 'Saef@gmail.com', '1992-10-14', 'Male'),
('998629110273', 'alia', 'lia', '17 Jalan raya', '01342751862', 'alia@gmail.com', '2024-02-23', 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `patient_login`
--

CREATE TABLE `patient_login` (
  `p_ssn` varchar(20) NOT NULL,
  `pass` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_login`
--

INSERT INTO `patient_login` (`p_ssn`, `pass`) VALUES
('010101011233', 'password'),
('010327035532', 'password'),
('011112035042', 'password'),
('020202021235', 'password'),
('030303031234', 'password'),
('961120112128', '123'),
('991020115878', '123'),
('991231141231', 'password'),
('998629110273', '123');

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire_responses`
--

CREATE TABLE `questionnaire_responses` (
  `Patient_SSN` varchar(20) NOT NULL,
  `q1` varchar(100) NOT NULL,
  `q2` varchar(100) NOT NULL,
  `q3` varchar(100) NOT NULL,
  `q4` varchar(100) NOT NULL,
  `q5` varchar(100) NOT NULL,
  `q6` varchar(100) NOT NULL,
  `q7` varchar(100) NOT NULL,
  `q8` varchar(100) NOT NULL,
  `q9` varchar(100) NOT NULL,
  `q10` varchar(100) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questionnaire_responses`
--

INSERT INTO `questionnaire_responses` (`Patient_SSN`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `score`) VALUES
('010101011233', 'very-well', 'yes', 'yes', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 12),
('010101011233', 'very-well', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 12),
('010101011233', 'very-well', 'no', 'yes', 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'no', 6),
('010101011233', 'very-well', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'no', 'no', 4),
('010101011233', 'very-well', 'yes', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'no', 'no', 4),
('010101011233', 'very-well', 'no', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'no', 'no', 2),
('010101011233', 'very-well', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 14),
('010101011233', 'very-well', 'no', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'no', 'no', 2),
('010101011233', 'very-well', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 14);

-- --------------------------------------------------------

--
-- Table structure for table `staff_login`
--

CREATE TABLE `staff_login` (
  `s_ssn` varchar(20) NOT NULL,
  `pass` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff_login`
--

INSERT INTO `staff_login` (`s_ssn`, `pass`) VALUES
('021108110280', 'password'),
('920815101234', 'password');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`Patient_SSN`,`Doctor_SSN`,`Date_Time`),
  ADD KEY `DoctorSSN_FK` (`Doctor_SSN`);

--
-- Indexes for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD PRIMARY KEY (`Patient_SSN`,`Doctor_SSN`,`Date_Time`),
  ADD KEY `diagnosis_ibfk_2` (`Doctor_SSN`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`SSN`),
  ADD KEY `Hospital_ID` (`Hospital_ID`);

--
-- Indexes for table `doctor_login`
--
ALTER TABLE `doctor_login`
  ADD PRIMARY KEY (`d_ssn`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medical_administration`
--
ALTER TABLE `medical_administration`
  ADD UNIQUE KEY `Date_Time` (`Date_Time`),
  ADD KEY `Patient_SSN_2` (`Patient_SSN`);

--
-- Indexes for table `medical_staff`
--
ALTER TABLE `medical_staff`
  ADD PRIMARY KEY (`SSN`),
  ADD KEY `Hospital_ID` (`Hospital_ID`),
  ADD KEY `SSN` (`SSN`);

--
-- Indexes for table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`Patient_SSN`,`Doctor_SSN`,`Date_Time`),
  ADD KEY `operation_ibfk_2` (`Doctor_SSN`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`SSN`),
  ADD UNIQUE KEY `SSN` (`SSN`),
  ADD UNIQUE KEY `SSN_2` (`SSN`),
  ADD KEY `SSN_3` (`SSN`);

--
-- Indexes for table `patient_login`
--
ALTER TABLE `patient_login`
  ADD PRIMARY KEY (`p_ssn`);

--
-- Indexes for table `questionnaire_responses`
--
ALTER TABLE `questionnaire_responses`
  ADD KEY `Patient_SSN` (`Patient_SSN`);

--
-- Indexes for table `staff_login`
--
ALTER TABLE `staff_login`
  ADD PRIMARY KEY (`s_ssn`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `DoctorSSN_FK` FOREIGN KEY (`Doctor_SSN`) REFERENCES `doctor` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PatientSSN_FK` FOREIGN KEY (`Patient_SSN`) REFERENCES `patient` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `diagnosis`
--
ALTER TABLE `diagnosis`
  ADD CONSTRAINT `diagnosis_ibfk_1` FOREIGN KEY (`Patient_SSN`) REFERENCES `patient` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `diagnosis_ibfk_2` FOREIGN KEY (`Doctor_SSN`) REFERENCES `doctor` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`Hospital_ID`) REFERENCES `hospital` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `doctor_login`
--
ALTER TABLE `doctor_login`
  ADD CONSTRAINT `doctor_login_ibfk_1` FOREIGN KEY (`d_ssn`) REFERENCES `doctor` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_administration`
--
ALTER TABLE `medical_administration`
  ADD CONSTRAINT `medical_administration_ibfk_1` FOREIGN KEY (`Patient_SSN`) REFERENCES `patient` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `medical_staff`
--
ALTER TABLE `medical_staff`
  ADD CONSTRAINT `medical_staff_ibfk_1` FOREIGN KEY (`Hospital_ID`) REFERENCES `hospital` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `operation_ibfk_1` FOREIGN KEY (`Patient_SSN`) REFERENCES `patient` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `operation_ibfk_2` FOREIGN KEY (`Doctor_SSN`) REFERENCES `doctor` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `patient_login`
--
ALTER TABLE `patient_login`
  ADD CONSTRAINT `fk_pssn` FOREIGN KEY (`p_ssn`) REFERENCES `patient` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `staff_login`
--
ALTER TABLE `staff_login`
  ADD CONSTRAINT `staff_login_ibfk_1` FOREIGN KEY (`s_ssn`) REFERENCES `medical_staff` (`SSN`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

/*
Navicat MySQL Data Transfer

Source Server         : Acess Solutions
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : oliviet_college

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2020-02-12 08:34:18
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `course_registeration`
-- ----------------------------
DROP TABLE IF EXISTS `course_registeration`;
CREATE TABLE `course_registeration` (
  `course_id` varchar(50) NOT NULL,
  `course_reg_id` varchar(50) NOT NULL,
  `students_id` varchar(50) NOT NULL,
  `is_elective` int(1) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  PRIMARY KEY (`course_reg_id`),
  KEY `course_reg_id` (`course_reg_id`),
  KEY `student` (`students_id`),
  CONSTRAINT `student` FOREIGN KEY (`students_id`) REFERENCES `student_information` (`student_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_registeration
-- ----------------------------

-- ----------------------------
-- Table structure for `course_setup`
-- ----------------------------
DROP TABLE IF EXISTS `course_setup`;
CREATE TABLE `course_setup` (
  `course_id` varchar(80) NOT NULL,
  `department_id` varchar(80) NOT NULL,
  `course_name` varchar(80) NOT NULL,
  `course_lecturer` varchar(80) NOT NULL,
  `course_unit` int(1) NOT NULL,
  PRIMARY KEY (`course_id`),
  KEY `course lec` (`course_lecturer`),
  KEY `courses` (`department_id`),
  CONSTRAINT `course lec` FOREIGN KEY (`course_lecturer`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `courses` FOREIGN KEY (`department_id`) REFERENCES `department_setup` (`dapartment_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of course_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `department_setup`
-- ----------------------------
DROP TABLE IF EXISTS `department_setup`;
CREATE TABLE `department_setup` (
  `dapartment_id` varchar(80) NOT NULL,
  `department_name` varchar(80) NOT NULL,
  `established` year(4) NOT NULL,
  `faculty_code` varchar(80) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(8) NOT NULL,
  `depaartment_head` varchar(80) NOT NULL,
  PRIMARY KEY (`dapartment_id`),
  KEY `department` (`faculty_code`),
  KEY `department head` (`depaartment_head`),
  CONSTRAINT `department` FOREIGN KEY (`faculty_code`) REFERENCES `faculty_setup` (`faculty_id`) ON DELETE CASCADE,
  CONSTRAINT `department head` FOREIGN KEY (`depaartment_head`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of department_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `departmental_course_setup`
-- ----------------------------
DROP TABLE IF EXISTS `departmental_course_setup`;
CREATE TABLE `departmental_course_setup` (
  `dept_code` varchar(50) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `semester_id` varchar(50) NOT NULL,
  `dept_course_id` varchar(50) NOT NULL,
  `created_by` date NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  PRIMARY KEY (`dept_course_id`),
  KEY `course code` (`course_code`),
  KEY `dept` (`dept_code`),
  KEY `level` (`level`),
  KEY `semester` (`semester_id`),
  KEY `session` (`session_id`),
  CONSTRAINT `course code` FOREIGN KEY (`course_code`) REFERENCES `course_setup` (`course_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `dept` FOREIGN KEY (`dept_code`) REFERENCES `department_setup` (`dapartment_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `level` FOREIGN KEY (`level`) REFERENCES `level_setup` (`level_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `semester` FOREIGN KEY (`semester_id`) REFERENCES `semester_setup` (`semester_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `session` FOREIGN KEY (`session_id`) REFERENCES `session_setup` (`session_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of departmental_course_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `departmental_head_table1`
-- ----------------------------
DROP TABLE IF EXISTS `departmental_head_table1`;
CREATE TABLE `departmental_head_table1` (
  `dept_head_id` varchar(80) NOT NULL,
  `department` varchar(50) NOT NULL,
  `start_session` year(4) NOT NULL,
  `end_session` year(4) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` date NOT NULL,
  PRIMARY KEY (`dept_head_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of departmental_head_table1
-- ----------------------------

-- ----------------------------
-- Table structure for `employment_status`
-- ----------------------------
DROP TABLE IF EXISTS `employment_status`;
CREATE TABLE `employment_status` (
  `full_time` varchar(50) NOT NULL,
  `part_time` varchar(50) NOT NULL,
  `contract` varchar(50) NOT NULL,
  `employment_id` varchar(255) NOT NULL,
  PRIMARY KEY (`employment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of employment_status
-- ----------------------------

-- ----------------------------
-- Table structure for `faculty_head_table1`
-- ----------------------------
DROP TABLE IF EXISTS `faculty_head_table1`;
CREATE TABLE `faculty_head_table1` (
  `faculty_head_id` varchar(80) NOT NULL,
  `faculty_name` varchar(80) NOT NULL,
  `start_session` year(4) NOT NULL,
  `end_session` year(4) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` date NOT NULL,
  PRIMARY KEY (`faculty_head_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of faculty_head_table1
-- ----------------------------

-- ----------------------------
-- Table structure for `faculty_setup`
-- ----------------------------
DROP TABLE IF EXISTS `faculty_setup`;
CREATE TABLE `faculty_setup` (
  `faculty_id` varchar(80) NOT NULL,
  `faculty_head` varchar(80) NOT NULL,
  `faculty_established` year(4) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(80) NOT NULL,
  PRIMARY KEY (`faculty_id`),
  KEY `faculty_head` (`faculty_head`),
  CONSTRAINT `faculty_head` FOREIGN KEY (`faculty_head`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of faculty_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `fees_information`
-- ----------------------------
DROP TABLE IF EXISTS `fees_information`;
CREATE TABLE `fees_information` (
  `fees_id` varchar(50) NOT NULL,
  `fess_type` varchar(50) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `amount` int(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `dept` varchar(50) NOT NULL,
  PRIMARY KEY (`fees_id`),
  KEY `fees type` (`fess_type`),
  KEY `session1` (`session_id`),
  CONSTRAINT `fees type` FOREIGN KEY (`fess_type`) REFERENCES `fees_information` (`fees_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `session1` FOREIGN KEY (`session_id`) REFERENCES `session_setup` (`session_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of fees_information
-- ----------------------------

-- ----------------------------
-- Table structure for `level_setup`
-- ----------------------------
DROP TABLE IF EXISTS `level_setup`;
CREATE TABLE `level_setup` (
  `level_id` varchar(80) NOT NULL,
  `level_name` varchar(80) NOT NULL,
  `created_by` date NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of level_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `refere_ information`
-- ----------------------------
DROP TABLE IF EXISTS `refere_ information`;
CREATE TABLE `refere_ information` (
  `student_id` varchar(50) NOT NULL,
  `referee_name` varchar(50) NOT NULL,
  `referee_phone` varchar(11) NOT NULL,
  `referee_email` varchar(50) NOT NULL,
  `referee_address` varchar(50) NOT NULL,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of refere_ information
-- ----------------------------

-- ----------------------------
-- Table structure for `school_information`
-- ----------------------------
DROP TABLE IF EXISTS `school_information`;
CREATE TABLE `school_information` (
  `School_name` varchar(100) NOT NULL,
  `school_address` varchar(200) NOT NULL,
  `school_logo` varchar(200) NOT NULL,
  `school_type` varchar(80) NOT NULL,
  `school_id` varchar(80) NOT NULL,
  `school_motto` text,
  `year_of_establishment` year(4) NOT NULL,
  `colour_code` varchar(4) DEFAULT NULL,
  `founder` text,
  `vision_mission` text,
  `created_by` datetime NOT NULL,
  `posted_by` varchar(80) NOT NULL,
  PRIMARY KEY (`school_id`),
  KEY `school_type` (`school_type`),
  CONSTRAINT `school_type` FOREIGN KEY (`school_type`) REFERENCES `school_information` (`school_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of school_information
-- ----------------------------

-- ----------------------------
-- Table structure for `school_type`
-- ----------------------------
DROP TABLE IF EXISTS `school_type`;
CREATE TABLE `school_type` (
  `sch_tyoe_id` varchar(80) NOT NULL,
  `type_name` varchar(80) NOT NULL,
  `created` datetime NOT NULL,
  `posted_by` varchar(80) NOT NULL,
  PRIMARY KEY (`sch_tyoe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of school_type
-- ----------------------------

-- ----------------------------
-- Table structure for `semester_setup`
-- ----------------------------
DROP TABLE IF EXISTS `semester_setup`;
CREATE TABLE `semester_setup` (
  `semester_id` varchar(80) NOT NULL,
  `semester_name` varchar(80) NOT NULL,
  `created_by` date NOT NULL,
  `posted_by` varchar(50) NOT NULL,
  PRIMARY KEY (`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of semester_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `session_setup`
-- ----------------------------
DROP TABLE IF EXISTS `session_setup`;
CREATE TABLE `session_setup` (
  `session_id` varchar(80) NOT NULL,
  `session_period_start` year(4) NOT NULL,
  `session_period_end` year(4) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of session_setup
-- ----------------------------

-- ----------------------------
-- Table structure for `staff_information`
-- ----------------------------
DROP TABLE IF EXISTS `staff_information`;
CREATE TABLE `staff_information` (
  `staff_id` varchar(80) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) DEFAULT NULL,
  `Address` varchar(80) NOT NULL,
  `home_phone` varchar(11) NOT NULL,
  `alternate_phone` varchar(11) NOT NULL,
  `staff_email` varchar(50) NOT NULL,
  `birth_date` date NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  `staff_type` int(1) NOT NULL COMMENT '1 represents academic staff while 0 represents non-academic staff',
  `Employment_date` date NOT NULL,
  `employment_status` varchar(50) NOT NULL,
  `staff_qualification` varchar(80) NOT NULL,
  `staff_faculty` varchar(80) NOT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `employment` (`employment_status`),
  KEY `staff qualification` (`staff_qualification`),
  KEY `faculty` (`staff_faculty`),
  CONSTRAINT `employment` FOREIGN KEY (`employment_status`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `faculty` FOREIGN KEY (`staff_faculty`) REFERENCES `faculty_setup` (`faculty_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `staff qualification` FOREIGN KEY (`staff_qualification`) REFERENCES `staff_information` (`staff_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of staff_information
-- ----------------------------

-- ----------------------------
-- Table structure for `student_information`
-- ----------------------------
DROP TABLE IF EXISTS `student_information`;
CREATE TABLE `student_information` (
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `middle_name` varchar(80) NOT NULL,
  `matric_number` varchar(50) NOT NULL,
  `blood_group` varchar(50) NOT NULL,
  `state_of_origin` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `passport_upload` varchar(60) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `address` varchar(80) NOT NULL,
  `genotype` varchar(50) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `student_type` varchar(50) NOT NULL,
  PRIMARY KEY (`student_id`),
  KEY `origin` (`state_of_origin`),
  KEY `nationality` (`nationality`),
  CONSTRAINT `nationality` FOREIGN KEY (`nationality`) REFERENCES `student_information` (`student_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `origin` FOREIGN KEY (`state_of_origin`) REFERENCES `student_information` (`student_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of student_information
-- ----------------------------

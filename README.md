# 🎓 Drivedo - Comprehensive Educational Platform

![Laravel](https://img.shields.io/badge/Laravel-13.0-FF2D20?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php)
![Blade](https://img.shields.io/badge/Blade-67%25-FFC529?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

**Drivedo** is a full-featured educational platform built with Laravel 13 that connects institutions, teachers, students, and alumni. It provides integrated features for course management, file sharing, assessment, mentorship, blockchain-based certificate management, and peer-to-peer resource exchange.

---

## 🌟 Features Overview

### 👥 Multi-Role User System
- **Students** – Browse courses, access materials, take quizzes, track attendance
- **Teachers** – Upload resources, create assessments, manage student attendance, issue certificates
- **Admin (HOD)** – Manage users, approve registrations, set up timetables, create subjects
- **Principal** – Oversee HODs, issue institution-wide notices, manage policies
- **Alumni** – Mentorship programs, career guidance, network with students

### 📚 Core Academic Features

#### **File Management & Resource Library**
- Teachers upload structured course materials (documents, videos, audio, images)
- Automatic file categorization by type
- PDF text extraction for searchable content
- Student file access by teacher and material type
- Download and preview capabilities

#### **Attendance & Academic Records**
- Teacher-led attendance marking per student and subject
- Bulk attendance import/export functionality
- Monthly and semester-wise attendance reports
- Automated attendance tracking linked to subjects and teachers

#### **Subject Management**
- Create subjects with unique codes and branch mapping
- Assign multiple teachers per subject
- Semester-based subject organization
- Branch and stream-based subject filtering

#### **Timetable System**
- Admin-managed timetable creation and scheduling
- Student/teacher views with printable formats
- Subject and time slot management
- Semester and branch-specific schedules

#### **Assessment System (Quizzes)**
- Create quizzes with multiple question types
- Multiple-choice questions with automatic grading
- Teacher control over quiz availability (open/close)
- Student quiz attempts with detailed results
- View past attempts and performance analytics
- Reset option for student retakes

#### **Mentorship & Alumni Network**
- Students browse and request mentorship from alumni
- Alumni accept/decline mentorship requests
- Secure messaging for mentorship sessions
- Session management and communication history
- Alumni profile and availability management

#### **Book Exchange (BookLoop)**
- Students list and browse books for exchange
- Peer-to-peer book trading marketplace
- In-app chat for book negotiations
- Listing status management (active/pending/exchanged)
- Search and filter by title and availability

#### **Notices & Announcements**
- Institutional announcements from multiple roles
- Role-specific targeting (students, teachers, admins, principals)
- Rich notice creation with timestamps
- Notification system for alert delivery

### 🔐 Blockchain & Certificate Management (CertChain)

#### **Certificate Issuance**
- Issue single or bulk certificates to students
- Customizable certificate templates with design editor
- Certificate template library for institution branding
- Dynamic text field mapping (student name, date, course)
- PDF certificate generation and download

#### **Blockchain Ledger**
- Immutable blockchain-based certificate records
- Hash verification for certificate authenticity
- Event-based certificate tracking
- Tamper-proof certificate storage
- Public certificate verification interface

#### **Template Management**
- Create and store reusable certificate designs
- Template preview and editing
- Template-based bulk certificate generation
- Admin-controlled template library

### 💬 AI-Powered Chat & RAG
- NVIDIA API integration for intelligent responses
- Retrieval-Augmented Generation (RAG) using extracted PDF content
- Context-aware question answering from uploaded materials
- Chat history with multiple conversations
- Smart content search across all uploaded files

### 📊 Admin Dashboard
- User statistics and analytics
- Pending registration approvals
- User management interface with bulk operations
- CSV export for institutional records
- Role-based user filtering

---

## 🏗️ Project Architecture

### Stack
- **Backend Framework:** Laravel 13 (PHP 8.3+)
- **Frontend:** Blade templating + Tailwind CSS + Vite
- **Database:** MySQL (configured in migrations)
- **Authentication:** Role-based with middleware guards
- **External APIs:** NVIDIA (AI Chat), Optional: Email services

### Directory Structure

# Software Requirements Specification (SRS)
## TrekAdvisor: Trek & Hotel Booking and Management System

### 1. Introduction
To build a strong software requirements document, both functional and non-functional requirements must be clearly stated. These requirements guide development, testing, and project planning. They also serve as a shared reference for all project stakeholders.

### 2. Purpose
This document represents the SRS for TrekAdvisor, a web-based trekking and hotel booking system. The main goal is to provide a simple, secure, and modern way for Customers to browse treks and hotels, book trips, and pay online. At the same time, Admins, Staff, and Hotel Owners can manage listings, bookings, reviews, and users in a structured dashboard.

### 3. Scope
The TrekAdvisor platform connects travelers with trekking packages and hotel stays through a single system. It provides:
- A simple public interface for exploring treks and hotels.
- Customer accounts for booking, payment, and profile management.
- Dashboards for Admin, Staff, and Hotel Owner roles.
- Payment integration using Stripe.
- Review and rating features to build trust and quality.
- Search and filtering to improve browsing efficiency.
- The flexibility to expand features in the future.

### 4. Definitions and Terms
- Admin: The system administrator who manages treks, hotels, users, bookings, and reviews.
- Staff: A support role responsible for booking operations and status updates.
- Hotel Owner: A partner who manages hotel listings, rooms, images, and hotel bookings.
- Customer: The end-user who browses listings, makes bookings, pays, and leaves reviews.
- Booking: A reservation created for a trek or hotel stay.
- Payment: The transaction record created after checkout through Stripe.
- Review: Customer feedback about a trek or hotel after booking.

### 5. Overall Description

#### 5.1 User Interfaces
The system provides four major interfaces:
- Admin Dashboard: Manage treks, hotels, users, reviews, and bookings.
- Staff Panel: View bookings and update booking status.
- Hotel Owner Panel: Manage hotel listings, rooms, images, and bookings.
- Customer Interface: Browse listings, book treks/hotels, manage profile, view bookings, and make payments.

#### 5.2 System Interfaces
- Database: MySQL database accessed through Laravel ORM.
- Payment Gateway: Stripe checkout for online payment processing.
- Storage: Public storage for uploaded images (avatars, hotel images, trek images).

#### 5.3 Constraints
- Framework: Laravel (PHP)
- Front end: Blade templates, HTML, CSS, JavaScript
- Database: MySQL
- Tools: Local development through XAMPP or similar stack, VS Code

### 6. Functional Requirements

#### 6.1 Authentication, Registration, and Access Control
**Description:** Each role must log in and access only the features assigned to that role.
**Functional Requirements:**
- The system shall allow Customers to register with name, email, and password.
- The system shall validate required fields and enforce password rules.
- The system shall securely hash passwords.
- The system shall allow login and logout for all roles.
- The system shall allow password reset and confirmation flows.
- The system shall restrict access based on role (Admin, Staff, Hotel Owner, Customer).
- The system shall redirect users to the correct dashboard after login.
- Unauthorized users shall not access restricted routes.

#### 6.2 Customer Profile and Security Settings
**Description:** Customers manage their personal details and security.
**Functional Requirements:**
- Customers shall update name, email, phone, and address.
- Phone numbers shall follow valid format rules if provided.
- Customers shall upload or remove a profile avatar.
- Customers shall change their password from the security settings.
- The system shall show confirmation messages after updates.

#### 6.3 Trek Listings and Details
**Description:** Customers can browse treks and see full information.
**Functional Requirements:**
- The system shall list all available treks.
- Customers shall filter treks by difficulty, price, duration, and region.
- Customers shall view trek images, itinerary, departure dates, and pricing.
- The system shall show trek availability status.

#### 6.4 Hotel Listings and Details
**Description:** Customers can browse hotels and view rooms.
**Functional Requirements:**
- The system shall list all available hotels.
- Customers shall filter hotels by location, price, and rating.
- Customers shall view hotel images, amenities, and room types.
- The system shall show room availability and pricing.

#### 6.5 Trek Booking Workflow
**Description:** Customers can book treks and manage the booking lifecycle.
**Functional Requirements:**
- Customers shall create trek bookings from trek detail pages.
- The system shall store booking details, passenger info, and dates.
- The system shall show booking confirmation after creation.
- The system shall show booking status (pending, confirmed, cancelled, completed).
- Customers shall request cancellations and withdraw requests.

#### 6.6 Hotel Booking Workflow
**Description:** Customers can book hotel rooms and manage bookings.
**Functional Requirements:**
- Customers shall create hotel bookings from hotel detail pages.
- The system shall store booking details, guest info, and stay dates.
- The system shall show booking confirmation after creation.
- The system shall show booking status (pending, confirmed, cancelled, completed).
- Customers shall request cancellations and withdraw requests.

#### 6.7 Payments (Stripe)
**Description:** Payments must be safe and linked to bookings.
**Functional Requirements:**
- The system shall integrate Stripe checkout for trek and hotel payments.
- Each payment attempt shall be recorded with status.
- Successful payment shall update booking and payment records.
- Failed payments shall keep bookings in pending state and display errors.

#### 6.8 Reviews and Ratings
**Description:** Customers provide reviews, Admin moderates.
**Functional Requirements:**
- Customers shall submit reviews after completed bookings.
- The system shall display reviews and ratings on listings.
- Admin shall flag, unflag, or delete reviews.

#### 6.9 Admin Trek Management
**Description:** Admin manages trek inventory and departures.
**Functional Requirements:**
- Admin shall create, edit, and delete trek listings.
- Admin shall manage trek images.
- Admin shall create and update trek departures.

#### 6.10 Admin Hotel Management
**Description:** Admin approves and manages hotels.
**Functional Requirements:**
- Admin shall review new hotel listings.
- Admin shall approve, reject, or mark hotels as pending.
- Admin shall view hotel listings and related metadata.

#### 6.11 Hotel Owner Management
**Description:** Hotel Owners manage their hotels and rooms.
**Functional Requirements:**
- Hotel Owners shall create and edit hotel listings.
- Hotel Owners shall upload and manage hotel images.
- Hotel Owners shall create, edit, and delete rooms.
- Hotel Owners shall view bookings for their hotels.

#### 6.12 Staff Booking Management
**Description:** Staff supports booking operations.
**Functional Requirements:**
- Staff shall view booking lists and booking details.
- Staff shall update booking status based on permissions.

#### 6.13 User and Role Management (Admin)
**Description:** Admin manages users and assigns roles.
**Functional Requirements:**
- Admin shall create new users and assign roles.
- Admin shall edit user details and roles.
- Admin shall deactivate or delete users.
- The system shall enforce role-based permissions.

#### 6.14 Search
**Description:** Search helps users find treks, hotels, and users (where permitted).
**Functional Requirements:**
- The system shall allow search for treks and hotels.
- Admin shall search across treks, hotels, and users.
- Search results shall respect role-based visibility.

#### 6.15 Notifications and Feedback
**Description:** Users need clear action feedback.
**Functional Requirements:**
- The system shall display success and error messages.
- Transient messages shall auto-dismiss after a short time.

### 7. Non-Functional Requirements

#### 7.1 Reliability
- The system should be available 24/7 with minimal downtime.
- Regular backups should be scheduled to avoid data loss.

#### 7.2 Maintainability
- The codebase should be modular and easy to update.
- Version control (Git) should be used for tracking changes.

#### 7.3 Interoperability
- The platform must work with Stripe API for payments.
- The system should run correctly in modern browsers (Chrome, Firefox, Edge).

#### 7.4 Scalability
- The system should handle growth in users and bookings.
- New features should be added without major redesign.

#### 7.5 Performance
- Core pages should load within a few seconds under normal load.
- The payment flow should complete within about 10 seconds.

#### 7.6 Data Privacy and Security
- Passwords and sensitive data must be encrypted or hashed.
- Role-based access must protect administrative data.
- Secure payment handling must follow Stripe requirements.

#### 7.7 Mobile Optimization
- The interface must be responsive on mobile and tablet devices.
- Navigation and forms should be touch-friendly.

#### 7.8 Recovery and Failover
- Backups should allow recovery after outages.

#### 7.9 Ethical and Legal Considerations
- The system shall show clear terms of service and privacy policy.
- Users should be informed about data usage and refunds.

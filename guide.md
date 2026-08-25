# 🎓 Alumni Mentorship Feature - Testing Guide

This guide will help you test the newly integrated **Alumni Mentorship** feature in the **EduShare** project.

## 🚀 Step 1: Database Setup
First, ensure your database schema is up to date. Run the following command in your terminal:

```bash
php artisan migrate
```
*Note: This will add the `alumni` role to your `users` table and create all mentorship-related tables.*

---

## 👤 Step 2: Register as an Alumni
1. Go to the **Registration Page** (`/register`).
2. Fill in your details (Username, Password).
3. Select **Role: Alumni**.
4. Fill in the dynamic fields:
   - **Company / Organization**: e.g., Google
   - **Professional Bio**: e.g., Full Stack Developer with 5 years experience.
   - **Branch**: Select your graduation branch.
5. Click **Register**.

---

## 🛡️ Step 3: Admin Approval (Testing Shortcut)
By default, Alumni accounts are registered with `status = 'pending'`. In a production environment, an Admin would approve this. 

**For testing, you can approve the account via Tinker:**

```bash
php artisan tinker
```
Inside Tinker, run:
```php
\App\Models\User::where('role', 'alumni')->update(['status' => 'approved']);
```
*Alternatively, you can manually change the `status` column to `approved` in your database manager (like phpMyAdmin).*

---

## 🎓 Step 4: Student Interaction
1. Log in as an **existing Student** or register a new student account.
2. Navigate to **Alumni Mentorship** via the sidebar.
3. You should see your newly registered Alumni in the directory.
4. Click **Request Mentorship**.
5. Enter a message (e.g., "I need help with Laravel architecture") and send.

---

## 💬 Step 5: Accepting & Chatting (Alumni Side)
1. Log out and log back in as the **Alumni**.
2. Go to **Mentorship Requests** in the sidebar.
3. You will see the student's request. Click **Accept**.
4. Go to **My Sessions**. You will see a session has been automatically scheduled.
5. Click **Join Chat**.
6. Send a message to the student!

---

## ✅ Step 6: Chatting (Student Side)
1. Switch back to the **Student** account.
2. Go to **Alumni Mentorship** -> **My Sessions**.
3. Join the chat and reply to the mentor!

---

## 🛠️ Troubleshooting
- **Permission Denied**: Ensure you are logged in with the correct role.
- **Role not found**: Ensure the migration ran successfully and the `users` table has the `alumni` role in the enum.
- **Logout/Login**: If you register a new user, make sure you log out of any existing sessions first.

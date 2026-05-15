# WauminiLink Mobile App API Documentation

This documentation provides details on the API endpoints available for the WauminiLink mobile application.

## Base URL
`http://192.168.100.103:8000/api`

## Authentication
The API uses **Laravel Sanctum** for authentication. Most endpoints require a Bearer Token.

---

## 1. Authentication Endpoints

### Login
*   **URL:** `/auth/login`
*   **Method:** `POST`
*   **Body:**
    ```json
    {
        "username": "user@example.com OR MemberID",
        "password": "password123",
        "device_name": "iPhone 13"
    }
    ```
*   **Response:**
    ```json
    {
        "success": true,
        "token": "1|abcde...",
        "user": { ... }
    }
    ```

### Logout
*   **URL:** `/auth/logout`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer [token]`

---

## 2. Member & Profile Endpoints

### Get Member Profile
*   **URL:** `/member/profile`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Update Profile (Account Settings)
*   **URL:** `/member/profile`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer [token]`
*   **Body:**
    ```json
    {
        "full_name": "New Name",
        "email": "newemail@example.com",
        "phone_number": "0712345678",
        "address": "Moshi, Kilimanjaro",
        "profession": "Engineer"
    }
    ```

---

## 3. Services & Events

### Get Sunday Services
*   **URL:** `/member/services`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Get Special Events
*   **URL:** `/member/events`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

---

## 4. Announcements

### Get All Announcements
*   **URL:** `/member/announcements`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Mark Announcement as Read
*   **URL:** `/member/announcements/{id}/read`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer [token]`

---

## 5. Leadership

### Get Leaders List
*   **URL:** `/member/leaders`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

---

## 6. Departments

### Get All Departments
*   **URL:** `/departments`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Get Department Details
*   **URL:** `/departments/{id}`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

---

## 7. Dashboard (All-in-one)

### Get Dashboard Data
*   **URL:** `/member/dashboard`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`
*   **Description:** Returns a summary of profile, financial status, and latest announcements in one request.

---

## 8. Prayer Requests (Maombi)

### Get My Prayer Requests
*   **URL:** `/member/prayer-requests`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Send New Prayer Request
*   **URL:** `/member/prayer-requests`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer [token]`
*   **Body:**
    ```json
    {
        "subject": "Ombi la Afya",
        "content": "Naomba mniombee kwa ajili ya afya yangu...",
        "is_anonymous": false
    }
    ```

---

## 9. Attendance via QR Code (Mahudhurio)

### Scan QR Code to Record Attendance
*   **URL:** `/member/attendance/scan`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer [token]`
*   **Body:**
    ```json
    {
        "qr_code": "QR_CODE_STRING_FROM_SCANNER"
    }
    ```
*   **Success Response:**
    ```json
    {
        "success": true,
        "message": "Attendance recorded successfully!",
        "data": {
            "service_name": "Ibada ya Asubuhi",
            "type": "sunday_service",
            "time": "2026-05-07 10:00:00"
        }
    }
    ```

---

## 10. Financial & Payments

### Get Annual Fees Status
*   **URL:** `/annual-fees`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`
*   **Response:**
    ```json
    {
        "success": true,
        "data": [
            {
                "id": 1,
                "year": 2026,
                "category": "Adult",
                "amount": 2000.0,
                "amount_paid": 500.0,
                "balance": 1500.0,
                "status": "pending"
            }
        ]
    }
    ```

### Upload Payment Receipt
*   **URL:** `/upload-receipt`
*   **Method:** `POST`
*   **Headers:** 
    *   `Authorization: Bearer [token]`
    *   `Content-Type: multipart/form-data`
*   **Body (Form Data):**
    *   `receipt_type`: "Tithe", "Offering", "Annual Fee", etc.
    *   `amount`: 5000 (optional)
    *   `reference_number`: "REF123" (optional)
    *   `receipt_image`: [File Binary]
    *   `notes`: "Malipo ya mwezi Mei" (optional)
*   **Response:**
    ```json
    {
        "success": true,
        "message": "Receipt uploaded successfully.",
        "data": { ... }
    }
    ```

---

## 11. Extra Services

### Get Celebrations
*   **URL:** `/celebrations`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Get Weekly Assignments
*   **URL:** `/assignments`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

### Get Sermons
*   **URL:** `/sermons`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer [token]`

---

## Standard Response Format
All responses follow this structure:
```json
{
    "success": true,
    "data": { ... },
    "message": "Optional message"
}
```
In case of errors (4xx or 5xx):
```json
{
    "success": false,
    "message": "Error message description",
    "errors": { ... }
}
```

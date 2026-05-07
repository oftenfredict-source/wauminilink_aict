# WauminiLink Mobile App API Documentation

This documentation provides details on the API endpoints available for the WauminiLink mobile application.

## Base URL
`https://aict-moshi.wauminilink.co.tz/api`

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
        "email": "user@example.com",
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

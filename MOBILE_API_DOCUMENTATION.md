# Mobile App API Documentation

This document provides information about the API endpoints created for the Flutter mobile app member dashboard.

## Base URL

```
https://unurbane-dina-superconservatively.ngrok-free.dev/api
```

## Authentication

The API uses Laravel Sanctum for token-based authentication. After successful login, you'll receive a Bearer token that must be included in all subsequent requests.

### Headers Required for Authenticated Requests

```
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

---

## API Endpoints

### 1. Login

**Endpoint:** `POST /api/auth/login`

**Description:** Authenticate a member using phone number and password.

**Request Body:**
```json
{
  "phone_number": "+255712345678",
  "password": "password123"
}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "2025ABC12-WL",
      "phone_number": "+255712345678",
      "role": "member"
    },
    "member": {
      "id": 1,
      "member_id": "2025ABC12-WL",
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_number": "+255712345678"
    },
    "token": "1|abcdef1234567890...",
    "token_type": "Bearer"
  }
}
```

**Response (Error - 401):**
```json
{
  "success": false,
  "message": "Invalid phone number or password."
}
```

**Response (Error - 403 - Account Blocked):**
```json
{
  "success": false,
  "message": "Your account is temporarily blocked. Please try again in 5 minute(s).",
  "blocked_until": "2025-01-15T10:30:00.000000Z"
}
```

**Response (Error - 422 - Validation):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "phone_number": ["Phone number is required."],
    "password": ["Password is required."]
  }
}
```

---

### 2. Get Current User

**Endpoint:** `GET /api/auth/me`

**Description:** Get the authenticated user's information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "2025ABC12-WL",
      "phone_number": "+255712345678",
      "role": "member"
    },
    "member": {
      "id": 1,
      "member_id": "2025ABC12-WL",
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_number": "+255712345678"
    }
  }
}
```

---

### 3. Logout

**Endpoint:** `POST /api/auth/logout`

**Description:** Logout the current user and revoke the access token.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 4. Get Member Dashboard

**Endpoint:** `GET /api/member/dashboard`

**Description:** Get all dashboard data for the authenticated member including financial summary, announcements, events, and leadership information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (Success - 200):**
```json
{
  "success": true,
  "data": {
    "member_info": {
      "member_id": "2025ABC12-WL",
      "full_name": "John Doe",
      "email": "john@example.com",
      "phone_number": "+255712345678",
      "date_of_birth": "1990-05-15",
      "gender": "male",
      "membership_type": "permanent",
      "member_type": "father",
      "profession": "Engineer",
      "address": "123 Main St",
      "region": "Kilimanjaro",
      "district": "Moshi"
    },
    "financial_summary": {
      "total_tithes": 50000.00,
      "monthly_tithes": 5000.00,
      "total_offerings": 30000.00,
      "monthly_offerings": 3000.00,
      "total_donations": 20000.00,
      "monthly_donations": 2000.00,
      "total_pledges": 100000.00,
      "total_pledge_payments": 60000.00,
      "remaining_pledges": 40000.00,
      "recent_transactions": [
        {
          "id": 1,
          "amount": 5000.00,
          "date": "2025-01-10",
          "type": "tithe"
        },
        {
          "id": 2,
          "amount": 3000.00,
          "date": "2025-01-08",
          "type": "offering"
        }
      ]
    },
    "announcements": {
      "announcements": [
        {
          "id": 1,
          "title": "Church Meeting",
          "content": "All members are invited...",
          "is_pinned": true,
          "is_unread": true,
          "created_at": "2025-01-10T10:00:00.000000Z",
          "updated_at": "2025-01-10T10:00:00.000000Z"
        }
      ],
      "events": [
        {
          "id": 1,
          "title": "Youth Conference",
          "description": "Annual youth conference",
          "event_date": "2025-02-15",
          "event_time": "09:00",
          "location": "Church Hall"
        }
      ],
      "celebrations": [
        {
          "id": 1,
          "member_name": "Jane Doe",
          "celebration_type": "birthday",
          "celebration_date": "2025-01-20",
          "description": "Birthday celebration"
        }
      ],
      "sunday_services": [
        {
          "id": 1,
          "service_date": "2025-01-19",
          "service_time": "10:00",
          "theme": "Faith and Hope",
          "preacher": "Pastor John"
        }
      ]
    },
    "unread_announcements_count": 3,
    "leadership": {
      "all_leaders": [
        {
          "id": 1,
          "position": "Pastor",
          "member_name": "Pastor John",
          "member_phone": "+255712345679",
          "appointment_date": "2024-01-01",
          "end_date": null
        }
      ],
      "member_positions": [
        {
          "id": 5,
          "position": "Youth Leader",
          "appointment_date": "2024-06-01",
          "end_date": null
        }
      ],
      "has_leadership_position": true
    }
  }
}
```

---

### 5. Mark Announcement as Read

**Endpoint:** `POST /api/member/announcements/{announcementId}/read`

**Description:** Mark a specific announcement as read by the member.

**Headers:**
```
Authorization: Bearer {token}
```

**Parameters:**
- `announcementId` (path parameter): The ID of the announcement to mark as read

**Response (Success - 200):**
```json
{
  "success": true,
  "message": "Announcement marked as read."
}
```

**Response (Error - 404):**
```json
{
  "success": false,
  "message": "Announcement not found."
}
```

---

## Error Responses

All endpoints may return the following error responses:

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Access denied. Only members can access the mobile app."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found."
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "Internal server error."
}
```

---

## Flutter Integration Guide

### Step 1: Setup HTTP Client

Add the following dependencies to your `pubspec.yaml`:

```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0
```

### Step 2: Create API Service Class

Create a file `lib/services/api_service.dart`:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'https://unurbane-dina-superconservatively.ngrok-free.dev/api';
  
  // Get stored token
  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }
  
  // Save token
  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }
  
  // Remove token
  static Future<void> removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
  
  // Get headers with authentication
  static Future<Map<String, String>> getHeaders({bool includeAuth = true}) async {
    Map<String, String> headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    
    if (includeAuth) {
      final token = await getToken();
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }
    }
    
    return headers;
  }
  
  // Login
  static Future<Map<String, dynamic>> login(String phoneNumber, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/login'),
        headers: await getHeaders(includeAuth: false),
        body: jsonEncode({
          'phone_number': phoneNumber,
          'password': password,
        }),
      );
      
      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success'] == true) {
        // Save token
        await saveToken(data['data']['token']);
        return data;
      } else {
        throw Exception(data['message'] ?? 'Login failed');
      }
    } catch (e) {
      throw Exception('Login error: $e');
    }
  }
  
  // Logout
  static Future<void> logout() async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/auth/logout'),
        headers: await getHeaders(),
      );
      
      await removeToken();
    } catch (e) {
      // Even if request fails, remove token locally
      await removeToken();
    }
  }
  
  // Get dashboard data
  static Future<Map<String, dynamic>> getDashboard() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/member/dashboard'),
        headers: await getHeaders(),
      );
      
      final data = jsonDecode(response.body);
      
      if (response.statusCode == 200 && data['success'] == true) {
        return data;
      } else {
        throw Exception(data['message'] ?? 'Failed to load dashboard');
      }
    } catch (e) {
      throw Exception('Dashboard error: $e');
    }
  }
  
  // Mark announcement as read
  static Future<void> markAnnouncementAsRead(int announcementId) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/member/announcements/$announcementId/read'),
        headers: await getHeaders(),
      );
      
      if (response.statusCode != 200) {
        final data = jsonDecode(response.body);
        throw Exception(data['message'] ?? 'Failed to mark as read');
      }
    } catch (e) {
      throw Exception('Error marking announcement as read: $e');
    }
  }
}
```

### Step 3: Create Models

Create `lib/models/user_model.dart`:

```dart
class UserModel {
  final int id;
  final String name;
  final String email;
  final String? phoneNumber;
  final String role;
  
  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phoneNumber,
    required this.role,
  });
  
  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phoneNumber: json['phone_number'],
      role: json['role'],
    );
  }
}
```

Create `lib/models/member_model.dart`:

```dart
class MemberModel {
  final int id;
  final String memberId;
  final String fullName;
  final String? email;
  final String? phoneNumber;
  
  MemberModel({
    required this.id,
    required this.memberId,
    required this.fullName,
    this.email,
    this.phoneNumber,
  });
  
  factory MemberModel.fromJson(Map<String, dynamic> json) {
    return MemberModel(
      id: json['id'],
      memberId: json['member_id'],
      fullName: json['full_name'],
      email: json['email'],
      phoneNumber: json['phone_number'],
    );
  }
}
```

### Step 4: Create Login Screen

Create `lib/screens/login_screen.dart`:

```dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  
  Future<void> _login() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isLoading = true);
      
      try {
        final response = await ApiService.login(
          _phoneController.text.trim(),
          _passwordController.text,
        );
        
        // Navigate to dashboard
        Navigator.pushReplacementNamed(context, '/dashboard');
      } catch (e) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e.toString())),
        );
      } finally {
        setState(() => _isLoading = false);
      }
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              TextFormField(
                controller: _phoneController,
                decoration: InputDecoration(labelText: 'Phone Number'),
                keyboardType: TextInputType.phone,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter phone number';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _passwordController,
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter password';
                  }
                  return null;
                },
              ),
              SizedBox(height: 24),
              _isLoading
                  ? CircularProgressIndicator()
                  : ElevatedButton(
                      onPressed: _login,
                      child: Text('Login'),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}
```

### Step 5: Create Dashboard Screen

Create `lib/screens/dashboard_screen.dart`:

```dart
import 'package:flutter/material.dart';
import '../services/api_service.dart';

class DashboardScreen extends StatefulWidget {
  @override
  _DashboardScreenState createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  Map<String, dynamic>? _dashboardData;
  bool _isLoading = true;
  String? _error;
  
  @override
  void initState() {
    super.initState();
    _loadDashboard();
  }
  
  Future<void> _loadDashboard() async {
    try {
      final data = await ApiService.getDashboard();
      setState(() {
        _dashboardData = data['data'];
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _error = e.toString();
        _isLoading = false;
      });
    }
  }
  
  Future<void> _logout() async {
    await ApiService.logout();
    Navigator.pushReplacementNamed(context, '/login');
  }
  
  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }
    
    if (_error != null) {
      return Scaffold(
        body: Center(child: Text('Error: $_error')),
      );
    }
    
    final memberInfo = _dashboardData!['member_info'];
    final financialSummary = _dashboardData!['financial_summary'];
    final announcements = _dashboardData!['announcements'];
    
    return Scaffold(
      appBar: AppBar(
        title: Text('Member Dashboard'),
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: _logout,
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _loadDashboard,
        child: ListView(
          padding: EdgeInsets.all(16),
          children: [
            // Member Info Card
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Welcome, ${memberInfo['full_name']}',
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 8),
                    Text('Member ID: ${memberInfo['member_id']}'),
                    Text('Phone: ${memberInfo['phone_number']}'),
                  ],
                ),
              ),
            ),
            
            SizedBox(height: 16),
            
            // Financial Summary
            Text(
              'Financial Summary',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),
            Card(
              child: Padding(
                padding: EdgeInsets.all(16),
                child: Column(
                  children: [
                    _buildFinancialRow('Total Tithes', financialSummary['total_tithes']),
                    _buildFinancialRow('Monthly Tithes', financialSummary['monthly_tithes']),
                    _buildFinancialRow('Total Offerings', financialSummary['total_offerings']),
                    _buildFinancialRow('Total Donations', financialSummary['total_donations']),
                    _buildFinancialRow('Remaining Pledges', financialSummary['remaining_pledges']),
                  ],
                ),
              ),
            ),
            
            SizedBox(height: 16),
            
            // Announcements
            Text(
              'Announcements',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 8),
            ...(announcements['announcements'] as List)
                .map((announcement) => Card(
                      child: ListTile(
                        title: Text(announcement['title']),
                        subtitle: Text(announcement['content']),
                        trailing: announcement['is_unread']
                            ? Icon(Icons.circle, color: Colors.blue, size: 12)
                            : null,
                      ),
                    ))
                .toList(),
          ],
        ),
      ),
    );
  }
  
  Widget _buildFinancialRow(String label, double amount) {
    return Padding(
      padding: EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label),
          Text(
            'TZS ${amount.toStringAsFixed(2)}',
            style: TextStyle(fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }
}
```

### Step 6: Update main.dart

```dart
import 'package:flutter/material.dart';
import 'screens/login_screen.dart';
import 'screens/dashboard_screen.dart';
import 'services/api_service.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Member Dashboard',
      routes: {
        '/': (context) => FutureBuilder(
          future: _checkAuth(),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return Scaffold(body: Center(child: CircularProgressIndicator()));
            }
            return snapshot.data == true
                ? DashboardScreen()
                : LoginScreen();
          },
        ),
        '/login': (context) => LoginScreen(),
        '/dashboard': (context) => DashboardScreen(),
      },
    );
  }
  
  Future<bool> _checkAuth() async {
    final token = await ApiService.getToken();
    return token != null;
  }
}
```

---

## Testing the API

You can test the API endpoints using tools like Postman or cURL:

### Test Login:
```bash
curl -X POST https://unurbane-dina-superconservatively.ngrok-free.dev/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "phone_number": "+255712345678",
    "password": "password123"
  }'
```

### Test Dashboard (replace {token} with actual token):
```bash
curl -X GET https://unurbane-dina-superconservatively.ngrok-free.dev/api/member/dashboard \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## Notes

1. **Phone Number Format**: The API accepts phone numbers in various formats. It will normalize them by removing spaces and special characters.

2. **Token Storage**: Store the token securely in your Flutter app using `shared_preferences` or `flutter_secure_storage`.

3. **Token Expiration**: Tokens don't expire by default in Sanctum, but you can implement token refresh logic if needed.

4. **Error Handling**: Always handle network errors and API errors gracefully in your Flutter app.

5. **CORS**: If you encounter CORS issues, make sure your Laravel app has CORS middleware configured properly.

6. **ngrok**: Since you're using ngrok, the URL might change. Update the base URL in your Flutter app when the ngrok URL changes.

---

## Next Steps

1. Test all API endpoints using Postman or similar tools
2. Implement the Flutter UI based on the dashboard data structure
3. Add error handling and loading states
4. Implement token refresh if needed
5. Add offline support if required
6. Implement push notifications for announcements (future enhancement)




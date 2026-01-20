# Entity Relationship Diagram (ERD)
## Telehealth Application Database Schema

```mermaid
erDiagram
    USERS ||--o{ HEALTH_RECORDS : has
    USERS ||--o{ FEEDBACKS : submits
    USERS ||--o{ NOTIFICATIONS : receives
    USERS ||--o{ USER_BADGES : earns
    USERS ||--o{ DOCTOR_PATIENT : manages
    USERS ||--o{ DOCTOR_PATIENT : sees_doctor
    BADGES ||--o{ USER_BADGES : "awarded to"
    HEALTH_RECORDS ||--o{ FEEDBACKS : relates_to

    USERS {
        bigint id PK
        string name
        string email UK
        string password
        enum role "pasien|dokter|admin"
        string phone
        text address
        string medical_id
        date date_of_birth
        timestamp last_data_entry_date
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    HEALTH_RECORDS {
        bigint id PK
        bigint user_id FK
        integer systolic_bp
        integer diastolic_bp
        integer heart_rate
        decimal weight
        decimal height
        string status "Normal|Prehipertensi|Hipertensi Stage 1|Hipertensi Stage 2"
        text notes
        timestamp created_at
        timestamp updated_at
    }

    FEEDBACKS {
        bigint id PK
        bigint user_id FK
        bigint health_record_id FK
        integer rating "1-5"
        text comment
        string follow_up_status "pending|completed|cancelled"
        timestamp follow_up_date
        timestamp created_at
        timestamp updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string type "health_alert|badge_earned|appointment|recommendation"
        string title
        text message
        string status "unread|read"
        timestamp read_at
        json data
        timestamp created_at
        timestamp updated_at
    }

    BADGES {
        bigint id PK
        string name
        text description
        string icon_color "yellow|orange|red|purple|blue|green"
        integer points_required
        string tier "bronze|silver|gold|platinum"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    USER_BADGES {
        bigint id PK
        bigint user_id FK
        bigint badge_id FK
        integer progress_percentage
        timestamp earned_at
        timestamp created_at
        timestamp updated_at
    }

    DOCTOR_PATIENT {
        bigint id PK
        bigint doctor_id FK "User(dokter)"
        bigint patient_id FK "User(pasien)"
        timestamp consultation_date
        string status "active|inactive|completed"
        timestamp created_at
        timestamp updated_at
    }
```

## Relasi Detail

### 1. **Users** (Pengguna Sistem)
- Central entity dengan role: pasien, dokter, atau admin
- One doctor dapat have banyak patients
- One patient dapat have satu atau lebih doctors

### 2. **Health Records** (Catatan Kesehatan)
- Milik seorang pasien
- Menyimpan data tekanan darah, detak jantung, berat badan
- Menghasilkan status kesehatan otomatis
- Berkaitan dengan feedback dari dokter

### 3. **Feedbacks** (Umpan Balik/Konsultasi)
- Dokter memberikan feedback terhadap health record pasien
- Memiliki follow-up tracking
- Rating system untuk kepuasan pasien

### 4. **Notifications** (Notifikasi)
- Sistem otomatis untuk alert kesehatan
- Badge achievement notifications
- Appointment reminders
- Personalized health recommendations

### 5. **Badges & User Badges** (Gamifikasi)
- Badge adalah penghargaan yang dapat dicapai
- User_badges mencatat progress dan saat earning
- Multiple tiers untuk motivasi berkelanjutan

### 6. **Doctor Patient** (Hubungan Dokter-Pasien)
- Junction table untuk many-to-many relationship
- Melacak konsultasi dan status hubungan

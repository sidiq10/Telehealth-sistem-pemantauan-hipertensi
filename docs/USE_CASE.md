    # Use Case Diagram
    ## Telehealth Application Interactions

    ```mermaid
    graph TB
        subgraph Actors
            Patient["👤 Pasien"]
            Doctor["👨‍⚕️ Dokter"]
            System["🤖 Sistem"]
        end

        subgraph PatientUC["Pasien - Use Cases"]
            UC1["Register/Login"]
            UC2["Input Data Kesehatan"]
            UC3["Lihat Rekam Medis"]
            UC4["Terima Feedback Dokter"]
            UC5["Lihat Dashboard Gamifikasi"]
            UC6["Terima Notifikasi Alert"]
            UC7["Lihat Rekomendasi Kesehatan"]
            UC8["Berikan Rating Feedback"]
            UC9["Jadwalkan Konsultasi"]
        end

        subgraph DoctorUC["Dokter - Use Cases"]
            UC10["Register/Login"]
            UC11["Lihat Pasien Terdaftar"]
            UC12["View Rekam Medis Pasien"]
            UC13["Analisis Tren Kesehatan"]
            UC14["Berikan Feedback/Konsultasi"]
            UC15["Lihat Analytics Dashboard"]
            UC16["Monitor Pasien Berisiko"]
            UC17["Lihat Engagement Metrics"]
            UC18["Track Follow-up Status"]
        end

        subgraph SystemUC["Sistem - Use Cases"]
            UC19["Calculate Points & Streaks"]
            UC20["Award Badges"]
            UC21["Generate AI Recommendations"]
            UC22["Send Notifications"]
            UC23["Aggregate Health Trends"]
            UC24["Calculate Engagement Score"]
            UC25["Generate Health Alerts"]
        end

        %% Pasien relationships
        Patient -->|perform| UC1
        Patient -->|perform| UC2
        Patient -->|perform| UC3
        Patient -->|perform| UC4
        Patient -->|perform| UC5
        Patient -->|perform| UC6
        Patient -->|perform| UC7
        Patient -->|perform| UC8
        Patient -->|perform| UC9

        %% Dokter relationships
        Doctor -->|perform| UC10
        Doctor -->|perform| UC11
        Doctor -->|perform| UC12
        Doctor -->|perform| UC13
        Doctor -->|perform| UC14
        Doctor -->|perform| UC15
        Doctor -->|perform| UC16
        Doctor -->|perform| UC17
        Doctor -->|perform| UC18

        %% Sistem relationships
        UC2 -->|trigger| UC19
        UC2 -->|trigger| UC23
        UC2 -->|trigger| UC25
        UC19 -->|trigger| UC20
        UC19 -->|trigger| UC22
        UC25 -->|trigger| UC22
        UC20 -->|trigger| UC22
        UC7 -->|uses| UC21
        UC13 -->|uses| UC23
        UC15 -->|uses| UC24
        UC16 -->|uses| UC25
        UC4 -->|trigger| UC18

        style Patient fill:#e1f5ff
        style Doctor fill:#fff3e0
        style System fill:#f3e5f5
    ```

    ## Deskripsi Use Cases

    ### **Pasien (Patient) Use Cases:**

    | # | Use Case | Deskripsi |
    |---|----------|-----------|
    | UC1 | **Register/Login** | Pasien mendaftar akun atau login ke sistem |
    | UC2 | **Input Data Kesehatan** | Pasien memasukkan data tekanan darah, detak jantung, berat badan, tinggi badan |
    | UC3 | **Lihat Rekam Medis** | Pasien melihat riwayat kesehatan dan data yang telah diinput |
    | UC4 | **Terima Feedback Dokter** | Pasien menerima feedback/konsultasi dari dokter |
    | UC5 | **Lihat Dashboard Gamifikasi** | Pasien melihat poin, streak, badges, dan progress engagement |
    | UC6 | **Terima Notifikasi Alert** | Sistem mengirim alert kesehatan dan notifikasi penting |
    | UC7 | **Lihat Rekomendasi Kesehatan** | Pasien menerima rekomendasi kesehatan berbasis AI |
    | UC8 | **Berikan Rating Feedback** | Pasien memberikan rating untuk feedback dokter (1-5 bintang) |
    | UC9 | **Jadwalkan Konsultasi** | Pasien membuat jadwal konsultasi dengan dokter |

    ### **Dokter (Doctor) Use Cases:**

    | # | Use Case | Deskripsi |
    |---|----------|-----------|
    | UC10 | **Register/Login** | Dokter mendaftar atau login ke sistem |
    | UC11 | **Lihat Pasien Terdaftar** | Dokter melihat daftar pasien yang terdaftar |
    | UC12 | **View Rekam Medis Pasien** | Dokter melihat detail rekam medis pasien spesifik |
    | UC13 | **Analisis Tren Kesehatan** | Dokter menganalisis tren kesehatan jangka panjang pasien |
    | UC14 | **Berikan Feedback/Konsultasi** | Dokter memberikan feedback terhadap data kesehatan pasien |
    | UC15 | **Lihat Analytics Dashboard** | Dokter melihat dashboard analytics dengan metrik engagement |
    | UC16 | **Monitor Pasien Berisiko** | Dokter memonitor pasien dengan status hipertensi stage 2 |
    | UC17 | **Lihat Engagement Metrics** | Dokter melihat metriks engagement pasien (poin, entries, badges) |
    | UC18 | **Track Follow-up Status** | Dokter melacak status follow-up dari feedback yang diberikan |

    ### **Sistem (System) Use Cases:**

    | # | Use Case | Deskripsi |
    |---|----------|-----------|
    | UC19 | **Calculate Points & Streaks** | Sistem menghitung poin dan streak harian pasien |
    | UC20 | **Award Badges** | Sistem memberikan badges ketika milestone tercapai |
    | UC21 | **Generate AI Recommendations** | Sistem menghasilkan rekomendasi kesehatan berbasis AI |
    | UC22 | **Send Notifications** | Sistem mengirim notifikasi ke pasien/dokter |
    | UC23 | **Aggregate Health Trends** | Sistem mengagregasi tren kesehatan untuk analisis |
    | UC24 | **Calculate Engagement Score** | Sistem menghitung skor engagement keseluruhan |
    | UC25 | **Generate Health Alerts** | Sistem menghasilkan alert kesehatan otomatis |

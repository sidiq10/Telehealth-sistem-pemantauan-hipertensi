# Activity Diagram
## Telehealth Application Workflows

## 1. Patient Input & Gamification Flow

```mermaid
graph TD
    A["🏥 Pasien Login<br/>ke Sistem"] -->|Akses Dashboard| B["📊 Dashboard<br/>Pasien"]
    B -->|Klik Input Data| C["📝 Form Input<br/>Data Kesehatan"]
    C -->|Isi Tekanan Darah<br/>Denyut Jantung<br/>Berat Badan| D["✅ Validasi Data"]
    
    D -->|Valid| E["💾 Simpan ke<br/>Database"]
    D -->|Invalid| F["❌ Tampilkan Error<br/>Message"]
    F -->|Kembali| C
    
    E -->|Trigger| G["🎮 Sistem Gamifikasi"]
    
    G -->|Tambah Poin| H["⭐ +10 Points<br/>for Entry"]
    G -->|Jaga Streak| I["🔥 Streak Counter<br/>+1 Hari"]
    G -->|Check Milestone| J{{"Capai<br/>Milestone?"}}
    
    J -->|Ya| K["🏅 Award Badge"]
    J -->|Tidak| L["📈 Update Progress<br/>to Next Badge"]
    
    K -->|Trigger| M["📬 Kirim Notifikasi<br/>Badge Earned"]
    L -->|Update| N["📲 Update Dashboard<br/>Display"]
    M --> N
    H --> N
    I --> N
    
    N -->|Tampilkan| O["✨ Dashboard Diperbarui<br/>dengan Poin, Streak, Badge"]
    O -->|Pasien Lihat| P["😊 Pasien Terpuji"]
```

## 2. Doctor Analytics & Follow-up Flow

```mermaid
graph TD
    A["👨‍⚕️ Dokter Login<br/>ke Sistem"] -->|Akses Dashboard| B["📊 Doctor Dashboard"]
    B -->|Klik Analytics| C["📈 Analytics Page"]
    
    C -->|Load Data| D["🔍 Sistem Aggregasi"]
    D -->|Patient Records| E["📋 Collect Health<br/>Records"]
    D -->|Feedback Data| F["⭐ Collect Ratings<br/>& Feedback"]
    D -->|Engagement Data| G["🎮 Collect Points<br/>Streak & Badges"]
    
    E -->|Calculate| H["📊 Health Trends<br/>Analysis"]
    F -->|Calculate| I["⭐ Avg Rating<br/>per Patient"]
    G -->|Calculate| J["🎯 Engagement<br/>Score"]
    
    H -->|Identify| K["🚨 Patients with<br/>Hipertensi Stage 2"]
    I -->|Display| L["📊 Feedback<br/>Distribution<br/>Excellent/Good/Neutral/Poor"]
    J -->|Display| M["📈 Total Patients<br/>Total Feedback<br/>Metrics"]
    
    K -->|Show| N["⚠️ Warning Patients<br/>Table"]
    L -->|Show| O["📊 Chart<br/>Visualization"]
    M -->|Show| P["🔢 Metric Cards"]
    
    N -->|Dokter Review| Q{{"Perlu<br/>Intervensi?"}}
    Q -->|Ya| R["📞 Hubungi Pasien"]
    Q -->|Tidak| S["✓ Monitor Lanjut"]
    
    R -->|Input| T["📝 Berikan Feedback<br/>Konsultasi"]
    T -->|Submit| U["💾 Simpan Feedback<br/>ke Database"]
    U -->|Trigger| V["📬 Notifikasi ke<br/>Pasien"]
    V -->|Pasien Terima| W["⭐ Pasien Beri Rating<br/>Feedback"]
    W -->|Status Updated| X["✅ Follow-up<br/>Tracking"]
```

## 3. Health Recommendations & Alert System

```mermaid
graph TD
    A["💾 Data Kesehatan<br/>Baru Disimpan"] -->|Trigger| B["🤖 AI Engine"]
    
    B -->|Analisis Data| C{"Status<br/>Kesehatan?"}
    
    C -->|Normal| D["✅ Green Alert<br/>Keep Healthy"]
    C -->|Prehipertensi| E["🟡 Yellow Alert<br/>Monitor & Improve"]
    C -->|Hipertensi Stage 1| F["🟠 Orange Alert<br/>Take Action"]
    C -->|Hipertensi Stage 2| G["🔴 Red Alert<br/>Immediate Action"]
    
    D -->|Generate| H1["✍️ Rekomendasi:<br/>Maintain routine<br/>Regular exercise"]
    E -->|Generate| H2["✍️ Rekomendasi:<br/>Reduce salt intake<br/>Increase activity"]
    F -->|Generate| H3["✍️ Rekomendasi:<br/>Consult doctor<br/>Modify lifestyle"]
    G -->|Generate| H4["✍️ Rekomendasi:<br/>See doctor ASAP<br/>Take medication"]
    
    H1 -->|Simpan| I["📲 Notification"]
    H2 -->|Simpan| I
    H3 -->|Simpan| I
    H4 -->|Simpan| I
    
    I -->|Send| J["📬 Notifikasi ke<br/>Pasien"]
    J -->|Display| K["📱 Dashboard Alert<br/>Severity-based Color"]
    K -->|Pasien Baca| L["👁️ Pasien Terima<br/>Recommendations"]
    
    G -->|Also Alert| M["👨‍⚕️ Notify Doctor"]
    M -->|View| N["⚠️ Warning Patients<br/>List"]
    N -->|Action| O["📞 Doctor Follow-up"]
```

## 4. Notification & Engagement Cycle

```mermaid
graph TD
    A["📲 Notification Queue"] -->|Type| B{"Kategori<br/>Notifikasi?"}
    
    B -->|Health Alert| C["🏥 Health Alert<br/>Notification"]
    B -->|Badge Earned| D["🏅 Achievement<br/>Notification"]
    B -->|Appointment| E["📅 Appointment<br/>Reminder"]
    B -->|Recommendation| F["💡 Health Tips<br/>Notification"]
    
    C -->|Content| C1["Status Change<br/>Alert"]
    D -->|Content| D1["Badge Name<br/>Achievement Details"]
    E -->|Content| E1["Date, Time, Doctor"]
    F -->|Content| F1["Personalized<br/>Recommendation"]
    
    C1 -->|Send| G["👤 To Patient"]
    D1 -->|Send| G
    E1 -->|Send| G
    F1 -->|Send| G
    
    G -->|Deliver| H["📱 Display<br/>In App Notification"]
    H -->|User Interact| I{{"User<br/>Action?"}}
    
    I -->|Read| J["✅ Mark as Read"]
    I -->|Dismiss| K["🚫 Mark as Dismissed"]
    I -->|Click| L["📖 View Details"]
    
    J -->|Update| M["📊 Engagement<br/>Metric"]
    K -->|Log| M
    L -->|Log| M
    M -->|Increase| N["📈 Engagement<br/>Score"]
    N -->|Track| O["🎯 Doctor Analytics"]
    
    L -->|Navigate| P{{"Action<br/>Type?"}}
    P -->|Health Alert| Q["📊 View Trends"]
    P -->|Badge| R["🏅 View Badge"]
    P -->|Appointment| S["📅 View Schedule"]
    P -->|Recommendation| T["💡 Read Full<br/>Recommendation"]
```

## 5. Complete User Journey - Patient

```mermaid
graph LR
    Start["🟢 Start"] -->|Login| A["Dashboard"]
    A -->|Option 1| B1["📝 Input<br/>Health Data"]
    A -->|Option 2| B2["📊 View<br/>Health Records"]
    A -->|Option 3| B3["🏅 View<br/>Achievements"]
    A -->|Option 4| B4["💡 View<br/>Recommendations"]
    A -->|Option 5| B5["📬 Check<br/>Notifications"]
    
    B1 -->|Validate<br/>& Save| C["🎮 Gamification<br/>Engine"]
    C -->|Points<br/>Streak<br/>Badges| D["✨ Update<br/>Profile"]
    
    B2 -->|Display| D
    B3 -->|Display| D
    B4 -->|Display| D
    B5 -->|Display| D
    
    D -->|Notification| E["📲 Receive<br/>Doctor Feedback"]
    E -->|Rate| F["⭐ Give Rating"]
    F -->|Track| G["✅ Follow-up<br/>Status"]
    
    G -->|Continue| A
    A -->|Schedule| H["📅 Book<br/>Consultation"]
    H -->|End| End["🏁 End"]
```

## 6. Complete User Journey - Doctor

```mermaid
graph LR
    Start["🟢 Start"] -->|Login| A["Dashboard"]
    A -->|Option 1| B1["📋 View<br/>Patient List"]
    A -->|Option 2| B2["📊 View<br/>Analytics"]
    A -->|Option 3| B3["⚠️ Monitor<br/>Risk Patients"]
    A -->|Option 4| B4["📞 Patient<br/>Consultation"]
    
    B1 -->|Select| C["👤 Patient<br/>Profile"]
    C -->|View| D["📋 Health<br/>Records"]
    D -->|Analyze| E["📈 Trends"]
    
    B2 -->|View| F["📊 Engagement<br/>Metrics"]
    F -->|Analyze| G["📈 Trends<br/>Distribution"]
    
    B3 -->|Identify| H["🚨 High Risk<br/>Patients"]
    H -->|Action| I["📞 Contact<br/>Patient"]
    
    B4 -->|Input| J["📝 Give<br/>Feedback"]
    J -->|Save| K["💾 Feedback<br/>Stored"]
    K -->|Notify| L["📬 Patient<br/>Notified"]
    L -->|Track| M["✅ Follow-up<br/>Status"]
    
    E -->|Provide| N["📝 Feedback<br/>Konsultasi"]
    G -->|Review| O["📈 Performance<br/>Report"]
    
    N -->|End| End["🏁 End"]
    M -->|Continue| A
    O -->|Continue| A
```

## Key Flows Summary

| Flow | Pemicu | Proses | Output |
|------|--------|--------|--------|
| **Input & Gamification** | Pasien input data | Validasi → Simpan → Hitung poin/streak → Award badge | Dashboard update, Notifikasi |
| **Doctor Analytics** | Dokter akses analytics | Agregasi data → Hitung metrik → Identifikasi risiko | Dashboard metrics, Warning list |
| **Health Recommendations** | Data kesehatan baru | Analisis AI → Generate rekomendasi → Alert berdasar status | Notifikasi + Rekomendasi |
| **Feedback & Follow-up** | Dokter beri feedback | Simpan → Notifikasi → Pasien rating → Track status | Follow-up tracking |
| **Engagement Cycle** | Notifikasi terkirim | User interaksi → Track engagement → Update score | Meningkatkan engagement |

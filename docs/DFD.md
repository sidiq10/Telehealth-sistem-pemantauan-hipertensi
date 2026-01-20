# 📊 Data Flow Diagram (DFD) - Sistem TeleHealth

## Level 0 DFD: Context Diagram

```mermaid
graph TD
    subgraph External_Entities
        P[👤 Pasien<br/>Input: Data Kesehatan<br/>Output: Dashboard, Riwayat]
        D[👨‍⚕️ Dokter<br/>Input: Feedback, Monitoring<br/>Output: Analytics, Reports]
        A[🤖 Sistem AI<br/>Input: Health Data<br/>Output: Recommendations]
    end

    subgraph System_Boundary
        TH[📱 TeleHealth System<br/>Data Processing & Storage]
    end

    subgraph External_Systems
        DB[(🗄️ Database<br/>MySQL)]
        CACHE[(⚡ Redis Cache)]
        EMAIL[📧 Email Service<br/>SMTP]
        OPENAI[🧠 OpenAI API<br/>GPT Models]
    end

    P --> TH
    D --> TH
    A --> TH

    TH --> DB
    TH --> CACHE
    TH --> EMAIL
    TH --> OPENAI

    DB --> TH
    CACHE --> TH
    EMAIL --> TH
    OPENAI --> TH
```

## Level 1 DFD: Main System Processes

```mermaid
graph TD
    subgraph Input_Processes
        IP1[🔐 Authentication<br/>Process 1.0]
        IP2[📝 Health Data Input<br/>Process 2.0]
        IP3[💬 Communication<br/>Process 3.0]
        IP4[🎮 Gamification<br/>Process 4.0]
    end

    subgraph Processing_Processes
        PP1[🧠 AI Analysis<br/>Process 5.0]
        PP2[📊 Analytics Engine<br/>Process 6.0]
        PP3[🔔 Notification System<br/>Process 7.0]
        PP4[📈 Dashboard Generation<br/>Process 8.0]
    end

    subgraph Output_Processes
        OP1[📱 Patient Dashboard<br/>Process 9.0]
        OP2[👨‍⚕️ Doctor Dashboard<br/>Process 10.0]
        OP3[📧 Email Notifications<br/>Process 11.0]
        OP4[📋 Reports & Exports<br/>Process 12.0]
    end

    subgraph Data_Stores
        DS1[(👤 User Data)]
        DS2[(❤️ Health Records)]
        DS3[(💬 Chat Messages)]
        DS4[(🏆 Gamification Data)]
        DS5[(📊 Analytics Cache)]
    end

    IP1 --> PP1
    IP2 --> PP1
    IP3 --> PP2
    IP4 --> PP3

    PP1 --> PP4
    PP2 --> PP4
    PP3 --> PP4

    PP4 --> OP1
    PP4 --> OP2
    PP3 --> OP3
    PP2 --> OP4

    IP1 --> DS1
    IP2 --> DS2
    IP3 --> DS3
    IP4 --> DS4
    PP2 --> DS5

    DS1 --> PP1
    DS2 --> PP1
    DS3 --> PP2
    DS4 --> PP3
    DS5 --> PP4
```

## Level 2 DFD: Patient Health Data Flow

```mermaid
graph TD
    subgraph Patient_Inputs
        PI1[📱 Login Credentials]
        PI2[❤️ Blood Pressure Data<br/>Systolic/Diastolic]
        PI3[💓 Heart Rate]
        PI4[📝 Health Notes]
        PI5[💬 Chat Messages]
    end

    subgraph Data_Validation
        DV1[✅ Input Validation<br/>Process 2.1]
        DV2[🔍 Data Sanitization<br/>Process 2.2]
        DV3[📊 Health Classification<br/>Process 2.3]
    end

    subgraph AI_Processing
        AI1[🧠 Health Insights AI<br/>Process 5.1]
        AI2[💡 Recommendation Engine<br/>Process 5.2]
        AI3[📈 Trend Analysis<br/>Process 5.3]
    end

    subgraph Storage_Layer
        SL1[(📊 Health Records<br/>MySQL)]
        SL2[(⚡ Recommendations<br/>JSON Cache)]
        SL3[(📈 Historical Data<br/>Time Series)]
    end

    subgraph Output_Generation
        OG1[📱 Dashboard Display<br/>Process 9.1]
        OG2[📊 Charts & Graphs<br/>Process 9.2]
        OG3[💬 AI Responses<br/>Process 9.3]
    end

    PI1 --> DV1
    PI2 --> DV1
    PI3 --> DV1
    PI4 --> DV1
    PI5 --> DV2

    DV1 --> DV3
    DV2 --> DV3

    DV3 --> AI1
    AI1 --> AI2
    AI2 --> AI3

    AI1 --> SL1
    AI2 --> SL2
    AI3 --> SL3

    SL1 --> OG1
    SL2 --> OG1
    SL3 --> OG2

    AI2 --> OG3
```

## Level 2 DFD: Doctor Analytics Flow

```mermaid
graph TD
    subgraph Doctor_Inputs
        DI1[🔐 Doctor Login]
        DI2[👥 Patient Selection]
        DI3[💬 Feedback Input]
        DI4[📊 Analytics Filters]
    end

    subgraph Patient_Data_Retrieval
        PR1[📋 Patient List<br/>Process 6.1]
        PR2[❤️ Health Records<br/>Process 6.2]
        PR3[📈 Trend Data<br/>Process 6.3]
        PR4[🏆 Engagement Metrics<br/>Process 6.4]
    end

    subgraph Analytics_Processing
        AP1[📊 Risk Assessment<br/>Process 6.5]
        AP2[📈 Trend Analysis<br/>Process 6.6]
        AP3[👥 Patient Segmentation<br/>Process 6.7]
        AP4[📋 Report Generation<br/>Process 6.8]
    end

    subgraph Feedback_System
        FS1[💬 Feedback Processing<br/>Process 3.1]
        FS2[⭐ Rating Analysis<br/>Process 3.2]
        FS3[📧 Follow-up Tracking<br/>Process 3.3]
    end

    subgraph Output_Delivery
        OD1[📊 Analytics Dashboard<br/>Process 10.1]
        OD2[📋 Patient Reports<br/>Process 10.2]
        OD3[🔔 Alert Notifications<br/>Process 10.3]
        OD4[📧 Email Updates<br/>Process 10.4]
    end

    subgraph Data_Stores
        DS1[(👤 Patient Data)]
        DS2[(❤️ Health Records)]
        DS3[(💬 Feedback History)]
        DS4[(📊 Analytics Cache)]
        DS5[(🔔 Notification Queue)]
    end

    DI1 --> PR1
    DI2 --> PR2
    DI3 --> FS1
    DI4 --> AP1

    PR1 --> PR4
    PR2 --> PR4
    PR3 --> AP2
    PR4 --> AP3

    AP1 --> AP4
    AP2 --> AP4
    AP3 --> AP4

    FS1 --> FS3
    FS2 --> FS3

    AP4 --> OD1
    AP4 --> OD2
    FS3 --> OD3
    OD3 --> OD4

    PR1 --> DS1
    PR2 --> DS2
    FS1 --> DS3
    AP4 --> DS4
    OD3 --> DS5

    DS1 --> PR1
    DS2 --> PR2
    DS3 --> FS2
    DS4 --> OD1
    DS5 --> OD4
```

## Level 2 DFD: AI Module Data Flow

```mermaid
graph TD
    subgraph Input_Data
        ID1[❤️ Raw Health Data<br/>BP, Heart Rate]
        ID2[📈 Historical Trends<br/>7-day averages]
        ID3[👤 Patient Profile<br/>Age, History]
        ID4[💬 User Queries<br/>Chat messages]
        ID5[🎯 Gamification Data<br/>Points, Streaks]
    end

    subgraph Health_Insights_AI
        HIAI1[🔍 Data Preprocessing<br/>Process 5.1.1]
        HIAI2[📊 Risk Classification<br/>Process 5.1.2]
        HIAI3[💊 Recommendation Logic<br/>Process 5.1.3]
        HIAI4[📝 Response Formatting<br/>Process 5.1.4]
    end

    subgraph Chatbot_AI
        CAI1[🧠 Natural Language Processing<br/>Process 5.2.1]
        CAI2[💭 Context Integration<br/>Process 5.2.2]
        CAI3[🤖 GPT Response Generation<br/>Process 5.2.3]
        CAI4[🔄 Fallback Processing<br/>Process 5.2.4]
    end

    subgraph AI_Output
        AO1[📋 Personalized Recommendations<br/>JSON Format]
        AO2[💬 Chat Responses<br/>Natural Language]
        AO3[🚨 Health Alerts<br/>Priority Levels]
        AO4[📊 Predictive Insights<br/>Trend Analysis]
    end

    subgraph External_AI_Services
        EAS1[🧠 OpenAI GPT API<br/>Primary AI Engine]
        EAS2[⚡ Local AI Fallback<br/>Rule-based Logic]
        EAS3[📈 Analytics Engine<br/>Data Mining]
    end

    subgraph Data_Storage
        DST1[(💾 AI Recommendations<br/>JSON Cache)]
        DST2[(💬 Chat History<br/>Database)]
        DST3[(📊 AI Analytics<br/>Redis Cache)]
    end

    ID1 --> HIAI1
    ID2 --> HIAI1
    ID3 --> HIAI1
    ID4 --> CAI1
    ID5 --> HIAI2

    HIAI1 --> HIAI2
    HIAI2 --> HIAI3
    HIAI3 --> HIAI4

    CAI1 --> CAI2
    CAI2 --> CAI3
    CAI3 --> CAI4

    HIAI4 --> AO1
    CAI3 --> AO2
    CAI4 --> AO2
    HIAI2 --> AO3
    HIAI1 --> AO4

    HIAI3 --> EAS1
    CAI3 --> EAS1
    CAI4 --> EAS2
    AO4 --> EAS3

    EAS1 --> HIAI3
    EAS2 --> CAI4
    EAS3 --> AO4

    AO1 --> DST1
    AO2 --> DST2
    AO4 --> DST3

    DST1 --> AO1
    DST2 --> AO2
    DST3 --> AO4
```

## Level 3 DFD: Health Data Input Process

```mermaid
graph TD
    subgraph User_Interface
        UI1[📱 Health Input Form<br/>Web Interface]
        UI2[✅ Form Validation<br/>Client-side]
        UI3[📤 Data Submission<br/>AJAX/HTTP]
    end

    subgraph Input_Processing
        IP1[🔐 Authentication Check<br/>Process 2.1.1]
        IP2[📊 Data Validation<br/>Process 2.1.2]
        IP3[🧹 Data Sanitization<br/>Process 2.1.3]
        IP4[🏷️ Health Classification<br/>Process 2.1.4]
    end

    subgraph AI_Integration
        AI1[🧠 Health Insights Service<br/>Process 2.1.5]
        AI2[💡 Recommendation Generation<br/>Process 2.1.6]
        AI3[📈 Trend Calculation<br/>Process 2.1.7]
    end

    subgraph Database_Operations
        DB1[💾 Record Creation<br/>Process 2.1.8]
        DB2[🏆 Points Awarding<br/>Process 2.1.9]
        DB3[🔥 Streak Update<br/>Process 2.1.10]
        DB4[🏅 Badge Check<br/>Process 2.1.11]
    end

    subgraph Notification_System
        NS1[🔔 Alert Generation<br/>Process 2.1.12]
        NS2[📧 Email Queue<br/>Process 2.1.13]
        NS3[📱 Push Notification<br/>Process 2.1.14]
    end

    subgraph Response_Generation
        RG1[✅ Success Response<br/>Process 2.1.15]
        RG2[📊 Updated Dashboard<br/>Process 2.1.16]
        RG3[📈 Real-time Updates<br/>Process 2.1.17]
    end

    subgraph Data_Flows
        DF1[❤️ Health Data<br/>Systolic/Diastolic/HR]
        DF2[👤 User Context<br/>ID, Role, History]
        DF3[🤖 AI Recommendations<br/>JSON Response]
        DF4[🏆 Gamification Data<br/>Points, Badges]
        DF5[🔔 Notification Data<br/>Alerts, Messages]
    end

    UI1 --> UI2
    UI2 --> UI3

    UI3 --> IP1
    IP1 --> IP2
    IP2 --> IP3
    IP3 --> IP4

    IP4 --> AI1
    AI1 --> AI2
    AI2 --> AI3

    AI3 --> DB1
    DB1 --> DB2
    DB2 --> DB3
    DB3 --> DB4

    DB4 --> NS1
    NS1 --> NS2
    NS2 --> NS3

    NS3 --> RG1
    RG1 --> RG2
    RG2 --> RG3

    DF1 --> IP2
    DF2 --> AI1
    AI2 --> DF3
    DB2 --> DF4
    NS1 --> DF5

    DF3 --> RG2
    DF4 --> RG2
    DF5 --> RG3
```

## Data Dictionary

### Data Flows

| Data Flow | Description | Source | Destination | Format |
|-----------|-------------|--------|-------------|--------|
| **DF1 - Health Data** | Raw blood pressure, heart rate, notes | Patient Input Form | Validation Process | JSON |
| **DF2 - User Context** | User ID, role, medical history | Authentication | AI Processing | Object |
| **DF3 - AI Recommendations** | Personalized health advice | AI Engine | Database/Response | JSON |
| **DF4 - Gamification Data** | Points, streaks, badges earned | Gamification Engine | User Dashboard | JSON |
| **DF5 - Notifications** | Alerts, reminders, updates | Notification System | Email/Push Service | JSON |

### Data Stores

| Data Store | Description | Structure | Access |
|------------|-------------|-----------|--------|
| **DS1 - User Data** | User profiles, credentials, roles | MySQL Table | CRUD |
| **DS2 - Health Records** | BP readings, vitals, timestamps | MySQL Table | Create/Read |
| **DS3 - Chat Messages** | AI conversations, history | MySQL Table | Create/Read |
| **DS4 - Gamification** | Points, badges, achievements | MySQL/Redis | Read/Update |
| **DS5 - Analytics Cache** | Computed metrics, reports | Redis Cache | Read/Write |

### Processes

| Process ID | Name | Description | Input | Output |
|------------|------|-------------|-------|--------|
| **1.0** | Authentication | User login/validation | Credentials | Session Token |
| **2.0** | Health Data Input | Process health submissions | Health Data | Validated Records |
| **3.0** | Communication | Handle chat/feedback | Messages | Responses |
| **4.0** | Gamification | Award points/badges | User Actions | Achievements |
| **5.0** | AI Analysis | Generate insights | Health Data | Recommendations |
| **6.0** | Analytics | Process metrics | Raw Data | Reports |
| **7.0** | Notifications | Send alerts | Events | Messages |
| **8.0** | Dashboard Gen | Create UI data | Processed Data | Display Data |

### External Entities

| Entity | Description | Data Input | Data Output |
|--------|-------------|------------|-------------|
| **Patient** | End user monitoring health | Health data, queries | Dashboard, reports |
| **Doctor** | Healthcare provider | Feedback, monitoring | Analytics, alerts |
| **AI System** | OpenAI GPT service | Health context | Recommendations |
| **Email Service** | SMTP notification service | Alert data | Sent emails |

## Trust Boundaries & Security

```mermaid
graph TD
    subgraph Public_Zone
        PZ1[🌐 Web Interface<br/>Public Access]
        PZ2[📧 Email Notifications<br/>Outbound Only]
    end

    subgraph DMZ_Zone
        DMZ1[🔐 Authentication Layer<br/>Session Management]
        DMZ2[🛡️ Input Validation<br/>Sanitization]
        DMZ3[📊 Public Analytics<br/>Read-Only]
    end

    subgraph Application_Zone
        AZ1[⚙️ Business Logic<br/>Laravel Controllers]
        AZ2[🤖 AI Services<br/>Health Insights]
        AZ3[🎮 Gamification Engine<br/>Point System]
    end

    subgraph Data_Zone
        DZ1[(🔒 User Data<br/>Encrypted)]
        DZ2[(❤️ Health Records<br/>PHI Data)]
        DZ3[(💬 Chat History<br/>Sensitive)]
        DZ4[(⚡ Cache Layer<br/>Redis)]
    end

    subgraph External_Zone
        EZ1[🧠 OpenAI API<br/>External Service]
        EZ2[📧 SMTP Server<br/>Mail Service]
        EZ3[📱 Push Notifications<br/>FCM/APNs]
    end

    PZ1 --> DMZ1
    PZ2 --> EZ2

    DMZ1 --> AZ1
    DMZ2 --> AZ1
    DMZ3 --> AZ2

    AZ1 --> DZ1
    AZ2 --> DZ2
    AZ3 --> DZ3

    AZ1 --> EZ1
    AZ2 --> EZ3

    DZ4 --> AZ1
    DZ4 --> AZ2
    DZ4 --> AZ3
```

## Data Flow Summary

### Primary Data Flows:
1. **Patient → System**: Health data input and queries
2. **System → AI**: Health data for analysis and recommendations
3. **AI → System**: Personalized insights and responses
4. **System → Doctor**: Analytics and patient monitoring data
5. **System → Patient**: Dashboard updates and notifications
6. **System → External**: Email alerts and push notifications

### Critical Data Paths:
- **Health Data Pipeline**: Input → Validation → AI → Storage → Display
- **Communication Pipeline**: Message → AI → Response → Storage
- **Gamification Pipeline**: Action → Points → Badges → Notifications
- **Analytics Pipeline**: Raw Data → Processing → Cache → Dashboard

This DFD provides a comprehensive view of how data flows through the TeleHealth system, highlighting the AI integration and multi-user interactions.

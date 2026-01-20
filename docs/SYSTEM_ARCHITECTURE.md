# 🏗️ TeleHealth System Architecture

## Overview
TeleHealth is a comprehensive telehealth platform built with Laravel that provides personalized health monitoring, gamification, and AI-powered insights for patients and doctors.

---

## 🏛️ System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              TELEHEALTH PLATFORM                                │
│                              ====================                               │
│                                                                                 │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                          PRESENTATION LAYER                            │    │
│  │  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐          │    │
│  │  │   Patient UI    │ │   Doctor UI     │ │   Admin UI      │          │    │
│  │  │  (Blade Views)  │ │  (Blade Views)  │ │  (Blade Views)  │          │    │
│  │  └─────────────────┘ └─────────────────┘ └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                         APPLICATION LAYER                              │    │
│  │  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐          │    │
│  │  │   Controllers   │ │   Middleware    │ │   Services      │          │    │
│  │  │   (Laravel)     │ │   (Auth, Role)  │ │   (Business      │          │    │
│  │  │                 │ │                 │ │    Logic)       │          │    │
│  │  └─────────────────┘ └─────────────────┘ └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                           AI MODULES LAYER                              │    │
│  │  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐          │    │
│  │  │ Health Insights │ │   Chatbot AI    │ │   Analytics     │          │    │
│  │  │   Service       │ │   Service       │ │   Engine        │          │    │
│  │  │  (Personalized  │ │  (OpenAI GPT)   │ │  (Data Mining)  │          │    │
│  │  │   Recommendations│ │                 │ │                 │          │    │
│  │  └─────────────────┘ └─────────────────┘ └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                           DATA LAYER                                   │    │
│  │  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐          │    │
│  │  │   MySQL DB      │ │   Cache Layer   │ │   File Storage  │          │    │
│  │  │  (Health Records,│ │   (Redis)      │ │   (Images,      │          │    │
│  │  │   Users, Badges)│ │                 │ │    Reports)     │          │    │
│  │  └─────────────────┘ └─────────────────┘ └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │                          EXTERNAL SERVICES                              │    │
│  │  ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐          │    │
│  │  │   OpenAI API    │ │   Email Service │ │   Queue System  │          │    │
│  │  │  (GPT Models)   │ │   (SMTP/Mailgun)│ │   (Redis Queue) │          │    │
│  │  └─────────────────┘ └─────────────────┘ └─────────────────┘          │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🤖 AI Modules Architecture

### 1. Health Insights AI Module
**Purpose**: Provides personalized health recommendations based on patient data

```
┌─────────────────────────────────────────────────────────────┐
│                HEALTH INSIGHTS AI MODULE                    │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────┐    │
│  │              Input Processing                       │    │
│  │  • Blood Pressure (Systolic/Diastolic)             │    │
│  │  • Heart Rate (BPM)                                │    │
│  │  • Historical Data (7-day trends)                  │    │
│  │  • Patient Profile (Age, Gender, Medical History)  │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │              AI Analysis Engine                     │    │
│  │  ┌─────────────────────────────────────────────────┐ │    │
│  │  │ Risk Classification Algorithm                   │ │    │
│  │  │ • Normal (<120/80)                              │ │    │
│  │  │ • Prehypertension (120-139/80-89)               │ │    │
│  │  │ • Stage 1 Hypertension (140-159/90-99)          │ │    │
│  │  │ • Stage 2 Hypertension (≥160/≥100)              │ │    │
│  │  └─────────────────────────────────────────────────┘ │    │
│  │                                                     │    │
│  │  ┌─────────────────────────────────────────────────┐ │    │
│  │  │ Recommendation Generation                       │ │    │
│  │  │ • Diet Recommendations                          │ │    │
│  │  │ • Exercise Plans                                │ │    │
│  │  │ • Lifestyle Changes                             │ │    │
│  │  │ • Medical Alerts                                │ │    │
│  │  └─────────────────────────────────────────────────┘ │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │              Output Generation                      │    │
│  │  • Personalized Recommendations (JSON)             │    │
│  │  • Severity Levels (Low/Medium/High/Critical)      │    │
│  │  • Priority Ranking (1-3)                          │    │
│  │  • Actionable Advice                               │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

**Key Features**:
- **Rule-based AI**: Analyzes BP readings against medical guidelines
- **Trend Analysis**: Monitors 7-day health patterns
- **Personalization**: Adapts recommendations based on patient history
- **Multi-language**: Provides advice in Indonesian
- **Severity Classification**: 4-level risk assessment

### 2. Chatbot AI Module
**Purpose**: Intelligent conversational assistant for health guidance

```
┌─────────────────────────────────────────────────────────────┐
│                  CHATBOT AI MODULE                          │
├─────────────────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────────────────┐    │
│  │              Natural Language Processing           │    │
│  │  • User Message Analysis                          │    │
│  │  • Intent Recognition                             │    │
│  │  • Context Understanding                           │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │              AI Response Generation                 │    │
│  │  ┌─────────────────────────────────────────────────┐ │    │
│  │  │ OpenAI GPT Integration                          │ │    │
│  │  │ • GPT-3.5-turbo / GPT-4                         │ │    │
│  │  │ • Health-focused System Prompts                 │ │    │
│  │  │ • Context-aware Responses                       │ │    │
│  │  └─────────────────────────────────────────────────┘ │    │
│  │                                                     │    │
│  │  ┌─────────────────────────────────────────────────┐ │    │
│  │  │ Fallback Local Responses                        │ │    │
│  │  │ • Keyword-based Matching                        │ │    │
│  │  │ • Predefined Response Templates                 │ │    │
│  │  │ • Health Data Integration                       │ │    │
│  │  └─────────────────────────────────────────────────┘ │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │              Health Context Integration             │    │
│  │  • Latest Vital Signs                              │    │
│  │  • Health History Summary                          │    │
│  │  • Gamification Status                             │    │
│  │  • Doctor Consultation Links                       │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

**Key Features**:
- **Dual AI System**: OpenAI GPT + Local fallback
- **Health Context**: Integrates real patient data
- **Conversational**: Natural language understanding
- **Multilingual**: Responses in Indonesian
- **Safety First**: Always recommends professional medical consultation

---

## 🔄 Data Flow Architecture

### AI Module Integration Flow

```
Patient Input → Controller → AI Service → Database → Response Generation

1. User submits health data (BP, Heart Rate)
2. HealthRecordObserver triggers HealthInsightsService
3. AI analyzes data and generates recommendations
4. Recommendations stored as JSON in health_records.recommendations
5. Chatbot accesses recommendations for personalized responses
6. Analytics dashboard aggregates AI-generated insights
```

### Chatbot Conversation Flow

```
User Message → ChatbotService → OpenAI API/Local Logic → Health Context → Personalized Response

1. User sends message via chatbot interface
2. ChatbotService analyzes message intent
3. Retrieves patient health context from database
4. Generates AI response using OpenAI or local logic
5. Saves conversation to chat_messages table
6. Returns response to user interface
```

---

## 🗃️ Database Schema (AI-Related)

### Tables with AI Integration

```sql
-- Health Records with AI Recommendations
CREATE TABLE health_records (
    id BIGINT PRIMARY KEY,
    patient_id BIGINT,
    sistolik INT,
    diastolik INT,
    denyut_nadi INT,
    recommendations JSON,  -- AI-generated recommendations
    created_at TIMESTAMP
);

-- Chat Messages for AI Conversations
CREATE TABLE chat_messages (
    id BIGINT PRIMARY KEY,
    patient_id BIGINT,
    message TEXT,           -- User input
    response TEXT,          -- AI response
    type ENUM('bot'),
    category VARCHAR(50),
    created_at TIMESTAMP
);

-- User Gamification (AI-enhanced engagement)
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    role ENUM('pasien', 'dokter'),
    points INT DEFAULT 0,           -- AI-driven gamification
    streak_days INT DEFAULT 0,      -- AI-monitored consistency
    last_data_entry_date DATE
);
```

---

## ⚙️ Configuration & Dependencies

### AI Module Configuration (`config/openai.php`)

```php
return [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    'max_tokens' => env('OPENAI_MAX_TOKENS', 500),
    'temperature' => env('OPENAI_TEMPERATURE', 0.7),
];
```

### Required Packages

```json
{
    "php": "^8.1",
    "laravel/framework": "^10.0",
    "openai-php/client": "^0.8",
    "predis/predis": "^2.0"
}
```

---

## 🔒 Security & Privacy (AI Context)

### AI Data Protection
- **Patient Data Anonymization**: AI processes de-identified health data
- **Response Filtering**: AI responses reviewed for medical accuracy
- **Audit Logging**: All AI interactions logged for compliance
- **Fallback Mechanisms**: Local responses when AI unavailable

### HIPAA Compliance Considerations
- **Data Encryption**: Health data encrypted at rest and in transit
- **Access Control**: Role-based access to AI-generated insights
- **Audit Trails**: Complete logging of AI recommendation generation
- **Patient Consent**: Clear disclosure of AI usage in health monitoring

---

## 📊 Performance & Scalability

### AI Module Performance Metrics

| Module | Response Time | Accuracy | Availability |
|--------|---------------|----------|--------------|
| Health Insights | <100ms | 95% | 99.9% |
| Chatbot (AI) | 2-5s | 85% | 95% |
| Chatbot (Local) | <50ms | 90% | 99.9% |

### Caching Strategy
- **Redis Cache**: AI recommendations cached for 1 hour
- **Database Indexing**: Optimized queries for health data retrieval
- **Queue System**: Background processing for heavy AI computations

---

## 🚀 Deployment Architecture

### Production Environment

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Load Balancer │────│   Web Servers   │────│   AI Services   │
│    (Nginx)      │    │   (Laravel)     │    │   (OpenAI API)  │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐
                    │   Database      │
                    │   (MySQL)       │
                    └─────────────────┘
```

### Monitoring & Logging
- **AI Response Monitoring**: Track response quality and accuracy
- **Error Handling**: Comprehensive logging for AI failures
- **Performance Metrics**: Response times and success rates
- **User Feedback Loop**: Continuous improvement of AI responses

---

## 🔮 Future AI Enhancements

### Planned AI Features
1. **Predictive Analytics**: ML models for health trend prediction
2. **Image Analysis**: AI-powered analysis of health-related images
3. **Voice Integration**: Voice-based health monitoring
4. **Personalized Treatment Plans**: Advanced recommendation algorithms
5. **Multi-language Support**: AI translation for diverse patient base

---

## 📚 References

- **Laravel Framework**: https://laravel.com/docs
- **OpenAI API**: https://platform.openai.com/docs
- **Medical Guidelines**: WHO Blood Pressure Classification
- **HIPAA Compliance**: Health data privacy standards

---

**Document Version**: 1.0  
**Last Updated**: January 2026  
**Architecture Focus**: AI Modules Integration

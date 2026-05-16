# 병원 관리 시스템 (HMS)

Laravel 10 기반 병원 관리 웹 애플리케이션입니다. Gemini AI 어시스턴트, 신체 통증 지도(Body Pain Map), 예약·처방전·결제 관리 기능을 포함합니다.

## 요구 사항

- **PHP 8.1 이상** (8.3 권장)
- **PHP 확장:** `gd`, `sqlite3`, `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`
- **Composer** — `vendor/`가 포함되지 않은 경우 필요
- **웹 브라우저** (Chrome, Edge, Firefox — Bootstrap 5 CDN 사용)

PHP 버전 확인:

```bash
php -v
```

GD 확장 확인 (Windows 예시):

```cmd
php -m | findstr gd
```

## 설치 (3단계)

### 1. `.env` 파일 만들기

프로젝트 폴더에서 `.env.example`을 `.env`로 복사합니다.

```bash
cp .env.example .env
```

Windows:

```cmd
copy .env.example .env
```

### 2. 앱 키 및 Gemini API 키

```bash
php artisan key:generate
```

`.env` 파일에서 **`GEMINI_API_KEY`**에 본인의 키를 입력합니다.

```
GEMINI_API_KEY=AIza...본인키
```

키 발급: https://aistudio.google.com/apikey

### 3. 서버 실행

`vendor/`가 이미 있으면:

```bash
php artisan serve --port=9000
```

`vendor/`가 없으면:

```bash
composer install
php artisan migrate --seed
php artisan serve --port=9000
```

브라우저에서 http://localhost:9000 을 엽니다.

## 테스트 계정

시더로 생성되며, 공통 비밀번호는 **`password`** 입니다.

| 역할            | 이메일                                        | 비밀번호 |
| --------------- | --------------------------------------------- | -------- |
| 관리자          | admin@hospital.com                            | password |
| 의사 (심장내과) | doctor1@hospital.com                          | password |
| 의사 (신경과)   | doctor2@hospital.com                          | password |
| 의사 (정형외과) | doctor3@hospital.com                          | password |
| 환자 #1~5       | patient1@hospital.com ~ patient5@hospital.com | password |

## 주요 기능

- **3가지 역할** — 관리자, 의사, 환자
- **예약** — 환자가 신청, 의사가 확정
- **처방전** — 의사가 작성, PDF보내기
- **결제** — 결제 상태 추적
- **통증 신체 지도** — 관리자 환자 상세에서 통증 부위 시각화
- **HMS AI 어시스턴트** — Google Gemini 2.5 Flash 연동 챗봇
- **PDF** — barryvdh/laravel-dompdf

## 기술 스택

- **백엔드:** Laravel 10, PHP 8.1+
- **데이터베이스:** SQLite (`database/database.sqlite`)
- **프론트엔드:** Bootstrap 5 (CDN), Bootstrap Icons, 바닐라 JS
- **AI:** Google Gemini 2.5 Flash API
- **PDF:** barryvdh/laravel-dompdf

## 프로젝트 구조 (요약)

```
hospital-management/
├── app/Http/Controllers/   # 컨트롤러 (ChatController — AI)
├── app/Models/
├── database/migrations/
├── database/seeders/
├── resources/views/
├── config/labels.php        # 한국어 UI 라벨
├── routes/web.php
└── .env
```

## UI 언어

애플리케이션 기본 로케일은 **`config/app.php`** 의 `locale` = `ko` 입니다.  
공통 라벨(예약 상태, 결제 상태 등)은 `config/labels.php`에서 수정할 수 있습니다.

## PDF 한글 표시

DomPDF 기본 폰트(DejaVu Sans)는 한글 글리프가 제한될 수 있습니다. PDF에서 한글이 깨지면 DomPDF용 한글 폰트(예: Noto Sans KR)를 등록해 사용하세요.

## 문제 해결

### "Class GuzzleHttp\HandlerStack not found"

`composer install`을 실행하세요.

### 채팅에서 오류

`.env`의 `GEMINI_API_KEY`를 확인하세요.  
429(요청 한도)인 경우 무료 한도 초과일 수 있습니다.

### SQLite 드라이버 오류

`php.ini`에서 `pdo_sqlite` 확장을 활성화하세요.

### 포트 9000 사용 중

다른 포트로 실행: `php artisan serve --port=8000`

## 제작

교육용 데모 프로젝트입니다.

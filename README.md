# Simple Accounting API

A lightweight Laravel-based accounting API that provides basic double-entry bookkeeping functionality, including journal entry management, general ledger reporting, and balance sheet reports.

## Table of Contents

1. [About the Project](#about-the-project)
2. [Setup Instructions](#setup-instructions)
3. [Default Master Data](#default-master-data)
   * [Account Classifications](#1-account-classifications)
   * [Cash Flow Classifications](#2-cash-flow-classifications)
   * [Account Sub-Classifications](#3-account-sub-classifications)
   * [Accounts (Chart of Accounts)](#4-accounts-chart-of-accounts)
4. [API Reference & Examples](#api-reference--examples)
   * [Create Journal Entry](#1-create-journal-entry)
   * [Update Journal Entry](#2-update-journal-entry)
   * [Delete Journal Entry](#3-delete-journal-entry)
   * [Get Account Ledger](#4-get-account-ledger)
   * [Balance Sheet Report](#5-balance-sheet-report)
   * [Income Statement Report](#6-income-statement-report)

## About the Project

This project provides a simple accounting backend for managing double-entry journal records, general ledger reports, and balance sheet calculations.

### Key Features

* **Journal Entries:** Record financial transactions with balanced debit and credit entries.
* **General Ledger Report:** Retrieve detailed account activity within an optional date range (`from` and `to`), along with opening and closing balance calculations.
* **Financial Reports:** Generate a Balance Sheet report as of a specified date (`as_of`).
* **Income Statement:** Report revenue, expenses, and net income for a date range (`from` and `to`).

## Setup Instructions

Follow these steps to set up the project locally:

### 1. Install Dependencies

Install the PHP dependencies using Composer:

```bash
composer install
```

### 2. Environment Configuration

Copy the example environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Ensure your `.env` file is properly configured with your database credentials.

### 3. Database Migration and Seeding

Run database migrations to create the required tables and seed initial master data:

```bash
php artisan migrate --seed
```

### 4. Serve the Application

Start the local development server:

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

## Default Master Data

The application seeds default accounting classification and master account data upon running `php artisan db:seed`.

### 1. Account Classifications

| Code | Name | Root Type | Normal Balance |
| ----- | ----- | ----- | ----- |
| `100` | Asset | Assets | Debit |
| `200` | Liabilities | Liabilities | Credit |
| `300` | Equity | Equity | Credit |
| `400` | Revenue | Revenue | Credit |
| `500` | Cost Of Sales | Expenses | Debit |
| `600` | Operating Expense | Expenses | Debit |
| `700` | Non Operating Expense | Expenses | Debit |
| `800` | Other Income | Revenue | Credit |
| `900` | Other Expense | Expenses | Debit |

### 2. Cash Flow Classifications

| Code | Name |
| ----- | ----- |
| `001` | Undefined |
| `002` | Operating Activity |
| `003` | Investing Activity |
| `004` | Financing Activity |

### 3. Account Sub-Classifications

| Account Classification ID | Cash Flow Classification ID | Code | Name |
| ----- | ----- | ----- | ----- |
| `1` | `1` | `1100` | Cash |
| `1` | `1` | `1200` | Bank |
| `1` | `2` | `1300` | Receiveables |
| `1` | `2` | `1400` | Inventories |
| `1` | `3` | `1500` | Fixed Asset |
| `2` | `2` | `2100` | Current Liabilities |
| `3` | `4` | `3100` | Equity |
| `3` | `4` | `3200` | Earning |
| `4` | `2` | `4100` | Income |
| `5` | `2` | `5100` | Cost of Goods Sold |
| `6` | `2` | `6100` | Operating Expense |
| `7` | `3` | `7100` | Non Operating Expense |
| `8` | `2` | `8100` | Other Income |
| `9` | `2` | `9100` | Other Expense |

### 4. Accounts (Chart of Accounts)

| ID | Account Class ID | Sub Class ID | Code | Name | Cash/Bank |
| ----- | ----- | ----- | ----- | ----- | ----- |
| `1` | `1` | `1` | `1101` | Petty Cash | Yes (`1`) |
| `2` | `1` | `2` | `1201` | Bank | Yes (`1`) |
| `3` | `1` | `4` | `1301` | Account Receiveable | No (`0`) |
| `4` | `1` | `4` | `1401` | Inventory | No (`0`) |
| `5` | `1` | `5` | `1501` | Property | No (`0`) |
| `6` | `2` | `6` | `2101` | Account Payable | No (`0`) |
| `7` | `2` | `6` | `2102` | Tax Payable | No (`0`) |
| `8` | `3` | `7` | `3101` | Owner Capital | No (`0`) |
| `9` | `3` | `8` | `3201` | Current Year Earning | No (`0`) |
| `10` | `4` | `9` | `4101` | Food Sales | No (`0`) |
| `11` | `4` | `9` | `4102` | Beverage Sales | No (`0`) |
| `12` | `4` | `9` | `4103` | Service Charge | No (`0`) |
| `13` | `5` | `10` | `5101` | Cost of Food Sales | No (`0`) |
| `14` | `5` | `10` | `5102` | Cost of Beverage Sales | No (`0`) |
| `15` | `6` | `11` | `6101` | Sales Discount | No (`0`) |
| `16` | `6` | `11` | `6102` | Purchase Discount | No (`0`) |
| `17` | `6` | `11` | `6201` | Rent Expense | No (`0`) |
| `18` | `7` | `12` | `7101` | Property Depreciation | No (`0`) |
| `19` | `8` | `13` | `8101` | Interest Income | No (`0`) |
| `20` | `9` | `14` | `9100` | Miscellaneous Expense | No (`0`) |

## API Reference & Examples

### 1. Create Journal Entry

* **Endpoint:** `POST /api/journal-entries`
* **Headers:** `Content-Type: application/json`, `Accept: application/json`

#### cURL Request

```bash
curl -X POST http://localhost:8000/api/journal-entries \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "JV-2026-0002",
    "journal_date": "2026-10-18 10:30:00",
    "description": "Cash receipt",
    "journal_entry_details": [
        {
            "account_id": 1,
            "voucher": "RC-001",
            "description": "Cash received",
            "debit": 1000,
            "credit": 0
        },
        {
            "account_id": 2,
            "voucher": "RC-001",
            "description": "Revenue received",
            "debit": 0,
            "credit": 1000
        }
    ]
}'
```

#### Response (`201 Created`)

```json
{
    "id": 1,
    "code": "JV-2026-0002",
    "journal_date": "2026-10-18 10:30:00",
    "description": "Cash receipt",
    "total_debit": 1000,
    "total_credit": 1000,
    "created_at": "2026-10-18T14:47:56.000000Z",
    "updated_at": "2026-10-18T14:54:57.000000Z",
    "journal_entry_details": [
        {
            "id": 3,
            "journal_entry_id": 1,
            "account_id": 1,
            "rownumber": 1,
            "voucher": "RC-001",
            "description": "Cash received",
            "debit": 1000,
            "credit": 0,
            "created_at": "2026-10-18T14:54:57.000000Z",
            "updated_at": "2026-10-18T14:54:57.000000Z"
        },
        {
            "id": 4,
            "journal_entry_id": 1,
            "account_id": 2,
            "rownumber": 2,
            "voucher": "RC-001",
            "description": "Revenue received",
            "debit": 0,
            "credit": 1000,
            "created_at": "2026-10-18T14:54:57.000000Z",
            "updated_at": "2026-10-18T14:54:57.000000Z"
        }
    ]
}
```

### 2. Update Journal Entry

* **Endpoint:** `PUT /api/journal-entries/{id}`
* **Headers:** `Content-Type: application/json`, `Accept: application/json`

#### cURL Request

```bash
curl -X PUT http://localhost:8000/api/journal-entries/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "code": "JV-2026-0002",
    "journal_date": "2026-10-18 10:30:00",
    "description": "Updated Cash receipt",
    "journal_entry_details": [
        {
            "account_id": 1,
            "voucher": "RC-001",
            "description": "Cash received updated",
            "debit": 1500,
            "credit": 0
        },
        {
            "account_id": 2,
            "voucher": "RC-001",
            "description": "Revenue received updated",
            "debit": 0,
            "credit": 1500
        }
    ]
}'
```

#### Response (`200 OK`)

```json
{
    "id": 1,
    "code": "JV-2026-0002",
    "journal_date": "2026-10-18 10:30:00",
    "description": "Updated Cash receipt",
    "total_debit": 1500,
    "total_credit": 1500,
    "created_at": "2026-10-18T14:47:56.000000Z",
    "updated_at": "2026-10-18T15:00:00.000000Z",
    "journal_entry_details": [
        {
            "id": 3,
            "journal_entry_id": 1,
            "account_id": 1,
            "rownumber": 1,
            "voucher": "RC-001",
            "description": "Cash received updated",
            "debit": 1500,
            "credit": 0,
            "created_at": "2026-10-18T14:54:57.000000Z",
            "updated_at": "2026-10-18T15:00:00.000000Z"
        },
        {
            "id": 4,
            "journal_entry_id": 1,
            "account_id": 2,
            "rownumber": 2,
            "voucher": "RC-001",
            "description": "Revenue received updated",
            "debit": 0,
            "credit": 1500,
            "created_at": "2026-10-18T14:54:57.000000Z",
            "updated_at": "2026-10-18T15:00:00.000000Z"
        }
    ]
}
```

### 3. Delete Journal Entry

* **Endpoint:** `DELETE /api/journal-entries/{id}`
* **Headers:** `Accept: application/json`

#### cURL Request

```bash
curl -X DELETE http://localhost:8000/api/journal-entries/1 \
  -H "Accept: application/json"
```

#### Response (`204 No Content`)

```http
HTTP/1.1 204 No Content
```

### 4. Get Account Ledger

* **Endpoint:** `GET /api/accounts/ledger`
* **Query Parameters:**
  * `account_id` (required, integer): ID of the target account.
  * `from` (optional, date): Filter start date (`YYYY-MM-DD`).
  * `to` (optional, date): Filter end date (`YYYY-MM-DD`).

#### cURL Request (Basic)

```bash
curl -X GET "http://localhost:8000/api/accounts/ledger?account_id=1" \
  -H "Accept: application/json"
```

#### cURL Request (With Date Filter)

```bash
curl -X GET "http://localhost:8000/api/accounts/ledger?account_id=1&from=2026-10-01&to=2026-10-31" \
  -H "Accept: application/json"
```

#### Response (`200 OK`)

```json
{
    "account_id": "1",
    "from": null,
    "to": null,
    "opening_balance": 0,
    "total_debit": 1000,
    "total_credit": 0,
    "closing_balance": 1000,
    "transactions": [
        {
            "journal_date": "2026-10-18 10:30:00account_classificationsaccount_classifications",
            "code": "JV-2026-0002",
            "journal_description": "Test",
            "rownumber": 1,
            "voucher": "RC-001",
            "description": "Cash received",
            "debit": 1000,
            "credit": 0,
            "balance": 1000
        }
    ]
}
```

### 5. Balance Sheet Report

* **Endpoint:** `GET /api/financial-reports/balance-sheet`
* **Query Parameters:**
  * `as_of` (required, date): The cutoff date for the balance sheet (`YYYY-MM-DD`).

#### cURL Request

```bash
curl -X GET "http://localhost:8000/api/financial-reports/balance-sheet?as_of=2027-10-18" \
  -H "Accept: application/json"
```

#### Response (`200 OK`)

```json
{
    "as_of": "2027-10-18",
    "assets": {
        "accounts": [
            {
                "account_id": 1,
                "account_code": "1101",
                "account_name": "Petty Cash",
                "balance": 1000
            },
            {
                "account_id": 2,
                "account_code": "1201",
                "account_name": "Bank",
                "balance": 0
            },
            {
                "account_id": 3,
                "account_code": "1301",
                "account_name": "Account Receiveable",
                "balance": 0
            },
            {
                "account_id": 4,
                "account_code": "1401",
                "account_name": "Inventory",
                "balance": 0
            },
            {
                "account_id": 5,
                "account_code": "1501",
                "account_name": "Property",
                "balance": 0
            }
        ],
        "total": 1000
    },
    "liabilities": {
        "accounts": [
            {
                "account_id": 6,
                "account_code": "2101",
                "account_name": "Account Payable",
                "balance": -0
            },
            {
                "account_id": 7,
                "account_code": "2102",
                "account_name": "Tax Payable",
                "balance": -0
            }
        ],
        "total": 0
    },
    "equity": {
        "accounts": [
            {
                "account_id": 8,
                "account_code": "3101",
                "account_name": "Owner Capital",
                "balance": -0
            },
            {
                "account_id": 9,
                "account_code": "3201",
                "account_name": "Current Year Earning",
                "balance": -0
            }
        ],
        "net_income": 1000,
        "total": 1000
    }
}
```

### 6. Income Statement Report

* **Endpoint:** `GET /api/financial-reports/income-statement`
* **Query Parameters:**
    * `from` (required, date): The start date for the income statement (`YYYY-MM-DD`).
    * `to` (required, date): The end date for the income statement (`YYYY-MM-DD`). Must be on or after `from`

#### cURL Request

```bash
curl -X GET "http://localhost:8000/api/financial-reports/income-statement?from=2026-10-01&to=2026-10-31" \
  -H "Accept: application/json"
```

#### Response (`200 OK`)

```json
{
  "from": "2026-10-01",
  "to": "2026-10-31",
  "revenue": {
    "accounts": [
      {
        "account_id": 10,
        "account_code": "4101",
        "account_name": "Food Sales",
        "balance": 1000
      }
    ],
    "total": 1000
  },
  "expenses": {
    "accounts": [
      {
        "account_id": 13,
        "account_code": "5101",
        "account_name": "Cost of Food Sales",
        "balance": 300
      }
    ],
    "total": 300
  },
  "net_income": 700
}
```

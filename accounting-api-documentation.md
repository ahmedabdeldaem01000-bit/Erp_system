# 1. Project Overview

This financial ERP system implements a Chart of Accounts, Journal Entries, Ledger Posting, and Reverse Journal Entry flow. It supports account hierarchy, postable account validation, balanced debit/credit ledger posting, and structured API responses for accounting workflows.

# 2. Latest Changes

- Added Chart of Accounts API with hierarchical parent/child accounts and account archiving.
- Implemented `LedgerPostingService` for journal entry creation, validation, and ledger posting.
- Added journal entry posting rules that require `total debit == total credit`.
- Added reverse journal entry support via `POST /api/journal-entries/{id}/reverse`.
- Added status tracking for journal entries: `posted` and `reversed`.
- Added journal entry response resources with line-level account details and totals.
- Enforced account `is_postable` validation for journal entry lines.
- Protected accounting APIs behind `auth:sanctum` and `role:admin` middleware.

# 3. API Documentation (Postman Ready)

## Accounts APIs

### List Accounts
- Method: GET
- URL: `/api/accounts`
- Description: Returns top-level accounts with nested child accounts.
- Request Body: None
- Response Example:
```json
[
  {
    "id": 1,
    "code": "1000",
    "name": "Cash",
    "type": "asset",
    "parent_id": null,
    "is_postable": true,
    "is_active": true,
    "children": [],
    "created_at": "2026-06-14T12:00:00.000000Z"
  }
]
```

### Get Account
- Method: GET
- URL: `/api/accounts/{id}`
- Description: Returns a single account with its child accounts.
- Request Body: None
- Response Example:
```json
{
  "id": 1,
  "code": "1000",
  "name": "Cash",
  "type": "asset",
  "parent_id": null,
  "is_postable": true,
  "is_active": true,
  "children": [],
  "created_at": "2026-06-14T12:00:00.000000Z"
}
```

### Create Account
- Method: POST
- URL: `/api/accounts`
- Description: Creates a new chart of account entry.
- Request Body:
```json
{
  "code": "1000",
  "name": "Cash",
  "type": "asset",
  "parent_id": null,
  "is_postable": true
}
```
- Response Example:
```json
{
  "message": "Account created successfully",
  "data": {
    "id": 1,
    "code": "1000",
    "name": "Cash",
    "type": "asset",
    "parent_id": null,
    "is_postable": true,
    "is_active": true,
    "children": [],
    "created_at": "2026-06-14T12:00:00.000000Z"
  }
}
```

### Update Account
- Method: PUT
- URL: `/api/accounts/{id}`
- Description: Updates an existing account.
- Request Body:
```json
{
  "code": "1000",
  "name": "Cash on Hand",
  "type": "asset",
  "parent_id": null,
  "is_postable": true
}
```
- Response Example:
```json
{
  "message": "Account updated successfully",
  "data": {
    "id": 1,
    "code": "1000",
    "name": "Cash on Hand",
    "type": "asset",
    "parent_id": null,
    "is_postable": true,
    "is_active": true,
    "children": [],
    "created_at": "2026-06-14T12:00:00.000000Z"
  }
}
```

### Archive Account
- Method: DELETE
- URL: `/api/accounts/{id}`
- Description: Archives an account by setting `is_active` to false.
- Request Body: None
- Response Example:
```json
{
  "message": "Account archived successfully"
}
```

## Journal Entries APIs

### List Journal Entries
- Method: GET
- URL: `/api/journal-entries`
- Description: Returns paginated journal entries with line details and account metadata.
- Request Body: None
- Response Example:
```json
{
  "data": [
    {
      "id": 1,
      "reference": "JV-000001",
      "entry_date": "2026-06-14",
      "description": "Sales receipt",
      "status": "posted",
      "total_debit": 1000.0,
      "total_credit": 1000.0,
      "lines": [
        {
          "id": 1,
          "account": {
            "id": 1,
            "code": "1000",
            "name": "Cash"
          },
          "debit": 1000.0,
          "credit": 0.0,
          "description": "Cash received"
        },
        {
          "id": 2,
          "account": {
            "id": 2,
            "code": "4000",
            "name": "Service Revenue"
          },
          "debit": 0.0,
          "credit": 1000.0,
          "description": "Sales revenue"
        }
      ],
      "created_at": "2026-06-14T12:10:00.000000Z"
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 20,
    "to": 1,
    "total": 1
  }
}
```

### Get Journal Entry
- Method: GET
- URL: `/api/journal-entries/{id}`
- Description: Returns a single journal entry with its lines and account references.
- Request Body: None
- Response Example:
```json
{
  "id": 1,
  "reference": "JV-000001",
  "entry_date": "2026-06-14",
  "description": "Sales receipt",
  "status": "posted",
  "total_debit": 1000.0,
  "total_credit": 1000.0,
  "lines": [
    {
      "id": 1,
      "account": {
        "id": 1,
        "code": "1000",
        "name": "Cash"
      },
      "debit": 1000.0,
      "credit": 0.0,
      "description": "Cash received"
    },
    {
      "id": 2,
      "account": {
        "id": 2,
        "code": "4000",
        "name": "Service Revenue"
      },
      "debit": 0.0,
      "credit": 1000.0,
      "description": "Sales revenue"
    }
  ],
  "created_at": "2026-06-14T12:10:00.000000Z"
}
```

### Create Journal Entry
- Method: POST
- URL: `/api/journal-entries`
- Description: Posts a new journal entry and ledger lines.
- Request Body:
```json
{
  "entry_date": "2026-06-14",
  "description": "Sales receipt",
  "lines": [
    {
      "account_id": 1,
      "debit": 1000.00,
      "credit": 0.00,
      "description": "Cash received"
    },
    {
      "account_id": 2,
      "debit": 0.00,
      "credit": 1000.00,
      "description": "Sales revenue"
    }
  ]
}
```
- Response Example:
```json
{
  "id": 1,
  "reference": "JV-000001",
  "entry_date": "2026-06-14",
  "description": "Sales receipt",
  "status": "posted",
  "total_debit": 1000.0,
  "total_credit": 1000.0,
  "lines": [
    {
      "id": 1,
      "account": {
        "id": 1,
        "code": "1000",
        "name": "Cash"
      },
      "debit": 1000.0,
      "credit": 0.0,
      "description": "Cash received"
    },
    {
      "id": 2,
      "account": {
        "id": 2,
        "code": "4000",
        "name": "Service Revenue"
      },
      "debit": 0.0,
      "credit": 1000.0,
      "description": "Sales revenue"
    }
  ],
  "created_at": "2026-06-14T12:10:00.000000Z"
}
```

### Reverse Journal Entry
- Method: POST
- URL: `/api/journal-entries/{id}/reverse`
- Description: Creates a reversing journal entry with debit/credit amounts swapped and marks the original entry as `reversed`.
- Request Body: None
- Response Example:
```json
{
  "id": 2,
  "reference": "JV-000002",
  "entry_date": "2026-06-14",
  "description": "Reverse: JV-000001",
  "status": "posted",
  "total_debit": 1000.0,
  "total_credit": 1000.0,
  "lines": [
    {
      "id": 3,
      "account": {
        "id": 1,
        "code": "1000",
        "name": "Cash"
      },
      "debit": 0.0,
      "credit": 1000.0,
      "description": "Cash received"
    },
    {
      "id": 4,
      "account": {
        "id": 2,
        "code": "4000",
        "name": "Service Revenue"
      },
      "debit": 1000.0,
      "credit": 0.0,
      "description": "Sales revenue"
    }
  ],
  "created_at": "2026-06-14T12:15:00.000000Z"
}
```

# 4. Data Flow Explanation

1. Accounts are created in the Chart of Accounts and may be nested via `parent_id`.
2. Accounts are marked with `is_postable`; only postable accounts may be used in journal entry lines.
3. A journal entry is submitted via `POST /api/journal-entries` with at least two lines.
4. `LedgerPostingService` validates each line, checks balances, and persists the entry.
5. Journal entries store a header record plus related `journal_entry_lines`.
6. Account metadata is returned with each line to support ledger reporting.
7. Reversal is performed by `POST /api/journal-entries/{id}/reverse`, which creates a mirror entry with swapped debit and credit values and updates the original entry status to `reversed`.

# 5. Notes / Rules

- Journal entries cannot be updated or deleted through the API.
- The only supported correction method for a posted entry is reversal.
- Total debit must equal total credit for each journal entry.
- Only accounts with `is_postable = true` may be used in journal entry lines.
- Account deletion is logical: `DELETE /api/accounts/{id}` sets `is_active = false`.
- Reverse entries are created automatically and linked by `reversed_entry_id`.
- Journal entries are generated with a `reference` value like `JV-000001`.

# Additional Index

`url : https://ti054a03.agussbn.my.id/api/additional`

## <span style="color: green;">GET</span> /additional

<div style="background-color: #eef3fe; border-left: 5px solid #3b82f6; padding: 0.5rem 1rem; margin-bottom: 1rem; border-radius: 0.25rem;">
    <p style="font-family: monospace; font-weight: bold; font-size: 1.1rem;">
        <span style="color: green;">GET</span> /api/additional
    </p>
    <p style="margin-top: 0.25rem;">Menampilkan daftar semua biaya tambahan dengan pagination.</p>
</div>

### Parameter

> ℹ️ Header `Authorization` dengan skema `Basic Auth` wajib disertakan untuk autentikasi.

| Nama            | Di dalam | Deskripsi                                                         | Wajib                          |
| :-------------- | :------- | :---------------------------------------------------------------- | :----------------------------- |
| `Authorization` | `header` | Kredensial **Basic Auth** (`username:password` di-encode Base64). | ✅ **Ya**                      |
| `Accept`        | `header` | Tentukan format response yang diinginkan.                         | ✅ **Ya** (`application/json`) |
| `Content-Type`  | `header` | Menentukan format data yang dikirim di dalam body.                | ✅                             |
| `body`          | `body`   | Body tidak dibutuhkan.                                            | ❌                             |

### CURL

```bash
curl -X GET "[https://ti054a03.agussbn.my.id/api/additional](https://ti054a03.agussbn.my.id/api/additional)" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -u "apiuser:your_password"
```

### Responses

#### | <strong style="color: #22c55e;">200 OK</strong> - Permintaan Berhasil

```json
{
    "data": [
        {
            "id": 1,
            "no_registrasi": 123,
            "price": 50000.0,
            "description": "Biaya administrasi",
            "created_at": "2025-07-01T10:00:00+00:00"
        },
        {
            "id": 2,
            "no_registrasi": 124,
            "price": 75000.0,
            "description": "Biaya suntik vitamin",
            "created_at": "2025-07-01T11:00:00+00:00"
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
        "last_page": 5,
        "total": 50,
        "per_page": 10
    }
}
```

#### | <strong style="color: #d22e2e;">401 Unauthorized</strong> - Gagal Autentikasi

```json
{
    "message": "Unauthenticated."
}
```

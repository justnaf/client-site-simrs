# Additional Delete

`url : https://ti054a03.agussbn.my.id/api/additional/{id}`

## <span style="color: #ef4444;">DELETE</span> /additional/{id}

<div style="background-color: #eef3fe; border-left: 5px solid #3b82f6; padding: 0.5rem 1rem; margin-bottom: 1rem; border-radius: 0.25rem;">
    <p style="font-family: monospace; font-weight: bold; font-size: 1.1rem;">
        <span style="color: #ef4444;">DELETE</span> /api/additional/{id}
    </p>
    <p style="margin-top: 0.25rem;">Menghapus satu record biaya tambahan secara permanen.</p>
</div>

### Parameter

#### Path Parameters

| Nama | Di dalam | Deskripsi                                              | Wajib     | Tipe    |
| :--- | :------- | :----------------------------------------------------- | :-------- | :------ |
| `id` | `path`   | ID unik dari record biaya tambahan yang akan diupdate. | ✅ **Ya** | integer |

#### Headers

> ℹ️ Header `Authorization` dengan skema `Basic Auth` wajib disertakan untuk autentikasi.

| Nama            | Di dalam | Deskripsi                                                         | Wajib     | Contoh Nilai            |
| :-------------- | :------- | :---------------------------------------------------------------- | :-------- | :---------------------- |
| `Authorization` | `header` | Kredensial **Basic Auth** (`username:password` di-encode Base64). | ✅ **Ya** | `Basic dXNlcjpwYXNz...` |
| `Content-Type`  | `header` | Menentukan format data yang dikirim di dalam body.                | ✅ **Ya** | `application/json`      |
| `Accept`        | `header` | Menentukan format response yang diinginkan.                       | ✅ **Ya** | `application/json`      |

### CURL

````bash
curl -X DELETE "[http://situsanda.test/api/additional-prices/4](http://situsanda.test/api/additional-prices/4)" \
     -H "Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=" \
     -H "Accept: application/json"
```
### Responses

#### | <strong style="color: #22c55e;">204 No Content</strong> - Permintaan Berhasil

```json
Blank
````

#### | <strong style="color: #d22e2e;">404 Not Found/strong> - Tidak Ditemukan

```json
{
    "message": "No query results for model [App\\Models\\PriceAdditional] 4"
}
```

#### | <strong style="color: #d22e2e;">401 Unauthorized</strong> - Gagal Autentikasi

```json
{
    "message": "Unauthenticated."
}
```

# Additional Update

`url : https://ti054a03.agussbn.my.id/api/additional/{id}`

## <span style="color: blue;">PUT/PATCH</span> /additional/{id}

<div style="background-color: #eef3fe; border-left: 5px solid #3b82f6; padding: 0.5rem 1rem; margin-bottom: 1rem; border-radius: 0.25rem;">
    <p style="font-family: monospace; font-weight: bold; font-size: 1.1rem;">
        <span style="color: blue;">PUT/PATCH</span> /api/additional/{id}
    </p>
    <p style="margin-top: 0.25rem;">Mengupdate satu record biaya tambahan yang ada.</p>
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

#### Request Body

Body dari request harus berisi objek JSON dengan field yang ingin diubah.

| Field   | Tipe    | Validasi                           | Deskripsi                      |
| :------ | :------ | :--------------------------------- | :----------------------------- |
| `price` | numeric | `sometimes`, `required`, `numeric` | _Opsional._ Jumlah biaya baru. |
| `desc`  | string  | `sometimes`, `nullable`, `string`  | _Opsional._ Deskripsi baru.    |

*`sometimes` berarti field ini hanya akan divalidasi jika ada di dalam request.*keterangan untuk biaya tambahan ini.

**Contoh Body:**

```json
{
    "price": 85000,
    "desc": "Biaya jahit luka dan perban steril (revisi)"
}
```

### CURL

````bash
curl -X PUT "[http://situsanda.test/api/additional-prices/4](http://situsanda.test/api/additional-prices/4)" \
     -H "Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
         "price": 85000,
         "desc": "Biaya jahit luka dan perban steril (revisi)"
     }'
```
### Responses

#### | <strong style="color: #22c55e;">200 OK</strong> - Permintaan Berhasil

```json
{
    "data": {
        "id": 4,
        "no_registrasi": 17,
        "price": 85000.00,
        "description": "Biaya jahit luka dan perban steril (revisi)",
        "created_at": "2025-07-01T02:29:00+00:00"
    }
}
````

#### | <strong style="color: #FFA500;">422 Unprocessable Entity</strong> - Gagal Validasi

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "price": ["The price field must be a number."]
    }
}
```

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

# Additional Store

`url : https://ti054a03.agussbn.my.id/api/additional`

## <span style="color: orange;">POST</span> /additional

<div style="background-color: #eef3fe; border-left: 5px solid #3b82f6; padding: 0.5rem 1rem; margin-bottom: 1rem; border-radius: 0.25rem;">
    <p style="font-family: monospace; font-weight: bold; font-size: 1.1rem;">
        <span style="color: orange;">POST</span> /api/additional
    </p>
    <p style="margin-top: 0.25rem;">Menyimpan biaya tambahan.</p>
</div>

### Parameter

> ℹ️ Header `Authorization` dengan skema `Basic Auth` wajib disertakan untuk autentikasi.

| Nama            | Di dalam | Deskripsi                                                         | Wajib     | Contoh Nilai            |
| :-------------- | :------- | :---------------------------------------------------------------- | :-------- | :---------------------- |
| `Authorization` | `header` | Kredensial **Basic Auth** (`username:password` di-encode Base64). | ✅ **Ya** | `Basic dXNlcjpwYXNz...` |
| `Content-Type`  | `header` | Menentukan format data yang dikirim di dalam body.                | ✅ **Ya** | `application/json`      |
| `Accept`        | `header` | Menentukan format response yang diinginkan.                       | ✅ **Ya** | `application/json`      |

#### Request Body

Body dari request harus berisi objek JSON dengan struktur berikut:

| Field           | Tipe    | Validasi                                        | Deskripsi                                                             |
| --------------- | ------- | ----------------------------------------------- | --------------------------------------------------------------------- |
| `no_registrasi` | integer | `required`, `integer`, `ExistsInPendaftaranApi` | **Wajib.** Nomor registrasi valid yang ada di sistem pendaftaran API. |
| `price`         | numeric | `required`, `numeric`, `min:0`                  | **Wajib.** Jumlah biaya.                                              |
| `desc`          | string  | `nullable`, `string`, `max:255`                 | _Opsional._ Deskripsi atau keterangan untuk biaya tambahan ini.       |

**Contoh Body:**

```json
{
    "no_registrasi": 17,
    "price": 55000,
    "desc": "Biaya penjahitan luka"
}
```

### CURL

````bash
curl -X POST "[http://situsanda.test/api/additional-prices](http://situsanda.test/api/additional-prices)" \
     -H "Authorization: Basic dXNlcm5hbWU6cGFzc3dvcmQ=" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
         "no_registrasi": 17,
         "price": 55000,
         "desc": "Biaya penjahitan luka"
     }'
```
### Responses

#### | <strong style="color: #22c55e;">200 OK</strong> - Permintaan Berhasil

```json
{
    "data": {
        "id": 4,
        "no_registrasi": 17,
        "price": 55000.00,
        "description": "Biaya penjahitan luka",
        "created_at": "2025-07-01T02:29:00+00:00"
    }
}
````

#### | <strong style="color: #FFA500;">422 Unprocessable Entity</strong> - Gagal Validasi

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "no_registrasi": [
            "No registrasi '9999' tidak valid atau tidak ditemukan di sistem pendaftaran."
        ]
    }
}
```

#### | <strong style="color: #d22e2e;">401 Unauthorized</strong> - Gagal Autentikasi

```json
{
    "message": "Unauthenticated."
}
```

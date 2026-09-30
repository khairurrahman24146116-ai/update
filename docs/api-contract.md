# API Contract — SMA Madani v2

Base: `/api` — Auth: `Bearer <sanctum token>` kecuali `POST /login`.

## Auth

### POST /api/login
Req: `{"email":"admin@madani.test","password":"secret"}`
Res 200:
```json
{"token":"1|xxx","user":{"id":1,"name":"Admin","email":"admin@madani.test","role":"admin"}}
```
Err 401: `{"message":"Kredensial salah"}`
Err 422: `{"message":"The email field is required.","errors":{"email":["..."]}}`

### GET /api/me (auth)
Res 200: `{"id":1,"role":"admin","email":"..."}`
Err 401: `{"message":"Unauthenticated."}`

### POST /api/logout (auth)
Res 204 no content

## Classrooms — role:admin

### GET /api/classrooms?search=X&page=1
Res 200:
```json
{
  "data":[{"id":1,"name":"X-1","grade":10,"academic_year":"2026/2027","created_at":"..."}],
  "current_page":1,"last_page":1,"total":1
}
```

### POST /api/classrooms
Req: `{"name":"X-1","grade":10,"academic_year":"2026/2027"}`
Res 201: `{"id":1,"name":"X-1","grade":10,"academic_year":"2026/2027"}`
Err 422: `{"message":"The grade field is required.","errors":{"grade":["..."]}}`
Err 403: `{"message":"Unauthorized"}` — non-admin
Err 401: `{"message":"Unauthenticated."}`

### GET /api/classrooms/{id}
Res 200: `{"id":1,"name":"X-1","grade":10}`
Err 404: `{"message":"No query results for model [App\\Models\\Classroom] 99."}`

### PUT /api/classrooms/{id}
Req: `{"name":"X-1A"}`
Res 200: `{"id":1,"name":"X-1A","grade":10}`

### DELETE /api/classrooms/{id}
Res 204 no content
Err 409: `{"message":"Kelas masih memiliki siswa"}`

## Subjects

### GET /api/subjects?search=Mat
Res 200 paginated sama seperti classrooms

### POST /api/subjects
Req: `{"name":"Matematika","code":"MTK"}`
Res 201: `{"id":1,"name":"Matematika","code":"MTK"}`
Err 422 duplicate: `{"errors":{"code":["The code has already been taken."]}}`

### PUT /api/subjects/{id}
Req: `{"name":"Matematika Wajib"}`
Res 200

### DELETE /api/subjects/{id}
Res 204
Err 409: `{"message":"Mapel masih dipakai penugasan"}`

## Students

### GET /api/students?search=Ahmad&classroom_id=1&status=aktif
Res 200:
```json
{
  "data":[{"id":1,"nis":"2026001","name":"Ahmad","classroom":{"id":1,"name":"X-1"},"parent":null,"status":"aktif"}],
  "current_page":1,"total":1
}
```

### POST /api/students
Req: `{"nis":"2026001","name":"Ahmad","classroom_id":1,"parent_id":5,"status":"aktif"}`
Res 201: `{"id":1,"nis":"2026001","name":"Ahmad","classroom_id":1}`

### GET /api/students/{id}
Res 200: `{"id":1,"nis":"...","classroom":{"id":1,"name":"X-1"},"parent":{"id":5,"name":"Wali"}}`

### PUT /api/students/{id}
Req: `{"classroom_id":2}`
Res 200

### DELETE /api/students/{id}
Res 204

## Teacher Subjects

### GET /api/teacher-subjects?teacher_id=2&classroom_id=1
Res 200:
```json
{
  "data":[{"id":1,"teacher":{"id":2,"name":"Budi"},"subject":{"id":1,"name":"MTK"},"classroom":{"id":1,"name":"X-1"},"academic_year":"2026/2027"}],
  "current_page":1,"total":1
}
```

### POST /api/teacher-subjects
Req: `{"teacher_id":2,"subject_id":1,"classroom_id":1,"academic_year":"2026/2027"}`
Res 201: `{"id":1,"teacher_id":2,"subject_id":1,"classroom_id":1}`
Err 422 unique: `{"errors":{"teacher_id":["The teacher has already been taken."]}}` (ts_unique)

### DELETE /api/teacher-subjects/{id}
Res 204

## Error umum
- 401 `{"message":"Unauthenticated."}` — token hilang/salah
- 403 `{"message":"Unauthorized"}` — role bukan admin
- 404 `{"message":"No query results..."}`
- 409 business rule (kelas ada siswa / mapel terpakai)
- 422 validasi Laravel default

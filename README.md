AJAX SAMPLE
===========

Sebuah proyek PHP yang memberikan sekumpulan API RESTful, untuk digunakan sebagai latihan pengembangan kode AJAX. Proyek ini dibuat sebagai pendukung dari karya tulis [Javascript: Pembahasan Lanjut](http://bertzzie.com/knowledge/javascript-lanjut/).

API yang disediakan hanya sangat sederhana, dan belum diimplementasikan dengan lengkap. Silahkan berikan *pull request* jika terdapat API yang telah anda implementasikan.

Untuk sekarang, API yang dapat diakses yaitu:

| Nama URL          | Method | URL                | Deskripsi                                                                |
|-------------------|--------|--------------------|--------------------------------------------------------------------------|
| Root              | GET    | /                  | Memberikan daftar method.                                                |
| List Departement  | GET    | /departmens        | Memberikan daftar departemen.                                            |
| Show Department   | GET    | /departments/{id}/ | Memberikan informasi detil sebuah departemen.                            |
| Add Department    | PUT    | /departments/{id}/ | Menambahkan departemen baru. *                                           |
| Update Department | POST   | /departments/{id}/ | Memperbaharui data departemen. Format data seperti Add Department.       |
| Delete Department | DELETE | /departments/{id}/ | Menghapus departemen dari database. Format data seperti Add Department.  |
| List Employee     | GET    | /employees         | Menampilkan daftar karyawan per halaman (1 halaman == 25 karyawan).      |
| Show Employee     | GET    | /employees/{id}/   | Menampilkan data detil satu karyawan.                                    |
| Add Employee      | PUT    | /employees/{id}/   | Menambahkan karyawan baru. **                                            |
| Update Employee   | POST   | /employees/{id}/   | Memperbaharui data karyawan. Format data seperti Add Employee.           |
| Delete Employee   | DELETE | /employees/{id}/   | Menghapus data karyawan dari database. Format data seperti Add Employee. |

- `*`  : Format data: `{'dept_no': xxx, 'dept_name': yyy}`
- `**` : Format data: `{'emp_no': 123, 'birth_date': yyyy-mm-dd, 'first_name': xxx, 'last_name': yyy, 'gender': m/f, 'hire_date': yyyy-mm-dd}`

Kebutuhan Penggunaan
--------------------

Adapun perangkat lunak dan data yang harus dimiliki untuk menjalankan sistem yaitu:

1. PHP 5.5+
2. MySQL 5.6+
3. [MySQL Employee Database](http://dev.mysql.com/doc/employee/en/employees-installation.html)

Sistem dapat dijalankan di lingkungan apapun, selama lingkungan tersebut dapat menjalankan kode PHP dan MySQL (*web server* tidak penting).

jadi kali ini kita sudah sampai pada tahap pembuatan logic peminjaman

untuk saat ini logikanya seperti ini

untuk melakukan peminjaman

cek dahulu apakah ada buku yang tersedia atau tidak di pinjam

jika ada maka bisa pinjam jika tidak ada maka masuk ke dalam antrian

untuk status antri adalah data yang dimana memiliki loaned_at nya kosong
karena disini waktu batas peminjaman sudah ditentukan secara otomatis
jadi kita hanya perlu melakukan order by loanet_at saja untuk data yang mengantri

jadi buku yang digunakan untuk pengantri nantinya akan diurutkan dari loaned_at
yang tercepat.


hal yang perlu diperbaiki adalah pada bagian halaman loaned at yang belum difilter
dimana user_id = orang yg login dan loaned_at nya ada maka bisa dibaca jika loaned_at kosong
maka buku tetap terlihat namun tidak bisa dibaca dengan hanya menampilkan status sedang mengantri.

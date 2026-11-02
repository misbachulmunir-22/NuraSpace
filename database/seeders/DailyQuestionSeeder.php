<?php

namespace Database\Seeders;

use App\Models\DailyQuestion;
use Illuminate\Database\Seeder;

class DailyQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds with 105 curated couple questions.
     */
    public function run(): void
    {
        $questions = [
            // Kategori: Kenangan & Romantisme Awal (1-20)
            ['Apa momen pertama saat kamu menyadari bahwa kamu benar-benar jatuh cinta padaku?', 'romance', 1],
            ['Kalau kamu bisa mengulang satu hari kencan kita dari awal, hari kencan mana yang kamu pilih?', 'romance', 2],
            ['Apa hal pertama yang terlintas di kepalamu saat pertama kali melihat mataku?', 'romance', 3],
            ['Apa lagu yang selalu mengingatkanmu tentang hubungan kita dan perjalanan cinta kita?', 'romance', 4],
            ['Kapan momen paling lucu yang pernah kita lewati bersama yang selalu membuatmu tersenyum?', 'romance', 5],
            ['Apa kebiasaan kecil dariku yang awalnya kamu anggap aneh, tapi sekarang justru kamu rindukan?', 'romance', 6],
            ['Pernahkah kamu merasa sangat bangga menjadi pasanganku di depan orang lain? Kapan itu?', 'romance', 7],
            ['Apa hal paling spontan yang pernah kita lakukan berdua yang paling berkesan?', 'romance', 8],
            ['Jika kisah cinta kita dijadikan judul buku atau film, apa judul yang paling cocok?', 'romance', 9],
            ['Apa panggilan sayang favoritmu untukku dan apa arti rahasia di baliknya?', 'romance', 10],
            ['Apa hadiah dariku yang paling kamu simpan baik-baik atau paling berkesan di hatimu?', 'romance', 11],
            ['Bagaimana caramu menceritakan tentang diriku ke teman-teman terdekatmu saat awal pacaran?', 'romance', 12],
            ['Apa pakaian atau gaya penampilanku yang menurutmu paling memikat hati?', 'romance', 13],
            ['Kalau kita bisa memutar waktu ke kencan pertama kita, apa satu hal yang ingin kamu bisikkan padaku?', 'romance', 14],
            ['Apa tempat kencan yang menurutmu menyimpan memori paling magis untuk kita berdua?', 'romance', 15],
            ['Kapan terakhir kali kamu merasa berdebar-debar (butterfly in the stomach) saat bersamaku?', 'romance', 16],
            ['Apa foto favoritmu tentang kita berdua dan mengapa kamu sangat menyukai foto itu?', 'romance', 17],
            ['Apa makanan yang selalu membuatmu ingat momen kebersamaan kita?', 'romance', 18],
            ['Apa hal termanis yang pernah kulakukan tanpa sengaja namun sangat membekas di hatimu?', 'romance', 19],
            ['Bagaimana rasanya memelukku setelah seharian penuh rasa lelah di luar sana?', 'romance', 20],

            // Kategori: Refleksi Mendalam & Kedekatan Hati (21-45)
            ['Apa satu hal tentang dirimu yang paling ingin kamu agar aku selalu mengerti tanpa perlu kamu jelaskan?', 'deep', 21],
            ['Saat kamu merasa sedih atau tertekan, bagaimana caraku yang paling bisa menenangkan hatimu?', 'deep', 22],
            ['Apa ketakutan terbesarmu saat ini dan bagaimana aku bisa membantumu melaluinya?', 'deep', 23],
            ['Apakah ada hal yang akhir-akhir ini membebani pikiranmu yang belum sempat kamu ceritakan padaku?', 'deep', 24],
            ['Bagian mana dari sifatku yang paling membuatmu merasa aman dan terlindungi?', 'deep', 25],
            ['Ketika kita berbeda pendapat, apa yang biasanya membantumu untuk bisa tenang kembali?', 'deep', 26],
            ['Apa satu pengorbanan terbesarku untuk hubungan kita yang paling kamu hargai?', 'deep', 27],
            ['Bagaimana caraku menunjukkan kasih sayang yang paling membuatmu merasa dicintai seutuhnya?', 'deep', 28],
            ['Apa kata-kata penyemangat dariku yang paling pernah mengubah harimu yang buruk menjadi indah?', 'deep', 29],
            ['Apakah kamu merasa bebas menjadi dirimu yang apa adanya saat bersamaku? Mengapa?', 'deep', 30],
            ['Apa satu kelemahanmu yang hanya berani kamu tunjukkan saat kamu berada di sampingku?', 'deep', 31],
            ['Jika hari ini adalah hari terakhir di dunia, apa pesan terpenting yang ingin kamu sampaikan padaku?', 'deep', 32],
            ['Apa yang membuatmu yakin bahwa kita diciptakan untuk saling melengkapi dan bersama?', 'deep', 33],
            ['Kapan kamu merasa paling dekat secara emosional denganku belakangan ini?', 'deep', 34],
            ['Apa satu hal yang paling kamu syukuri dari hubungan kita sepanjang tahun ini?', 'deep', 35],
            ['Bagaimana caraku bisa menjadi pendengar yang lebih baik lagi untukmu setiap hari?', 'deep', 36],
            ['Apa arti "rumah" bagimu saat kamu memikirkan diriku?', 'deep', 37],
            ['Apakah ada luka masa lalu yang berhasil sembuh berkat kehadiran cinta kita berdua?', 'deep', 38],
            ['Apa satu doa yang selalu kamu panjatkan secara diam-diam untuk kebahagiaan kita berdua?', 'deep', 39],
            ['Bagaimana kehadiran anak-anak kita memperkaya cinta di antara kita berdua?', 'deep', 40],
            ['Apa kualitas terbaik yang kamu lihat dariku dalam menjalani peran sebagai pasangan hidup?', 'deep', 41],
            ['Ketika kita menua bersama nanti, apa yang paling ingin tetap kita lakukan berdua?', 'deep', 42],
            ['Apa nasihat terbaik tentang cinta yang ingin kita wariskan untuk anak-anak kita kelak?', 'deep', 43],
            ['Bagian mana dari kepribadianku yang paling menginspirasimu untuk menjadi orang yang lebih baik?', 'deep', 44],
            ['Apa satu rahasia kecil tentang perasaanmu padaku yang belum pernah kamu ungkapkan?', 'deep', 45],

            // Kategori: Impian, Harapan & Masa Depan (46-70)
            ['Negara atau kota impian mana di dunia yang paling ingin kamu kunjungi bersamaku suatu hari nanti?', 'future', 46],
            ['Seperti apa bayanganmu tentang rumah impian kita dalam 5 atau 10 tahun ke depan?', 'future', 47],
            ['Apa pencapaian finansial atau tabungan yang paling membuatmu bersemangat untuk kita wujudkan bersama?', 'future', 48],
            ['Kalau kita punya waktu luang satu minggu penuh tanpa gangguan kerja, apa yang ingin kita lakukan?', 'future', 49],
            ['Tradisi keluarga baru apa yang ingin kita bangun bersama anak-anak kita setiap tahunnya?', 'future', 50],
            ['Apa impian pribadimu yang ingin kamu capai, dan bagaimana aku bisa mendukungmu 100%?', 'future', 51],
            ['Jika kita bisa membuka usaha atau proyek bersama berdua, ide apa yang ingin kamu rintis?', 'future', 52],
            ['Bagaimana cara kita merayakan ulang tahun pernikahan ke-10 atau ke-25 nanti?', 'future', 53],
            ['Apa destinasi liburan impian keluarga kita yang ingin kita datangi bersama anak-anak?', 'future', 54],
            ['Hobi baru apa yang menurutmu seru jika kita mulai pelajari berdua akhir pekan ini?', 'future', 55],
            ['Apa satu hal yang ingin kita capai bersama sebelum tahun ini berganti?', 'future', 56],
            ['Bagaimana bayanganmu saat kita berdua duduk santai di teras rumah saat rambut kita sudah memutih?', 'future', 57],
            ['Apa satu kebiasaan sehat yang ingin kamu ajak aku lakukan secara konsisten mulai bulan ini?', 'future', 58],
            ['Jika kita memenangkan hadiah besar, apa 3 hal pertama yang ingin kita gunakan bersama?', 'future', 59],
            ['Apa proyek renovasi atau dekorasi sudut rumah kita yang paling ingin kamu wujudkan?', 'future', 60],
            ['Keterampilan apa yang menurutmu sangat keren jika salah satu dari kita bisa menguasainya?', 'future', 61],
            ['Bagaimana cara kita menjaga api cinta kita tetap berkobar hangat di tengah kesibukan sehari-hari?', 'future', 62],
            ['Apa hal paling menantang yang ingin kita hadapi dan taklukkan bersama sebagai satu tim?', 'future', 63],
            ['Tempat kuliner legendaris mana yang masuk daftar wajib kita datangi bersama selanjutnya?', 'future', 64],
            ['Apa satu impian masa kecilmu yang sangat ingin kamu wujudkan sekarang bersamaku?', 'future', 65],
            ['Bagaimana kita bisa membuat waktu kencan berdua tetap rutin meskipun jadwal padat?', 'future', 66],
            ['Apa resolusi keluarga terbaik yang pernah kita buat bersama?', 'future', 67],
            ['Jika kita bisa menulis pesan untuk diri kita 20 tahun ke depan, apa isi pesan itu?', 'future', 68],
            ['Pengalaman petualangan apa (camping, naik gunung, menyelam) yang ingin kita coba sekali seumur hidup?', 'future', 69],
            ['Apa satu warisan kebaikan yang paling ingin kita tinggalkan untuk generasi kita kelak?', 'future', 70],

            // Kategori: Kehidupan Sehari-hari, Seru & Manis (71-95)
            ['Apa menu masakan favoritmu saat kita makan bersama di rumah?', 'fun', 71],
            ['Film atau serial apa yang menurutmu paling asyik kita tonton marathon sambil selimutan berdua?', 'fun', 72],
            ['Kalau kita sedang berada di pasar malam atau taman hiburan, wahana apa yang pertama kamu pilih?', 'fun', 73],
            ['Apa hal konyol yang sering kulakukan yang selalu berhasil membuatmu tertawa geli?', 'fun', 74],
            ['Siapa di antara kita yang menurutmu paling tidak bisa menahan tawa saat situasi serius?', 'fun', 75],
            ['Apa camilan wajib yang harus selalu ada saat kita sedang santai mengobrol berdua?', 'fun', 76],
            ['Kalau aku berubah menjadi karakter kartun atau fiksi, menurutmu karakter mana yang paling mirip denganku?', 'fun', 77],
            ['Apa aroma parfum atau wangi tubuhku yang paling kamu sukai saat aku berada dekat denganmu?', 'fun', 78],
            ['Siapa di antara kita yang lebih gampang tertidur saat menonton film di bioskop?', 'fun', 79],
            ['Apa playlist lagu terbaik versi kamu untuk menemani perjalanan darat (road trip) kita berdua?', 'fun', 80],
            ['Kalau kita tersesat di pulau terpencil berdua, siapa yang bertugas mencari makanan dan siapa yang membangun tenda?', 'fun', 81],
            ['Apa emoji yang paling menggambarkan hubungan kita minggu ini?', 'fun', 82],
            ['Apa minuman hangat favoritmu yang paling cocok kita nikmati bersama di malam hujan?', 'fun', 83],
            ['Kapan terakhir kali kamu tertawa sampai perutmu sakit bersamaku?', 'fun', 84],
            ['Apa permainan papan (board game) atau game santai yang ingin kamu mainkan melawanku malam ini?', 'fun', 85],
            ['Bila kita bertukar peran selama 24 jam, apa hal pertama yang akan kamu lakukan sebagai diriku?', 'fun', 86],
            ['Apa panggilan unik anak-anak kita kepadamu yang paling membuat hatimu meleleh?', 'fun', 87],
            ['Apakah kamu tim bangun pagi untuk menikmati matahari terbit berdua atau tim begadang mengobrol hingga larut?', 'fun', 88],
            ['Apa tempat duduk favoritmu saat kita sedang berkendara berdua?', 'fun', 89],
            ['Kalau kita membuka cafe berdua, menu andalan apa yang akan kita namai dengan nama cinta kita?', 'fun', 90],
            ['Apa rutinitas sebelum tidur yang paling membuatmu merasa tenang dan bersyukur?', 'fun', 91],
            ['Siapa di antara kita yang paling jago memilih tempat makan saat kita bingung mau makan apa?', 'fun', 92],
            ['Apa pakaian santai rumahanku yang menurutmu paling lucu atau menggemaskan?', 'fun', 93],
            ['Apa kebiasaan unik kita saat akhir pekan yang tidak pernah kita lewatkan?', 'fun', 94],
            ['Jika kamu bisa memberiku kekuatan super khusus, kekuatan super apa yang paling cocok untukku?', 'fun', 95],

            // Kategori: Apresiasi, Rasa Syukur & Keintiman (96-105)
            ['Apa satu hal kecil yang kulakukan hari ini atau kemarin yang sangat kamu hargai?', 'gratitude', 96],
            ['Bagian tubuhku mana yang paling sering membuatmu ingin menggenggam atau menciumnya?', 'gratitude', 97],
            ['Bagaimana caraku tersenyum yang paling bisa meluluhkan hatimu saat kamu sedang kesal?', 'gratitude', 98],
            ['Apa satu pujian tulus darimu yang paling ingin kamu dengar kuucapkan untukmu hari ini?', 'gratitude', 99],
            ['Jika kamu bisa merangkum perasaan cintamu padaku dalam satu kata, kata apa yang kamu pilih?', 'gratitude', 100],
            ['Bagaimana kehadiran diriku membuat hidupmu terasa lebih bermakna dan penuh warna?', 'gratitude', 101],
            ['Kapan momen kamu merasa paling bersyukur kepada Tuhan karena telah mempertemukan kita?', 'gratitude', 102],
            ['Apa yang paling kamu rindukan saat kita harus terpisah jarak beberapa hari karena pekerjaan?', 'gratitude', 103],
            ['Sentuhan seperti apa (gandengan tangan, elusan kepala, pelukan) yang paling membuatmu merasa damai?', 'gratitude', 104],
            ['Apa janji cintaku kepadamu yang akan selalu kamu ingat seumur hidupmu?', 'gratitude', 105],
        ];

        foreach ($questions as $q) {
            DailyQuestion::updateOrCreate(
                ['question_text' => $q[0]],
                [
                    'category' => $q[1],
                    'order_index' => $q[2],
                ]
            );
        }
    }
}

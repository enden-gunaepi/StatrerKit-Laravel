var getTimeZOne = document.getElementById("current-time");
const timezone = getTimeZOne;
// const timezone = @json($timezone);

function updateTime() {
    // Mendapatkan waktu saat ini dengan zona waktu yang ditentukan
    const now = new Date().toLocaleString("en-US", {
        timeZone: timezone,
    });

    // Membuat objek Date berdasarkan string waktu
    const date = new Date(now);

    // Mendapatkan hari, bulan, tahun, jam, menit, dan detik
    const daysOfWeek = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
    ];
    const monthsOfYear = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];

    let dayOfWeek = daysOfWeek[date.getDay()];
    let month = monthsOfYear[date.getMonth()];
    let day = date.getDate();
    let year = date.getFullYear();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();

    // Menambahkan angka 0 di depan jika jam, menit, atau detik kurang dari 10
    hours = hours < 10 ? "0" + hours : hours;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    // Format waktu dalam bentuk "Day, Month Day, Year - HH:MM:SS"
    const timeString = `${dayOfWeek}, ${month} ${day}, ${year} - ${hours}:${minutes}:${seconds}`;

    // Menampilkan waktu pada elemen dengan id 'current-time'
    document.getElementById("current-time").textContent = timeString;
}

// Memanggil fungsi updateTime setiap 1000 ms (1 detik)
setInterval(updateTime, 1000);

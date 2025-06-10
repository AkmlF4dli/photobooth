<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Photo Booth with Live Filters</title>
  <link rel="stylesheet" href="indexstyle.css"> 
  <style>
    #countdown {
      font-size: 24px;
      color: red;
      text-align: center;
      margin: 10px 0;
      display: none;
    }
    .photo-wrapper img {
      width: 150px;
      margin-bottom: 5px;
    }
  </style>
</head>
<body>
  <h2>Eightics Company</h2>
  <div class="container">
    <form id="photoForm" method="POST" action="process.php" class="form-box">
      <label for="email">Masukkan Email:</label><br>
      <input type="email" name="email" required><br><br>

      <video id="video" autoplay></video>
      <div id="countdown"></div><br>

      <label for="filters">Pilih Filter:</label>
      <select id="filters">
        <option value="none">None</option>
        <option value="grayscale">Grayscale</option>
        <option value="sepia">Sepia</option>
        <option value="invert">Invert</option>
        <option value="contrast">High Contrast</option>
        <option value="blur">Blur</option>
      </select><br><br>

      <label for="timerSelect">Timer:</label>
      <select id="timerSelect">
        <option value="0">Tanpa Timer</option>
        <option value="3000">3 Detik</option>
        <option value="5000">5 Detik</option>
      </select><br><br>

      <!-- Hidden input untuk foto dan filter -->
      <input type="hidden" name="photo1" id="photo1">
      <input type="hidden" name="filter1" id="filter1">
      <input type="hidden" name="photo2" id="photo2">
      <input type="hidden" name="filter2" id="filter2">
      <input type="hidden" name="photo3" id="photo3">
      <input type="hidden" name="filter3" id="filter3">

      <button type="button" onclick="takePhoto()">Ambil Foto</button><br><br>
      <button type="submit">Kirim Kolase ke Email</button>
    </form>

    <div class="preview-gallery">
      <div class="photo-wrapper" id="preview1"></div>
      <div class="photo-wrapper" id="preview2"></div>
      <div class="photo-wrapper" id="preview3"></div>
    </div>
  </div>

<script>
  const video = document.getElementById('video');
  const filters = document.getElementById('filters');
  const timerSelect = document.getElementById('timerSelect');
  const countdownEl = document.getElementById('countdown');
  let currentPhotoIndex = 1;

  // Akses webcam
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
    })
    .catch(err => {
      alert("Tidak bisa mengakses kamera: " + err.message);
    });

  // Ganti filter live
  filters.addEventListener('change', (e) => {
    video.style.filter = getCSSFilter(e.target.value);
  });

  // Konversi value filter ke CSS
  function getCSSFilter(filter) {
    const filters = {
      grayscale: 'grayscale(100%)',
      sepia: 'sepia(100%)',
      invert: 'invert(100%)',
      contrast: 'contrast(200%)',
      blur: 'blur(5px)',
      none: 'none'
    };
    return filters[filter] || 'none';
  }

  // Fungsi countdown
  function startCountdown(milliseconds, callback) {
    if (!milliseconds) {
      callback();
      return;
    }

    let seconds = milliseconds / 1000;
    countdownEl.style.display = 'block';
    countdownEl.innerText = seconds;
    const interval = setInterval(() => {
      seconds--;
      if (seconds > 0) {
        countdownEl.innerText = seconds;
      } else {
        clearInterval(interval);
        countdownEl.style.display = 'none';
        callback();
      }
    }, 1000);
  }

  // Fungsi ambil foto utama
  function takePhoto(index = null, isRetake = false) {
    const delay = parseInt(timerSelect.value);

    if (index && isRetake) {
      return startCountdown(delay, () => capturePhoto(index));
    }

    if (currentPhotoIndex > 3) {
      alert("Maksimal 3 foto! Silakan retake salah satu.");
      return;
    }

    startCountdown(delay, () => {
      capturePhoto(currentPhotoIndex);
      currentPhotoIndex++;
    });
  }

  // Ambil gambar dari video
  function capturePhoto(index) {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 480;
    canvas.height = 360;

    const selectedFilter = filters.value;
    ctx.filter = getCSSFilter(selectedFilter);
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1); // mirror
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataURL = canvas.toDataURL('image/jpeg');
    document.getElementById('photo' + index).value = dataURL;
    document.getElementById('filter' + index).value = selectedFilter;

    // Tampilkan preview
    const wrapper = document.getElementById('preview' + index);
    wrapper.innerHTML = '';

    const img = document.createElement('img');
    img.src = dataURL;
    img.alt = 'Foto ' + index;

    const btn = document.createElement('button');
    btn.type = 'button';
    btn.innerText = 'Retake Foto ' + index;
    btn.onclick = () => takePhoto(index, true);

    wrapper.appendChild(img);
    wrapper.appendChild(btn);
  }
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Photo Booth with Live Filters</title>
  <link rel="stylesheet" href="indexstyle.css"> 
</head>
<body>
  <h2>Eightics Company</h2>
  <div class="container">
    <form id="photoForm" method="POST" action="process.php" class="form-box">
      <label for="email">Masukkan Email:</label><br>
      <input type="email" name="email" required><br><br>

      <video id="video" autoplay></video>
      <br>

      <label for="filters">Pilih Filter:</label>
      <select id="filters">
        <option value="none">None</option>
        <option value="grayscale">Grayscale</option>
        <option value="sepia">Sepia</option>
        <option value="invert">Invert</option>
        <option value="contrast">High Contrast</option>
        <option value="blur">Blur</option>
      </select>
      <br><br>

      <!-- Hidden inputs for photo data -->
      <input type="hidden" name="photo1" id="photo1">
      <input type="hidden" name="filter1" id="filter1">
      <input type="hidden" name="photo2" id="photo2">
      <input type="hidden" name="filter2" id="filter2">
      <input type="hidden" name="photo3" id="photo3">
      <input type="hidden" name="filter3" id="filter3">

      <button type="button" onclick="takePhoto()">Ambil Foto</button>
      <br><br>
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
    let currentPhotoIndex = 1; // Start from 1 to match preview1, photo1, etc.

    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(err => {
        alert("Tidak bisa mengakses kamera: " + err.message);
      });

    filters.addEventListener('change', (e) => {
      const filter = e.target.value;
      video.className = '';
      if (filter !== 'none') video.classList.add(filter);
    });

    function takePhoto(index = null) {
      if (index === null) {
        if (currentPhotoIndex > 3) {
          alert("Maksimal 3 foto! Silakan retake salah satu.");
          return;
        }
        index = currentPhotoIndex;
      }

      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      canvas.width = 480;
      canvas.height = 360;

      const selectedFilter = filters.value;
      switch (selectedFilter) {
        case 'grayscale': ctx.filter = 'grayscale(100%)'; break;
        case 'sepia': ctx.filter = 'sepia(100%)'; break;
        case 'invert': ctx.filter = 'invert(100%)'; break;
        case 'contrast': ctx.filter = 'contrast(200%)'; break;
        case 'blur': ctx.filter = 'blur(10px)'; break;
        default: ctx.filter = 'none';
      }

      // Undo mirror effect for captured image
      ctx.translate(canvas.width, 0);
      ctx.scale(-1, 1);
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      const dataURL = canvas.toDataURL('image/jpeg');

      document.getElementById('photo' + index).value = dataURL;
      document.getElementById('filter' + index).value = selectedFilter;

      const wrapper = document.getElementById('preview' + index);
      wrapper.innerHTML = '';

      const img = document.createElement('img');
      img.src = dataURL;
      img.alt = 'Foto ' + index;
      img.classList.add('fade-in');

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.innerText = 'Retake Foto ' + index;
      btn.onclick = () => takePhoto(index);

      wrapper.appendChild(img);
      wrapper.appendChild(btn);

      if (index === currentPhotoIndex) currentPhotoIndex++;
    }
  </script>
</body>
</html>

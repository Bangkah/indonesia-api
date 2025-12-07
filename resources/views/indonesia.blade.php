<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">API Indonesia</h1>

        <!-- Lokasi Indonesia -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-2xl font-semibold mb-4">Lokasi Indonesia</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Provinsi:</label>
                    <select id="provinsi" class="w-full border-gray-300 rounded p-2">
                        <option value="">-- Pilih Provinsi --</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Kota/Kabupaten:</label>
                    <select id="kota" class="w-full border-gray-300 rounded p-2" disabled>
                        <option value="">-- Pilih Kota/Kabupaten --</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Kecamatan:</label>
                    <select id="kecamatan" class="w-full border-gray-300 rounded p-2" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium mb-1">Kelurahan/Desa:</label>
                    <select id="kelurahan" class="w-full border-gray-300 rounded p-2" disabled>
                        <option value="">-- Pilih Kelurahan/Desa --</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Cuaca BMKG -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-2xl font-semibold mb-4">Cuaca BMKG</h2>
            <div class="flex gap-2 mb-4">
                <input type="text" id="inputCuaca" placeholder="Masukkan nama kota" class="flex-1 border-gray-300 rounded p-2">
                <button onclick="getCuaca()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cek Cuaca</button>
            </div>
            <pre id="cuacaResult" class="bg-gray-100 p-4 rounded overflow-auto"></pre>
        </div>

        <!-- Jadwal Shalat -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4">Jadwal Shalat</h2>
            <div class="flex gap-2 mb-4">
                <input type="text" id="inputShalat" placeholder="Masukkan nama kota" class="flex-1 border-gray-300 rounded p-2">
                <button onclick="getShalat()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Cek Shalat</button>
            </div>
            <pre id="shalatResult" class="bg-gray-100 p-4 rounded overflow-auto"></pre>
        </div>
    </div>

    <script>
        // Load Provinsi
        const provSelect = document.getElementById('provinsi');
        const kotaSelect = document.getElementById('kota');
        const kecSelect = document.getElementById('kecamatan');
        const kelSelect = document.getElementById('kelurahan');

        // Helper untuk set loading
        function setLoading(select, text = 'Loading...') {
            select.innerHTML = `<option>${text}</option>`;
            select.disabled = true;
        }

        // Helper untuk reset select
        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }

        // Load Provinsi
        setLoading(provSelect);
        axios.get('/api/provinsi')
            .then(res => {
                provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                if (Array.isArray(res.data) && res.data.length > 0) {
                    res.data.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.text = p.name;
                        provSelect.appendChild(opt);
                    });
                    provSelect.disabled = false;
                } else {
                    setLoading(provSelect, 'Data tidak tersedia');
                }
            })
            .catch(() => setLoading(provSelect, 'Gagal memuat'));

        // Load Kota saat Provinsi dipilih
        provSelect.addEventListener('change', function() {
            const provId = this.value;
            resetSelect(kotaSelect, '-- Pilih Kota/Kabupaten --');
            resetSelect(kecSelect, '-- Pilih Kecamatan --');
            resetSelect(kelSelect, '-- Pilih Kelurahan/Desa --');

            if(provId) {
                setLoading(kotaSelect);
                axios.get(`/api/kota/${provId}`)
                    .then(res => {
                        kotaSelect.innerHTML = '<option value="">-- Pilih Kota/Kabupaten --</option>';
                        if (Array.isArray(res.data) && res.data.length > 0) {
                            res.data.forEach(k => {
                                const opt = document.createElement('option');
                                opt.value = k.id;
                                opt.text = k.name;
                                kotaSelect.appendChild(opt);
                            });
                            kotaSelect.disabled = false;
                        } else {
                            setLoading(kotaSelect, 'Data tidak tersedia');
                        }
                    })
                    .catch(() => setLoading(kotaSelect, 'Gagal memuat'));
            }
        });

        // Load Kecamatan saat Kota dipilih
        kotaSelect.addEventListener('change', function() {
            const kotaId = this.value;
            resetSelect(kecSelect, '-- Pilih Kecamatan --');
            resetSelect(kelSelect, '-- Pilih Kelurahan/Desa --');

            if(kotaId) {
                setLoading(kecSelect);
                axios.get(`/api/kecamatan/${kotaId}`)
                    .then(res => {
                        kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                        if (Array.isArray(res.data) && res.data.length > 0) {
                            res.data.forEach(k => {
                                const opt = document.createElement('option');
                                opt.value = k.id;
                                opt.text = k.name;
                                kecSelect.appendChild(opt);
                            });
                            kecSelect.disabled = false;
                        } else {
                            setLoading(kecSelect, 'Data tidak tersedia');
                        }
                    })
                    .catch(() => setLoading(kecSelect, 'Gagal memuat'));
            }
        });

        // Load Kelurahan saat Kecamatan dipilih
        kecSelect.addEventListener('change', function() {
            const kecId = this.value;
            resetSelect(kelSelect, '-- Pilih Kelurahan/Desa --');

            if(kecId) {
                setLoading(kelSelect);
                axios.get(`/api/kelurahan/${kecId}`)
                    .then(res => {
                        kelSelect.innerHTML = '<option value="">-- Pilih Kelurahan/Desa --</option>';
                        if (Array.isArray(res.data) && res.data.length > 0) {
                            res.data.forEach(k => {
                                const opt = document.createElement('option');
                                opt.value = k.id;
                                opt.text = k.name;
                                kelSelect.appendChild(opt);
                            });
                            kelSelect.disabled = false;
                        } else {
                            setLoading(kelSelect, 'Data tidak tersedia');
                        }
                    })
                    .catch(() => setLoading(kelSelect, 'Gagal memuat'));
            }
        });

        // Fungsi cek cuaca
        function getCuaca() {
            const kota = document.getElementById('inputCuaca').value;
            const result = document.getElementById('cuacaResult');
            result.innerText = 'Memuat...';
            axios.get(`/api/cuaca/${kota}`)
                .then(res => {
                    result.innerText = JSON.stringify(res.data, null, 2);
                })
                .catch(err => {
                    result.innerText = err.response?.data?.message || 'Terjadi kesalahan';
                });
        }

        // Fungsi cek jadwal shalat
        function getShalat() {
            const kota = document.getElementById('inputShalat').value;
            const result = document.getElementById('shalatResult');
            result.innerText = 'Memuat...';
            axios.get(`/api/shalat/${kota}`)
                .then(res => {
                    result.innerText = JSON.stringify(res.data, null, 2);
                })
                .catch(err => {
                    result.innerText = err.response?.data?.message || 'Terjadi kesalahan';
                });
        }
    </script>
</body>
</html>

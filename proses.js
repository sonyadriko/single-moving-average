
    // Function to update the options for Tanggal Akhir based on selected Tanggal Awal
    var uniqueDates;
    function updateTanggalAkhirOptions() {
        if (!uniqueDates) {
            console.error("Error: uniqueDates is undefined");
            return;
        }
    
        var tanggalAwal = document.getElementById("tanggal_awal");
        var tanggalAkhir = document.getElementById("tanggal_akhir");
    
        // Clear existing options
        tanggalAkhir.innerHTML = '<option value="" selected disabled>Pilih Tanggal Akhir</option>';
    
        // Get the selected value of Tanggal Awal
        var selectedTanggalAwal = tanggalAwal.value;
    
        // Generate options for Tanggal Akhir based on Tanggal Awal
        for (var i = 0; i < uniqueDates.length; i++) {
            var date = uniqueDates[i];
            if (date > selectedTanggalAwal) {
                tanggalAkhir.innerHTML += '<option value="' + date + '">' + date + '</option>';
            }
        }
    }
    // Function to be called when Durasi, Bulan, or Tanggal Awal is changed
    function hitung() {
        // Add your logic here to handle the calculation
        // You can retrieve selected values using document.getElementById("element_id").value
        // For example:
        var namaBarang = document.getElementById("nama_barang").value;
        var durasi = document.getElementById("durasi").value;
        var bulan = document.getElementById("bulan").value;
        var tanggalAwal = document.getElementById("tanggal_awal").value;
        var tanggalAkhir = document.getElementById("tanggal_akhir").value;

        // You can use these values to perform the calculation or send them to the server for processing
        // Example: You might want to use AJAX to send the data to the server
        // For simplicity, I'll just submit the form for now
        document.getElementById("hitungForm").submit();
    }

    // Add event listeners to update Tanggal Akhir options when Tanggal Awal is changed
    function initScript(uniqueDates) {
        // Add event listeners to update Tanggal Akhir options when Tanggal Awal is changed
        document.getElementById("tanggal_awal").addEventListener("change", function() {
            updateTanggalAkhirOptions(uniqueDates);
        });
    }


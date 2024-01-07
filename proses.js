
    // Function to update the options for Tanggal Akhir based on selected Tanggal Awal
    var uniqueDates;

    
    // function updateTanggalAkhirOptions() {
    //     if (!uniqueDates) {
    //         console.error("Error: uniqueDates is undefined");
    //         return;
    //     }
    
    //     var tanggalAwal = document.getElementById("tanggal_awal");
    //     var tanggalAkhir = document.getElementById("tanggal_akhir");
    
    //     // Clear existing options
    //     tanggalAkhir.innerHTML = '<option value="" selected disabled>Pilih Tanggal Akhir</option>';
    //     // Get the selected value of Tanggal Awal
    //     var selectedTanggalAwal = tanggalAwal.value;
    
    //     // Generate options for Tanggal Akhir based on Tanggal Awal
    //     for (var i = 0; i < uniqueDates.length; i++) {
    //         var date = uniqueDates[i];
    //         if (date > selectedTanggalAwal) {
    //             var formattedDate = new Date(date);
    //             var options = { day: 'numeric', month: 'long', year: 'numeric' };
    //             var formattedDateString = formattedDate.toLocaleDateString('id-ID', options);
    //             // Log ke konsol untuk memeriksa nilai formattedDateString
    //             // console.log(formattedDateString);
    //             // Tambahkan opsi ke Tanggal Akhir
    //             tanggalAkhir.innerHTML += '<option value="' + date + '">' + formattedDateString + '</option>';
    //         }
    //     }
    // }


    // Function to be called when Durasi, Bulan, or Tanggal Awal is changed
    function hitung() {

        var namaBarang = document.getElementById("nama_barang").value;
        var durasi = document.getElementById("durasi").value;
        var tanggalAwal = document.getElementById("tanggal_awal").value;
        var tanggalAkhir = document.getElementById("tanggal_akhir").value;

        
        document.getElementById("hitungForm").submit();
    }

    // Add event listeners to update Tanggal Akhir options when Tanggal Awal is changed
    function initScript(uniqueDates) {
        // Add event listeners to update Tanggal Akhir options when Tanggal Awal is changed
        document.getElementById("tanggal_awal").addEventListener("change", function() {
            updateTanggalAkhirOptions(uniqueDates);
        });
    }


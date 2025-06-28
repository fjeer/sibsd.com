
$(document).ready(function () {
	let item_count = 1;

	// Fungsi untuk menambahkan baris item baru
	$("#add_item_btn").click(function () {
		const sampahOptions = $("#jenis_sampah_0").html();

		const item_template = `
            <div class="row item-row mb-3 align-items-end">
                <div class="col-md-5">
                    <label for="jenis_sampah_${item_count}" class="form-label">Jenis Sampah</label>
                    <select class="form-select" name="id_sampah[]" id="jenis_sampah_${item_count}" required>
                        ${sampahOptions}
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="berat_${item_count}" class="form-label">Berat Sampah (kg)</label>
                    <input type="number" step="0.01" min="0" class="form-control" name="berat_sampah[]" id="berat_${item_count}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button>
                </div>
            </div>
        `;
		$("#item_list").append(item_template);
		updateRemoveButtons();
		item_count++;
	});

	// Fungsi untuk menghapus baris item
	$(document).on("click", ".remove-item", function () {
		$(this).closest(".item-row").remove();
		updateRemoveButtons();
	});

	// Fungsi untuk memperbarui visibility tombol hapus
	function updateRemoveButtons() {
		if ($(".item-row").length > 1) {
			$(".remove-item").show();
		} else {
			$(".remove-item").hide();
		}
	}

	// Panggil saat halaman pertama kali dimuat
	updateRemoveButtons();
});

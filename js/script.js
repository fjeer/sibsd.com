const ctx = document.getElementById("sampahChart").getContext("2d");

const chart = new Chart(ctx, {
	type: "bar",
	data: {
		labels: window.bulan_labels,
		datasets: [
			{
				label: "Setoran (kg)",
				data: window.jumlah_kg,
				backgroundColor: "#0d6efd",
			},
		],
	},
	options: {
		responsive: true,
		scales: {
			y: {
				beginAtZero: true,
			},
		},
	},
});

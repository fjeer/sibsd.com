const ctx = document.getElementById("sampahChart").getContext("2d");

const chart = new Chart(ctx, {
	type: "line", // ubah dari bar ke line
	data: {
		labels: window.bulan_labels,
		datasets: [
			{
				label: "Setoran (kg)",
				data: window.jumlah_kg,
				fill: false,
				borderColor: "#0d6efd",
				backgroundColor: "#0d6efd",
				tension: 0.4, // membuat garis melengkung (smooth)
				pointBackgroundColor: "#fff",
				pointBorderColor: "#0d6efd",
				pointRadius: 5,
				pointHoverRadius: 7,
			},
		],
	},
	options: {
		responsive: true,
		plugins: {
			legend: {
				display: true,
				position: "top",
			},
			tooltip: {
				mode: "index",
				intersect: false,
			},
		},
		scales: {
			y: {
				beginAtZero: true,
				title: {
					display: true,
					text: "Berat Setoran (kg)",
				},
			},
			x: {
				title: {
					display: true,
					text: "Bulan",
				},
			},
		},
	},
});

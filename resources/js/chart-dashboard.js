document.addEventListener('DOMContentLoaded', () => {
    const barCtx = document.getElementById('chartKinerjaUtama');
    if (barCtx && window.Chart) {
        new window.Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [
                    { label: 'Pramubakti (95)', data: [24, 23, 25, 23], backgroundColor: '#1E3A8A', borderRadius: 6 },
                    { label: 'Satpam (91)', data: [22, 23, 22, 24], backgroundColor: '#F59E0B', borderRadius: 6 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const doughnutCtx = document.getElementById('chartStatusVerifikasi');
    if (doughnutCtx && window.Chart) {
        new window.Chart(doughnutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui (178)', 'Menunggu Verifikasi (8)'],
                datasets: [{ data: [178, 8], backgroundColor: ['#10B981', '#F59E0B'], borderWidth: 0 }]
            },
            options: { responsive: true, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
        });
    }
});
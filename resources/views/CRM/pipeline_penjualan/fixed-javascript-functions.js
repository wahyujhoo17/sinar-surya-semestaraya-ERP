//Fixed JavaScript Functions

// calculateConversionRates function
calculateConversionRates() {
    // Calculate conversion rates between stages
    const {
        baru,
        tertarik,
        negosiasi,
        menjadi_customer
    } = this.stats;

    // Avoid division by zero and ensure integer values for percentage
    this.conversionRates.prospekToTertarik = baru > 0 ? parseInt((tertarik / baru) * 100) : 0;
    this.conversionRates.tertarikToNegosiasi = tertarik > 0 ? parseInt((negosiasi / tertarik) * 100) : 0;
    this.conversionRates.negosiasiToCustomer = negosiasi > 0 ? parseInt((menjadi_customer / negosiasi) * 100) : 0;
    this.conversionRates.overall = baru > 0 ? parseInt((menjadi_customer / baru) * 100) : 0;

    // Calculate progress data
    this.progressData = {
        baruToTertarik: {
            count: tertarik,
            rate: this.conversionRates.prospekToTertarik
        },
        tertarikToNegosiasi: {
            count: negosiasi,
            rate: this.conversionRates.tertarikToNegosiasi
        },
        negosiasiToCustomer: {
            count: menjadi_customer,
            rate: this.conversionRates.negosiasiToCustomer
        }
    };
},

// initChart function
initChart() {
    // Clear existing chart if it exists
    if (this.pipelineChart) {
        this.pipelineChart.destroy();
    }

    // Check if the chart canvas exists
    const canvas = document.getElementById('pipelineChart');
    if (!canvas) {
        console.error('Chart canvas element not found');
        return;
    }

    const ctx = canvas.getContext('2d');
    const colors = {
        backgroundColor: [
            'rgba(255, 193, 7, 0.6)', // Baru - yellow
            'rgba(13, 110, 253, 0.6)', // Tertarik - blue
            'rgba(102, 16, 242, 0.6)', // Negosiasi - indigo
            'rgba(220, 53, 69, 0.6)', // Menolak - red
            'rgba(25, 135, 84, 0.6)' // Menjadi Customer - green
        ],
        borderColor: [
            'rgba(255, 193, 7, 1)',
            'rgba(13, 110, 253, 1)',
            'rgba(102, 16, 242, 1)',
            'rgba(220, 53, 69, 1)',
            'rgba(25, 135, 84, 1)'
        ]
    };

    const labels = ['Baru', 'Tertarik', 'Negosiasi', 'Menolak', 'Menjadi Customer'];
    const data = [
        this.stats.baru || 0,
        this.stats.tertarik || 0,
        this.stats.negosiasi || 0,
        this.stats.menolak || 0,
        this.stats.menjadi_customer || 0
    ];

    try {
        // Configure chart options based on chart type
        const chartOptions = this.getChartOptions();

        this.pipelineChart = new Chart(ctx, {
            type: this.chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Prospek',
                    data: data,
                    backgroundColor: colors.backgroundColor,
                    borderColor: colors.borderColor,
                    borderWidth: 1
                }]
            },
            options: chartOptions
        });
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
},

// getChartOptions function
getChartOptions() {
    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 500
        },
        plugins: {
            legend: {
                display: this.chartType === 'doughnut',
                position: 'right',
                labels: {
                    font: {
                        size: 12
                    },
                    usePointStyle: true,
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                padding: 10,
                callbacks: {
                    label: function (context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0) || 1;
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} prospek (${percentage}%)`;
                    }
                }
            }
        }
    };

    // Add specific options for bar chart
    if (this.chartType === 'bar') {
        return {
            ...baseOptions,
            indexAxis: 'y', // Horizontal bar
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // Ensure whole numbers
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            }
        };
    }

    // For line chart
    if (this.chartType === 'line') {
        return {
            ...baseOptions,
            elements: {
                line: {
                    tension: 0.3
                },
                point: {
                    radius: 4,
                    hoverRadius: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        };
    }

    // For doughnut chart
    return {
        ...baseOptions,
        cutout: '60%',
        radius: '90%'
    };
},

// switchChartType function
switchChartType() {
    // Completely destroy the existing chart
    if (this.pipelineChart) {
        this.pipelineChart.destroy();
        this.pipelineChart = null;
    }

    // Wait for DOM update before recreating chart
    setTimeout(() => {
        this.initChart();
    }, 100);
},

// updateChart function
updateChart() {
    if (!this.pipelineChart) {
        this.initChart();
        return;
    }

    try {
        // Update data
        this.pipelineChart.data.datasets[0].data = [
            this.stats.baru || 0,
            this.stats.tertarik || 0,
            this.stats.negosiasi || 0,
            this.stats.menolak || 0,
            this.stats.menjadi_customer || 0
        ];

        // Update chart options based on chart type
        const newOptions = this.getChartOptions();

        // Apply the updated options
        this.pipelineChart.options = newOptions;

        // Update the chart
        this.pipelineChart.update();
    } catch (error) {
        console.error('Error updating chart:', error);
        // Try to reinitialize chart
        this.pipelineChart = null;
        this.initChart();
    }
}

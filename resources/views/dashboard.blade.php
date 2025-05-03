<x-app-layout :breadcrumbs="[]" :currentPage="__('Dashboard')">

    @push('styles')
        <style>
            /* Modern scrollbar styling */
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(156, 163, 175, 0.3);
                border-radius: 20px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(107, 114, 128, 0.3);
            }

            .custom-scrollbar:hover::-webkit-scrollbar-thumb {
                background-color: rgba(107, 114, 128, 0.5);
            }

            .dark .custom-scrollbar:hover::-webkit-scrollbar-thumb {
                background-color: rgba(75, 85, 99, 0.5);
            }

            /* Modern canvas styling */
            canvas {
                display: block;
                width: 100%;
            }

            /* Enhanced card styling */
            .stat-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08), 0 4px 8px rgba(0, 0, 0, 0.04);
            }

            .dark .stat-card {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2);
            }

            .dark .stat-card:hover {
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4), 0 4px 8px rgba(0, 0, 0, 0.3);
            }

            /* Enhanced dashboard card styling */
            .dashboard-card {
                border-radius: 1rem;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid rgba(229, 231, 235, 0.7);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            }

            .dark .dashboard-card {
                border: 1px solid rgba(75, 85, 99, 0.2);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            }

            .dashboard-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .dark .dashboard-card:hover {
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
            }

            /* Enhanced table hover effect */
            tbody tr {
                transition: all 0.2s ease;
            }

            tbody tr:hover {
                background-color: rgba(249, 250, 251, 0.8);
                transform: scale(1.01);
            }

            .dark tbody tr:hover {
                background-color: rgba(55, 65, 81, 0.4);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
        <script>
            let dashboardCharts = {};

            // Register the plugin globally
            Chart.register(ChartDataLabels);

            function setupDashboardCharts() {
                // --- 1. Define Modern Color Palette ---
                const isDarkMode = document.documentElement.classList.contains('dark');

                // Define base colors (Modern Aesthetic Palette)
                const colors = {
                    primary: {
                        light: '94, 92, 230',
                        dark: '129, 140, 248'
                    }, // Indigo shades
                    secondary: {
                        light: '156, 163, 175',
                        dark: '107, 114, 128'
                    }, // Gray shades
                    warning: {
                        light: '249, 115, 22',
                        dark: '251, 146, 60'
                    }, // Orange shades
                    danger: {
                        light: '239, 68, 68',
                        dark: '248, 113, 113'
                    }, // Red shades
                    info: {
                        light: '6, 182, 212',
                        dark: '45, 212, 191'
                    }, // Cyan/Teal
                    success: {
                        light: '16, 185, 129',
                        dark: '52, 211, 153'
                    }, // Emerald shades
                    indigo: {
                        light: '79, 70, 229',
                        dark: '129, 140, 248'
                    }, // Indigo
                    violet: {
                        light: '124, 58, 237',
                        dark: '167, 139, 250'
                    }, // Violet
                    pink: {
                        light: '236, 72, 153',
                        dark: '244, 114, 182'
                    }, // Pink
                    sky: {
                        light: '14, 165, 233',
                        dark: '56, 189, 248'
                    }, // Sky blue
                    teal: {
                        light: '20, 184, 166',
                        dark: '94, 234, 212'
                    } // Teal
                };

                // Helper to get RGBA color string
                const getColor = (name, opacity = 1) => {
                    const rgb = isDarkMode ? colors[name].dark : colors[name].light;
                    return `rgba(${rgb}, ${opacity})`;
                };

                // Modern chart colors
                const textColor = isDarkMode ? 'rgba(229, 231, 235, 0.8)' : 'rgba(31, 41, 55, 0.8)';
                const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.03)';
                const tooltipBgColor = isDarkMode ? 'rgba(17, 24, 39, 0.95)' : 'rgba(31, 41, 55, 0.95)';
                const doughnutBorderColor = isDarkMode ? 'rgb(31, 41, 55)' : '#fff';

                // --- 3. Destroy Existing Charts ---
                Object.values(dashboardCharts).forEach(chart => {
                    if (chart instanceof Chart) chart.destroy();
                });
                dashboardCharts = {};

                // --- 4. Initialize Charts with Modern Styles ---

                // --- Chart Penjualan Bulanan (Area Chart with Gradient) ---
                const penjualanCtx = document.getElementById('chartPenjualanBulanan');
                const penjualanPlaceholder = document.getElementById('chartPenjualanBulananPlaceholder');
                if (penjualanCtx) {
                    const penjualanData = @json($penjualanPerBulan ?? []);
                    const gradient = penjualanCtx.getContext('2d').createLinearGradient(0, 0, 0, penjualanCtx.offsetHeight *
                        1.2);
                    gradient.addColorStop(0, getColor('indigo', 0.6));
                    gradient.addColorStop(0.6, getColor('indigo', 0.1));
                    gradient.addColorStop(1, getColor('indigo', 0.0));

                    dashboardCharts.penjualan = new Chart(penjualanCtx, {
                        type: 'line',
                        data: {
                            labels: penjualanData.map(item => item.bulan),
                            datasets: [{
                                label: 'Total Penjualan (Rp)',
                                data: penjualanData.map(item => item.total),
                                borderColor: getColor('indigo', 0.8),
                                backgroundColor: gradient,
                                pointBackgroundColor: getColor('indigo'),
                                pointBorderColor: isDarkMode ? '#374151' : '#ffffff',
                                pointBorderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                borderWidth: 3,
                                pointRadius: 4,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 20,
                                    right: 20,
                                    bottom: 0,
                                    left: 10
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor,
                                        drawBorder: false,
                                        lineWidth: 0.5
                                    },
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 11
                                        },
                                        padding: 10,
                                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: tooltipBgColor,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 15,
                                    cornerRadius: 8,
                                    displayColors: false,
                                    callbacks: {
                                        label: (context) => `Rp ${context.parsed.y.toLocaleString('id-ID')}`
                                    }
                                }
                            },
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                    penjualanPlaceholder.style.display = 'none';
                }

                // --- Chart Produk per Kategori (Modern Doughnut) ---
                const kategoriCtx = document.getElementById('chartProdukKategori');
                const kategoriPlaceholder = document.getElementById('chartProdukKategoriPlaceholder');
                if (kategoriCtx) {
                    try {
                        let kategoriData = @json($produkPerKategori ?? []);
                        // Ensure the data structure is consistent
                        if (kategoriData && Array.isArray(kategoriData)) {
                            kategoriData = kategoriData.filter(item => item && item.nama && (item.total > 0));
                        } else {
                            kategoriData = [];
                        }
                        if (kategoriData.length === 0) {
                            const placeholderDiv = document.createElement('div');
                            placeholderDiv.className = 'flex items-center justify-center h-full';
                            placeholderDiv.innerHTML =
                                '<div class="text-gray-400 dark:text-gray-500 text-center p-6"><svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg><p>Data kategori produk tidak tersedia</p></div>';
                            kategoriCtx.parentNode.replaceChildren(placeholderDiv);
                            return;
                        }
                        const backgroundColors = [
                            getColor('indigo', 0.8), getColor('sky', 0.8), getColor('teal', 0.8),
                            getColor('violet', 0.8), getColor('pink', 0.8), getColor('warning', 0.8),
                            getColor('info', 0.8), getColor('success', 0.8)
                        ];
                        dashboardCharts.kategori = new Chart(kategoriCtx, {
                            type: 'doughnut',
                            data: {
                                labels: kategoriData.map(item => item.nama),
                                datasets: [{
                                    label: 'Jumlah Produk',
                                    data: kategoriData.map(item => item.total),
                                    backgroundColor: backgroundColors.slice(0, kategoriData.length),
                                    hoverBackgroundColor: backgroundColors.map(color => color.replace(
                                        /, [\d.]+\)$/, ', 1)')),
                                    hoverOffset: 12,
                                    borderWidth: 5,
                                    borderColor: doughnutBorderColor,
                                    borderRadius: 3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                layout: {
                                    padding: 20
                                },
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: textColor,
                                            padding: 15,
                                            usePointStyle: true,
                                            pointStyle: 'circle',
                                            font: {
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: tooltipBgColor,
                                        titleFont: {
                                            size: 13,
                                            weight: 'bold'
                                        },
                                        bodyFont: {
                                            size: 12
                                        },
                                        padding: 15,
                                        cornerRadius: 8,
                                        displayColors: true,
                                        usePointStyle: true,
                                        callbacks: {
                                            label: (context) => {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const percentage = (context.parsed * 100 / total).toFixed(1);
                                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: '#fff',
                                        display: function(context) {
                                            return context.dataset.data[context.dataIndex] > 0;
                                        },
                                        anchor: 'center',
                                        align: 'center',
                                        offset: 0,
                                        font: {
                                            size: 11,
                                            weight: 'bold'
                                        },
                                        formatter: (value, ctx) => {
                                            if (!value || value <= 0) return '';
                                            const total = ctx.dataset.data.reduce((acc, val) => acc + (val || 0),
                                                0);
                                            if (total === 0) return '';
                                            const percentage = (value * 100 / total).toFixed(0) + "%";
                                            return percentage;
                                        }
                                    }
                                },
                                animation: {
                                    animateRotate: true,
                                    animateScale: true,
                                    duration: 1000,
                                    easing: 'easeOutQuart'
                                }
                            }
                        });
                        kategoriPlaceholder.style.display = 'none';
                    } catch (error) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className =
                            'p-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg text-sm';
                        errorDiv.textContent = 'Terjadi kesalahan saat memuat chart kategori produk.';
                        kategoriCtx.parentNode.replaceChildren(errorDiv);
                    }
                }

                // --- Chart Work Order Status (Modern Pie) ---
                const woStatusCtx = document.getElementById('chartWorkOrderStatus');
                const woStatusPlaceholder = document.getElementById('chartWorkOrderStatusPlaceholder');
                if (woStatusCtx) {
                    const woData = {
                        direncanakan: {{ $workOrderPending ?? 0 }},
                        berjalan: {{ $workOrderProgress ?? 0 }},
                        selesai: {{ $workOrderComplete ?? 0 }}
                    };

                    dashboardCharts.woStatus = new Chart(woStatusCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Direncanakan', 'Berjalan', 'Selesai'],
                            datasets: [{
                                label: 'Status Work Order',
                                data: [woData.direncanakan, woData.berjalan, woData.selesai],
                                backgroundColor: [
                                    getColor('warning', 0.85),
                                    getColor('info', 0.85),
                                    getColor('success', 0.85)
                                ],
                                hoverBackgroundColor: [
                                    getColor('warning'),
                                    getColor('info'),
                                    getColor('success')
                                ],
                                hoverOffset: 12,
                                borderWidth: 5,
                                borderColor: doughnutBorderColor,
                                borderRadius: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: 20
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: textColor,
                                        padding: 15,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: tooltipBgColor,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 15,
                                    cornerRadius: 8,
                                    displayColors: true,
                                    usePointStyle: true,
                                    callbacks: {
                                        label: (context) => {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = (context.parsed * 100 / total).toFixed(1);
                                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    anchor: 'center',
                                    align: 'center',
                                    offset: 0,
                                    font: {
                                        size: 11,
                                        weight: 'bold'
                                    },
                                    formatter: (value, ctx) => {
                                        let sum = 0;
                                        let dataArr = ctx.chart.data.datasets[0].data;
                                        dataArr.map(data => {
                                            sum += data;
                                        });
                                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                                        return value > 0 ? percentage : '';
                                    }
                                }
                            },
                            animation: {
                                animateRotate: true,
                                animateScale: true,
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                    woStatusPlaceholder.style.display = 'none';
                }

                // --- Chart Delivery Status (Modern Bar) ---
                const deliveryStatusCtx = document.getElementById('chartDeliveryStatus');
                const deliveryPlaceholder = document.getElementById('chartDeliveryStatusPlaceholder');
                if (deliveryStatusCtx) {
                    const deliveryData = {
                        draft: {{ $deliveryPending ?? 0 }},
                        dikirim: {{ $deliveryOnProgress ?? 0 }},
                        diterima: {{ $deliveryComplete ?? 0 }}
                    };

                    dashboardCharts.delivery = new Chart(deliveryStatusCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Draft', 'Dikirim', 'Diterima'],
                            datasets: [{
                                label: 'Status Pengiriman',
                                data: [deliveryData.draft, deliveryData.dikirim, deliveryData.diterima],
                                backgroundColor: [
                                    getColor('secondary', 0.7),
                                    getColor('warning', 0.8),
                                    getColor('success', 0.8)
                                ],
                                hoverBackgroundColor: [
                                    getColor('secondary', 0.9),
                                    getColor('warning', 0.9),
                                    getColor('success', 0.9)
                                ],
                                barThickness: 'flex',
                                maxBarThickness: 40,
                                borderRadius: 8,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 20,
                                    right: 20,
                                    bottom: 0,
                                    left: 10
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor,
                                        drawBorder: false,
                                        lineWidth: 0.5
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        color: textColor,
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 11
                                        },
                                        padding: 10
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: tooltipBgColor,
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 15,
                                    cornerRadius: 8,
                                    displayColors: true,
                                    usePointStyle: true
                                },
                                datalabels: {
                                    color: textColor,
                                    anchor: 'end',
                                    align: 'top',
                                    offset: 6,
                                    font: {
                                        size: 11,
                                        weight: 'bold'
                                    },
                                    formatter: (value) => value > 0 ? value : ''
                                }
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                    deliveryPlaceholder.style.display = 'none';
                }
            } // End of setupDashboardCharts function

            // --- Initial Setup & Theme Change Watcher ---
            document.addEventListener('DOMContentLoaded', function() {
                setupDashboardCharts();

                // Watch for theme changes
                const observer = new MutationObserver((mutationsList) => {
                    for (const mutation of mutationsList) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            const targetElement = mutation.target;
                            const wasDarkMode = mutation.oldValue ? mutation.oldValue.includes('dark') : false;
                            const isDarkMode = targetElement.classList.contains('dark');
                            if (wasDarkMode !== isDarkMode) {
                                console.log('Theme changed, updating charts...');
                                setupDashboardCharts();
                            }
                            break;
                        }
                    }
                });
                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class'],
                    attributeOldValue: true
                });
            });
        </script>
    @endpush

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 space-y-7">
            {{-- Dashboard Header with Overview --}}
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Overview performa dan aktivitas perusahaan PT Sinar Surya Semestaraya
                </p>
            </div>

            {{-- Quick Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
                <div
                    class="stat-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/80 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total
                                        Piutang</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                            <div
                                class="flex-shrink-0 h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 p-2 flex items-center justify-center ml-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-indigo-500 dark:text-indigo-400">
                                    <path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" />
                                    <path fill-rule="evenodd"
                                        d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>




                <div
                    class="stat-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/80 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total
                                        Hutang</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalHutang ?? 0, 0, ',', '.') }}</dd>
                                </dl>
                            </div>
                            <div
                                class="flex-shrink-0 h-9 w-9 rounded-full bg-sky-100 dark:bg-sky-900/40 p-2 flex items-center justify-center ml-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-sky-500 dark:text-sky-400">
                                    <path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z" />
                                    <path fill-rule="evenodd"
                                        d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="stat-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/80 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Produk
                                        Stok Min.</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $countStokMinimum ?? 0 }} Item</dd>
                                </dl>
                            </div>
                            <div
                                class="flex-shrink-0 h-9 w-9 rounded-full bg-amber-100 dark:bg-amber-900/40 p-2 flex items-center justify-center ml-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-amber-500 dark:text-amber-400">
                                    <path fill-rule="evenodd"
                                        d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="stat-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/80 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Kehadiran
                                        Hari Ini</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $absensiHariIni ?? 0 }} / {{ $totalKaryawan ?? 0 }}
                                        ({{ number_format($persentaseKehadiran ?? 0, 1) }}%)</dd>
                                </dl>
                            </div>
                            <div
                                class="flex-shrink-0 h-9 w-9 rounded-full bg-emerald-100 dark:bg-emerald-900/40 p-2 flex items-center justify-center ml-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-emerald-500 dark:text-emerald-400">
                                    <path fill-rule="evenodd"
                                        d="M7.502 6h7.128A3.375 3.375 0 0118 9.375v9.375a3 3 0 003-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 00-.673-.05A3 3 0 0015 1.5h-1.5a3 3 0 00-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6zM13.5 3A1.5 1.5 0 0012 4.5h4.5A1.5 1.5 0 0015 3h-1.5z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625V9.375zM6 12a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V12zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 15a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V15zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM6 18a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H6.75a.75.75 0 01-.75-.75V18zm2.25 0a.75.75 0 01.75-.75h3.75a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="stat-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/80 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 relative">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <div class="w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Pengajuan
                                        Cuti</dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $cutiPending ?? 0 }} Pending</dd>
                                </dl>
                            </div>
                            <div
                                class="flex-shrink-0 h-9 w-9 rounded-full bg-violet-100 dark:bg-violet-900/40 p-2 flex items-center justify-center ml-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-violet-500 dark:text-violet-400">
                                    <path fill-rule="evenodd"
                                        d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts Row 1 --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                {{-- Penjualan Bulanan Chart --}}
                <div class="lg:col-span-3 dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400">
                                <path
                                    d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875-1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875-1.875h-.75A1.875 1.875 0 013 19.875v-6.75z" />
                            </svg>
                            Penjualan 6 Bulan Terakhir
                        </h3>
                        <div class="h-80 relative">
                            {{-- Placeholder --}}
                            <div id="chartPenjualanBulananPlaceholder"
                                class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 animate-pulse rounded-lg">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Memuat data penjualan...</span>
                            </div>
                            <canvas id="chartPenjualanBulanan"></canvas>
                        </div>
                    </div>
                </div>
                {{-- Produk per Kategori Chart --}}
                <div class="lg:col-span-2 dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400">
                                <path
                                    d="M5.566 4.657A4.505 4.505 0 016.75 4.5h10.5c.41 0 .806.055 1.183.157A3 3 0 0015.75 3h-7.5a3 3 0 00-2.684 1.657zM2.25 12a3 3 0 013-3h13.5a3 3 0 013 3v6a3 3 0 01-3 3H5.25a3 3 0 01-3-3v-6zM5.25 7.5c-.41 0-.806.055-1.184.157A3 3 0 016.75 6h10.5a3 3 0 012.683 1.657A4.505 4.505 0 0018.75 7.5H5.25z" />
                            </svg>
                            Produk per Kategori
                        </h3>
                        <div class="h-80 flex items-center justify-center relative">
                            {{-- Placeholder --}}
                            <div id="chartProdukKategoriPlaceholder"
                                class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 animate-pulse rounded-lg">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Memuat data kategori...</span>
                            </div>
                            <canvas id="chartProdukKategori"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Production, Inventory & Delivery Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Work Order Status Chart --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 mr-2 text-warning-500 dark:text-warning-400">
                                <path fill-rule="evenodd"
                                    d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm2.25-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0113.5 9zm3.75-1.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z"
                                    clip-rule="evenodd" />
                            </svg>
                            Status Work Order
                        </h3>
                        <div class="h-72 flex items-center justify-center relative">
                            {{-- Placeholder --}}
                            <div id="chartWorkOrderStatusPlaceholder"
                                class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 animate-pulse rounded-lg">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Memuat data WO...</span>
                            </div>
                            <canvas id="chartWorkOrderStatus"></canvas>
                        </div>
                    </div>
                </div>
                {{-- Delivery Status Chart --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-5 h-5 mr-2 text-info-500 dark:text-info-400">
                                <path
                                    d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" />
                                <path
                                    d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" />
                                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                            </svg>
                            Status Pengiriman
                        </h3>
                        <div class="h-72 relative">
                            {{-- Placeholder --}}
                            <div id="chartDeliveryStatusPlaceholder"
                                class="absolute inset-0 flex items-center justify-center bg-gray-50 dark:bg-gray-700/50 animate-pulse rounded-lg">
                                <span class="text-gray-400 dark:text-gray-500 text-sm">Memuat data pengiriman...</span>
                            </div>
                            <canvas id="chartDeliveryStatus"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Produk Stok Minimum List --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 mr-2 text-amber-500 dark:text-amber-400">
                                    <path fill-rule="evenodd"
                                        d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                        clip-rule="evenodd" />
                                </svg>
                                Produk Perlu Restock
                            </h3>
                            <span
                                class="text-sm bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 py-1 px-3 rounded-full">Stok
                                <= Minimum</span>
                        </div>
                        <div class="overflow-y-auto max-h-72 custom-scrollbar pr-2">
                            <table class="min-w-full">
                                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-700/60 backdrop-blur-sm z-10">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Produk</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Stok</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Min</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($produkStokMinimum ?? [] as $produk)
                                        <tr class="transition-colors duration-150">
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100">
                                                <div class="font-medium">{{ $produk->nama }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $produk->kode }}</div>
                                            </td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-red-600 dark:text-red-400 text-right font-semibold">
                                                {{ number_format($produk->stok, 0, ',', '.') }}
                                                {{ $produk->satuan_nama }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                                {{ number_format($produk->stok_minimum, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                                Stok aman.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rest of the dashboard content follows the same pattern --}}
            {{-- Sales & Purchasing Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Sales Order Terbaru --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Sales Order Terbaru
                        </h3>
                        <div class="overflow-y-auto max-h-80 custom-scrollbar pr-2">
                            <table class="min-w-full">
                                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-700/60 backdrop-blur-sm z-10">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No. SO</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Customer</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($salesOrderTerbaru ?? [] as $so)
                                        <tr class="transition-colors duration-150">
                                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                                <a href="#"
                                                    class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ $so->nomor }}</a>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($so->tanggal)->isoFormat('D MMM YYYY') }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100">
                                                {{ $so->customer->nama ?? 'N/A' }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100 text-right">
                                                Rp {{ number_format($so->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                                Tidak ada Sales Order terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Purchase Order Terbaru --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Purchase Order Terbaru
                        </h3>
                        <div class="overflow-y-auto max-h-80 custom-scrollbar pr-2">
                            <table class="min-w-full">
                                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-700/60 backdrop-blur-sm z-10">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No. PO</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Supplier</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($purchaseOrderTerbaru ?? [] as $po)
                                        <tr class="transition-colors duration-150">
                                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                                <a href="#"
                                                    class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ $po->nomor }}</a>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($po->tanggal)->isoFormat('D MMM YYYY') }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100">
                                                {{ $po->supplier->nama ?? 'N/A' }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100 text-right">
                                                Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                                Tidak ada Purchase Order terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aktivitas Terbaru & Faktur Jatuh Tempo --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Aktivitas Sistem Terbaru --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Aktivitas Sistem
                            Terbaru</h3>
                        <div class="overflow-y-auto max-h-[26rem] custom-scrollbar pr-2">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($aktivitasTerbaru ?? [] as $log)
                                    <li class="py-3.5">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700">
                                                    @if (Str::contains(strtolower($log->modul), ['sales', 'invoice', 'quotation']))
                                                        @svg('heroicon-o-shopping-cart', 'h-4 w-4 text-gray-500 dark:text-gray-400')
                                                    @elseif(Str::contains(strtolower($log->modul), ['purchase', 'supplier']))
                                                        @svg('heroicon-o-truck', 'h-4 w-4 text-gray-500 dark:text-gray-400')
                                                    @elseif(Str::contains(strtolower($log->modul), ['produk', 'stok', 'gudang']))
                                                        @svg('heroicon-o-archive-box', 'h-4 w-4 text-gray-500 dark:text-gray-400')
                                                    @elseif(Str::contains(strtolower($log->modul), ['user', 'role']))
                                                        @svg('heroicon-o-user-circle', 'h-4 w-4 text-gray-500 dark:text-gray-400')
                                                    @else
                                                        @svg('heroicon-o-document-text', 'h-4 w-4 text-gray-500 dark:text-gray-400')
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-800 truncate dark:text-white">
                                                    {{ $log->user->name ?? 'Sistem' }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    {{ $log->aktivitas }} <span
                                                        class="text-xs">({{ $log->modul }}{{ $log->data_id ? ' #' . $log->data_id : '' }})</span>
                                                </p>
                                            </div>
                                            <div
                                                class="inline-flex items-center text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                                {{ $log->created_at->diffForHumans(null, true) }}
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="py-6 text-center text-sm text-gray-500 dark:text-gray-400 italic">Tidak
                                        ada aktivitas terbaru.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Faktur Jatuh Tempo Minggu Ini --}}
                <div class="dashboard-card bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Faktur Jatuh Tempo (7
                            Hari)</h3>
                        <div class="overflow-y-auto max-h-[26rem] custom-scrollbar pr-2">
                            <table class="min-w-full">
                                <thead class="sticky top-0 bg-gray-50 dark:bg-gray-700/60 backdrop-blur-sm z-10">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            No. Invoice</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Customer</th>
                                        <th scope="col"
                                            class="px-3 py-2.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Sisa Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($terminJatuhTempo ?? [] as $invoice)
                                        <tr class="transition-colors duration-150">
                                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                                <a href="#"
                                                    class="text-blue-600 dark:text-blue-400 hover:underline font-medium">{{ $invoice->nomor }}</a>
                                                <div
                                                    class="text-xs {{ now()->gt($invoice->jatuh_tempo) ? 'text-red-500 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                                    {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->isoFormat('D MMM YYYY') }}
                                                    ({{ now()->gt($invoice->jatuh_tempo) ? 'Lewat ' . now()->diffInDays($invoice->jatuh_tempo) . ' hr' : now()->diffInDays($invoice->jatuh_tempo) . ' hr lagi' }})
                                                </div>
                                            </td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100">
                                                {{ $invoice->customer->nama ?? 'N/A' }}</td>
                                            <td
                                                class="px-3 py-3 whitespace-nowrap text-sm text-gray-800 dark:text-gray-100 text-right font-medium">
                                                Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3"
                                                class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400 italic">
                                                Tidak ada faktur jatuh tempo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

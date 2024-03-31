<x-front-layout>

    <div>
        <canvas id="myChart" style="width:100vw; height:80vh"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="/js/chartjs-plugin-zoom.js"></script>

    <script>
        const chartsData = @json($charts);
        const datasets = [];

        const scales = {
            x: {
                type: 'time',
                time: {
                    minUnit: 'hour',
                    displayFormats: {
                        hour: 'd MMM H:mm',
                        day: 'd MMM',
                        month: 'yyyy MMM d'
                    }
                }
            }
        };

        const colors = ['#0080FF', '#FF0080', '#80FF00', '#00FF80', '#8000FF', '#FF8000'];

        let i = 0;
        for (let label in chartsData) {
            let asset = chartsData[label];
            let yAxisId = `y${i}`;

            Array.from(asset).map(function(item) {
                return {'x': new Date(item.x), y: item.y }
            });

            datasets.push({
                label: label,
                data: asset,
                borderColor: colors[i],
                fill: false,
                cubicInterpolationMode: 'monotone',
                tension: 0.4,
                yAxisID: yAxisId
            });

            scales[yAxisId] = {
                title: {
                    display: true,
                    text: label
                },
            };

            i++;
        }

        const config = {
            type: 'line',
            data: {
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart - Cubic interpolation mode'
                    },
                    zoom: {
                        zoom: {
                            mode: 'xy',
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                        },
                        pan: {
                            enabled: true,
                            mode: 'xy'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                },
                scales: scales,
                transitions: {
                    zoom: {
                        animation: {
                            duration: 1000,
                            easing: 'easeOutCubic'
                        }
                    }
                }
            },
        };

        const ctx = document.getElementById('myChart');

        new Chart(ctx, config);

    </script>
</x-front-layout>
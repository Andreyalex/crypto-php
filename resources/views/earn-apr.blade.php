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

            Array.from(asset.data).map(function(item) {
                return {'x': new Date(item.x), y: item.y }
            });

            scales[yAxisId] = {
                title: {
                    text: label
                },
            };

            let dataset = {
                label: label,
                data: asset.data,
                borderColor: colors[i],
                fill: false,
                cubicInterpolationMode: 'monotone',
                tension: 0.4,
                yAxisID: yAxisId,
                scale: scales[yAxisId],
            }
            if (asset.type === 'bars') {
                dataset.type = 'bar';
                dataset.barThickness = 2;
                dataset.backgroundColor = '#001122';
            }
            datasets.push(dataset);

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
                    legend: {
                        onClick: function(event, label) {
                            let dataset = event.chart.getDatasetMeta(label.datasetIndex);
                            let scale = event.chart.config.options.scales[`y${label.datasetIndex}`];
                            scale.display = !scale.display;
                            dataset.hidden = !dataset.hidden;
                            event.chart.update();
                        }
                    },
                    zoom: {
                        zoom: {
                            mode: 'x',
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                        },
                        pan: {
                            enabled: true,
                            mode: 'x'
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
                },
                elements: {
                    point: {
                        pointStyle: false
                    }
                }
            },
        };

        const ctx = document.getElementById('myChart');
        const chart = new Chart(ctx, config);

        chart.data.datasets.forEach((dataSet, i) => {
            let meta = chart.getDatasetMeta(i);
            let scale = chart.config.options.scales[`y${i}`];
            meta.hidden = (i !== 0);
            scale.display = (i === 0);
        });
        chart.update();

    </script>
</x-front-layout>
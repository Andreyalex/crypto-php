<x-front-layout>

    <div>
        <canvas id="myChart" style="width:100vw; height:100vh"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hammerjs@2.0.8"></script>
    <script src="/js/chartjs-plugin-zoom.js"></script>

    <script>
        const labels = [];
        Array.from(@json($earnApr['x'])).forEach((item)=> labels.push(new Date(item)));
        const datapoints = @json($earnApr['y']);
        const data = {
            labels: labels,
            datasets: [
                {
                    label: '{{ $earnApr['title'] }}',
                    data: datapoints,
                    borderColor: '#FF0000',
                    fill: false,
                    cubicInterpolationMode: 'monotone',
                    tension: 0.4
                }/*, {
                    label: 'Cubic interpolation',
                    data: datapoints,
                    borderColor: '#0000FF',
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Linear interpolation (default)',
                    data: datapoints,
                    borderColor: '#00FF00',
                    fill: false
                }*/
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart - Cubic interpolation mode'
                    },
                    zoom: {
                        zoom: {
                            wheel: {
                                enabled: true,
                            },
                            pinch: {
                                enabled: true
                            },
                            mode: 'x',
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
                scales: {
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
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Value'
                        },
                        suggestedMin: {{ $earnApr['minY'] }},
                        suggestedMax: {{ $earnApr['maxY'] }}
                    }
                },
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
<div class="hidden sm:block">
    <h3 class="text-day-7 dark:text-night-4 font-medium text-sm text-center">{{ __('timer.team_statistics') }}</h3>

    <div class="">
        <div
            x-data="{
                seriesData: {{ $entries }},
                labels: {{ $labels }},
                chart: null,
                init() {
                    if (!this.labels || this.labels.length === 0) {
                        console.error('Labels array is empty or undefined:', this.labels);
                        return;
                    }

                    let series = [];
                    let seriesNames = [];

                    // Prepare the series data for the chart
                    this.labels.forEach((label, index) => {
                        let users = this.seriesData[label] || {};

                        Object.keys(users).forEach((userId) => {
                            let userData = users[userId];

                            // Check if the user already exists in the seriesNames array
                            let seriesIndex = seriesNames.indexOf(userData.user);

                            // If the user does not exist, initialize a new series object
                            if (seriesIndex === -1) {
                                seriesNames.push(userData.user);
                                series.push({
                                    name: userData.user,
                                    data: Array(this.labels.length).fill(0)  // Initialize the data array with zeroes
                                });
                                seriesIndex = series.length - 1; // Update seriesIndex to the last added series
                            }

                            // Safely assign the total_duration to the correct index in the data array
                            series[seriesIndex].data[index] = userData.total_duration;
                        });
                    });

                    this.chart = new ApexCharts(this.$refs.chart, this.options(series));

                    this.chart.render();
                },
                options(series) {
                    return {
                        chart: {
                            name: 'Last 15 days activity',
                            type: 'bar',
                            stacked: true,
                            toolbar: false,
                            width: '94%',
                            height: '400px'
                        },
                        series: series,
                        colors: ['#ccfbf1', '#99f6e4', '#5eead4', '#2dd4bf', '#14b8a6', '#0d9488', '#0f766e', '#115e59', '#134e4a'],
                        fill: {
                            opacity: 1
                        },
                        legend: {
                            labels: {
                                colors: '#959595',  // Set the legend text color here
                                fontSize: '14px', // Optionally adjust the font size
                                fontFamily: 'Arial, sans-serif', // Optionally set the font family
                            }
                        },
                        states: {
                            hover: {
                                filter: {
                                    type: 'darken', // or 'lighten', 'none'
                                    value: 0.7, // Adjust the brightness (higher value means lighter)
                                }
                            },
                            active: {
                                allowMultipleDataPointsSelection: false,
                                filter: {
                                    type: 'darken',
                                    value: 0.35, // Adjust the brightness when the bar is clicked
                                }
                            }
                        },
                        tooltip: {
                            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                                const label = w.globals.labels[dataPointIndex];
                                const user = w.config.series[seriesIndex].name;

                                return `
                                    <div style='padding: 10px; background-color: #4FC1B5; border: 1px solid #3d978d; color: #ffffff;box-shadow:none;'>
                                        <span style='font-weight: bold;'>${user}</span><br/>
                                    </div>
                                `;
                            },
                            style: {
                                fontSize: '12px',
                                fontFamily: 'Arial, sans-serif',
                            },
                        },
                        xaxis: {
                            type: 'category',
                            categories: this.labels,
                            tickPlacement: 'on',
                            labels: {
                                style: {
                                    colors: '#959595',
                                    fontSize: '0.75rem',
                                    fontFamily: 'Arial, sans-serif',
                                    fontWeight: 'bold',
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: '#959595',
                                    fontSize: '0.75rem',
                                    fontFamily: 'Arial, sans-serif',
                                    fontWeight: 'bold',
                                }
                            }
                        },
                        dataLabels: {
                            style: {
                                colors: ['#171F26'],
                                fontSize: '0.75rem',
                                fontFamily: 'Arial, sans-serif',
                            },
                            formatter: function (val) {
                                return val;
                            },
                        },
                    }
                },
                destroy() {
                    if (this.chart) {
                        this.chart.destroy();  // Destroy the chart instance to clean up resources
                        this.chart = null;
                    }
                }
            }"
            class="mx-auto w-[80vw]">
            <div x-ref="chart" class="flex justify-center text-day-7 dark:text-night-1"></div>
        </div>
    </div>
</div>

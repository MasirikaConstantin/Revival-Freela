'use strict';

if ($('#monthlySubscriptionChart').length > 0) {
  const chartOne = document.getElementById('monthlySubscriptionChart').getContext('2d');
  const monthlySubscriptionChart = new Chart(chartOne, {
    type: 'line',
    data: {
      labels: monthArr,
      datasets: [{
        label: 'Monthly Subscriptions',
        data: subscriptionArr,
        borderColor: '#1d7af3',
        pointBorderColor: '#FFF',
        pointBackgroundColor: '#1d7af3',
        pointBorderWidth: 2,
        pointHoverRadius: 4,
        pointHoverBorderWidth: 1,
        pointRadius: 4,
        backgroundColor: 'transparent',
        fill: true,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        labels: {
          padding: 10,
          fontColor: '#1d7af3'
        }
      },
      tooltips: {
        bodySpacing: 4,
        mode: 'nearest',
        intersect: 0,
        position: 'nearest',
        xPadding: 10,
        yPadding: 10,
        caretPadding: 10
      },
      layout: {
        padding: {
          left: 15,
          right: 15,
          top: 15,
          bottom: 15
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            stepSize: 1
          }
        }]
      }
    }
  });
}
if ($('#serviceOrderChart').length > 0) {
  const chartOne = document.getElementById('serviceOrderChart').getContext('2d');
  const serviceOrderChart = new Chart(chartOne, {
    type: 'line',
    data: {
      labels: monthArr,
      datasets: [{
        label: 'Monthly Service Orders',
        data: serviceOrderArr,
        borderColor: '#1d7af3',
        pointBorderColor: '#FFF',
        pointBackgroundColor: '#1d7af3',
        pointBorderWidth: 2,
        pointHoverRadius: 4,
        pointHoverBorderWidth: 1,
        pointRadius: 4,
        backgroundColor: 'transparent',
        fill: true,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        labels: {
          padding: 10,
          fontColor: '#1d7af3'
        }
      },
      tooltips: {
        bodySpacing: 4,
        mode: 'nearest',
        intersect: 0,
        position: 'nearest',
        xPadding: 10,
        yPadding: 10,
        caretPadding: 10
      },
      layout: {
        padding: {
          left: 15,
          right: 15,
          top: 15,
          bottom: 15
        }
      },
      scales: {
        yAxes: [{
          ticks: {
            stepSize: 1
          }
        }]
      }
    }
  });
}


if ($('#serviceIncomeChart').length > 0) {
  const chartThree = document.getElementById('serviceIncomeChart').getContext('2d');
  const serviceIncomeChart = new Chart(chartThree, {
    type: 'line',
    data: {
      labels: monthArr,
      datasets: [{
        label: 'Month Wise Total Incomes',
        data: serviceIncomeArr,
        borderColor: '#01a100',
        pointBorderColor: '#FFF',
        pointBackgroundColor: '#01a100',
        pointBorderWidth: 2,
        pointHoverRadius: 4,
        pointHoverBorderWidth: 1,
        pointRadius: 4,
        backgroundColor: 'transparent',
        fill: true,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        labels: {
          padding: 10,
          fontColor: '#01a100'
        }
      },
      tooltips: {
        bodySpacing: 4,
        mode: 'nearest',
        intersect: 0,
        position: 'nearest',
        xPadding: 10,
        yPadding: 10,
        caretPadding: 10
      },
      layout: {
        padding: {
          left: 15,
          right: 15,
          top: 15,
          bottom: 15
        }
      }
    }
  });
}

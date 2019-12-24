/* eslint-disable object-curly-newline */

/* global Chart */

/**
 * --------------------------------------------------------------------------
 * CoreUI Free Boostrap Admin Template (v2.0.0): main.js
 * Licensed under MIT (https://coreui.io/license)
 * --------------------------------------------------------------------------
 */

// random Numbers
var random = function random() {
    return Math.round(Math.random() * 1050000);
};


var lineChart = new Chart($('#canvas-1'), {
    type: 'line',
    data: {
    labels: ['Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November'],
        datasets: [{
            label: 'Jumlah Sepatutnya Dikutip (RM)',
            backgroundColor: 'rgba(220, 220, 220, 0.2)',
            borderColor: 'rgba(220, 220, 220, 1)',
            pointBackgroundColor: 'rgba(220, 220, 220, 1)',
            pointBorderColor: '#fff',
            data: [random(), random(), random(), random(), random(), random(), random(), random(), random(), random(), random()]
        }, {
            label: 'Jumlah Dibayar (RM)',
            backgroundColor: 'rgba(151, 187, 205, 0.2)',
            borderColor: 'rgba(151, 187, 205, 1)',
            pointBackgroundColor: 'rgba(151, 187, 205, 1)',
            pointBorderColor: '#fff',
            data: [random(), random(), random(), random(), random(), random(), random(), random(), random(), random(), random()]
        }]
    },
    options: {
        responsive: true
    }
}); // eslint-disable-next-line no-unused-vars

var rand = function rand() {
    return Math.round(Math.random() * 105);
};

var barChart = new Chart($('#canvas-2'), {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
            label: 'Diterima',
            backgroundColor: 'rgba(151, 187, 205, 0.5)',
            borderColor: 'rgba(151, 187, 205, 0.8)',
            highlightFill: 'rgba(151, 187, 205, 0.75)',
            highlightStroke: 'rgba(151, 187, 205, 1)',
            data: [rand(), rand(), rand(), rand(), rand(), rand(), rand()]
        },
        {
            label: 'Ditolak',
            backgroundColor: 'rgba(220, 220, 220, 0.5)',
            borderColor: 'rgba(220, 220, 220, 0.8)',
            highlightFill: 'rgba(220, 220, 220, 0.75)',
            highlightStroke: 'rgba(220, 220, 220, 1)',
            data: [rand(), rand(), rand(), rand(), rand(), rand(), rand()]
        }]
    },
    options: {
        responsive: true
    }
}); // eslint-disable-next-line no-unused-vars

var doughnutChart = new Chart($('#canvas-3'), {
    type: 'doughnut',
    data: {
        labels: ['Pasar', 'Medan Selera', 'Gerai PLB', 'Gerai UPEN', 'Kiosk Mikro-Mara', 'Papan Iklan Luaran', 'Tapak', 'Kuarters', 'Gerai'],
        datasets: [{
            data: [300, 50, 100, '20', '412', '59', 78, '13', '67'],
            backgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    },
    options: {
        responsive: true
    }
}); // eslint-disable-next-line no-unused-vars

var radarChart = new Chart($('#canvas-4'), {
    type: 'radar',
    data: {
        labels: ['Pasar', 'Medan Selera', 'Gerai PLB', 'Gerai UPEN', 'Kiosk Mikro-Mara', 'Papan Iklan Luaran', 'Tapak', 'Kuarters', 'Gerai'],
        datasets: [{
            label: 'Tawaran Sewaan',
            backgroundColor: 'rgba(220, 220, 220, 0.2)',
            borderColor: 'rgba(220, 220, 220, 1)',
            pointBackgroundColor: 'rgba(220, 220, 220, 1)',
            pointBorderColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220, 220, 220, 1)',
            data: [65, 59, 70, 51, 56, 55, 40, 30, 61]
        }, {
            label: 'Setuju Terima',
            backgroundColor: 'rgba(151, 187, 205, 0.2)',
            borderColor: 'rgba(151, 187, 205, 1)',
            pointBackgroundColor: 'rgba(151, 187, 205, 1)',
            pointBorderColor: '#fff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(151, 187, 205, 1)',
            data: [38, 48, 40, 21, 49, 27, 20, 11, 30]
        }]
    },
    options: {
        responsive: true
    }
}); // eslint-disable-next-line no-unused-vars

var pieChart = new Chart($('#canvas-5'), {
    type: 'pie',
    data: {
        labels: ['Pasar', 'Medan Selera', 'Gerai PLB', 'Gerai UPEN', 'Kiosk Mikro-Mara', 'Papan Iklan Luaran', 'Tapak', 'Kuarters', 'Harta Komersial', 'Gerai', 'Rumah Taman Mudun'],
        datasets: [{
            data: [300, 50, 100, '20', '412', '59', 78, '13', '67', '7', '1'],
            backgroundColor: ['#FF6384', '#43c9c9', '#688f8f', '#3caf85', '#309fdb', '#fbce4a', '#e95b54', '#f7915e', '#854e9b', '#2b4b71', '#2dc63e'],
            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    },
    options: {
        responsive: true
    }
}); // eslint-disable-next-line no-unused-vars

var polarAreaChart = new Chart($('#canvas-6'), {
    type: 'polarArea',
    data: {
        labels: ['Red', 'Green', 'Yellow', 'Grey', 'Blue'],
        datasets: [{
            data: [11, 16, 7, 3, 14],
            backgroundColor: ['#FF6384', '#4BC0C0', '#FFCE56', '#E7E9ED', '#36A2EB']
        }]
    },
    options: {
        responsive: true
    }
});
//# sourceMappingURL=charts.js.map